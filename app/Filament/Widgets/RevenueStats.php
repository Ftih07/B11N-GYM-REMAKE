<?php

namespace App\Filament\Widgets;

use App\Models\Finance; // <-- Kita pakai Model Finance
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;
use Illuminate\Database\Eloquent\Builder;

class RevenueStats extends BaseWidget
{
    // Opsional: Refresh data tiap 15 detik
    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        // Fungsi helper biar kodenya rapi
        // Kita cari Finance yang tipenya 'income', lalu cari berdasarkan nama Gymkos-nya
        $getIncome = function ($gymName) {
            return Finance::query()
                ->where('type', 'income') // Hanya hitung Pemasukan
                ->whereHas('gymkos', function (Builder $query) use ($gymName) {
                    $query->where('name', $gymName); // Filter berdasarkan nama relasi gymkos
                })
                ->sum('amount');
        };

        return [
            Stat::make('Pendapatan Kost Istana Merdeka 3', Number::currency($getIncome('Istana Merdeka 3'), 'IDR'))
                ->description('Total Pemasukan')
                ->descriptionIcon('heroicon-m-home')
                ->color('success'),

            Stat::make('Pendapatan B11N Gym', Number::currency($getIncome('B11N Gym'), 'IDR'))
                ->description('Total Pemasukan')
                ->descriptionIcon('heroicon-m-user-group') // Ikon contoh
                ->color('warning'),

            Stat::make('Pendapatan K1NG Gym', Number::currency($getIncome('K1NG Gym'), 'IDR'))
                ->description('Total Pemasukan')
                ->descriptionIcon('heroicon-m-trophy') // Ikon contoh
                ->color('info'),
        ];
    }
}
