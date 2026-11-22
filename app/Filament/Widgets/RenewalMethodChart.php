<?php

namespace App\Filament\Widgets;

use App\Services\SalesAnalytics;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class RenewalMethodChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static bool $isDiscovered = false;
    protected static ?string $heading = 'Metode Bayar Perpanjangan';
    protected static ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $rows = SalesAnalytics::make($this->filters)->renewalMethods();

        return [
            'datasets' => [[
                'data' => array_values($rows),
                'backgroundColor' => ['#f59e0b', '#3b82f6', '#10b981', '#8b5cf6', '#ec4899', '#6b7280'],
            ]],
            'labels' => array_keys($rows),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return ['scales' => ['x' => ['display' => false], 'y' => ['display' => false]]];
    }
}
