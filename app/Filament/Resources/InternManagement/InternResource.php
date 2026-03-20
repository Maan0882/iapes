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
                Section::make('Intern Selection')
                    ->description('Select an intern to generate or edit their completion details.')
                    ->schema([
                        Select::make('id') // Binds to the Intern ID
                            ->label('Select Intern')
                            ->options(
                                Intern::with(['application', 'offer_letters'])
                                    ->whereHas('offer_letters', fn ($query) => $query->where('is_accepted', true))
                                    ->get()
                                    ->mapWithKeys(fn ($intern) => [
                                        $intern->id => "{$intern->intern_code} - {$intern->application->name}"
                                    ])
                            )
                            ->searchable()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state) {
                                if (!$state) return;

                                $intern = Intern::with(['application', 'offer_letters'])->find($state);
                                if (!$intern) return;

                                // Fetch from Application
                                $set('intern_name', $intern->application->name);
                                $set('college', $intern->application->college);
                                $set('degree', $intern->application->degree);

                                // Fetch from Offer Letter
                                if ($intern->offer_letters) {
                                    $set('joining_date', $intern->offer_letters->joining_date);
                                    $set('internship_role', $intern->offer_letters->internship_role);
                                    $set('internship_position', $intern->offer_letters->internship_position);
                                    $set('university', $intern->offer_letters->university);
                                }
                            }),
                    ]),

                Section::make('Completion Details')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('project_name')
                                ->label('Project Name')
                                ->required(),
                            
                            DatePicker::make('completion_date')
                                ->label('Completion Date')
                                ->default(now())
                                ->required(),
                        ]),
                        
                        RichEditor::make('project_description')
                            ->label('Project Description')
                            ->columnSpanFull(),

                        Select::make('completion_letter_template')
                            ->label('Certificate Template')
                            ->options([
                                'bachelors' => 'Bachelor Degree Template',
                                'masters' => 'Master Degree Template',
                            ])
                            ->required()
                            ->native(false),
                    ]),

                Section::make('Editable Fetched Information')
                    ->description('Changes here will update the Application and Offer Letter records.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('intern_name')
                                ->label('Full Name')
                                ->required(),
                            TextInput::make('degree')
                                ->label('Degree/Course'),
                            TextInput::make('college')
                                ->label('College'),
                            TextInput::make('university')
                                ->label('University'),
                            TextInput::make('internship_role')
                                ->label('Role'),
                            TextInput::make('internship_position')
                                ->label('Position'),
                            DatePicker::make('joining_date')
                                ->label('Joining Date'),
                        ]),
                    ]),
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
