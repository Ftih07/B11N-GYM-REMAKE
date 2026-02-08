<?php

namespace App\Filament\Widgets;

use App\Models\Finance;
use App\Models\Member; // Pastikan Model Member di-import
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;
use Illuminate\Support\Carbon;

class GlobalFinanceStats extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $now = Carbon::now();

        // 1. Hitung Total Pemasukan (BULAN INI SAJA)
        // Asumsi kolom tanggal di database adalah 'created_at', jika punya kolom 'date' ganti saja.
        $income = Finance::where('type', 'income')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('amount');

        // 2. Hitung Total Pengeluaran (BULAN INI SAJA)
        $expense = Finance::where('type', 'expense')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('amount');

        // 3. Hitung Total Membership Active
        // Asumsi di tabel members ada kolom 'status' yang isinya 'active'
        $activeMembers = Member::where('status', 'active')->count();

        return [
            Stat::make('Pemasukan Bulan Ini', Number::currency($income, 'IDR'))
                ->description('Periode: ' . $now->format('F Y'))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Pengeluaran Bulan Ini', Number::currency($expense, 'IDR'))
                ->description('Periode: ' . $now->format('F Y'))
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),

            Stat::make('Total Member Aktif', $activeMembers . ' Member') // Menampilkan jumlah member
                ->description('Status Membership: Active')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
        ];
    }
}
