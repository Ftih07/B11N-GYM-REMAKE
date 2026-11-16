<?php

namespace App\Filament\Widgets;

use App\Services\SalesAnalytics as A;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SalesStats extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

    protected static bool $isDiscovered = false;
    protected int|string|array $columnSpan = 'full';

    protected function getColumns(): int
    {
        return 4;
    }

    /** Format ringkas supaya angka besar tidak membuat kartu melebar. */
    protected function short(float|int $v): string
    {
        $abs = abs($v);
        $sign = $v < 0 ? '-' : '';

        return match (true) {
            $abs >= 1_000_000_000 => $sign . 'Rp ' . number_format($abs / 1_000_000_000, 2, ',', '.') . ' M',
            $abs >= 1_000_000     => $sign . 'Rp ' . number_format($abs / 1_000_000, 1, ',', '.') . ' jt',
            $abs >= 100_000       => $sign . 'Rp ' . number_format($abs / 1_000, 0, ',', '.') . ' rb',
            default               => $sign . 'Rp ' . number_format($abs, 0, ',', '.'),
        };
    }

    protected function money(string $label, float|int $v): Stat
    {
        return Stat::make($label, $this->short($v))
            ->extraAttributes(['title' => A::rp($v)]);
    }

    protected function delta(float|int $now, float|int $prev, string $suffix = 'vs periode lalu'): array
    {
        $g = A::pct($now, $prev);
        if ($g === null) {
            return ['Belum ada pembanding', 'heroicon-m-minus', 'gray'];
        }

        return [
            abs($g) . '% ' . $suffix,
            $g >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down',
            $g >= 0 ? 'success' : 'danger',
        ];
    }

    protected function getStats(): array
    {
        $a = A::make($this->filters);

        $rev = $a->revenue();
        $exp = $a->expense();
        $net = $rev - $exp;
        $trx = $a->trxCount();
        $new = $a->newMembers();
        $aov = $a->avgTransaction();

        [$revDesc, $revIcon, $revColor] = $this->delta($rev, $a->revenue(true));
        [$trxDesc, $trxIcon, $trxColor] = $this->delta($trx, $a->trxCount(true));
        [$newDesc, $newIcon, $newColor] = $this->delta($new, $a->newMembers(true));
        [$aovDesc, $aovIcon, $aovColor] = $this->delta($aov, $a->avgTransaction(true));

        return [
            $this->money('Pendapatan', $rev)
                ->description($revDesc)->descriptionIcon($revIcon)->color($revColor),
            $this->money('Pengeluaran (tercatat)', $exp)
                ->description('Hanya yang dicatat di sistem')
                ->color('warning'),
            $this->money('Laba Bersih', $net)
                ->description($rev > 0 ? 'Margin ' . round($net / $rev * 100, 1) . '%' : '-')
                ->color($net >= 0 ? 'success' : 'danger'),
            $this->money('Pendapatan / Hari', $rev / $a->dayCount())
                ->description('Rata-rata harian')
                ->color('gray'),
            Stat::make('Jumlah Transaksi', number_format($trx, 0, ',', '.'))
                ->description($trxDesc)->descriptionIcon($trxIcon)->color($trxColor),
            $this->money('Rata-rata / Transaksi', $aov)
                ->description($aovDesc)->descriptionIcon($aovIcon)->color($aovColor),
            Stat::make('Member Baru', number_format($new, 0, ',', '.'))
                ->description($newDesc)->descriptionIcon($newIcon)->color($newColor),
            Stat::make('Segera Expired (30 hari)', number_format($a->expiringSoon(), 0, ',', '.'))
                ->description('Lihat tabel follow-up')
                ->color('danger'),
        ];
    }
}