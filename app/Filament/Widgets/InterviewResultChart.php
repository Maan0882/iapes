<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\InterviewManagement\InterviewAssignment;


class InterviewResultChart extends ChartWidget
{
    protected static ?string $heading = 'Interview Results';

    protected function getData(): array
    {
        $selected = InterviewAssignment::where('result','selected')->count();
        $rejected = InterviewAssignment::where('result','rejected')->count();
        $pending = InterviewAssignment::whereNull('result')->count();

        return [
            'datasets' => [
                [
                    'data' => [$selected, $rejected, $pending],
                ],
            ],
            'labels' => ['Selected', 'Rejected', 'Pending'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
