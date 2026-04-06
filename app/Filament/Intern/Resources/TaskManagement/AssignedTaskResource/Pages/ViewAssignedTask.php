<?php

namespace App\Filament\Intern\Resources\TaskManagement\AssignedTaskResource\Pages;

use App\Filament\Intern\Resources\TaskManagement\AssignedTaskResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;
use Illuminate\Support\HtmlString;

class ViewAssignedTask extends ViewRecord
{
    protected static string $resource = AssignedTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Back')
                ->icon('heroicon-m-arrow-left')
                ->color('gray')
                ->url(AssignedTaskResource::getUrl('index')), // redirect to list page
        ];
    }

    public function infolist(Infolist $infolist): Infolist
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
                                
                                    // --- ADDED THIS ---
                                    TextEntry::make('assigned_type')
                                    ->label('Task Assigned To')
                                    ->formatStateUsing(function ($record) {
                                            return match ($record->assigned_type) {
                                                'intern' => "Intern",
                                                'team'   => "Team",
                                                'batch'  => "Batch",
                                                default  => 'Unassigned',
                                            };
                                    }),

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
                    ]),
            ]);
    }
}
