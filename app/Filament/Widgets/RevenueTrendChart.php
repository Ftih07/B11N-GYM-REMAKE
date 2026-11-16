<?php

namespace App\Filament\Widgets;

use App\Services\SalesAnalytics;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class RevenueTrendChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static bool $isDiscovered = false;
    protected static ?string $heading = 'Tren Pendapatan per Cabang vs Pengeluaran';
    protected static ?string $maxHeight = '300px';
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $a = SalesAnalytics::make($this->filters);
        $series = $a->monthlyRevenueByBranch();

        $datasets = [];
        $i = 0;
        foreach ($a->branches() as $id => $name) {
            $color = SalesAnalytics::COLORS[$i++ % count(SalesAnalytics::COLORS)];
            $datasets[] = [
                'label' => $name,
                'data' => $series[$id] ?? [],
                'borderColor' => $color,
                'backgroundColor' => $color . '33',
                'tension' => 0.3,
                'fill' => true,
            ];
        }

        $datasets[] = [
            'label' => 'Pengeluaran (total)',
            'data' => $a->monthlyExpense(),
            'borderColor' => '#ef4444',
            'borderDash' => [6, 4],
            'tension' => 0.3,
            'fill' => false,
        ];

        return ['datasets' => $datasets, 'labels' => array_values($a->months())];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<'JS'
        {
            scales: {
                y: {
                    ticks: {
                        callback: (value) => 'Rp ' + (value / 1000000).toLocaleString('id-ID') + ' jt',
                    },
                },
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: (ctx) => ctx.dataset.label + ': Rp ' + Number(ctx.parsed.y).toLocaleString('id-ID'),
                    },
                },
            },
        }
        JS);
    }
}