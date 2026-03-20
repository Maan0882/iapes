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
    protected static ?int $navigationSort = 5;

    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Select::make('applications')
                    ->label('Select Intern')
                    //->multiple()
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
                    ->required()
                   ->live() // This ensures the form state updates immediately when an intern is selected
                   ->afterStateUpdated(function (Set $set, Get $get, $state) {
                    if (!$state) return;

                    // Fetch data from both tables
                    $application = Application::find($state);
                    $offerLetter = OfferLetter::where('application_id', $state)->first();

                    if ($application) {
                        // Set values from Application table
                        $set('college', $application->college);
                       // $set('project_name', $application->project_title ?? ''); // Example of another column
                    }

                    if ($offerLetter) {
                        // Set values from Offer Letter table
                        $set('joining_date', $offerLetter->joining_date);
                        
                        // Recalculate completion date immediately
                        //self::updateCompletionDate($set, $get);
                    }
                }),

                TextInput::make('college')
                    ->live()
                    ->label('College Name')
                    ->placeholder('Web Developer / Full Stack Developer, etc..')
                    ->required(),

               
                DatePicker::make('joining_date')
                    ->live()
                    ->native(false)
                    ->displayFormat('d-m-Y')
                    ->afterStateUpdated(fn (Set $set, Get $get) => self::updateCompletionDate($set, $get)),

                DatePicker::make('completion_date')
                ->native(false)
                ->displayFormat('d-m-Y'),


                Select::make('template')
                    ->label('Completion Letter Template')
                    ->options([
                        '3_month_offer_letter' => '3 Month Completion Letter',
                        //'4_month_offer_letter' => '4 Month Offer Letter',
                        'masters_offer_letter' => 'Master Completion Letter',
                        // 'one_month' => 'One Month Internship',
                        // 'general' => 'General Internship',
                    ])
                    ->required(),
                
                TextInput::make('project_name')
                    ->placeholder('Project Name'),
                    

                RichEditor::make('project_description')
                    ->placeholder('Project Description'),
                    
            
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('intern_code')
                    ->label('Intern ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('application.application_code')
                    ->label('Application ID')
                    ->searchable(),

                Tables\Columns\TextColumn::make('application.name')
                    ->label('Intern Name')
                    //->description(fn ($record) => $record->application->name)
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('application.degree')
                    ->label('Intern Course')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('application.domain')
                    ->label('Intern Domain')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('internship_duration')
                    ->label('Internship Duration')
                    ->getStateUsing(function ($record) {

                        if (!$record->application) 
                        {
                            return 'No Application';
                        }

                        return $record->application->duration . ' ' . $record->application->duration_unit . '';
                    }),



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
                ->url(fn ($record) => route('view-id-card', ['id' => $record->id]))
                ->openUrlInNewTab(),
            //----------------------------------------------------------------------------
                
            Tables\Actions\Action::make('view_completion_letter')
                ->label('Completion Letter')
                ->icon('heroicon-o-academic-cap')
                ->color('success')
                // Check the relationship to the OfferLetter model
                ->visible(fn ($record) => $record->offerLetter?->is_accepted ?? false)
                ->url(function ($record) {
                    // Points to the new route we will define below
                    return route('view-completion-pdf', ['id' => $record->id]);
                })
                ->openUrlInNewTab(),
                
            Tables\Actions\Action::make('print_certificate')
                    ->label('Certificate')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('info')
                    ->visible(fn ($record) => $record->offerLetter?->is_accepted ?? false)
                    ->url(fn (Intern $record): string => route('intern.certificate.download', ['id' => $record->id]))
                    ->openUrlInNewTab(),

            Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
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
