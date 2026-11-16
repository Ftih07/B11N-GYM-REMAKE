<?php

namespace App\Filament\Widgets;

use App\Services\SalesAnalytics;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class RevenueMixChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static bool $isDiscovered = false;
    protected static ?string $heading = 'Membership vs Produk';
    protected static ?string $description = 'Dihitung dari item transaksi, dipisah per paket membership.';
    protected static ?string $maxHeight = '280px';

    public ?string $filter = 'total';

    protected function getFilters(): ?array
    {
        return [
            'total' => 'Nilai penjualan (Rp)',
            'qty'   => 'Jumlah terjual',
        ];
    }

    protected function getData(): array
    {
        $metric = $this->filter === 'qty' ? 'qty' : 'total';
        $a = SalesAnalytics::make($this->filters);
        $branches = $a->branches();
        $mix = $a->revenueMix();

        $groups = [
            'bulanan'         => ['Membership Bulanan', '#f59e0b'],
            'mingguan'        => ['Membership Mingguan', '#fbbf24'],
            'harian'          => ['Membership Harian', '#fcd34d'],
            'membership_lain' => ['Membership Lainnya', '#a16207'],
            'produk'          => ['Produk Eceran', '#3b82f6'],
        ];

        $datasets = [];
        foreach ($groups as $key => [$label, $color]) {
            if (! isset($mix[$key])) {
                continue;
            }
            $datasets[] = [
                'label' => $label,
                'data' => $branches->keys()->map(fn ($id) => $mix[$key][$id][$metric] ?? 0)->all(),
                'backgroundColor' => $color,
            ];
        }

        return ['datasets' => $datasets, 'labels' => $branches->values()->all()];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): RawJs
    {
        $axis = $this->filter === 'qty'
            ? "(v) => Number(v).toLocaleString('id-ID')"
            : "(v) => (v / 1000000).toLocaleString('id-ID') + ' jt'";

        return RawJs::make(<<<JS
        {
            scales: {
                x: { stacked: true },
                y: { stacked: true, ticks: { callback: {$axis} } },
            },
        }
        JS);
    }
}