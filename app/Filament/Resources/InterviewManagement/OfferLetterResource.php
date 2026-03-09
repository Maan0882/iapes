<?php

namespace App\Filament\Resources\InterviewManagement;

use App\Filament\Resources\InterviewManagement\OfferLetterResource\Pages;
use App\Filament\Resources\InterviewManagement\OfferLetterResource\RelationManagers;
use App\Models\InterviewManagement\OfferLetter;
use App\Models\InterviewManagement\Application;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\{Select, DatePicker, TextInput};
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OfferLetterResource extends Resource
{
    protected static ?string $model = OfferLetter::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('application_id')
                    ->label('Select Intern')
                    ->options(
                        Application::where('status', 'Selected')
                            ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->required(),

                DatePicker::make('joining_date')
                    ->required(),

                DatePicker::make('completion_date')
                    ->required(),

                TextInput::make('internship_role')
                    ->label('Internship Role')
                    ->placeholder('Web Developer / Full Stack Developer')
                    ->required(),

                TextInput::make('working_hours')
                    ->placeholder('10 AM - 6 PM')
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
                    ->sortable()
                TextColumn::make('application.name')
                    ->label('Intern'),
                TextColumn::make('internship_role'),
                TextColumn::make('joining_date')->date(),
                TextColumn::make('completion_date')->date(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn ($record) => route('offer.download', $record->id))
                    ->openUrlInNewTab(),

                Tables\Actions\Action::make('print')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->url(fn ($record) => route('offer.print', $record->id))
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListOfferLetters::route('/'),
            'create' => Pages\CreateOfferLetter::route('/create'),
            'edit' => Pages\EditOfferLetter::route('/{record}/edit'),
        ];
    }
}
