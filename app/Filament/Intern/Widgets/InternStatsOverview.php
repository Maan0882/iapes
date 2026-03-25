<?php

namespace App\Filament\Intern\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\TaskManagement\TaskAssignment;
use App\Models\TaskManagement\TaskSubmission;
use Illuminate\Support\Facades\Auth;

class InternStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $internId = Auth::id();

        $totalAssigned   = TaskAssignment::where('intern_id', $internId)->count();
        $submittedTaskIds = TaskSubmission::where('intern_id', $internId)->pluck('task_id');
        $pendingCount    = TaskAssignment::where('intern_id', $internId)
                            ->whereNotIn('task_id', $submittedTaskIds)->count();
        $approvedCount   = TaskSubmission::where('intern_id', $internId)->where('status', 'approved')->count();
        $rejectedCount   = TaskSubmission::where('intern_id', $internId)->where('status', 'rejected')->count();

        return [
            Stat::make('Total Assigned', $totalAssigned)
                ->description('Overall task workload')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->icon('heroicon-m-clipboard-document-list')
                ->color('primary')
                ->chart([3, 5, 4, 6, $totalAssigned])
                ->url(route('filament.intern.resources.task-management.assigned-tasks.index'))
                ->openUrlInNewTab(),

            Stat::make('Pending', $pendingCount)
                ->description($pendingCount > 0 ? 'Action required' : 'All caught up')
                ->descriptionIcon($pendingCount > 0 ? 'heroicon-m-exclamation-circle' : 'heroicon-m-check-circle')
                ->icon('heroicon-m-clock')
                ->color($pendingCount > 0 ? 'warning' : 'success')
                ->chart([2, 4, 3, $pendingCount, $pendingCount]),

            Stat::make('Approved', $approvedCount)
                ->description('Quality work delivered')
                ->descriptionIcon('heroicon-m-check-badge')
                ->icon('heroicon-m-check-circle')
                ->color('success')
                ->chart([1, 2, 3, 4, $approvedCount]),

            Stat::make('Rejections', $rejectedCount)
                ->description($rejectedCount > 0 ? 'Review needed' : 'Excellent consistency')
                ->descriptionIcon($rejectedCount > 0 ? 'heroicon-m-arrow-path' : 'heroicon-m-star')
                ->icon('heroicon-m-x-circle')
                ->color($rejectedCount > 0 ? 'danger' : 'gray')
                ->chart([0, 1, 0, $rejectedCount, $rejectedCount]),
        ];
    }
}