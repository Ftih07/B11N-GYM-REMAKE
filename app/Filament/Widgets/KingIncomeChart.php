<?php

namespace App\Filament\Widgets;

use App\Models\Finance;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class KingIncomeChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Pendapatan K1NG Gym';
    protected static ?int $sort = 2; // Position on Dashboard

    // Filters for Date Range
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

        // 1. Prepare Empty Data (Default 0)
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
            // Default: Monthly for current year
            for ($i = 1; $i <= 12; $i++) {
                $month = now()->setMonth($i)->format('Y-m');
                $dataPoints[$month] = 0;
                $labels[] = \Carbon\Carbon::createFromFormat('Y-m', $month)->format('M');
            }
        }

        // 2. Query Database (Filtered by Gym Name: 'K1NG Gym')
        $query = Finance::query()
            ->where('type', 'income')
            ->whereHas('gymkos', function (Builder $q) {
                $q->where('name', 'K1NG Gym'); // SPECIFIC FILTER
            });

        // Apply Time Filter Logic
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

        // 3. Merge Results
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
                    'borderColor' => '#f59e0b', // Amber/Orange color
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
