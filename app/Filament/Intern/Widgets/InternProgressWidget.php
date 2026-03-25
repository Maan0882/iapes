<?php

namespace App\Filament\Intern\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class InternProgressWidget extends Widget
{
    protected static string $view = 'filament.intern.widgets.intern-progress-widget';
    protected static ?int $sort = 2;

    // Side-by-side with AttendanceChart
    protected int | string | array $columnSpan = 1;

    protected function getViewData(): array
    {
        $intern = Auth::user();
        $offer  = $intern->offerletter;

        if (! $offer || ! $offer->joining_date || ! $offer->completion_date) {
            return [
                'progress'       => 0,
                'days_left'      => 0,
                'total_days'     => 0,
                'elapsed_days'   => 0,
                'start_date'     => '—',
                'end_date'       => '—',
                'status_message' => 'Internship period not defined.',
            ];
        }

        $start = Carbon::parse($offer->joining_date);
        $end   = Carbon::parse($offer->completion_date);
        $now   = now();

        $totalDays   = max(1, $start->diffInDays($end));
        $elapsedDays = max(0, $start->diffInDays($now, false));
        $progress    = min(100, round(($elapsedDays / $totalDays) * 100, 1));
        $daysLeft    = max(0, $now->diffInDays($end, false));

        return [
            'progress'       => $progress,
            'days_left'      => round($daysLeft),
            'total_days'     => round($totalDays),
            'elapsed_days'   => round($elapsedDays),
            'start_date'     => $start->format('M d, Y'),
            'end_date'       => $end->format('M d, Y'),
            'status_message' => $daysLeft > 0
                ? round($daysLeft) . ' days remaining until completion'
                : 'Internship completed!',
        ];
    }
}