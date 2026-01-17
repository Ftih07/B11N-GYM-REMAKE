<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;

class MembershipPaymentChart extends ChartWidget
{
    protected static ?string $heading = 'Metode Pembayaran Membership';
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

        // Ambil jumlah pembayaran berdasarkan metode (QRIS & Transfer)
        $paymentData = DB::table('payment_membership')
            ->selectRaw('MONTH(created_at) as month, 
                        SUM(CASE WHEN payment = "qris" THEN 1 ELSE 0 END) as qris_count,
                        SUM(CASE WHEN payment = "transfer" THEN 1 ELSE 0 END) as transfer_count')
            ->whereYear('created_at', $selectedYear)
            ->where('status', 'confirmed')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $qrisCounts = [];
        $transferCounts = [];

        for ($i = 1; $i <= 12; $i++) {
            $qrisCounts[] = $paymentData->firstWhere('month', $i)->qris_count ?? 0;
            $transferCounts[] = $paymentData->firstWhere('month', $i)->transfer_count ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'QRIS',
                    'data' => $qrisCounts,
                    'borderColor' => '#FF6384',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                ],
                [
                    'label' => 'Transfer',
                    'data' => $transferCounts,
                    'borderColor' => '#36A2EB',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }

    private function getYearsOptions(): array
    {
        $years = DB::table('payment_membership')
            ->selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        return array_combine($years, $years);
    }
}
