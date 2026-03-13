<?php

namespace App\Filament\Intern\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use App\Models\TaskManagement\TaskAssignment;
use App\Models\TaskManagement\TaskSubmission;
use Illuminate\Support\Facades\Auth;

class InternStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $internId = Auth::id();

        // 1. Total Tasks Assigned (Directly or via Team/Batch if applicable)
        $totalAssigned = TaskAssignment::where('intern_id', $internId)->count();

        // 2. Pending Submissions (Tasks assigned but not in submissions table yet)
        $submittedTaskIds = TaskSubmission::where('intern_id', $internId)->pluck('task_id');
        $pendingCount = TaskAssignment::where('intern_id', $internId)
            ->whereNotIn('task_id', $submittedTaskIds)
            ->count();

        // 3. Approved Tasks (Based on your status enum)
        $approvedCount = TaskSubmission::where('intern_id', $internId)
            ->where('status', 'approved')
            ->count();

        // 4. Rejection Rate / Alert
        $rejectedCount = TaskSubmission::where('intern_id', $internId)
            ->where('status', 'rejected')
            ->count();

        return [
            Stat::make('Total Assigned Tasks', $totalAssigned)
                ->icon('heroicon-m-clipboard-document-list')
                ->color('info')
                // Link to the full list
                ->url(route('filament.intern.resources.task-management.assigned-tasks.index'))
                ->openUrlInNewTab(),

            Stat::make('Pending Tasks', $pendingCount)
                ->description($pendingCount > 0 ? 'Action required' : 'All caught up!')
                ->descriptionIcon($pendingCount > 0 ? 'heroicon-m-exclamation-circle' : 'heroicon-m-check-circle')
                ->color($pendingCount > 0 ? 'warning' : 'success'),

            Stat::make('Approved Submissions', $approvedCount)
                ->description('Finalized work')
                ->icon('heroicon-m-hand-thumb-up')
                ->color('success'),

            Stat::make('Rejections', $rejectedCount)
                ->description('Needs revision')
                ->icon('heroicon-m-x-circle')
                ->color($rejectedCount > 0 ? 'danger' : 'gray'),
        ];
    }
}
