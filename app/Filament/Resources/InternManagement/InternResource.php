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
                            ->relationship('application', 'name')
                            ->label('Select Intern')
                            ->options(
                                Application::query()
                                    ->whereHas('offer_letters', function ($query) {
                                        $query->where('is_accepted', true);
                                    })
                                    // This ensures we don't create duplicate intern records for the same application
                                    ->whereDoesntHave('intern') 
                                    ->get()
                                    ->pluck('id', 'name')
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state) {
                                if (!$state) return;

                                $app = Application::with(['offer_letters' => fn($q) => $q->where('is_accepted', true)])->find($state);
                                
                                if ($app) {
                                    $set('intern_name', $app->name);
                                    $set('college', $app->college);
                                    $set('degree', $app->degree);

                                    $offer = $app->offer_letters->first();
                                    if ($offer) {
                                        $set('joining_date', $offer->joining_date);
                                        $set('completion_date', $offer->completion_date);
                                        $set('internship_role', $offer->internship_role);
                                        $set('internship_position', $offer->internship_position);
                                        $set('university', $offer->university);
                                    }
                                }
                            }),
                    ]),

                Section::make('Completion Details')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('project_name')
                                ->label('Project Name')
                                ->required(),
                            
                            DatePicker::make('completion_date')
                                ->label('Completion Date')
                                ->default(now())
                                ->afterStateHydrated(fn ($component, $record) => $component->state($record?->offer_letters?->completion_date))
                                ->required(),

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
                    ->description('Changes here will update the Application and Offer Letter records.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('intern_name')
                                ->label('Full Name')
                                ->required()
                                ->afterStateHydrated(fn ($component, $record) => $component->state($record?->application?->name)),
                            TextInput::make('degree')
                                ->label('Degree/Course')
                                ->afterStateHydrated(fn ($component, $record) => $component->state($record?->application?->degree)),
                            TextInput::make('college')
                                ->label('College')
                                ->afterStateHydrated(fn ($component, $record) => $component->state($record?->application?->college)),
                            TextInput::make('university')
                                ->label('University')
                                ->afterStateHydrated(fn ($component, $record) => $component->state($record?->offer_letters?->university)),
                            TextInput::make('internship_role')
                                ->label('Role')
                                ->afterStateHydrated(fn ($component, $record) => $component->state($record?->offer_letters?->internship_role)),
                            TextInput::make('internship_position')
                                ->label('Position')
                                ->afterStateHydrated(fn ($component, $record) => $component->state($record?->offer_letters?->internship_position)),
                            DatePicker::make('joining_date')
                                ->label('Joining Date')
                                ->afterStateHydrated(fn ($component, $record) => $component->state($record?->offer_letters?->joining_date)),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
                        // Priority 1: Name on the Offer Letter (Legal)
                        // Priority 2: Name on the Application
                        return $record->offerLetter?->name 
                            ?? $record->application?->name 
                            ?? 'Unknown Name';
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
                            return 'No Application';
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

            Tables\Actions\Action::make('view_id_card')
                ->label('I-Card')
                ->icon('heroicon-o-identification')
                ->color('primary')
                // Check the relationship to the OfferLetter model
                ->visible(fn ($record) => $record->offerLetter?->is_accepted ?? false)
                ->url(fn ($record) => route('print-id-card', ['id' => $record->id]))
                ->openUrlInNewTab(),
            //----------------------------------------------------------------------------
            Tables\Actions\Action::make('view_completion_letter')
                ->label('View Completion Letter')
                ->icon('heroicon-o-eye')
                ->color('success')
                ->visible(fn (Intern $record) => 
                    ($record->offerLetter?->is_accepted ?? false) && 
                    filled($record->completion_letter_template) &&
                    filled($record->project_name)
                )
                ->url(fn (Intern $record): string => route('intern.completion_letter.view', ['id' => $record->id]))
                ->openUrlInNewTab(),

            Tables\Actions\Action::make('print_completion_letter')
                ->label('Completion Letter')
                ->icon('heroicon-o-document-check')
                ->color('success')
                // The button only shows if an offer is accepted AND a template is chosen
                ->visible(fn (Intern $record) => 
                    ($record->offerLetter?->is_accepted ?? false) && 
                    filled($record->completion_letter_template) &&
                    filled($record->project_name)
                )
                ->url(fn (Intern $record): string => route('intern.completion_letter.download', ['id' => $record->id]))
                ->openUrlInNewTab(),

            Tables\Actions\Action::make('view_certificate')
                ->label('View Certificate')
                ->icon('heroicon-o-eye')
                ->color('info')
                // Logic: Only visible if Offer is accepted AND Completion Letter details exist
                ->visible(fn (Intern $record) => 
                    ($record->offerLetter?->is_accepted ?? false) && 
                    filled($record->completion_letter_template) &&
                    filled($record->project_name)
                )
                ->url(fn (Intern $record): string => route('intern.certificate.view', ['id' => $record->id]))
                ->openUrlInNewTab(),

            Tables\Actions\Action::make('print_certificate')
                ->label('Certificate')
                ->icon('heroicon-o-document-arrow-down')
                ->color('info')
                ->visible(fn (Intern $record) => 
                    ($record->offerLetter?->is_accepted ?? false) && 
                    filled($record->completion_letter_template) &&
                    filled($record->project_name)
                )
                ->url(fn (Intern $record): string => route('intern.certificate.download', ['id' => $record->id]))
                ->openUrlInNewTab(),

            Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
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
                ]),
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
