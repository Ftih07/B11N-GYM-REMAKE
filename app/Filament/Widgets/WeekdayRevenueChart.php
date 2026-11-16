<?php

namespace App\Filament\Widgets;

use App\Services\SalesAnalytics;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class WeekdayRevenueChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static bool $isDiscovered = false;
    protected static ?string $heading = 'Rata-rata Pendapatan per Hari';
    protected static ?string $description = 'Rata-rata per hari dalam seminggu, bukan total, jadi adil walau jumlah harinya berbeda.';
    protected static ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $a = SalesAnalytics::make($this->filters);
        $series = $a->weekdayRevenue();

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

        return ['datasets' => $datasets, 'labels' => SalesAnalytics::DAYS_FULL];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<'JS'
        {
            scales: {
                y: { ticks: { callback: (v) => (v / 1000).toLocaleString('id-ID') + ' rb' } },
            },
            plugins: {
                tooltip: { callbacks: { label: (ctx) => ctx.dataset.label + ': Rp ' + Number(ctx.parsed.y).toLocaleString('id-ID') } },
            },
        }
        JS);
    }
}