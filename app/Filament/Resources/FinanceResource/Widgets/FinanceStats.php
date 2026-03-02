<?php

namespace App\Filament\Resources\FinanceResource\Widgets;

use App\Models\Finance;
use App\Models\Gymkos;
use App\Filament\Resources\FinanceResource\Pages\ListFinances;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Illuminate\Support\Number; // Tambahkan ini untuk Number::currency
use Carbon\Carbon; // Tambahkan ini untuk format tanggal

class FinanceStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListFinances::class;
    }

    protected function getStats(): array
    {
        $baseQuery = $this->getPageTableQuery();
        $now = Carbon::now();

        // --- 1. GLOBAL CALCULATION (All Branches Combined) ---
        $totalIncome = (clone $baseQuery)->where('type', 'income')->sum('amount');
        $totalExpense = (clone $baseQuery)->where('type', 'expense')->sum('amount');
        $grandTotal = $totalIncome - $totalExpense;

        $stats = [
            Stat::make('Total Pemasukan (Global)', Number::currency($totalIncome, 'IDR'))
                ->description('Semua uang masuk')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Total Pengeluaran (Global)', Number::currency($totalExpense, 'IDR'))
                ->description('Semua uang keluar')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),

            Stat::make('Sisa Saldo Tabungan', Number::currency($grandTotal, 'IDR'))
                ->description('Total bersih saat ini')
                ->descriptionIcon('heroicon-m-wallet')
                ->color($grandTotal >= 0 ? 'success' : 'danger')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
        ];

        // --- 2. PER-BRANCH CALCULATION (Dynamic Loop) ---
        // Ubah bagian ini untuk mengecualikan ID 4 dan 5
        $gyms = Gymkos::whereNotIn('id', [3, 4, 5])->get();

        foreach ($gyms as $gym) {
            $gymIncome = (clone $baseQuery)->where('gymkos_id', $gym->id)->where('type', 'income')->sum('amount');
            $gymExpense = (clone $baseQuery)->where('gymkos_id', $gym->id)->where('type', 'expense')->sum('amount');
            $gymBalance = $gymIncome - $gymExpense;

            // --- Customisasi Tampilan Sesuai Referensi ---
            $incomeIcon = 'heroicon-m-arrow-trending-up'; // Default
            $incomeColor = 'success'; // Default

            if ($gym->name === 'B11N Gym') {
                $incomeIcon = 'heroicon-m-user-group';
                $incomeColor = 'warning';
            } elseif ($gym->name === 'K1NG Gym') {
                $incomeIcon = 'heroicon-m-trophy';
                $incomeColor = 'info';
            }

            // Card Pendapatan Gym
            $stats[] = Stat::make("Pendapatan {$gym->name}", Number::currency($gymIncome, 'IDR'))
                ->description('Pemasukan ' . $now->format('F Y'))
                ->descriptionIcon($incomeIcon)
                ->color($incomeColor);

            // Card Pengeluaran Gym
            $stats[] = Stat::make("Pengeluaran {$gym->name}", Number::currency($gymExpense, 'IDR'))
                ->description('Pengeluaran ' . $now->format('F Y'))
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger');

            // Card Saldo Gym 
            $stats[] = Stat::make("Saldo: {$gym->name}", Number::currency($gymBalance, 'IDR'))
                ->description($gymBalance >= 0 ? 'Aman' : 'Defisit')
                ->color($gymBalance >= 0 ? 'success' : 'danger');
        }

        return $stats;
    }
}
