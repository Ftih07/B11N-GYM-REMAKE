<?php

namespace App\Filament\Widgets;

use App\Services\SalesAnalytics;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class PaymentMethodChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static bool $isDiscovered = false;
    protected static ?string $heading = 'Metode Pembayaran';
    protected static ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $rows = SalesAnalytics::make($this->filters)->paymentMethods();

        return [
            'datasets' => [[
                'data' => $rows->pluck('total')->map(fn ($v) => (float) $v)->all(),
                'backgroundColor' => ['#f59e0b', '#3b82f6', '#10b981', '#8b5cf6', '#ec4899', '#6b7280'],
            ]],
            'labels' => $rows->pluck('payment_method')->map(fn ($m) => strtoupper($m))->all(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'x' => ['display' => false],
                'y' => ['display' => false],
            ],
        ];
    }
}