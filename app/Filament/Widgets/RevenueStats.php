<?php

namespace App\Filament\Widgets;

use App\Models\Finance;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon; // Jangan lupa import Carbon

class RevenueStats extends BaseWidget
{
    // Opsional: Refresh data tiap 15 detik
    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        // Ambil waktu saat ini
        $now = Carbon::now();

        // Fungsi helper biar kodenya rapi
        $getIncome = function ($gymName) use ($now) {
            return Finance::query()
                ->where('type', 'income') // Hanya hitung Pemasukan
                ->whereMonth('created_at', $now->month) // Filter Bulan Ini
                ->whereYear('created_at', $now->year)   // Filter Tahun Ini
                ->whereHas('gymkos', function (Builder $query) use ($gymName) {
                    $query->where('name', $gymName); // Filter berdasarkan nama relasi gymkos
                })
                ->sum('amount');
        };

        return [
            Stat::make('Pendapatan Kost Istana Merdeka 3', Number::currency($getIncome('Istana Merdeka 3'), 'IDR'))
                ->description('Pemasukan ' . $now->format('F Y')) // Deskripsi Bulan & Tahun
                ->descriptionIcon('heroicon-m-home')
                ->color('success'),

            Stat::make('Pendapatan B11N Gym', Number::currency($getIncome('B11N Gym'), 'IDR'))
                ->description('Pemasukan ' . $now->format('F Y'))
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning'),

            Stat::make('Pendapatan K1NG Gym', Number::currency($getIncome('K1NG Gym'), 'IDR'))
                ->description('Pemasukan ' . $now->format('F Y'))
                ->descriptionIcon('heroicon-m-trophy')
                ->color('info'),
        ];
    }
}
