<?php

namespace App\Filament\Widgets;

use App\Models\Finance;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;

class RevenueStats extends BaseWidget
{
    // Auto-refresh tiap 60 detik (dulu 15 detik — PERF-03)
    protected static ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        $now = Carbon::now();

        // Ganti 'date' dengan nama kolom tanggal transaksi sebenarnya di tabel finances
        $dateColumn = 'date';

        // Satu query untuk kedua cabang (1 = B11N, 2 = K1NG), memakai index
        // fin_analytics_idx(gymkos_id, type, date) — PERF-03/04
        $income = Finance::query()
            ->where('type', 'income')
            ->whereIn('gymkos_id', [1, 2])
            ->whereBetween($dateColumn, [$now->copy()->startOfMonth()->toDateString(), $now->copy()->endOfMonth()->toDateString()])
            ->groupBy('gymkos_id')
            ->selectRaw('gymkos_id, SUM(amount) as total')
            ->pluck('total', 'gymkos_id');

        return [
            // Stats Card 1: B11N Gym
            Stat::make('Pendapatan B11N Gym', Number::currency((float) ($income[1] ?? 0), 'IDR'))
                ->description('Pemasukan '.$now->translatedFormat('F Y'))
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning'), // Yellow

            // Stats Card 2: K1NG Gym
            Stat::make('Pendapatan K1NG Gym', Number::currency((float) ($income[2] ?? 0), 'IDR'))
                ->description('Pemasukan '.$now->translatedFormat('F Y'))
                ->descriptionIcon('heroicon-m-trophy')
                ->color('info'), // Blue
        ];
    }
}
