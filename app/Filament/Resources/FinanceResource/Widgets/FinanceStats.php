<?php

namespace App\Filament\Resources\FinanceResource\Widgets;

use App\Filament\Resources\FinanceResource\Pages\ListFinances;
use App\Models\Finance;
use App\Models\Gymkos;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

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

        $stats = [];

        $isSuperAdmin = auth()->user()->role === 'super_admin';

        if ($isSuperAdmin) {
            // =========================================================================
            // 1. GLOBAL ALL-TIME (Semua Data dari Awal, Tidak Terpengaruh Filter Tabel)
            // =========================================================================
            $allTimeIncome = Finance::where('type', 'income')->sum('amount');
            $allTimeExpense = Finance::where('type', 'expense')->sum('amount');
            $allTimeBalance = $allTimeIncome - $allTimeExpense;

            $stats[] = Stat::make('Total Pemasukan (All-Time)', Number::currency($allTimeIncome, 'IDR'))
                ->description('Total semua uang masuk dari awal')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success');

            $stats[] = Stat::make('Total Pengeluaran (All-Time)', Number::currency($allTimeExpense, 'IDR'))
                ->description('Total semua uang keluar dari awal')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('danger');

            $stats[] = Stat::make('Sisa Saldo Tabungan Pusat', Number::currency($allTimeBalance, 'IDR'))
                ->description('Total uang bersih tersisa saat ini')
                ->descriptionIcon('heroicon-m-wallet')
                ->color($allTimeBalance >= 0 ? 'success' : 'danger')
                ->chart([7, 2, 10, 3, 15, 4, 17]);

            // =========================================================================
            // 2. GLOBAL GABUNGAN SESUAI FILTER (Default Bulan Ini)
            // =========================================================================
            $filteredIncome = (clone $baseQuery)->where('type', 'income')->sum('amount');
            $filteredExpense = (clone $baseQuery)->where('type', 'expense')->sum('amount');
            $filteredBalance = $filteredIncome - $filteredExpense;

            $stats[] = Stat::make('Pemasukan Gabungan', Number::currency($filteredIncome, 'IDR'))
                ->description('Total pemasukan '.$now->format('F Y'))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success');

            $stats[] = Stat::make('Pengeluaran Gabungan', Number::currency($filteredExpense, 'IDR'))
                ->description('Total pengeluaran '.$now->format('F Y'))
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger');

            $stats[] = Stat::make('Saldo Gabungan Cabang', Number::currency($filteredBalance, 'IDR'))
                ->description('Saldo bersih '.$now->format('F Y'))
                ->descriptionIcon('heroicon-m-scale')
                ->color($filteredBalance >= 0 ? 'success' : 'danger');
        }

        // =========================================================================
        // 3. PER-BRANCH CALCULATION (Bisa dilihat Semua Role, Mengikuti Filter)
        // =========================================================================
        $gyms = Gymkos::whereNotIn('id', [3, 4, 5])->get();

        foreach ($gyms as $gym) {
            $gymIncome = (clone $baseQuery)->where('gymkos_id', $gym->id)->where('type', 'income')->sum('amount');
            $gymExpense = (clone $baseQuery)->where('gymkos_id', $gym->id)->where('type', 'expense')->sum('amount');
            $gymBalance = $gymIncome - $gymExpense;

            $incomeIcon = 'heroicon-m-arrow-trending-up';
            $incomeColor = 'success';

            if ($gym->name === 'B11N Gym') {
                $incomeIcon = 'heroicon-m-user-group';
                $incomeColor = 'warning';
            } elseif ($gym->name === 'K1NG Gym') {
                $incomeIcon = 'heroicon-m-trophy';
                $incomeColor = 'info';
            }

            $stats[] = Stat::make("Pendapatan {$gym->name}", Number::currency($gymIncome, 'IDR'))
                ->description('Pemasukan '.$now->format('F Y'))
                ->descriptionIcon($incomeIcon)
                ->color($incomeColor);

            $stats[] = Stat::make("Pengeluaran {$gym->name}", Number::currency($gymExpense, 'IDR'))
                ->description('Pengeluaran '.$now->format('F Y'))
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger');

            $stats[] = Stat::make("Saldo: {$gym->name}", Number::currency($gymBalance, 'IDR'))
                ->description($gymBalance >= 0 ? 'Aman' : 'Defisit')
                ->color($gymBalance >= 0 ? 'success' : 'danger');
        }

        return $stats;
    }
}
