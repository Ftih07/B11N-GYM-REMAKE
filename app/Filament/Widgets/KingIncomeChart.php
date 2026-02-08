<?php

namespace App\Filament\Widgets;

use App\Models\Finance;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB; // Kita butuh DB facade buat Raw Query
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class KingIncomeChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Pendapatan K1NG Gym';
    protected static ?int $sort = 2;

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

        // 1. Siapkan Wadah Data Kosong (Biar grafiknya nyambung dari awal sampai akhir)
        $dataPoints = [];
        $labels = [];

        if ($activeFilter === 'day') {
            // Loop 30 hari ke belakang
            for ($i = 29; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $dataPoints[$date] = 0; // Default 0
                $labels[] = \Carbon\Carbon::parse($date)->format('d M');
            }
        } elseif ($activeFilter === 'year') {
            // Loop 5 tahun ke belakang
            for ($i = 4; $i >= 0; $i--) {
                $year = now()->subYears($i)->format('Y');
                $dataPoints[$year] = 0;
                $labels[] = $year;
            }
        } else {
            // Default: Bulan Jan-Des tahun ini
            for ($i = 1; $i <= 12; $i++) {
                $month = now()->setMonth($i)->format('Y-m');
                $dataPoints[$month] = 0;
                $labels[] = \Carbon\Carbon::createFromFormat('Y-m', $month)->format('M');
            }
        }

        // 2. Query Database (Sama kayak tadi, tapi lebih simpel)
        $query = Finance::query()
            ->where('type', 'income')
            ->whereHas('gymkos', function (Builder $q) {
                // !!! PENTING: Ganti 'K1NG Gym' jadi 'K1NG Gym' di file satunya !!!
                $q->where('name', 'K1NG Gym');
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

        // 3. Gabungkan Data DB ke Wadah Kosong
        foreach ($results as $row) {
            // Kalau periodenya ada di wadah, timpa nilai 0 dengan nilai asli
            if (isset($dataPoints[$row->period])) {
                $dataPoints[$row->period] = $row->aggregate;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan',
                    'data' => array_values($dataPoints), // Ambil nilainya saja
                    'borderColor' => '#f59e0b',
                    'fill' => 'start',
                    'tension' => 0.3, // Biar garisnya agak melengkung estetik
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
