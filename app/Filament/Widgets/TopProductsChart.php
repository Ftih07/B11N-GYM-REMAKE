<?php

namespace App\Filament\Widgets;

use App\Services\SalesAnalytics;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class TopProductsChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static bool $isDiscovered = false;
    protected static ?string $heading = 'Produk/Layanan Terlaris';
    protected static ?string $maxHeight = '320px';

    public ?string $filter = 'qty';

    protected function getFilters(): ?array
    {
        return [
            'qty'   => 'Jumlah terjual',
            'total' => 'Nilai penjualan (Rp)',
        ];
    }

    protected function getData(): array
    {
        $metric = $this->filter === 'total' ? 'total' : 'qty';

        // Ambil banyak baris lalu urutkan sesuai metrik, supaya produk murah
        // tapi laku keras (air mineral, pisang) tetap masuk daftar.
        $rows = SalesAnalytics::make($this->filters)
            ->topProducts(100)
            ->sortByDesc($metric)
            ->take(8)
            ->values();

        return [
            'datasets' => [[
                'label' => $metric === 'qty' ? 'Jumlah terjual' : 'Penjualan (Rp)',
                'data' => $rows->pluck($metric)->map(fn ($v) => (float) $v)->all(),
                'backgroundColor' => $metric === 'qty' ? '#3b82f6' : '#f59e0b',
                'borderRadius' => 4,
            ]],
            'labels' => $rows->pluck('product_name')->all(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): RawJs
    {
        if ($this->filter === 'total') {
            return RawJs::make(<<<'JS'
            {
                indexAxis: 'y',
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: (ctx) => 'Rp ' + Number(ctx.parsed.x).toLocaleString('id-ID') } },
                },
                scales: {
                    x: { ticks: { callback: (v) => (v / 1000000).toLocaleString('id-ID') + ' jt' } },
                },
            }
            JS);
        }

        return RawJs::make(<<<'JS'
        {
            indexAxis: 'y',
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: (ctx) => Number(ctx.parsed.x).toLocaleString('id-ID') + ' terjual' } },
            },
            scales: {
                x: { ticks: { callback: (v) => Number(v).toLocaleString('id-ID') } },
            },
        }
        JS);
    }
}