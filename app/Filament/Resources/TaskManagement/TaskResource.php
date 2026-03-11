<?php

namespace App\Filament\Resources\TaskManagement;

use App\Filament\Resources\TaskManagement\TaskResource\Pages;
use App\Filament\Resources\TaskManagement\TaskResource\RelationManagers;
use App\Models\TaskManagement\Task;
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

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                TextInput::make('title')
                ->required()
                ->maxLength(255),

            Textarea::make('description')
                ->rows(4),

            DatePicker::make('due_date'),

            Select::make('priority')
                ->options([
                    'low' => 'Low',
                    'medium' => 'Medium',
                    'high' => 'High',
                ])
                ->default('medium'),

            FileUpload::make('attachment')
                ->directory('task-files'),

            Select::make('assigned_type')
                ->label('Assign To')
                ->options([
                    'intern' => 'Intern',
                    'team' => 'Team',
                    'batch' => 'Batch',
                ])
                ->live()
                ->required(),

            Select::make('intern_id')
                ->label('Select Intern')
                ->relationship('intern','name')
                ->visible(fn ($get) => $get('assigned_type') === 'intern'),

            Select::make('team_id')
                ->label('Select Team')
                ->relationship('team','team_name')
                ->visible(fn ($get) => $get('assigned_type') === 'team'),

            Select::make('batch_id')
                ->label('Select Batch')
                ->relationship('batch','batch_name')
                ->visible(fn ($get) => $get('assigned_type') === 'batch'),

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
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
