<?php

namespace App\Filament\Resources\InternManagement;

use App\Filament\Resources\InternManagement\InternResource\Pages;
use App\Filament\Resources\InternManagement\InternResource\RelationManagers;
use App\Models\InterviewManagement\OfferLetter;
use App\Models\InternManagement\Intern;
use App\Models\InterviewManagement\Application;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\{TextInput, TextArea, FileUpload, Select, DatePicker, TimePicker, Section, Grid, RichEditor};
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\{TextColumn, ToggleColumn, BadgeColumn, IconColumn};
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\View;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Set;
use Filament\Forms\Get;

class InternResource extends Resource
{
    protected static ?string $model = Intern::class;

    protected static ?string $navigationIcon = 'heroicon-s-user-group';
    protected static ?string $navigationGroup = 'Intern Management';
    protected static ?int $navigationSort = 3;

    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Intern Selection')
                    ->description('Select an intern to generate or edit their completion details.')
                    ->schema([
                        Select::make('application_id') // Binds to the Intern ID
                            // ->relationship('application', 'name')
                            ->label('Select Intern')
                            ->options(function (?Intern $record) {
                                return Intern::with(['application', 'offerletter'])
                                    ->where(function ($query) use ($record) {
                                        // Always include the current intern when editing
                                        if ($record?->id) {
                                            $query->where('id', $record->id);
                                        }
                                    })
                                    ->orWhereHas('offerletter', fn($q) => $q->where('is_accepted', true))
                                    ->get()
                                    ->mapWithKeys(function (Intern $intern) {
                                        // Priority: offerLetter name → application name → intern_code
                                        $name = $intern->offerletter?->name
                                            ?? $intern->application?->name
                                            ?? $intern->intern_code;

                                        return [$intern->application_id ?? $intern->id => $name];
                                    });
                            })
                            ->searchable()
                            ->preload()
                            // ->required()
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state) {
                                if (!$state) return;

                                $app = Application::with([
                                    'offer_letters' => fn($q) => $q->where('is_accepted', true)
                                ])->find($state);

                                if ($app) {
                                    $offer = $app->offer_letters->first();

                                    // Always prefer offer letter name as that's the legal name
                                    $set('intern_name',          $offer?->name          ?? $app->name);
                                    $set('college',              $offer?->college        ?? $app->college);
                                    $set('degree',               $offer?->degree         ?? $app->degree);
                                    $set('university',           $offer?->university     ?? $app->university);
                                    $set('joining_date',         $offer?->joining_date);
                                    $set('internship_role',      $offer?->internship_role);
                                    $set('internship_position',  $offer?->internship_position);
                                }
                            }),
                    ]),

                Section::make('Completion Details')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('project_name')
                                ->label('Project Name')
                                ->required(),
                            
                            // DatePicker::make('completion_date')
                            //     ->label('Completion Date')
                            //     ->default(now())
                            //     ->afterStateHydrated(fn ($component, $record) => $component->state($record?->offer_letters?->completion_date))
                            //     ->required(),

                            DatePicker::make('issuing_date')
                                ->label('Issuing Date')
                                ->required()
                                ->after('completion_date')
                                ->minDate(fn (Get $get) => $get('completion_date')),

                            Select::make('completion_letter_template')
                            ->label('Completion Letter Template')
                            ->options([
                                'bachelors' => 'Bachelor Degree Completion Letter',
                                'masters' => 'Master Degree Completion Letter',
                            ])
                            ->required()
                            ->native(false) // This makes it look like the modern dropdown in your image
                            ->searchable()   // Optional: allows HR to type and find the template quickly
                            ->placeholder('Select a template')
                            ->columnSpan(1),
                        ]),
                        
                        RichEditor::make('project_description')
                            ->label('Project Description')
                            ->columnSpanFull(),
                    ]),

                Section::make('Editable Fetched Information')
                    ->description('Changes here will update the Offer Letter and Application records.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('intern_name')
                                ->label('Full Name')
                                ->required()
                                ->dehydrated(true) // Keep value on submit
                                ->afterStateHydrated(function ($component, $record) {
                                    $component->state(
                                        $record?->offerletter?->name
                                        ?? $record?->application?->name
                                    );
                                }),
 
                            TextInput::make('degree')
                                ->label('Degree/Course')
                                ->dehydrated(true)
                                ->afterStateHydrated(fn ($component, $record) => $component->state(
                                    $record?->offerletter?->degree ?? $record?->application?->degree
                                )),
 
                            TextInput::make('college')
                                ->label('College')
                                ->dehydrated(true)
                                ->afterStateHydrated(fn ($component, $record) => $component->state(
                                    $record?->offerletter?->college ?? $record?->application?->college
                                )),
 
                            TextInput::make('university')
                                ->label('University')
                                ->dehydrated(true)
                                ->afterStateHydrated(fn ($component, $record) => $component->state(
                                    $record?->offerletter?->university
                                )),
 
                            TextInput::make('internship_role')
                                ->label('Role')
                                ->dehydrated(true)
                                ->afterStateHydrated(fn ($component, $record) => $component->state(
                                    $record?->offerletter?->internship_role
                                )),
 
                            TextInput::make('internship_position')
                                ->label('Position')
                                ->dehydrated(true)
                                ->afterStateHydrated(fn ($component, $record) => $component->state(
                                    $record?->offerletter?->internship_position
                                )),
 
                            DatePicker::make('completion_date')
                                ->label('Completion Date')
                                ->dehydrated(true)
                                ->afterStateHydrated(fn ($component, $record) => $component->state(
                                    $record?->offerletter?->completion_date
                                )),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('3s') // ⬅ auto refresh
            ->columns([
                //
                TextColumn::make('intern_code')
                    ->label('Intern ID')
                    ->sortable(),

                TextColumn::make('application.application_code')
                    ->label('Application ID')
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Intern Name')
                    ->getStateUsing(function ($record) {
                        return $record->offerletter?->name 
                            ?? $record->application?->name 
                            ?? '';
                    })
                    ->searchable(['name']) // Allows searching if 'name' is a column in 'interns' table
                    ->sortable(),

                // TextColumn::make('application.degree ?? ')
                //     ->label('Intern Course')
                //     ->searchable()
                //     ->sortable(),
                
                TextColumn::make('offerletter.internship_role')
                    ->label('Intern Role')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('internship_duration')
                    ->label('Internship Duration')
                    ->getStateUsing(function ($record) {
                        if (!$record->application) 
                        {
                            $start = \Carbon\Carbon::parse($record->offerletter->joining_date);
                            $end = \Carbon\Carbon::parse($record->offerletter->completion_date);
                            $days = (int) $start->diffInDays($end);

                            // If less than 30 days, show in Days
                            if ($days < 30) {
                                return "{$days} " . \Illuminate\Support\Str::plural('Day', $days);
                            }

                            // Otherwise, show in Months (rounded to whole number)
                            $months = (int) round($start->floatDiffInMonths($end));
                            return "{$months} " . \Illuminate\Support\Str::plural('Month', $months);
                        }

                        return $record->application->duration . ' ' . $record->application->duration_unit . '';
                    }),
                TextColumn::make('completion_letter_template')
                    ->label('Letter Template')
                    ->placeholder('Not Selected')
                    ->badge()
                    ->colors([
                        'primary' => 'bachelors',
                        'info' => 'masters',
                        'gray' => null, // Shows gray if no template is selected yet
                    ])
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->sortable(),

                TextColumn::make('completion_date')
                    ->label('Completion Date')
                    ->date('d/m/Y')
                    ->sortable()
                    ->placeholder('Not Completed'),


               ToggleColumn::make('is_active')
                    ->label('Intern Status'),
                // BadgeColumn::make('is_active')
                //     ->colors([
                //         'primary' => 'active',
                //         //'warning' => 'active',
                //         'success' => 'completed',
                //         'danger' => 'dropped',
                //     ])
                    //->formatStateUsing(fn ($state) => ucfirst($state)),

            ])
            ->filters([
                //
            ])
            ->actions([
            Tables\Actions\EditAction::make(),

            Tables\Actions\ActionGroup::make([
                Tables\Actions\Action::make('view_id_card')
                    ->label('I-Card')
                    ->icon('heroicon-o-identification')
                    ->visible(fn ($record) => $record->offerLetter?->is_accepted ?? false)
                    ->url(fn ($record) => route('print-id-card', ['id' => $record->id]))
                    ->openUrlInNewTab(),

                Tables\Actions\Action::make('view_completion_letter')
                    ->label('View Completion Letter')
                    ->icon('heroicon-o-eye')
                    ->color('success')
                    ->visible(fn (Intern $record) => 
                        ($record->offerLetter?->is_accepted ?? false) && 
                        filled($record->completion_letter_template) &&
                        filled($record->project_name)
                    )
                    ->url(fn (Intern $record) => route('intern.completion_letter.view', ['id' => $record->id]))
                    ->openUrlInNewTab(),

                Tables\Actions\Action::make('download_completion_letter')
                    ->label('Download Completion Letter')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->visible(fn (Intern $record) => 
                        ($record->offerLetter?->is_accepted ?? false) && 
                        filled($record->completion_letter_template) &&
                        filled($record->project_name)
                    )
                    ->url(fn (Intern $record) => route('intern.completion_letter.download', ['id' => $record->id]))
                    ->openUrlInNewTab(),

                Tables\Actions\Action::make('view_certificate')
                    ->label('View Certificate')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->visible(fn (Intern $record) => 
                        ($record->offerLetter?->is_accepted ?? false) && 
                        filled($record->completion_letter_template) &&
                        filled($record->project_name)
                    )
                    ->url(fn (Intern $record) => route('intern.certificate.view', ['id' => $record->id]))
                    ->openUrlInNewTab(),

                Tables\Actions\Action::make('print_certificate')
                    ->label('Download Certificate')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info')
                    ->visible(fn (Intern $record) => 
                        ($record->offerLetter?->is_accepted ?? false) && 
                        filled($record->completion_letter_template) &&
                        filled($record->project_name)
                    )
                    ->url(fn (Intern $record) => route('intern.certificate.download', ['id' => $record->id]))
                    ->openUrlInNewTab(),
            ])
            ->icon('heroicon-m-ellipsis-vertical')
            ->color('gray')
            ->button() // Optional: makes the group look like a button
            ->label('Actions'),
            ])
            ->bulkActions([
                    Tables\Actions\BulkAction::make('bulk_print_completion_letter')
                        ->label('Bulk Completion Letters')
                        ->icon('heroicon-o-document-duplicate')
                        ->action(function ($records, $livewire) {
                            // Only pluck IDs for interns who have a template selected
                            $ids = $records->whereNotNull('completion_letter_template')->pluck('id')->implode(',');
                            
                            if (empty($ids)) {
                                Notification::make()->title('No templates selected for these records.')->danger()->send();
                                return;
                            }

                            $url = route('intern.completion_letter.download', ['id' => $ids]);
                            $livewire->js("window.open('" . addslashes($url) . "', '_blank')");
                        }),
                    Tables\Actions\BulkAction::make('bulk_print_certificate')
                        ->label('Bulk Certificate')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('info')
                        ->action(function ($records, $livewire) {
                            $ids = $records->pluck('id')->implode(',');
                            $url = route('intern.certificate.download', ['id' => $ids]);
                            $livewire->js("window.open('" . addslashes($url) . "', '_blank')");
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function canCreate(): bool
    {
    return false;
    }
    public static function can(string $action, ?\Illuminate\Database\Eloquent\Model $record = null): bool
    {
        // This overrides the Policy check entirely for this Resource
        return true; 
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInterns::route('/'),
            'create' => Pages\CreateIntern::route('/create'),
            'edit' => Pages\EditIntern::route('/{record}/edit'),
            'certificate' => Pages\ViewCertificate::route('/{record}/certificate'),
        ];
    }
}
