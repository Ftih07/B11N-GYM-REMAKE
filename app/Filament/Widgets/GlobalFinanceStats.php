<?php

namespace App\Filament\Widgets;

use App\Models\Finance;
use App\Models\Member;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;
use Illuminate\Support\Carbon;

class GlobalFinanceStats extends BaseWidget
{
    protected static ?int $sort = 1; // Top Priority (Top of Dashboard)

    protected function getStats(): array
    {
        $now = Carbon::now();

        // 1. Calculate Monthly Income
        $income = Finance::where('type', 'income')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('amount');

        // 2. Calculate Monthly Expense
        $expense = Finance::where('type', 'expense')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('amount');

        // 3. Count Active Members
        $activeMembers = Member::where('status', 'active')->count();

        return [
            // Stat Card 1: Income
            Stat::make('Pemasukan Bulan Ini', Number::currency($income, 'IDR'))
                ->description('Periode: ' . $now->format('F Y'))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'), // Green

            // Stat Card 2: Expense
            Stat::make('Pengeluaran Bulan Ini', Number::currency($expense, 'IDR'))
                ->description('Periode: ' . $now->format('F Y'))
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'), // Red

            // Stat Card 3: Active Members
            Stat::make('Total Member Aktif', $activeMembers . ' Member')
                ->description('Status Membership: Active')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'), // Blue
        ];
    }
}
