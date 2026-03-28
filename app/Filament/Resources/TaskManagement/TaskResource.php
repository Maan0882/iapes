<?php

namespace App\Filament\Resources\TaskManagement;

use App\Filament\Resources\TaskManagement\TaskResource\Pages;
use App\Filament\Resources\TaskManagement\TaskResource\RelationManagers;
use App\Models\TaskManagement\Task;
use App\Models\TaskManagement\TaskAssignment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\{TextInput, Textarea, FileUpload, Select, DatePicker, TimePicker, Section, Grid};
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\{Action, BulkAction};
use Filament\Tables\Columns\{TextColumn, ToggleColumn, BadgeColumn, IconColumn};
use Filament\Tables\Filters\SelectFilter;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;
    protected static ?string $navigationIcon = 'heroicon-s-list-bullet';
    protected static ?string $navigationGroup = 'Task And Evaluation Management';
    protected static ?int $navigationSort = 9;
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
                ->directory('task-files')
                ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file, callable $get): string {
                        $title = $get('title');
                        $slugifiedTitle = filled($title) ? Str::slug($title) : 'task';
                        
                        // Get original filename without the extension
                        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        
                        // Get original extension
                        $extension = $file->getClientOriginalExtension();

                        // Format: title-originalfilename.extension
                        // Example: my-task-document123.pdf
                        return (string) str($slugifiedTitle . '-' . Str::slug($originalName) . '-' . str()->random(4) . '.' . $extension);
                    }),

            Select::make('assigned_type')
                ->label('Assign To')
                ->options([
                    'intern' => 'Intern',
                    'team' => 'Team',
                    'batch' => 'Batch',
                ])
                ->live()
                ->required()
                ->dehydrated(false), // Add this
                

            Select::make('intern_id')
                ->label('Select Intern')
                //->multiple()
                // Use options instead of relationship to avoid the SQL save error
                ->options(\App\Models\InternManagement\Intern::pluck('name', 'id'))
                ->visible(fn ($get) => $get('assigned_type') === 'intern')
                ->dehydrated(false), // Add this

            Select::make('team_id')
                ->label('Select Team')
                ->options(\App\Models\InternManagement\InternTeam::pluck('team_name', 'id'))
                ->visible(fn ($get) => $get('assigned_type') === 'team')
                ->dehydrated(false), // Add this

            Select::make('batch_id')
                ->label('Select Batch')
                ->options(\App\Models\InternManagement\InternshipBatch::pluck('batch_name', 'id'))
                ->visible(fn ($get) => $get('assigned_type') === 'batch')
                ->dehydrated(false), // Add this

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),

                Tables\Columns\TextColumn::make('priority'),

                Tables\Columns\TextColumn::make('due_date')
                    ->date(),


                Tables\Columns\TextColumn::make('assignments')
                        ->label('Assigned To')
                        ->formatStateUsing(function ($record) {
                            // Map through assignments to find the relevant name
                            return $record->assignments->map(function ($assignment) {
                                return match ($assignment->assigned_type) {
                                    'intern' => "Intern: " . ($assignment->intern?->name ?? 'N/A'),
                                    'team'   => "Team: " . ($assignment->team?->team_name ?? 'N/A'),
                                    'batch'  => "Batch: " . ($assignment->batch?->batch_name ?? 'N/A'),
                                    default  => 'Unassigned',
                                };
                            })->implode(', '); // Useful if a task has multiple assignments
                        })
                        ->wrap(), // Good for readability if there are multiple names


                Tables\Columns\TextColumn::make('created_at')
                    ->label('Task Created On')
                    ->date(),

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
