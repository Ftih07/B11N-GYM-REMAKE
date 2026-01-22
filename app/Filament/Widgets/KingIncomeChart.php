<?php

namespace App\Filament\Widgets;

use App\Models\Finance;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Database\Eloquent\Builder;

class KingIncomeChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Pendapatan K1NG Gym';

    // Urutan widget (opsional, biar rapi)
    protected static ?int $sort = 2;

    // Opsi Filter
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
        // Ambil filter yang sedang aktif, default ke 'month' (Bulanan)
        $activeFilter = $this->filter ?? 'month';

        // Query Dasar: Cari Income milik K1NG Gym
        $query = Finance::query()
            ->where('type', 'income')
            ->whereHas('gymkos', function (Builder $q) {
                $q->where('name', 'K1NG Gym'); // <--- PENTING: Sesuaikan nama Gym
            });

        // ... kode atas ...

        // Logika Trend berdasarkan Filter
        $data = match ($activeFilter) {
            'day' => Trend::query($query)
                ->dateColumn('date') // <--- TAMBAHKAN INI
                ->between(
                    start: now()->subDays(30),
                    end: now(),
                )
                ->perDay()
                ->sum('amount'),

            'year' => Trend::query($query)
                ->dateColumn('date') // <--- TAMBAHKAN INI
                ->between(
                    start: now()->subYears(5),
                    end: now(),
                )
                ->perYear()
                ->sum('amount'),

            // Default: month
            default => Trend::query($query)
                ->dateColumn('date') // <--- TAMBAHKAN INI
                ->between(
                    start: now()->startOfYear(),
                    end: now()->endOfYear(),
                )
                ->perMonth()
                ->sum('amount'),
        };

        // ... kode bawah ...

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                    'borderColor' => '#f59e0b', // Warna Kuning/Orange (sesuai Stats K1NG tadi)
                    'fill' => 'start',
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => $this->formatLabel($value->date, $activeFilter)),
        ];
    }

    // Helper untuk format tanggal di bawah grafik biar enak dibaca
    private function formatLabel($date, $filter)
    {
        return match ($filter) {
            'day' => \Carbon\Carbon::parse($date)->format('d M'), // 01 Jan
            'year' => \Carbon\Carbon::parse($date)->format('Y'),    // 2024
            default => \Carbon\Carbon::parse($date)->format('M'),   // Jan
        };
    }

    protected function getType(): string
    {
        return 'line'; // Bisa ganti 'bar' kalau mau grafik batang
    }
}
