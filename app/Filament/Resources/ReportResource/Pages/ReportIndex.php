<?php

namespace App\Filament\Resources\ReportResource\Pages;

use App\Filament\Resources\ReportResource;
use Filament\Resources\Pages\Page;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;

use App\Models\InterviewManagement\Application;
use App\Models\InterviewManagement\InterviewAssignment;
use App\Models\InternManagement\Intern;
use App\Models\InternManagement\InternshipBatch;
use App\Models\InternManagement\InternTeam;
use App\Models\TaskManagement\Task;
use App\Models\TaskManagement\TaskSubmission;
use App\Models\TaskManagement\TaskAssignment;

use Carbon\Carbon;

class ReportIndex extends Page
{
    protected static string $resource = ReportResource::class;

    protected static string $view = 'filament.resources.report-resource.pages.report-index';

    protected ?string $heading = 'Reports & Analytics';
    protected ?string $subheading = 'Comprehensive overview of Applications, Interns, and Tasks';

    // ─────────────────────────────────────────────
    // DATA GATHERING
    // ─────────────────────────────────────────────

    public function getApplicationStats(): array
    {
        $apps = Application::whereNotIn('status', ['pending', 'verified']);

        $statusCounts = (clone $apps)->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $domainBreakdown = (clone $apps)->selectRaw('domain, count(*) as total')
            ->groupBy('domain')
            ->orderByDesc('total')
            ->limit(8)
            ->get()
            ->toArray();

        $monthlyTrend = collect(range(5, 0))->map(function ($m) use ($apps) {
            $date = Carbon::now()->subMonths($m);
            return [
                'month' => $date->format('M Y'),
                'count' => (clone $apps)
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
            ];
        });

        $interviews = InterviewAssignment::selectRaw('result, attendance, count(*) as total')
            ->groupBy('result', 'attendance')
            ->get();

        $avgCgpa = (clone $apps)->avg('cgpa');

        $durationBreakdown = (clone $apps)
            ->selectRaw('duration, duration_unit, count(*) as total')
            ->groupBy('duration', 'duration_unit')
            ->orderByDesc('total')
            ->limit(6)
            ->get()
            ->toArray();

        return [
            'total'            => (clone $apps)->count(),
            'applied'          => $statusCounts['applied'] ?? 0,
            'interview_scheduled' => $statusCounts['interview_scheduled'] ?? 0,
            'interviewed'      => $statusCounts['interviewed'] ?? 0,
            'shortlisted'      => $statusCounts['shortlisted'] ?? 0,
            'rejected'         => $statusCounts['rejected'] ?? 0,
            'domain_breakdown' => $domainBreakdown,
            'monthly_trend'    => $monthlyTrend,
            'interview_present'=> InterviewAssignment::where('attendance', 'present')->count(),
            'interview_absent' => InterviewAssignment::where('attendance', 'absent')->count(),
            'selected'         => InterviewAssignment::where('result', 'selected')->count(),
            'not_selected'     => InterviewAssignment::where('result', 'not_selected')->count(),
            'avg_cgpa'         => round($avgCgpa, 2),
            'duration_breakdown' => $durationBreakdown,
            'conversion_rate'  => (clone $apps)->count() > 0
                ? round((($statusCounts['shortlisted'] ?? 0) / (clone $apps)->count()) * 100, 1)
                : 0,
        ];
    }

    public function getInternStats(): array
    {
        $interns = Intern::query();

        $activeCount   = (clone $interns)->where('is_active', true)->count();
        $inactiveCount = (clone $interns)->where('is_active', false)->count();
        $total         = (clone $interns)->count();

        $batchBreakdown = InternshipBatch::withCount('interns')
            ->orderByDesc('interns_count')
            ->get()
            ->map(fn($b) => ['name' => $b->batch_name, 'count' => $b->interns_count])
            ->toArray();

        $teamBreakdown = InternTeam::withCount('interns')
            ->orderByDesc('interns_count')
            ->get()
            ->map(fn($t) => ['name' => $t->team_name, 'count' => $t->interns_count])
            ->toArray();

        $monthlyJoining = collect(range(5, 0))->map(function ($m) use ($interns) {
            $date = Carbon::now()->subMonths($m);
            return [
                'month' => $date->format('M Y'),
                'count' => (clone $interns)
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
            ];
        });

        return [
            'total'           => $total,
            'active'          => $activeCount,
            'inactive'        => $inactiveCount,
            'batch_breakdown' => $batchBreakdown,
            'team_breakdown'  => $teamBreakdown,
            'monthly_joining' => $monthlyJoining,
            'with_project'    => (clone $interns)->whereNotNull('project_name')->count(),
            'without_project' => (clone $interns)->whereNull('project_name')->count(),
        ];
    }

    public function getTaskStats(): array
    {
        $tasks       = Task::query();
        $submissions = TaskSubmission::query();

        $priorityBreakdown = (clone $tasks)
            ->selectRaw('priority, count(*) as total')
            ->groupBy('priority')
            ->pluck('total', 'priority')
            ->toArray();

        $submissionStatus = (clone $submissions)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $avgMarks = (clone $submissions)->whereNotNull('marks')->avg('marks');

        $gradeDistribution = (clone $submissions)
            ->whereNotNull('grade')
            ->selectRaw('grade, count(*) as total')
            ->groupBy('grade')
            ->orderByDesc('total')
            ->pluck('total', 'grade')
            ->toArray();

        $overdueTasks = (clone $tasks)
            ->whereDate('due_date', '<', Carbon::today())
            ->count();

        $dueSoon = (clone $tasks)
            ->whereDate('due_date', '>=', Carbon::today())
            ->whereDate('due_date', '<=', Carbon::today()->addDays(7))
            ->count();

        $totalTasks      = (clone $tasks)->count();
        $totalSubmissions = (clone $submissions)->count();
        $totalAssignments = TaskAssignment::count();

        $submissionRate = $totalAssignments > 0
            ? round(($totalSubmissions / $totalAssignments) * 100, 1)
            : 0;

        $monthlyTasks = collect(range(5, 0))->map(function ($m) use ($tasks) {
            $date = Carbon::now()->subMonths($m);
            return [
                'month' => $date->format('M Y'),
                'count' => (clone $tasks)
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
            ];
        });

        return [
            'total'              => $totalTasks,
            'priority_high'      => $priorityBreakdown['high'] ?? 0,
            'priority_medium'    => $priorityBreakdown['medium'] ?? 0,
            'priority_low'       => $priorityBreakdown['low'] ?? 0,
            'total_submissions'  => $totalSubmissions,
            'total_assignments'  => $totalAssignments,
            'submission_rate'    => $submissionRate,
            'avg_marks'          => round($avgMarks ?? 0, 1),
            'grade_distribution' => $gradeDistribution,
            'submission_status'  => $submissionStatus,
            'overdue'            => $overdueTasks,
            'due_soon'           => $dueSoon,
            'monthly_tasks'      => $monthlyTasks,
            'evaluated'          => (clone $submissions)->whereNotNull('marks')->count(),
            'pending_evaluation' => (clone $submissions)->whereNull('marks')->count(),
        ];
    }

    // ─────────────────────────────────────────────
    // PDF DOWNLOAD ACTIONS
    // ─────────────────────────────────────────────

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download_full_report')
                ->label('Download Full Report (PDF)')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('primary')
                ->url(route('report.download', ['type' => 'full']))
                ->openUrlInNewTab(),

            Action::make('download_application_report')
                ->label('Application Report')
                ->icon('heroicon-o-document-text')
                ->color('info')
                ->url(route('report.download', ['type' => 'application']))
                ->openUrlInNewTab(),

            Action::make('download_intern_report')
                ->label('Intern Report')
                ->icon('heroicon-o-user-group')
                ->color('success')
                ->url(route('report.download', ['type' => 'intern']))
                ->openUrlInNewTab(),

            Action::make('download_task_report')
                ->label('Task Report')
                ->icon('heroicon-o-clipboard-document-list')
                ->color('warning')
                ->url(route('report.download', ['type' => 'task']))
                ->openUrlInNewTab(),
        ];
    }

    // ─────────────────────────────────────────────
    // VIEW DATA
    // ─────────────────────────────────────────────

    public function getViewData(): array
    {
        return [
            'applicationStats' => $this->getApplicationStats(),
            'internStats'      => $this->getInternStats(),
            'taskStats'        => $this->getTaskStats(),
            'generatedAt'      => Carbon::now()->format('d M Y, h:i A'),
        ];
    }

}
