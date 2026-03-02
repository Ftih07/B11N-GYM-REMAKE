<?php

namespace App\Filament\Resources\FinanceResource\Widgets;

use App\Models\Finance;
use App\Models\Gymkos;
use App\Filament\Resources\FinanceResource\Pages\ListFinances; // Tambahkan ini
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageTable; // Tambahkan trait ini

class FinanceStats extends BaseWidget
{
    use InteractsWithPageTable;

    // Beri tahu widget ini agar mendengarkan (listen) filter dari tabel ListFinances
    protected function getTablePage(): string
    {
        return ListFinances::class;
    }

    protected function getStats(): array
    {
        // Ambil query dasar yang sudah terfilter dari tabel (termasuk filter bulan/tahun jika ada di tabel)
        $baseQuery = $this->getPageTableQuery();

        // --- 1. GLOBAL CALCULATION (All Branches Combined) ---
        // Gunakan (clone) agar query dasar tidak tertimpa saat di-chaining
        $totalIncome = (clone $baseQuery)->where('type', 'income')->sum('amount');
        $totalExpense = (clone $baseQuery)->where('type', 'expense')->sum('amount');
        $grandTotal = $totalIncome - $totalExpense; // Net Balance

        $stats = [
            Stat::make('Total Pemasukan (Global)', 'Rp ' . number_format($totalIncome, 0, ',', '.'))
                ->description('Semua uang masuk')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Total Pengeluaran (Global)', 'Rp ' . number_format($totalExpense, 0, ',', '.'))
                ->description('Semua uang keluar')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),

            Stat::make('Sisa Saldo Tabungan', 'Rp ' . number_format($grandTotal, 0, ',', '.'))
                ->description('Total bersih saat ini')
                ->descriptionIcon('heroicon-m-wallet')
                ->color($grandTotal >= 0 ? 'success' : 'danger')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
        ];

        // --- 2. PER-BRANCH CALCULATION (Dynamic Loop) ---
        $gyms = Gymkos::all();

        foreach ($gyms as $gym) {
            // Gunakan (clone) baseQuery lagi untuk masing-masing cabang
            $gymIncome = (clone $baseQuery)->where('gymkos_id', $gym->id)->where('type', 'income')->sum('amount');
            $gymExpense = (clone $baseQuery)->where('gymkos_id', $gym->id)->where('type', 'expense')->sum('amount');
            $gymBalance = $gymIncome - $gymExpense;

            $stats[] = Stat::make("Saldo: {$gym->name}", 'Rp ' . number_format($gymBalance, 0, ',', '.'))
                ->description($gymBalance >= 0 ? 'Aman' : 'Defisit')
                ->color($gymBalance >= 0 ? 'info' : 'danger');
        }

        return $stats;
    }
}
