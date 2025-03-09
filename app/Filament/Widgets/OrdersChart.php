<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;

class OrdersChart extends ChartWidget
{
    protected static ?string $heading = 'Pendapatan Kost Per Bulan'; // Judul widget
    public ?string $year = null; // Properti untuk menyimpan tahun yang dipilih

    protected function getFormSchema(): array
    {
        return [
            Select::make('year')
                ->options($this->getYearsOptions())
                ->default(date('Y'))
                ->reactive(), // Biar chart berubah saat tahun dipilih
        ];
    }

    protected function getData(): array
    {
        $selectedYear = $this->year ?? date('Y');

        // Ambil jumlah booking per bulan berdasarkan tahun yang dipilih
        $ordersPerMonth = DB::table('bookings')
            ->selectRaw('MONTH(date) as month, COUNT(*) as total')
            ->whereYear('date', $selectedYear)
            ->where('status', 'paid')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Buat array data untuk semua bulan
        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $data[] = $ordersPerMonth[$i] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $data,
                    'borderColor' => '#4CAF50',
                    'backgroundColor' => 'rgba(76, 175, 80, 0.2)',
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false, // Hilangkan legend
                ],
            ],
        ];
    }

    private function getYearsOptions(): array
    {
        $years = DB::table('bookings')
            ->selectRaw('YEAR(date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        return array_combine($years, $years);
    }
}
