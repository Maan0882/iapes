<?php

namespace App\Filament\Resources\InternManagement;

use App\Filament\Resources\InternManagement\InternshipBatchResource\Pages;
use App\Filament\Resources\InternManagement\InternshipBatchResource\RelationManagers;
use App\Models\InternManagement\InternshipBatch;
use App\Models\InternManagement\Intern;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\{TextInput, TextArea, FileUpload, Select, DatePicker, TimePicker, Section, Grid};
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InternshipBatchResource extends Resource
{
    protected static ?string $model = InternshipBatch::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
               Select::make('interns')
                    ->label('Select Interns')
                    ->multiple()
                    ->options(
                        Intern::whereNull('internship_batch_id')
                            ->pluck('name')
                    )
                    ->searchable()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListInternshipBatches::route('/'),
            'create' => Pages\CreateInternshipBatch::route('/create'),
            'edit' => Pages\EditInternshipBatch::route('/{record}/edit'),
        ];
    }
}
