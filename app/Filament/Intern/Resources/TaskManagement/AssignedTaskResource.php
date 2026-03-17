<?php

namespace App\Filament\Intern\Resources\TaskManagement;

use App\Filament\Intern\Resources\TaskManagement\AssignedTaskResource\Pages;
use App\Filament\Intern\Resources\TaskManagement\AssignedTaskResource\RelationManagers;
use App\Models\TaskManagement\TaskAssignment;
use App\Models\TaskManagement\TaskSubmission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\{TextInput, TextArea, FileUpload, Select, DatePicker, TimePicker, Placeholder};
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\{Action, BulkAction};
use Filament\Tables\Columns\{TextColumn, ToggleColumn, BadgeColumn, IconColumn};
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;

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

    protected static ?string $navigationIcon = 'heroicon-s-list-bullet';
    protected static ?string $navigationGroup = 'Task Management';
    protected static ?int $navigationSort = 6;

    // 1. IMPORTANT: Filter the query so interns only see their own tasks
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('intern_id', auth()->id()) // Assumes 'intern_id' is the foreign key in TaskAssignment
            ->with(['task', 'task_submission']); // ✅ MUST
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
                ->schema([
                    Section::make('Task Information')
                        ->description('Details of the task assigned to you.')
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    TextEntry::make('task.title')
                                        ->label('Task Title'),

                                    TextEntry::make('task.priority')
                                        ->label('Priority')
                                        ->badge()
                                        ->formatStateUsing(fn ($state) => strtoupper($state)),
                                    
                                    TextEntry::make('task.due_date')
                                        ->label('Deadline')
                                        ->dateTime('M d, Y')
                                        ->placeholder('No deadline'),

                                    TextEntry::make('task_submission.status')
                                        ->label('Current Status')
                                        ->badge()
                                        ->color(fn (string $state): string => match ($state) 
                                        {
                                            'approved' => 'success',
                                            'rejected' => 'danger',
                                            'reviewed' => 'info',
                                            'submitted' => 'warning',
                                            'not submitted' => 'gray',
                                            default => 'gray',
                                        }),
                                    
                                    
                                ]),

                            TextEntry::make('task.description')
                                ->label('Task Description')
                                ->markdown(),

                            TextEntry::make('task.attachment')
                                ->label('Attachment')
                                ->formatStateUsing(fn ($state) => $state ? 'Click here to view attachment' : 'No attachment')
                                ->url(fn ($record) => $record->task->attachment ? asset('storage/' . $record->task->attachment) : null, true)
                                ->color('warning') // TechStrota's yellow-ish gold
                                ->weight('bold'),

                            TextEntry::make('task_submission.admin_feedback')
                                ->label('Admin Remarks')
                                ->placeholder('No feedback provided yet.')
                                ->columnSpanFull()
                                ->prose() // Makes long text more readable
                                ->icon('heroicon-m-chat-bubble-left-right')
                                ->color('info'),
                        ]),
                        
                ]);
    }


    //--------------------------------------------------------

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
                
                 TextColumn::make('task.due_date')
                    ->label('Task Due Date')
                    ->date()
                    ->sortable(),

                TextColumn::make('submission_status')
                    ->label('Task Status')
                    ->getStateUsing(fn ($record) => 
                        $record->task_submission?->status ?? 'not submitted'
                    )
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'reviewed' => 'info',
                        'submitted' => 'warning',
                        'not submitted' => 'gray',
                        default => 'gray',
                    }),
                

                TextColumn::make('task.created_at')
                    ->label('Assigned On')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                //
                SelectFilter::make('status')
                ->options([
                    'submitted' => 'Submitted',
                    'reviewed'=> 'Reviewed',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalHeading('View Assigned Task')
                    ->modalWidth('4xl'), // Makes the modal look good on desktop

                Action::make('submit_task')
                    ->label('Submit Task')
                    ->icon('heroicon-m-paper-airplane')
                    ->color('success')
                    // Only show if the task hasn't been submitted yet or was rejected
                    ->visible(fn (TaskAssignment $record) => 
                        !$record->task_submission || $record->task_submission?->status === 'rejected'
                    )
                    ->form([
                        FileUpload::make('attachment')
                            ->label('Upload File (ZIP/PDF/Image)')
                            ->directory('submissions')
                            ->visibility('public'),
                        
                        TextInput::make('link')
                            ->label('Or Submission Link (GitHub)')
                            ->url()
                            ->placeholder('https://github.com/...'),
                            
                        TextArea::make('notes')
                            ->label('Additional Notes')
                            ->rows(3),
                    ])
                    ->action(function (TaskAssignment $record, array $data): void {
                        // Logic to create the submission
                        TaskSubmission::updateOrCreate(
                            ['task_id' => $record->task_id, 'intern_id' => auth()->id()],
                            [
                                'submission_file' => $data['attachment'],
                                'submission_text' => $data['link'],
                                'notes' => $data['notes'],
                                'status' => 'submitted', // Reset status to pending on (re)submission
                            ]
                        );

                        // Optional: Update status on the Assignment record if you have a status column there too
                        $record->update(['status' => 'submitted']);

                        \Filament\Notifications\Notification::make()
                            ->title('Task submitted successfully!')
                            ->success()
                            ->send();
                    })
                    ->modalWidth('lg')
                    ->requiresConfirmation()
                    ->modalHeading('Submit Your Work'),
            ])


            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                //    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    // 1. Add this method to disable the "Create" button globally for this resource
    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssignedTasks::route('/'),
            //'create' => Pages\CreateAssignedTask::route('/create'),
           // 'view' => Pages\ViewAssignedTask::route('/{record}'),
           // 'edit' => Pages\EditAssignedTask::route('/{record}/edit'),
        ];
    }
}
