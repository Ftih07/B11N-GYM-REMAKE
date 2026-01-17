<?php

namespace App\Filament\Resources\FinanceResource\Widgets;

use App\Models\Finance;
use App\Models\Gymkos; // Pastikan Model Gymkos di-import
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class FinanceStats extends BaseWidget
{
    protected function getStats(): array
    {
        // 1. Hitung Global (Semua Cabang)
        $totalIncome = Finance::where('type', 'income')->sum('amount');
        $totalExpense = Finance::where('type', 'expense')->sum('amount');
        $grandTotal = $totalIncome - $totalExpense;

        $stats = [
            // Kartu 1: Total Pemasukan
            Stat::make('Total Pemasukan (Global)', 'Rp ' . number_format($totalIncome, 0, ',', '.'))
                ->description('Semua uang masuk')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            // Kartu 2: Total Pengeluaran
            Stat::make('Total Pengeluaran (Global)', 'Rp ' . number_format($totalExpense, 0, ',', '.'))
                ->description('Semua uang keluar')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),

            // Kartu 3: Sisa Saldo Bersih
            Stat::make('Sisa Saldo Tabungan', 'Rp ' . number_format($grandTotal, 0, ',', '.'))
                ->description('Total bersih saat ini')
                ->descriptionIcon('heroicon-m-wallet')
                ->color($grandTotal >= 0 ? 'success' : 'danger') // Hijau kalau plus, Merah kalau minus
                ->chart([7, 2, 10, 3, 15, 4, 17]), // Hiasan grafik
        ];

        // 2. Hitung Per Cabang (Otomatis Loop sesuai jumlah cabang)
        $gyms = Gymkos::all();

        foreach ($gyms as $gym) {
            $gymIncome = Finance::where('gymkos_id', $gym->id)->where('type', 'income')->sum('amount');
            $gymExpense = Finance::where('gymkos_id', $gym->id)->where('type', 'expense')->sum('amount');
            $gymBalance = $gymIncome - $gymExpense;

            $stats[] = Stat::make("Saldo: {$gym->name}", 'Rp ' . number_format($gymBalance, 0, ',', '.'))
                ->description($gymBalance >= 0 ? 'Aman' : 'Defisit')
                ->color($gymBalance >= 0 ? 'info' : 'danger'); // Biru kalau aman
        }

        return $stats;
    }
}
