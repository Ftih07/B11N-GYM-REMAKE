<?php

namespace App\Filament\Widgets;

use App\Services\SalesAnalytics;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class MemberStatusChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static bool $isDiscovered = false;
    protected static ?string $heading = 'Status Member Saat Ini';
    protected static ?string $maxHeight = '280px';

    public function getDescription(): ?string
    {
        $a = SalesAnalytics::make($this->filters);
        $data = $a->memberStatusByBranch();

        $parts = [];
        foreach ($a->branches() as $id => $name) {
            $parts[] = $name . ': ' . number_format($data[$id]['nonaktif'] ?? 0);
        }

        return 'Kondisi hari ini (tidak terpengaruh filter tanggal). Nonaktif tidak digambar: ' . implode(' | ', $parts);
    }

    protected function getData(): array
    {
        $a = SalesAnalytics::make($this->filters);
        $branches = $a->branches();
        $data = $a->memberStatusByBranch();

        $series = [
            'aktif'  => ['Aktif (> 30 hari)', '#10b981'],
            'segera' => ['Expired ≤ 30 hari', '#f59e0b'],
            'lewat'  => ['Aktif tapi lewat masa berlaku', '#ef4444'],
        ];

        $datasets = [];
        foreach ($series as $key => [$label, $color]) {
            $datasets[] = [
                'label' => $label,
                'data' => $branches->keys()->map(fn ($id) => $data[$id][$key] ?? 0)->all(),
                'backgroundColor' => $color,
                'borderRadius' => 4,
            ];
        }

        return ['datasets' => $datasets, 'labels' => $branches->values()->all()];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}