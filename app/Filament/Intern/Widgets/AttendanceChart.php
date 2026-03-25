<?php

namespace App\Filament\Intern\Widgets;

use App\Models\Attendance;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceChart extends ChartWidget
{
    protected static ?string $heading = 'Daily Attendance';
    protected static ?string $description = 'Your activity over the last 30 days';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        $internId = Auth::id();

        $attendanceData = Attendance::where('intern_id', $internId)
            ->where('date', '>=', now()->subDays(30))
            ->orderBy('date', 'asc')
            ->get();

        $labels = $attendanceData->map(fn ($r) => Carbon::parse($r->date)->format('M d'));
        $data   = $attendanceData->map(fn ($r) => match ($r->status) {
            'present' => 1,
            'late'    => 0.6,
            'leave'   => 0.4,
            'absent'  => 0.1,
            default   => 0,
        });

        return [
            'datasets' => [
                [
                    'label'                => 'Activity',
                    'data'                 => $data,
                    'fill'                 => 'start',
                    'borderColor'          => 'rgb(99, 102, 241)',
                    'backgroundColor'      => 'rgba(99, 102, 241, 0.08)',
                    'tension'              => 0.45,
                    'pointRadius'          => 3,
                    'pointHoverRadius'     => 6,
                    'pointBackgroundColor' => 'rgb(129, 140, 248)',
                    'pointBorderColor'     => '#1e293b',
                    'pointBorderWidth'     => 2,
                    'borderWidth'          => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => false, // Allows the chart to fill the container height
            'aspectRatio' => 2,
            'plugins' => [
                'legend'  => ['display' => false],
                'tooltip' => [
                    'backgroundColor' => '#1e293b',
                    'borderColor'     => 'rgba(99,102,241,0.3)',
                    'borderWidth'     => 1,
                    'titleColor'      => '#818cf8',
                    'bodyColor'       => '#94a3b8',
                    'padding'         => 10,
                ],
            ],
            'scales' => [
                'x' => [
                    'grid'  => ['display' => false, 'drawBorder' => false],
                    'ticks' => [
                        'color'          => '#334155',
                        'font'           => ['size' => 10],
                        'maxTicksLimit'  => 6,
                    ],
                    'border' => ['display' => false],
                ],
                'y' => [
                    'min'     => 0,
                    'max'     => 1.2,
                    'display' => false,
                    'grid'    => ['display' => false],
                ],
            ],
        ];
    }
}