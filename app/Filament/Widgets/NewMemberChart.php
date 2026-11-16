<?php

namespace App\Filament\Widgets;

use App\Services\SalesAnalytics;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class NewMemberChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static bool $isDiscovered = false;
    protected static ?string $heading = 'Member Baru per Bulan';
    protected static ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $a = SalesAnalytics::make($this->filters);
        $series = $a->monthlyNewMembersByBranch();

        $datasets = [];
        $i = 0;
        foreach ($a->branches() as $id => $name) {
            $datasets[] = [
                'label' => $name,
                'data' => $series[$id] ?? [],
                'backgroundColor' => SalesAnalytics::COLORS[$i++ % count(SalesAnalytics::COLORS)],
                'borderRadius' => 4,
            ];
        }

        return ['datasets' => $datasets, 'labels' => array_values($a->months())];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}