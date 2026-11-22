<?php

namespace App\Filament\Widgets;

use App\Services\SalesAnalytics;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class MemberFlowChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static bool $isDiscovered = false;
    protected static ?string $heading = 'Member Baru vs Perpanjangan per Bulan';
    protected static ?string $description = 'Member baru dihitung dari tanggal join; perpanjangan dari tanggal bayar di Riwayat Perpanjangan (tercatat sejak fitur ini dipakai).';
    protected static ?string $maxHeight = '280px';
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $a = SalesAnalytics::make($this->filters);

        return [
            'datasets' => [
                [
                    'label' => 'Member Baru',
                    'data' => $a->monthlyNewMembersTotal(),
                    'backgroundColor' => SalesAnalytics::COLORS[2],
                    'borderRadius' => 4,
                ],
                [
                    'label' => 'Perpanjangan',
                    'data' => $a->monthlyRenewals(),
                    'backgroundColor' => SalesAnalytics::COLORS[1],
                    'borderRadius' => 4,
                ],
            ],
            'labels' => array_values($a->months()),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
