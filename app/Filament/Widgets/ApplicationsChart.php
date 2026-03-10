<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\InterviewManagement\Application;

class ApplicationsChart extends ChartWidget
{
    protected static ?string $heading = 'Applications Per Month';

    protected function getData(): array
    {
       $data = Application::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total');

        return [
            'datasets' => [
                [
                    'label' => 'Applications',
                    'data' => $data,
                ],
            ],
            'labels' => [
                'Jan','Feb','Mar','Apr','May','Jun',
                'Jul','Aug','Sep','Oct','Nov','Dec'
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
