<?php

namespace App\Filament\Widgets;

use App\Models\Finance;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class IstanaMerdeka01IncomeChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Pendapatan Istana Merdeka 01 - Pabuaran';
    protected static ?int $sort = 3; // Urutan di Dashboard

    protected function getFilters(): ?array
    {
        return [
            'day' => 'Harian (30 Hari Terakhir)',
            'month' => 'Bulanan (Tahun Ini)',
            'year' => 'Tahunan (5 Tahun Terakhir)',
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter ?? 'day';

        $dataPoints = [];
        $labels = [];

        if ($activeFilter === 'day') {
            for ($i = 29; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $dataPoints[$date] = 0;
                $labels[] = \Carbon\Carbon::parse($date)->format('d M');
            }
        } elseif ($activeFilter === 'year') {
            for ($i = 4; $i >= 0; $i--) {
                $year = now()->subYears($i)->format('Y');
                $dataPoints[$year] = 0;
                $labels[] = $year;
            }
        } else {
            for ($i = 1; $i <= 12; $i++) {
                $month = now()->setMonth($i)->format('Y-m');
                $dataPoints[$month] = 0;
                $labels[] = \Carbon\Carbon::createFromFormat('Y-m', $month)->format('M');
            }
        }

        // Filter Spesifik untuk Istana Merdeka 01
        $query = Finance::query()
            ->where('type', 'income')
            ->whereHas('gymkos', function (Builder $q) {
                $q->where('name', 'Istana Merdeka 01 - Pabuaran');
            });

        $results = match ($activeFilter) {
            'day' => $query
                ->selectRaw('DATE(date) as period, SUM(amount) as aggregate')
                ->where('date', '>=', now()->subDays(30))
                ->groupBy('period')
                ->get(),

            'year' => $query
                ->selectRaw('YEAR(date) as period, SUM(amount) as aggregate')
                ->where('date', '>=', now()->subYears(5)->startOfYear())
                ->groupBy('period')
                ->get(),

            default => $query
                ->selectRaw('DATE_FORMAT(date, "%Y-%m") as period, SUM(amount) as aggregate')
                ->whereYear('date', now()->year)
                ->groupBy('period')
                ->get(),
        };

        foreach ($results as $row) {
            if (isset($dataPoints[$row->period])) {
                $dataPoints[$row->period] = $row->aggregate;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan',
                    'data' => array_values($dataPoints),
                    'borderColor' => '#10b981', // Emerald/Green color
                    'fill' => 'start',
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
