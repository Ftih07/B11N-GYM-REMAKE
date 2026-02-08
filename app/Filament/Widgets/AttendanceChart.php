<?php

namespace App\Filament\Widgets;

use App\Models\Attendance; // Pastikan Model Attendance sudah ada
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class AttendanceChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Kunjungan Gym';
    protected static ?int $sort = 3; 

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

        // 1. Siapkan Wadah Data Kosong
        $dataPoints = [];
        $labels = [];

        if ($activeFilter === 'day') {
            // Loop 30 hari ke belakang
            for ($i = 29; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $dataPoints[$date] = 0;
                $labels[] = Carbon::parse($date)->format('d M');
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
                // Pakai create biar aman dari bug tanggal 31
                $month = Carbon::create(now()->year, $i, 1)->format('Y-m');
                $dataPoints[$month] = 0;
                $labels[] = Carbon::createFromFormat('Y-m', $month)->format('M');
            }
        }

        // 2. Query Database (Hitung Jumlah Orang / COUNT)
        $query = Attendance::query(); // Hapus filter 'type' karena ini bukan keuangan

        // Jika mau filter berdasarkan Gym tertentu (misal pake relasi)
        // $query->whereHas('gym', function ($q) { $q->where('name', 'B11N Gym'); });

        $results = match ($activeFilter) {
            'day' => $query
                ->selectRaw('DATE(created_at) as period, COUNT(*) as aggregate') // Pakai created_at & COUNT
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

        // 3. Gabungkan Data
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
                    'borderColor' => '#3b82f6', // Warna Biru (Primary) biar beda sama duit
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)', // Warna arsir tipis di bawah garis
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