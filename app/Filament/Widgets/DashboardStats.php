<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardStats extends BaseWidget
{
    protected function getCards(): array
    {
        $currentMonth = Carbon::now()->month;
        $lastMonth = Carbon::now()->subMonth()->month;

        // Hitung pendapatan bulan ini
        $currentRevenue = DB::table('booking_kost')
            ->whereMonth('date', $currentMonth)
            ->where('status', 'paid')
            ->sum(DB::raw("CASE 
                WHEN room_type = '750rb - AC' THEN 750000
                WHEN room_type = '500rb - Non AC' THEN 500000
                ELSE 0 END"));

        // Hitung pendapatan bulan lalu
        $lastMonthRevenue = DB::table('booking_kost')
            ->whereMonth('date', $lastMonth)
            ->where('status', 'paid')
            ->sum(DB::raw("CASE 
                WHEN room_type = '750rb - AC' THEN 750000
                WHEN room_type = '500rb - Non AC' THEN 500000
                ELSE 0 END"));

        // Hitung selisihnya
        $difference = $currentRevenue - $lastMonthRevenue;
        $differenceFormatted = number_format(abs($difference), 0, ',', '.');

        // Tentukan warna dan ikon berdasarkan tren (naik/turun)
        if ($difference > 0) {
            $trendText = "{$differenceFormatted} lebih tinggi dari bulan lalu";
            $trendColor = 'success';
            $trendIcon = 'heroicon-o-arrow-trending-up';
        } elseif ($difference < 0) {
            $trendText = "{$differenceFormatted} lebih rendah";
            $trendColor = 'danger';
            $trendIcon = 'heroicon-o-arrow-trending-down';
        } else {
            $trendText = "Sama dengan bulan lalu";
            $trendColor = 'gray';
            $trendIcon = 'heroicon-o-minus';
        }

        // Buat array pendapatan bulanan (biar tidak ada bulan yang kosong)
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $revenuePerMonth[$i] ?? 0;
        }

        // === DATA PEMBAYARAN MEMBERSHIP ===
        $paymentData = DB::table('payment_membership')
            ->where('status', 'confirmed')
            ->selectRaw('payment, COUNT(*) as total')
            ->groupBy('payment')
            ->pluck('total', 'payment')
            ->toArray();

        $qrisCount = $paymentData['qris'] ?? 0;
        $transferCount = $paymentData['transfer'] ?? 0;

        $paymentChartData = [$qrisCount, $transferCount];

        // === TOTAL UANG MASUK DARI MEMBERSHIP ===
        $totalMembershipRevenue = DB::table('payment_membership')
            ->where('status', 'confirmed')
            ->sum(DB::raw("CASE 
                    WHEN membership_type = 'Member Bulanan' THEN 80000
                    WHEN membership_type = 'Member Mingguan' THEN 30000
                    WHEN membership_type = 'Member Harian' THEN 10000
                    ELSE 0 END"));

        // === DATA PENDAPATAN MEMBERSHIP PER BULAN ===
        $membershipRevenuePerMonth = DB::table('payment_membership')
            ->selectRaw('MONTH(created_at) as month, SUM(CASE 
                WHEN membership_type = "Member Bulanan" THEN 80000
                WHEN membership_type = "Member Mingguan" THEN 30000
                WHEN membership_type = "Member Harian" THEN 10000
                ELSE 0 END) as total')
            ->where('status', 'confirmed')
            ->whereBetween('created_at', [Carbon::now()->subMonths(6), Carbon::now()])
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $chartDataMembershipRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i)->month;
            $chartDataMembershipRevenue[] = $membershipRevenuePerMonth[$month] ?? 0;
        }

        return [
            Card::make('Total Pendapatan Kost', 'Rp ' . number_format($currentRevenue, 0, ',', '.'))
                ->description($trendText)
                ->descriptionIcon($trendIcon)
                ->color($trendColor)
                ->chart($chartData),

            // Kartu Statistik Pembayaran Membership
            Card::make('Pembayaran Membership', "{$qrisCount} QRIS | {$transferCount} Transfer")
                ->description('Total pembayaran berdasarkan metode')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('primary')
                ->chart($paymentChartData)
                ->chartColor('info'), // Bisa diubah ke warna lain

            // 3. Kartu Total Uang Masuk dari Membership + Chart Sungai
            Card::make('Total Uang Masuk Membership', 'Rp ' . number_format($totalMembershipRevenue, 0, ',', '.'))
                ->description('Total dari semua membership yang telah dikonfirmasi')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('success')
                ->chart($chartDataMembershipRevenue)
                ->chartColor('success'),
        ];
    }
}
