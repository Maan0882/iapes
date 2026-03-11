<?php

namespace App\Filament\Intern\Resources\TaskManagement;

use App\Filament\Intern\Resources\TaskManagement\AssignedTaskResource\Pages;
use App\Filament\Intern\Resources\TaskManagement\AssignedTaskResource\RelationManagers;
use App\Models\TaskManagement\AssignedTask;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AssignedTaskResource extends Resource
{
    protected static ?string $model = AssignedTask::class;
    protected static ?string $navigationIcon = 'heroicon-s-list-bullet';
    protected static ?string $navigationGroup = 'Task Management';
    protected static ?int $navigationSort = 6;

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
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListAssignedTasks::route('/'),
            'create' => Pages\CreateAssignedTask::route('/create'),
            'view' => Pages\ViewAssignedTask::route('/{record}'),
            'edit' => Pages\EditAssignedTask::route('/{record}/edit'),
        ];
    }
}
