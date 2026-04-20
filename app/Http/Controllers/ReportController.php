<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

use App\Models\InterviewManagement\Application;
use App\Models\InterviewManagement\InterviewAssignment;
use App\Models\InternManagement\Intern;
use App\Models\InternManagement\InternshipBatch;
use App\Models\InternManagement\InternTeam;
use App\Models\TaskManagement\Task;
use App\Models\TaskManagement\TaskSubmission;
use App\Models\TaskManagement\TaskAssignment;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    private function getApplicationStats(): array
    {
        $apps = Application::whereNotIn('status', ['pending', 'verified']);

        $statusCounts = (clone $apps)->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $domainBreakdown = (clone $apps)->selectRaw('domain, count(*) as total')
            ->groupBy('domain')
            ->orderByDesc('total')
            ->limit(10)
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

        $durationBreakdown = (clone $apps)
            ->selectRaw('duration, duration_unit, count(*) as total')
            ->groupBy('duration', 'duration_unit')
            ->orderByDesc('total')
            ->limit(8)
            ->get()
            ->toArray();

        return [
            'total'               => (clone $apps)->count(),
            'applied'             => $statusCounts['applied'] ?? 0,
            'interview_scheduled' => $statusCounts['interview_scheduled'] ?? 0,
            'interviewed'         => $statusCounts['interviewed'] ?? 0,
            'shortlisted'         => $statusCounts['shortlisted'] ?? 0,
            'rejected'            => $statusCounts['rejected'] ?? 0,
            'domain_breakdown'    => $domainBreakdown,
            'monthly_trend'       => $monthlyTrend,
            'interview_present'   => InterviewAssignment::where('attendance', 'present')->count(),
            'interview_absent'    => InterviewAssignment::where('attendance', 'absent')->count(),
            'selected'            => InterviewAssignment::where('result', 'selected')->count(),
            'not_selected'        => InterviewAssignment::where('result', 'not_selected')->count(),
            'avg_cgpa'            => round((clone $apps)->avg('cgpa'), 2),
            'duration_breakdown'  => $durationBreakdown,
            'conversion_rate'     => (clone $apps)->count() > 0
                ? round((($statusCounts['shortlisted'] ?? 0) / (clone $apps)->count()) * 100, 1)
                : 0,
        ];
    }

    private function getInternStats(): array
    {
        $interns = Intern::query();
        $total   = (clone $interns)->count();

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
            'active'          => (clone $interns)->where('is_active', true)->count(),
            'inactive'        => (clone $interns)->where('is_active', false)->count(),
            'batch_breakdown' => $batchBreakdown,
            'team_breakdown'  => $teamBreakdown,
            'monthly_joining' => $monthlyJoining,
            'with_project'    => (clone $interns)->whereNotNull('project_name')->count(),
            'without_project' => (clone $interns)->whereNull('project_name')->count(),
        ];
    }

    private function getTaskStats(): array
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

        $gradeDistribution = (clone $submissions)
            ->whereNotNull('grade')
            ->selectRaw('grade, count(*) as total')
            ->groupBy('grade')
            ->orderByDesc('total')
            ->pluck('total', 'grade')
            ->toArray();

        $monthlyTasks = collect(range(5, 0))->map(function ($m) {
        $date = Carbon::now()->subMonths($m);
            return [
                'month' => $date->format('M Y'),
                'count' => Task::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
            ];
        });

        $totalAssignments = TaskAssignment::count();
        $totalSubmissions = (clone $submissions)->count();

        return [
            'total'              => (clone $tasks)->count(),
            'monthly_tasks'      => $monthlyTasks,
            'priority_high'      => $priorityBreakdown['high'] ?? 0,
            'priority_medium'    => $priorityBreakdown['medium'] ?? 0,
            'priority_low'       => $priorityBreakdown['low'] ?? 0,
            'total_submissions'  => $totalSubmissions,
            'total_assignments'  => $totalAssignments,
            'submission_rate'    => $totalAssignments > 0
                ? round(($totalSubmissions / $totalAssignments) * 100, 1)
                : 0,
            'avg_marks'          => round(TaskSubmission::whereNotNull('marks')->avg('marks') ?? 0, 1),
            'grade_distribution' => $gradeDistribution,
            'submission_status'  => $submissionStatus,
            'overdue'            => (clone $tasks)->whereDate('due_date', '<', Carbon::today())->count(),
            'due_soon'           => (clone $tasks)
                ->whereDate('due_date', '>=', Carbon::today())
                ->whereDate('due_date', '<=', Carbon::today()->addDays(7))
                ->count(),
            'evaluated'          => (clone $submissions)->whereNotNull('marks')->count(),
            'pending_evaluation' => (clone $submissions)->whereNull('marks')->count(),
        ];
    }

    public function download(Request $request)
    {
        $type = $request->query('type', 'full');
        $generatedAt = Carbon::now()->format('d M Y, h:i A');

        $data = [
            'generatedAt'      => $generatedAt,
            'type'             => $type,
            'applicationStats' => null, // Change from [] to null
            'internStats'      => null, // Change from [] to null
            'taskStats'        => null,
        ];

        if (in_array($type, ['full', 'application'])) {
            $data['applicationStats'] = $this->getApplicationStats();
        }
        if (in_array($type, ['full', 'intern'])) {
            $data['internStats'] = $this->getInternStats();
        }
        if (in_array($type, ['full', 'task'])) {
            $data['taskStats'] = $this->getTaskStats();
        }

        $pdf = Pdf::loadView('reports.pdf-report', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont'    => 'sans-serif',
                'isRemoteEnabled'=> true,
                'isPhpEnabled'   => true,
            ]);

        $filename = 'IAPES-' . ucfirst($type) . '-Report-' . Carbon::now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

}
