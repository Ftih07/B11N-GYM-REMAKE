<?php

namespace App\Filament\Widgets;

use App\Models\WebVisitor;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class WebTrafficChart extends ChartWidget
{
    protected static ?string $heading = 'Traffic Pengunjung Website (Unique)';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        // Default: 30 Hari terakhir
        $dataPoints = [];
        $labels = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dataPoints[$date] = 0;
            $labels[] = Carbon::parse($date)->format('d M');
        }

        // Query Unique Visitor per hari
        $results = WebVisitor::selectRaw('visit_date, COUNT(*) as aggregate')
            ->where('visit_date', '>=', now()->subDays(30))
            ->groupBy('visit_date')
            ->get();

        foreach ($results as $row) {
            // FIX: Format dulu Object Carbon-nya jadi string 'Y-m-d'
            // Karena di Model kita pakai casts => date, maka dia jadi Object.
            // Kita balikin jadi string biar bisa jadi Key Array.
            $dateString = $row->visit_date->format('Y-m-d');

            if (isset($dataPoints[$dateString])) {
                $dataPoints[$dateString] = $row->aggregate;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Unique Visitors',
                    'data' => array_values($dataPoints),
                    'borderColor' => '#10b981', // Warna Emerald/Hijau
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
