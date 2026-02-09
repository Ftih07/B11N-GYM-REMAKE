<?php

namespace App\Filament\Resources\FinanceResource\Widgets;

use App\Models\Finance;
use App\Models\Gymkos;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class FinanceStats extends BaseWidget
{
    protected function getStats(): array
    {
        // --- 1. GLOBAL CALCULATION (All Branches Combined) ---
        // Sum all income and expenses from the database
        $totalIncome = Finance::where('type', 'income')->sum('amount');
        $totalExpense = Finance::where('type', 'expense')->sum('amount');
        $grandTotal = $totalIncome - $totalExpense; // Net Balance

        // Define the static cards for Global Stats
        $stats = [
            // Card 1: Total Global Income
            Stat::make('Total Pemasukan (Global)', 'Rp ' . number_format($totalIncome, 0, ',', '.'))
                ->description('Semua uang masuk')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'), // Green

            // Card 2: Total Global Expense
            Stat::make('Total Pengeluaran (Global)', 'Rp ' . number_format($totalExpense, 0, ',', '.'))
                ->description('Semua uang keluar')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'), // Red

            // Card 3: Net Balance
            Stat::make('Sisa Saldo Tabungan', 'Rp ' . number_format($grandTotal, 0, ',', '.'))
                ->description('Total bersih saat ini')
                ->descriptionIcon('heroicon-m-wallet')
                ->color($grandTotal >= 0 ? 'success' : 'danger') // Green if positive, Red if negative
                ->chart([7, 2, 10, 3, 15, 4, 17]), // Decorative mini-chart
        ];

        // --- 2. PER-BRANCH CALCULATION (Dynamic Loop) ---
        // Fetch all gym branches
        $gyms = Gymkos::all();

        foreach ($gyms as $gym) {
            // Calculate Income/Expense specifically for THIS gym ($gym->id)
            $gymIncome = Finance::where('gymkos_id', $gym->id)->where('type', 'income')->sum('amount');
            $gymExpense = Finance::where('gymkos_id', $gym->id)->where('type', 'expense')->sum('amount');
            $gymBalance = $gymIncome - $gymExpense;

            // Add a new card to the $stats array dynamically
            $stats[] = Stat::make("Saldo: {$gym->name}", 'Rp ' . number_format($gymBalance, 0, ',', '.'))
                ->description($gymBalance >= 0 ? 'Aman' : 'Defisit')
                ->color($gymBalance >= 0 ? 'info' : 'danger'); // Blue if safe, Red if deficit
        }

        return $stats;
    }
}
