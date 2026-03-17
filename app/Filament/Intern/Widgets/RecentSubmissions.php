<?php

namespace App\Filament\Intern\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

use App\Models\TaskManagement\TaskSubmission;
use Illuminate\Support\Facades\Auth;
use App\Filament\Intern\Resources\TaskManagement\AssignedTaskResource;
//use Filament\Tables\Actions\ViewAction;

class RecentSubmissions extends BaseWidget
{
    // Make the table take up more space on the 4K screen
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Only show submissions for the logged-in intern
                TaskSubmission::query()
                    ->where('intern_id', Auth::id())
                    ->latest()
            )
            ->columns([
                // ...
                Tables\Columns\TextColumn::make('task.title') // Assumes a 'task' relationship exists
                    ->label('Task Name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('submitted_at')
                    ->label('Date Submitted')
                    ->date('d M, Y')
                    ->sortable(),

            
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'reviewed' => 'info',
                        'submitted' => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\IconColumn::make('submission_file')
                    ->label('File')
                    ->icon('heroicon-m-document-text')
                    ->color('primary')
                    ->url(fn ($record) => $record->submission_file ? asset('storage/' . $record->submission_file) : null)
                    ->openUrlInNewTab(),
            ])

            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('View Task')
                    ->icon('heroicon-m-eye')
                    ->infolist(fn ($infolist) => AssignedTaskResource::infolist($infolist))
                    // Optional: Ensure it opens in a modal
                    ->modalWidth('5xl')
                    //->resource(AssignedTaskResource::class)
                    // ->url(fn ($record): ?string => 
                    //     $record->taskAssignment
                    //         ? route('filament.intern.resources.task-management.assigned-tasks.view', $record->task->task_id)
                    //         : null
                    // )
                    ->disabled(fn ($record) => !$record->taskAssignment)
            ]);
    }
}
