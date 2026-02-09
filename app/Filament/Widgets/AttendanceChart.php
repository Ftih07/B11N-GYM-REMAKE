<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class AttendanceChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Kunjungan Gym';
    protected static ?int $sort = 3;

    // Filter Options (Dropdown on the chart)
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

        // 1. Prepare Empty Data Containers (To ensure continuous line)
        $dataPoints = [];
        $labels = [];

        if ($activeFilter === 'day') {
            // Loop last 30 days
            for ($i = 29; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $dataPoints[$date] = 0; // Default 0 visits
                $labels[] = Carbon::parse($date)->format('d M');
            }
        } elseif ($activeFilter === 'year') {
            // Loop last 5 years
            for ($i = 4; $i >= 0; $i--) {
                $year = now()->subYears($i)->format('Y');
                $dataPoints[$year] = 0;
                $labels[] = $year;
            }
        } else {
            // Default: Jan-Dec of current year
            for ($i = 1; $i <= 12; $i++) {
                $month = Carbon::create(now()->year, $i, 1)->format('Y-m');
                $dataPoints[$month] = 0;
                $labels[] = Carbon::createFromFormat('Y-m', $month)->format('M');
            }
        }

        // 2. Query Database (Count Visits)
        $query = Attendance::query();

        // Execute Query based on Filter
        $results = match ($activeFilter) {
            'day' => $query
                ->selectRaw('DATE(created_at) as period, COUNT(*) as aggregate')
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('period')
                ->get(),

            'year' => $query
                ->selectRaw('YEAR(created_at) as period, COUNT(*) as aggregate')
                ->where('created_at', '>=', now()->subYears(5)->startOfYear())
                ->groupBy('period')
                ->get(),

            default => $query
                ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as period, COUNT(*) as aggregate')
                ->whereYear('created_at', now()->year)
                ->groupBy('period')
                ->get(),
        };

        // 3. Merge DB Results into Data Containers
        foreach ($results as $row) {
            if (isset($dataPoints[$row->period])) {
                $dataPoints[$row->period] = $row->aggregate;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pengunjung',
                    'data' => array_values($dataPoints),
                    'borderColor' => '#3b82f6', // Blue Line
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)', // Light Blue Fill
                    'fill' => 'start',
                    'tension' => 0.3, // Smooth curve
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
