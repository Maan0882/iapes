<?php

namespace App\Filament\Resources\InternManagement;

use App\Filament\Resources\InternManagement\InternResource\Pages;
use App\Filament\Resources\InternManagement\InternResource\RelationManagers;
use App\Models\InternManagement\Intern;
use App\Models\InterviewManagement\Application;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\{TextInput, TextArea, FileUpload, Select, DatePicker, TimePicker, Section, Grid};
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\{Action, BulkAction};
use Filament\Tables\Columns\{TextColumn, ToggleColumn, BadgeColumn, IconColumn};
use Filament\Tables\Filters\SelectFilter;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                
            Tables\Actions\Action::make('download_certificate')
    ->label('Completion Letter')
    ->icon('heroicon-o-academic-cap')
    ->color('success')
    // Check if the related OfferLetter is accepted
    ->visible(fn ($record) => $record->offerLetter?->is_accepted ?? false) 
    ->action(function ($record) {
        // Load the offerLetter and its application
        $record->load(['offerLetter', 'application']);
        
        $offer = $record->offerLetter;

        if (!$offer) {
            return; // Or show a notification
        }

        $template = $offer->template ?? 'general';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView("completionletter.$template", [
            // We pass the offer letter object because your Blade expects @foreach($offers)
            'offers' => collect([$offer]),
        ]); 

        $fileName = 'Complettion_Letter_' . str_replace('/', '-', $offer->intern->intern_code);

        return response()->streamDownload(
            fn () => print($pdf->output()),
            $fileName . '.pdf'
        );
    }),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
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
        ];
    }
}
