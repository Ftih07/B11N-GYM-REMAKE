<?php

namespace App\Filament\Widgets;

use App\Models\Finance;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class RevenueStats extends BaseWidget
{
    // Auto-refresh every 15 seconds
    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        $now = Carbon::now();

        // Helper function to calculate Monthly Income per Branch
        $getIncome = function ($gymName) use ($now) {
            return Finance::query()
                ->where('type', 'income') // Only Income
                ->whereMonth('created_at', $now->month)
                ->whereYear('created_at', $now->year)
                ->whereHas('gymkos', function (Builder $query) use ($gymName) {
                    $query->where('name', $gymName); // Filter by Branch Name
                })
                ->sum('amount');
        };

        return [
            // Stats Card 1: Kost
            Stat::make('Pendapatan Kost Istana Merdeka 3', Number::currency($getIncome('Istana Merdeka 3'), 'IDR'))
                ->description('Pemasukan ' . $now->format('F Y'))
                ->descriptionIcon('heroicon-m-home')
                ->color('success'), // Green

            // Stats Card 2: B11N Gym
            Stat::make('Pendapatan B11N Gym', Number::currency($getIncome('B11N Gym'), 'IDR'))
                ->description('Pemasukan ' . $now->format('F Y'))
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning'), // Yellow

            // Stats Card 3: K1NG Gym
            Stat::make('Pendapatan K1NG Gym', Number::currency($getIncome('K1NG Gym'), 'IDR'))
                ->description('Pemasukan ' . $now->format('F Y'))
                ->descriptionIcon('heroicon-m-trophy')
                ->color('info'), // Blue
        ];
    }
}
