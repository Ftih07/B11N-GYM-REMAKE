<?php

namespace App\Filament\Widgets;

use App\Services\SalesAnalytics;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class AttendanceTrendChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static bool $isDiscovered = false;
    protected static ?string $heading = 'Kunjungan per Bulan';
    protected static ?string $maxHeight = '280px';
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $a = SalesAnalytics::make($this->filters);
        $series = $a->monthlyVisitsByBranch();

        $datasets = [];
        $i = 0;
        foreach ($a->branches() as $id => $name) {
            $color = SalesAnalytics::COLORS[$i++ % count(SalesAnalytics::COLORS)];
            $datasets[] = [
                'label' => $name,
                'data' => $series[$id] ?? [],
                'borderColor' => $color,
                'backgroundColor' => $color,
                'tension' => 0.3,
            ];
        }

        return ['datasets' => $datasets, 'labels' => array_values($a->months())];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
