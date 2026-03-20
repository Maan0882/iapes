<?php

namespace App\Filament\Resources\InterviewManagement;

use App\Filament\Resources\InterviewManagement\OfferLetterResource\Pages;
use App\Filament\Resources\InterviewManagement\OfferLetterResource\RelationManagers;
use App\Models\InterviewManagement\OfferLetter;
use App\Models\InterviewManagement\Application;
use App\Models\InternManagement\Intern;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\{Select, DatePicker, TextInput, Textarea, RichEditor};
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
    protected static ?string $navigationGroup = 'Interview Management';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('applications')
                    ->label('Select Interns')
                    ->multiple()
                   ->options(
                        Application::where('status', 'Shortlisted')
                           // ->whereDoesntHave('offerLetter')
                            ->get()
                            ->mapWithKeys(function ($app) {
                                return [
                                    $app->id => $app->name . ' - ' . $app->college. ' - ' . $app->duration.' ' . $app->duration_unit
                                ];
                            })
                    )
                    ->searchable()
                    ->required(),

                DatePicker::make('joining_date')
                    ->required(),

                DatePicker::make('completion_date')
                    ->required(),

                TextInput::make('internship_role')
                    ->label('Internship Role')
                    ->placeholder('Web Developer / Full Stack Developer, etc..')
                    ->required(),

                TextInput::make('working_hours')
                    ->placeholder('Total hour per week')
                    ->required(),

                Select::make('template')
                    ->label('Offer Letter Template')
                    ->options([
                        '3_month_offer_letter' => '3 Month Offer Letter',
                        '4_month_offer_letter' => '4 Month Offer Letter',
                        '6_month_offer_letter' => '6 Month Offer Letter',
                        'one_month' => 'One Month Internship',
                        'general' => 'General Internship',
                    ])
                    ->required(),

            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('offer_letter_code')
                    ->label('Offer Code')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('application.name')
                    ->label('Intern'),
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

                        // This returns a human-friendly string like "3 months" or "12 weeks"
                        return $start->diffInMonths($end) . ' Months';
                        
                        // Alternatively, for more precision:
                        // return $start->shortAbsoluteDiffForHumans($end, 2); 
                    }),
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
                                'application_id'=>$record->application->id,
                                'intern_code'   => $generatedCode,
                                'username'      => $username,
                                'password'      => \Illuminate\Support\Facades\Hash::make($plainPassword),
                                'name'          => $record->application->name ?? 'Intern ' . $sequence,
                                'email'         => $record->application->email ?? "intern{$sequence}@example.com",
                                'joining_date'  => $record->joining_date ?? now(),
                                'is_active'     => true,
                            ]);

                            $record->update(['intern_id' => $intern->id]);

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
