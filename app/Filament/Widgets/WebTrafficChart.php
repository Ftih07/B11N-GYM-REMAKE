<?php

namespace App\Filament\Widgets;

use App\Models\WebVisitor;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class WebTrafficChart extends ChartWidget
{
    protected static ?string $heading = 'Traffic Pengunjung Website (Unique)';
    protected static ?int $sort = 4;

    protected function getData(): array
    {
        // 1. Prepare Empty Data (Last 30 Days)
        $dataPoints = [];
        $labels = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dataPoints[$date] = 0;
            $labels[] = Carbon::parse($date)->format('d M');
        }

        // 2. Query DB: Count Unique Visitors per Day
        // Assuming `visit_date` is a Date column
        $results = WebVisitor::selectRaw('visit_date, COUNT(*) as aggregate')
            ->where('visit_date', '>=', now()->subDays(30))
            ->groupBy('visit_date')
            ->get();

        // 3. Merge Results
        foreach ($results as $row) {
            // FIX: Convert Carbon Object to String Key
            // This handles the case where `visit_date` is cast to a date object in the Model
            $dateString = is_object($row->visit_date) ? $row->visit_date->format('Y-m-d') : $row->visit_date;

            if (isset($dataPoints[$dateString])) {
                $dataPoints[$dateString] = $row->aggregate;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Unique Visitors',
                    'data' => array_values($dataPoints),
                    'borderColor' => '#10b981', // Emerald Green
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
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
