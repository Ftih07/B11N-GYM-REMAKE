<?php

namespace App\Filament\Resources\FinanceResource\Widgets;

use App\Filament\Resources\FinanceResource\Pages\ListFinances;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Illuminate\Support\Carbon;

class FinanceChart extends ChartWidget
{
    use InteractsWithPageTable;

    protected static ?string $heading = 'Grafik Arus Kas';

    protected static ?int $sort = 1;

    protected function getTablePage(): string
    {
        return ListFinances::class;
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        return [
            'week' => '7 Hari Terakhir',
            'month' => 'Bulan Ini',
            'year' => 'Tahun Ini',
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        // 1. Tentukan rentang waktu
        $startDate = match ($activeFilter) {
            'week' => Carbon::now()->subDays(7),
            'month' => Carbon::now()->startOfMonth(),
            'year' => Carbon::now()->startOfYear(),
            default => Carbon::now()->subDays(7), // Default jika belum ada yang dipilih
        };
        $endDate = Carbon::now();

        // Kolom tanggal yang digunakan di database (ubah jika namanya bukan created_at)
        $dateColumn = 'created_at';

        // 2. Buat array "kosong" untuk semua tanggal di rentang waktu
        // Ini penting agar jika hari Selasa tidak ada transaksi, grafiknya tetap turun ke 0, bukan bolong.
        $period = CarbonPeriod::create($startDate, $endDate);
        $labels = [];
        $emptyDataTemplate = [];

        foreach ($period as $date) {
            $dateString = $date->format('Y-m-d');
            $labels[] = $date->format('d M'); // Label untuk di bawah grafik (ex: 15 Jul)
            $emptyDataTemplate[$dateString] = 0; // Default nilai 0
        }

        $query = $this->getPageTableQuery();

        // 3. Ambil data Pemasukan dan kelompokkan per hari
        $incomes = (clone $query)
            ->reorder()
            ->where('type', 'income')
            ->whereBetween($dateColumn, [$startDate->startOfDay(), $endDate->endOfDay()])
            ->selectRaw("DATE($dateColumn) as date, SUM(amount) as total")
            ->groupByRaw("DATE($dateColumn)") // <--- UBAH DI SINI
            ->pluck('total', 'date')
            ->toArray();

        // 4. Ambil data Pengeluaran dan kelompokkan per hari
        $expenses = (clone $query)
            ->reorder()
            ->where('type', 'expense')
            ->whereBetween($dateColumn, [$startDate->startOfDay(), $endDate->endOfDay()])
            ->selectRaw("DATE($dateColumn) as date, SUM(amount) as total")
            ->groupByRaw("DATE($dateColumn)") // <--- UBAH DI SINI JUGA
            ->pluck('total', 'date')
            ->toArray();

        // 5. Gabungkan data template (angka 0) dengan data asli dari database
        // Jika ada transaksi, angka 0 akan tertimpa. Jika tidak ada, tetap 0.
        $incomeData = array_merge($emptyDataTemplate, $incomes);
        $expenseData = array_merge($emptyDataTemplate, $expenses);

        return [
            'datasets' => [
                [
                    'label' => 'Pemasukan',
                    'data' => array_values($incomeData), // Ambil nilainya saja
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.2)',
                    'fill' => true,
                ],
                [
                    'label' => 'Pengeluaran',
                    'data' => array_values($expenseData), // Ambil nilainya saja
                    'borderColor' => '#ef4444',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.2)',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
