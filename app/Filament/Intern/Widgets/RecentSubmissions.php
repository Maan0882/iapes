<?php

namespace App\Filament\Intern\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

use App\Models\TaskManagement\TaskSubmission;
use Illuminate\Support\Facades\Auth;
use App\Filament\Intern\Resources\TaskManagement\AssignedTaskResource;

class RecentSubmissions extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 4;
    protected static ?string $heading = 'Recent Submissions';

    public function table(Table $table): Table
    {
        return $table
            ->query(
            TaskSubmission::query()
            ->where('intern_id', Auth::id())
            ->latest()
        )
            ->columns([
            Tables\Columns\TextColumn::make('task.title')
            ->label('Task Name')
            ->searchable()
            ->weight(\Filament\Support\Enums\FontWeight::Medium)
            ->size(Tables\Columns\TextColumn\TextColumnSize::Small),

            Tables\Columns\TextColumn::make('submitted_at')
            ->label('Date Submitted')
            ->date('d M, Y')
            ->sortable()
            ->color('gray')
            ->size(Tables\Columns\TextColumn\TextColumnSize::Small),

            Tables\Columns\TextColumn::make('status')
            ->badge()
            ->formatStateUsing(fn(string $state): string => ucfirst($state))
            ->color(fn($state) => match ($state) {
            'approved' => 'success',
            'rejected' => 'danger',
            'reviewed' => 'info',
            'submitted' => 'gray',
            default => 'gray',
        })
            ->size(Tables\Columns\TextColumn\TextColumnSize::Small),

            Tables\Columns\IconColumn::make('submission_file')
            ->label('File')
            ->icon(fn($state) => $state ? 'heroicon-m-document-text' : 'heroicon-m-minus')
            ->color(fn($state) => $state ? 'primary' : 'gray')
            ->url(fn($record) => $record->submission_file
        ? asset('storage/' . $record->submission_file)
        : null
        )
            ->openUrlInNewTab(),
        ])
            ->actions([
            Tables\Actions\Action::make('view')
            ->label('View Task')
            ->icon('heroicon-m-eye')
            ->size(\Filament\Support\Enums\ActionSize::Small)
            ->color('gray')
            ->infolist(fn($infolist) => AssignedTaskResource::infolist($infolist))
            ->modalWidth('5xl')
            ->disabled(fn($record) => !$record->taskAssignment),
        ])
            ->striped(false)
            ->paginated([5, 10, 25]);
    }
}