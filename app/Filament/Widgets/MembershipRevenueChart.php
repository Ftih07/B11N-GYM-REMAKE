<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;

class MembershipRevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Total Uang Masuk Membership';
    public ?string $year = null;

    protected function getFormSchema(): array
    {
        return [
            Select::make('year')
                ->options($this->getYearsOptions())
                ->default(date('Y'))
                ->reactive(),
        ];
    }

    protected function getData(): array
    {
        $selectedYear = $this->year ?? date('Y');

        // Ambil total pendapatan dari membership per bulan
        $revenueData = DB::table('payment_membership')
            ->selectRaw('MONTH(created_at) as month, 
                        SUM(CASE 
                            WHEN membership_type = "Member Bulanan" THEN 80000
                            WHEN membership_type = "Member Mingguan" THEN 30000
                            WHEN membership_type = "Member Harian" THEN 10000
                            ELSE 0 END) as total_revenue')
            ->whereYear('created_at', $selectedYear)
            ->where('status', 'confirmed')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total_revenue', 'month')
            ->toArray();

        $revenues = [];

        for ($i = 1; $i <= 12; $i++) {
            $revenues[] = $revenueData[$i] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Uang Masuk',
                    'data' => $revenues,
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
