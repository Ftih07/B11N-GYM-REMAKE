<?php

namespace App\Filament\Widgets;

use App\Models\Finance;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class GlobalFinanceStats extends BaseWidget
{
    // Kita kasih sort = 1 supaya widget ini muncul PALING ATAS
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // 1. Hitung Total Pemasukan
        $income = Finance::where('type', 'income')->sum('amount');

        // 2. Hitung Total Pengeluaran
        $expense = Finance::where('type', 'expense')->sum('amount');

        // 3. Hitung Sisa Saldo (Pemasukan - Pengeluaran)
        $balance = $income - $expense;

        return [
            Stat::make('Total Pemasukan Keseluruhan', Number::currency($income, 'IDR'))
                ->description('Semua cabang')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'), // Hijau

            Stat::make('Total Pengeluaran Keseluruhan', Number::currency($expense, 'IDR'))
                ->description('Semua cabang')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'), // Merah

            Stat::make('Sisa Saldo Keseluruhan', Number::currency($balance, 'IDR'))
                ->description($balance >= 0 ? 'Profit' : 'Defisit') // Kalau minus tulis Defisit
                ->descriptionIcon('heroicon-m-wallet')
                ->color($balance >= 0 ? 'primary' : 'danger'), // Biru kalau aman, Merah kalau minus
        ];
    }
}
