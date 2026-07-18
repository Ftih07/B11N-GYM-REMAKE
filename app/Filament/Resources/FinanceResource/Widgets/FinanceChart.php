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

    // FUNGSI getFilters() DIHAPUS AGAR DROPDOWN MENGHILANG

    protected function getData(): array
    {
        $query = $this->getPageTableQuery();
        $dateColumn = 'date'; // Sesuai kolom database Anda

        // 1. Ambil batas tanggal secara otomatis dari query tabel yang difilter
        $minDate = (clone $query)->min($dateColumn);
        $maxDate = (clone $query)->max($dateColumn);

        // Jika tabel ada isinya, pakai rentang tanggal tersebut. Jika kosong, default 7 hari terakhir.
        $startDate = $minDate ? Carbon::parse($minDate)->startOfDay() : Carbon::now()->subDays(7)->startOfDay();
        $endDate = $maxDate ? Carbon::parse($maxDate)->endOfDay() : Carbon::now()->endOfDay();

        // (Opsional) Jika difilter cuma 1 hari persis, kita beri jarak agar grafik tidak cuma 1 titik
        if ($startDate->isSameDay($endDate)) {
            $startDate->subDay();
            $endDate->addDay();
        }

        // 2. Buat array template berisi angka 0 untuk setiap hari di rentang tersebut
        $period = CarbonPeriod::create($startDate, $endDate);
        $labels = [];
        $emptyDataTemplate = [];

        foreach ($period as $date) {
            $dateString = $date->format('Y-m-d');
            $labels[] = $date->format('d M');
            $emptyDataTemplate[$dateString] = 0;
        }

        // 3. Ambil data Pemasukan
        $incomes = (clone $query)
            ->reorder()
            ->where('type', 'income')
            ->selectRaw("DATE($dateColumn) as date_only, SUM(amount) as total")
            ->groupByRaw("DATE($dateColumn)")
            ->pluck('total', 'date_only')
            ->toArray();

        // 4. Ambil data Pengeluaran
        $expenses = (clone $query)
            ->reorder()
            ->where('type', 'expense')
            ->selectRaw("DATE($dateColumn) as date_only, SUM(amount) as total")
            ->groupByRaw("DATE($dateColumn)")
            ->pluck('total', 'date_only')
            ->toArray();

        // 5. Gabungkan template 0 dengan data asli
        $incomeData = array_merge($emptyDataTemplate, $incomes);
        $expenseData = array_merge($emptyDataTemplate, $expenses);

        return [
            'datasets' => [
                [
                    'label' => 'Pemasukan',
                    'data' => array_values($incomeData),
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.2)',
                    'fill' => true,
                ],
                [
                    'label' => 'Pengeluaran',
                    'data' => array_values($expenseData),
                    'borderColor' => '#ef4444',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.2)',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
