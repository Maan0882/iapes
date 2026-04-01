<?php

namespace App\Filament\Resources\InterviewManagement;

use App\Filament\Resources\InterviewManagement\OfferLetterResource\Pages;
use App\Filament\Resources\InterviewManagement\OfferLetterResource\RelationManagers;
use App\Models\InterviewManagement\OfferLetter;
use App\Models\InterviewManagement\Application;
use App\Models\InternManagement\Intern;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\{Select, DatePicker, TextInput, Textarea, RichEditor, Section};
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\{Action, BulkAction};
use Filament\Tables\Columns\{TextColumn, ToggleColumn, BadgeColumn, IconColumn};
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use ZipArchive;
use Filament\Forms\Set;
use Filament\Forms\Get;
use Illuminate\Support\Carbon;

class OfferLetterResource extends Resource
{
    protected static ?string $model = OfferLetter::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    // protected static ?string $navigationGroup = 'Interview Management';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // ─── STEP 1: Pick template FIRST ───────────────────────────────
                Forms\Components\Section::make('Offer Letter Template')
                    ->schema([
                        Select::make('template')
                            ->label('Offer Letter Template')
                            ->options([
                                '3_month_offer_letter' => '3 Month Offer Letter',
                                '4_month_offer_letter' => '4 Month Offer Letter',
                                '6_month_offer_letter' => '6 Month Offer Letter',
                                'one_month'            => 'One Month Internship',
                                'general'              => 'General Internship',
                            ])
                            ->required()
                            ->live() // Re-renders form on change
                            ->placeholder('Select a template to continue…'),
                    ]),

                // ─── SECTION A: Application-based (non-general templates) ──────
                Section::make('Intern Selection')
                    ->description('Select interns from the same college to generate letters in bulk.')
                    ->visible(fn (Get $get) => filled($get('template')) && $get('template') !== 'general')
                    ->schema([
                        Select::make('applications')
                            ->label('Select Interns')
                            ->multiple()
                            ->options(function (Get $get, ?OfferLetter $record) {
                                $query = Application::where('status', 'Shortlisted');
                                $query->where(function ($q) use ($record) {
                                    $q->whereDoesntHave('offerLetter')
                                    ->orWhereHas('offerLetter', function ($subQ) use ($record) {
                                        if ($record) {
                                            $subQ->where('id', $record->id);
                                        } else {
                                            $subQ->whereRaw('1 = 0');
                                        }
                                    });
                                });

                                if (!$record) {
                                    $selectedIds = $get('applications') ?? [];
                                    if (!empty($selectedIds)) {
                                        $firstIntern = Application::find($selectedIds[0]);
                                        if ($firstIntern) {
                                            $query->where('college', $firstIntern->college);
                                        }
                                    }
                                }

                                return $query->get()->mapWithKeys(
                                    fn ($app) => [$app->id => "{$app->name} - {$app->college}"]
                                );
                            })
                            ->live()
                            ->afterStateHydrated(function (Set $set, ?OfferLetter $record, $state) {
                                if ($record) {
                                    $set('intern_name', $record->name);
                                    $set('university',  $record->university);
                                    $set('college',     $record->college);
                                } elseif (!empty($state)) {
                                    $app = Application::find(is_array($state) ? $state[0] : $state);
                                    if ($app) {
                                        $set('intern_name', $app->name);
                                        $set('university',  $app->college);
                                        $set('college',     $app->college);
                                    }
                                }
                            })
                            ->afterStateUpdated(function (Set $set, Get $get, $state) {
                                if (!empty($state) && count($state) === 1) {
                                    $app = Application::find($state[0]);
                                    if ($app) {
                                        $set('intern_name', $app->name);
                                        $set('university',  $app->college);
                                        $set('college',     $app->college);
                                    }
                                }
                                self::updateCompletionDate($set, $get);
                            }),

                        Forms\Components\Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Intern Name (Editable)')
                                ->dehydrated(true)
                                ->helperText('Pre-fills from first selected intern but can be changed.')
                                ->required(),

                            TextInput::make('university')
                                ->label('University/College')
                                ->dehydrated(true)
                                ->placeholder('Enter University Name'),

                            TextInput::make('college')
                                ->label('College')
                                ->dehydrated(true)
                                ->placeholder('Enter College Name'),
                        ]),
                    ]),

                // ─── SECTION B: Standalone / Quick-Generate (general only) ────
                Section::make('Intern Details')
                    ->description('Fill in the intern\'s details directly — no application required.')
                    ->visible(fn (Get $get) => $get('template') === 'general')
                    ->schema([
                        Forms\Components\Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Intern Full Name')
                                ->dehydrated(true)
                                ->placeholder('e.g. Rahul Sharma')
                                ->required()
                                ->regex('/^(?=(?:.*?\s){1,5}(?![^\s]*\s))[a-zA-Z\s]+$/') // Validation: Only alphabets and spaces, with exactly 3 to 5 spaces total
                                ->validationMessages([
                                    'regex' => 'The name must only contain letters and white spaces.',
                                ]),

                            TextInput::make('college')
                                ->label('College / Institution')
                                ->dehydrated(true)
                                ->placeholder('e.g. M B Patel College of Engineering'),
                                

                            TextInput::make('university')
                                ->label('University')
                                ->dehydrated(true)
                                ->placeholder('e.g. GTU'),

                            TextInput::make('email')
                                ->label('Intern Email')
                                ->email()
                                ->placeholder('intern@example.com'),

                            TextInput::make('phone')
                                ->label('Phone Number')
                                ->placeholder('+91 XXXXX XXXXX'),
                        ]),
                    ]),

                // ─── INTERNSHIP DETAILS (shared by both flows) ────────────────
                Forms\Components\Section::make('Internship Details')
                    ->visible(fn (Get $get) => filled($get('template')))
                    ->columns(2)
                    ->schema([
                        DatePicker::make('joining_date')
                            ->required()
                            ->native(true)
                            ->live()
                            ->afterStateUpdated(fn (Set $set, Get $get) => self::updateCompletionDate($set, $get))
                            ->minDate(now()->subYear())   // Restrict joining date to be within 1 year of today (past or future)
                            ->maxDate(now()->addYears(2)),
                            

                        DatePicker::make('completion_date')
                            ->required()
                            ->native(true)
                            ->minDate(fn (Get $get) => $get('joining_date') ?? now())  // Constraint: Prevents picking a date before joining in the UI
                            ->maxDate(now()->addYears(2)) // Prevent absurd years like 2620
                            ->validationMessages([
                                'after' => 'The completion date must be a date after the joining date.',
                            ]),

                        TextInput::make('internship_role')
                            ->label('Internship Role')
                            ->placeholder('Web Developer')
                            ->required(),

                        TextInput::make('internship_position')
                            ->label('Internship Position')
                            ->placeholder('e.g. Junior Developer Intern')
                            ->required(),

                        TextInput::make('working_hours')
                            ->label('Working Hours')
                            ->numeric()
                            ->placeholder('e.g. 40 hours per week')
                            ->required(),
                        
                        Forms\Components\Section::make('Offer Letter Body')
                            ->visible(fn (Get $get) => filled($get('template')))
                            ->schema([
                                Forms\Components\RichEditor::make('description')
                                    ->dehydrated(true)
                                    ->label('Custom Description')
                                    ->helperText('This content will appear on the second page of the offer letter.')
                                    ->toolbarButtons([
                                        'bold', 'italic', 'underline',
                                        'bulletList', 'orderedList',
                                        'h2', 'h3',
                                        'undo', 'redo',
                                    ])
                                    ->columnSpanFull(),
                            ]),
                    ]),

                Forms\Components\Placeholder::make('note')
                    ->content('The Offer Letter Code will be generated automatically upon saving.')
                    ->columnSpanFull()
                    ->visible(fn (Get $get) => filled($get('template'))),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('offer_letter_code')
                    ->label('Offer Code')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Intern')
                    ->state(function ($record): string {
                        return $record->application?->name 
                            ?? $record->getRawOriginal('name') 
                            ?? '—';
                    }),
                TextColumn::make('internship_role'),
                TextColumn::make('joining_date')->date(),
                TextColumn::make('completion_date')->date(),
                TextColumn::make('duration')
                    ->label('Duration')
                    ->state(function ($record): string {
                        if (!$record->joining_date || !$record->completion_date) {
                            return 'N/A';
                        }
                        $start = \Carbon\Carbon::parse($record->joining_date);
                        $end = \Carbon\Carbon::parse($record->completion_date);
                        $months = round($start->floatDiffInMonths($end)); // 👈 rounded whole number
                        return $months . ' Months';
                    }),
                TextColumn::make('template')
                    ->label('Offer Letter Template')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        '3_month_offer_letter' => '3 Month Offer Letter',
                        '4_month_offer_letter' => '4 Month Offer Letter',
                        '6_month_offer_letter' => '6 Month Offer Letter',
                        'one_month' => 'One Month Internship',
                        'general' => 'General Internship',
                        default => $state,
                    })
                    ->badge() // Optional: Makes it look like a nice UI tag
                    ->color('info'),
                ToggleColumn::make('is_accepted')
                    ->label('Offer Status')
                    ->afterStateUpdated(function ($record, $state) {
                        // We trigger creation if state is TRUE and there is no linked intern yet
                        if ($state && !$record->intern_id) {

                            $year = now()->format('y'); 
                            $prefix = "TS{$year}/WD/";
                            $lastIntern = Intern::where('intern_code', 'like', "{$prefix}%")
                                ->latest('id')
                                ->first();
                            $sequence = $lastIntern 
                                ? (int) str($lastIntern->intern_code)->afterLast('/')->toString() + 1 
                                : 1;
                            $paddedSequence = str_pad($sequence, 3, '0', STR_PAD_LEFT);
                            // Intern ID
                            $generatedCode = $prefix . $paddedSequence;
                            // Username
                            $username = "ts{$year}{$paddedSequence}@user.com";
                            // Password
                            $plainPassword = "ts{$year}{$paddedSequence}";
                            $intern = Intern::create([
                                'application_id' => $record->application_id,   // null is fine for general
                                'offer_letter_id'=> $record->id,
                                'intern_code'   => $generatedCode,
                                'username'      => $username,
                                'password'      => \Illuminate\Support\Facades\Hash::make($plainPassword),
                                'name'          => $record->application->name ?? $record->name ?? '',
                                'email'         => $record->application->email ?? $record->email ?? '',
                                'joining_date'  => $record->joining_date,
                                'is_active'     => true,
                            ]);

                            //$record->update(['intern_id' => $intern->id]);

                            Notification::make()
                                ->title('Intern Account Created')
                                ->body("ID: **{$generatedCode}** | Username: **{$username}** | Pass: **{$plainPassword}**")
                                ->success()
                                ->send();
                        }
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function ($record) {

                        $template = $record->template ?? 'general';

                        $pdf = Pdf::loadView("offerletter.$template", [
                            'offers' => collect([$record])
                        ]);

                        $fileName = str_replace('/', '-', $record->offer_letter_code);

                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            $fileName . '.pdf'
                        );
                    }),

                Tables\Actions\Action::make('print')
                    ->label('View Offer')
                    ->icon('heroicon-o-eye')
                    ->color('success')
                    ->url(fn ($record) => route('view-offer-pdf', ['id' => $record->id]))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('bulk_download')
                    ->label('Bulk Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function ($records) {

                            $zipFileName = 'offer_letters.zip';
                            $zipPath = storage_path($zipFileName);

                            $zip = new ZipArchive;

                            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {

                                foreach ($records as $offer) {

                                    $template = $offer->template ?? 'general';

                                    $pdf = Pdf::loadView("offerletter.$template", [
                                        'offers' => collect([$offer])
                                    ]);

                                    $fileName = str_replace('/', '-', $offer->offer_letter_code) . '.pdf';

                                    $zip->addFromString($fileName, $pdf->output());
                                }

                                $zip->close();
                            }

                        return response()->download($zipPath)->deleteFileAfterSend(true);
                    })
                    ->deselectRecordsAfterCompletion(),
                Tables\Actions\BulkAction::make('bulk_print')
                    ->label('Bulk Print')
                    ->icon('heroicon-o-printer')
                    ->action(function ($records) {
                        $template = $records->first()?->template ?? 'general';
                        $pdf = Pdf::loadView("offerletter.$template", [
                            'offers' => $records
                        ]);

                            return response()->streamDownload(
                                fn () => print($pdf->output()),
                                'offer_letters.pdf'
                            );
                        })
                    ->deselectRecordsAfterCompletion(),
                Tables\Actions\BulkAction::make('send_credentials_bulk')
                    ->label('Email Credentials')
                    ->icon('heroicon-o-envelope')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($records) {
                        foreach ($records as $record) {
                            if ($record->is_accepted && $record->intern) {
                                // Sent directly (not queued)
                                \Illuminate\Support\Facades\Mail::to($record->intern->email)
                                    ->send(new \App\Mail\InternWelcomeMail($record->intern));
                            }
                        }

                        Notification::make()
                            ->title('Credentials Sent Successfully')
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion(),
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOfferLetters::route('/'),
            'create' => Pages\CreateOfferLetter::route('/create'),
            'edit' => Pages\EditOfferLetter::route('/{record}/edit'),
        ];
    }

    // Add this helper method inside your Resource class to keep the code clean
    public static function updateCompletionDate(Set $set, Get $get)
    {
        $selectedIds = $get('applications');
        $joiningDate = $get('joining_date');

        if (empty($selectedIds) || !$joiningDate) {
            return;
        }

        // We fetch the first intern selected to determine the duration
        $application = Application::find(collect($selectedIds)->first());

        if ($application && $application->duration && $application->duration_unit) {
            $date = Carbon::parse($joiningDate);
            $duration = (int) $application->duration;
            $unit = strtolower($application->duration_unit);

            // Add duration based on unit (e.g., 'months', 'weeks', 'days')
            if (str_contains($unit, 'month')) {
                $date->addMonths($duration);
            } elseif (str_contains($unit, 'week')) {
                $date->addWeeks($duration);
            } else {
                $date->addDays($duration);
            }

            $set('completion_date', $date->format('Y-m-d'));
        }
    }
}
