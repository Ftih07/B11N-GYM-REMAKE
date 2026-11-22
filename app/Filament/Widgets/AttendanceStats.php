<?php

namespace App\Filament\Widgets;

use App\Services\SalesAnalytics as A;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AttendanceStats extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

    protected static bool $isDiscovered = false;
    protected int|string|array $columnSpan = 'full';

    protected function getColumns(): int
    {
        return 4;
    }

    protected function delta(float|int $now, float|int $prev): array
    {
        $g = A::pct($now, $prev);
        if ($g === null) {
            return ['Belum ada pembanding', 'heroicon-m-minus', 'gray'];
        }

        return [
            abs($g) . '% vs periode lalu',
            $g >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down',
            $g >= 0 ? 'success' : 'danger',
        ];
    }

    protected function getStats(): array
    {
        $a = A::make($this->filters);

        $visits = $a->visits();
        $ren = $a->renewals();

        [$vDesc, $vIcon, $vColor] = $this->delta($visits, $a->visits(true));
        [$rDesc, $rIcon, $rColor] = $this->delta($ren, $a->renewals(true));

        return [
            Stat::make('Total Kunjungan', number_format($visits, 0, ',', '.'))
                ->description($vDesc)->descriptionIcon($vIcon)->color($vColor),
            Stat::make('Rata-rata Kunjungan / Hari', number_format($visits / $a->dayCount(), 1, ',', '.'))
                ->description('Member + tamu')->color('gray'),
            Stat::make('Member Hadir', number_format($a->uniqueVisitors(), 0, ',', '.'))
                ->description('Member berbeda yang check-in')->color('info'),
            Stat::make('Perpanjangan Tercatat', number_format($ren, 0, ',', '.'))
                ->description($rDesc)->descriptionIcon($rIcon)->color($rColor),
        ];
    }
}
