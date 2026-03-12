<?php

namespace App\Filament\Intern\Resources\TaskManagement;

use App\Filament\Intern\Resources\TaskManagement\AssignedTaskResource\Pages;
use App\Filament\Intern\Resources\TaskManagement\AssignedTaskResource\RelationManagers;
use App\Models\TaskManagement\TaskAssignment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\{TextInput, TextArea, FileUpload, Select, DatePicker, TimePicker, Section, Grid, Placeholder};
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\{Action, BulkAction};
use Filament\Tables\Columns\{TextColumn, ToggleColumn, BadgeColumn, IconColumn};
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AssignedTaskResource extends Resource
{
    protected static ?string $model = TaskAssignment::class;
    // This changes the text in the Sidebar
    protected static ?string $navigationLabel = 'Assigned Task';
    // This changes the Heading on the List page
    public static function getPluralLabel(): ?string
    {
        return 'Assigned Tasks';
    }
    // This changes "New Internship Batch" button to "New Intern Batch"
    public static function getModelLabel(): string
    {
        return 'Assigned Task';
    }

    protected static ?string $navigationIcon = 'heroicon-s-list-bullet';
    protected static ?string $navigationGroup = 'Task Management';
    protected static ?int $navigationSort = 6;

    // 1. IMPORTANT: Filter the query so interns only see their own tasks
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('intern_id', auth()->id()); // Assumes 'user_id' is the foreign key in TaskAssignment
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                // Assuming TaskAssignment belongsTo a 'Task' model
                        Placeholder::make('task_name')
                            ->content(fn ($record) => $record?->task?->title),
                        Textarea::make('notes')
                            ->disabled(), // Tasks are usually read-only for interns
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'in_progress' => 'In Progress',
                                'completed' => 'Completed',
                            ])->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                    // Adjust these based on your TaskAssignment & Task relationships
                TextColumn::make('task.title')
                    ->label('Task Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('task.priority')
                    ->label('Task Priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'medium' => 'primary',
                        'high' => 'danger',
                        'low' => 'success',
                        //default => 'primary',
                    }),
                
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'in_progress' => 'warning',
                        'completed' => 'success',
                        default => 'primary',
                    }),

                TextColumn::make('task.due_date')
                    ->date()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Assigned On')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),

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
