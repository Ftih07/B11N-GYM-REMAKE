<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Closure;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SalesAnalytics
{
    /** ID cabang gym di tabel gymkos (kost diabaikan dulu). */
    public const GYM_IDS = [1, 2];

    public const COLORS = ['#f59e0b', '#3b82f6', '#10b981', '#8b5cf6'];

    public const DAYS = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];

    public const DAYS_FULL = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

    /** Lama cache hasil query (detik). */
    public const CACHE_TTL = 300;

    public function __construct(protected array $f = [])
    {
    }

    public static function make(?array $filters): static
    {
        return new static($filters ?? []);
    }

    public static function rp(float|int $v): string
    {
        return 'Rp ' . number_format($v, 0, ',', '.');
    }

    public static function defaultStart(): Carbon
    {
        return now()->subMonths(5)->startOfMonth();
    }

    public static function pct(float|int $now, float|int $prev): ?float
    {
        return $prev > 0 ? round(($now - $prev) / $prev * 100, 1) : null;
    }

    /* ---------- Cache ---------- */

    protected static function version(): int
    {
        return (int) Cache::get('sales-analytics:version', 1);
    }

    /** Membuang semua cache analisis (dipakai tombol "Segarkan data"). */
    public static function bust(): void
    {
        Cache::forever('sales-analytics:version', self::version() + 1);
    }

    protected function cached(string $name, Closure $fn): mixed
    {
        $key = 'sales-analytics:' . $name . ':' . md5(json_encode([
            self::version(),
            $this->start()->toDateString(),
            $this->end()->toDateString(),
            $this->branchIds(),
            now()->toDateString(),
        ]));

        return Cache::remember($key, self::CACHE_TTL, $fn);
    }

    /* ---------- Filter helpers ---------- */

    public function start(): Carbon
    {
        return Carbon::parse($this->f['start_date'] ?? self::defaultStart())->startOfDay();
    }

    public function end(): Carbon
    {
        return Carbon::parse($this->f['end_date'] ?? now())->endOfDay();
    }

    public function branchIds(): array
    {
        $id = $this->f['gymkos_id'] ?? null;

        return $id ? [(int) $id] : self::GYM_IDS;
    }

    /** [id => nama cabang] */
    public function branches(): Collection
    {
        return DB::table('gymkos')
            ->whereIn('id', $this->branchIds())
            ->orderBy('id')
            ->pluck('name', 'id');
    }

    /** [Y-m => label] untuk setiap bulan dalam rentang filter */
    public function months(): array
    {
        $out = [];
        $c = $this->start()->copy()->startOfMonth();
        while ($c <= $this->end()) {
            $out[$c->format('Y-m')] = $c->locale('id')->translatedFormat('M Y');
            $c->addMonth();
        }

        return $out;
    }

    protected function range(bool $previous = false): array
    {
        if (! $previous) {
            return [$this->start(), $this->end()];
        }

        $days = (int) $this->start()->copy()->startOfDay()->diffInDays($this->end()->copy()->startOfDay()) + 1;

        return [
            $this->start()->copy()->subDays($days),
            $this->start()->copy()->subDay()->endOfDay(),
        ];
    }

    /** Jumlah hari dalam periode (tidak melewati hari ini). */
    public function dayCount(): int
    {
        $end = $this->end()->lessThan(now()) ? $this->end() : now();

        return max(1, (int) $this->start()->copy()->startOfDay()->diffInDays($end->copy()->startOfDay()) + 1);
    }

    /* ---------- Base queries ---------- */

    public function trx(bool $previous = false): Builder
    {
        [$s, $e] = $this->range($previous);

        return DB::table('transactions')
            ->where('status', 'paid')
            ->whereIn('gymkos_id', $this->branchIds())
            ->whereBetween('created_at', [$s, $e]);
    }

    /** $type: income | expense. Sumber tunggal angka keuangan. */
    public function finance(string $type, bool $previous = false): Builder
    {
        [$s, $e] = $this->range($previous);

        return DB::table('finances')
            ->where('type', $type)
            ->whereIn('gymkos_id', $this->branchIds())
            ->whereBetween('date', [$s->toDateString(), $e->toDateString()]);
    }

    public function newMembersQuery(bool $previous = false): Builder
    {
        [$s, $e] = $this->range($previous);

        return DB::table('members')
            ->whereIn('gymkos_id', $this->branchIds())
            ->whereBetween('join_date', [$s->toDateString(), $e->toDateString()]);
    }

    /* ---------- KPI ---------- */

    public function revenue(bool $previous = false): float
    {
        return $this->cached('revenue' . (int) $previous, fn () => (float) $this->finance('income', $previous)->sum('amount'));
    }

    public function expense(bool $previous = false): float
    {
        return $this->cached('expense' . (int) $previous, fn () => (float) $this->finance('expense', $previous)->sum('amount'));
    }

    public function trxCount(bool $previous = false): int
    {
        return $this->cached('trxcount' . (int) $previous, fn () => (int) $this->trx($previous)->count());
    }

    /** Rata-rata nilai per transaksi paid (AOV). */
    public function avgTransaction(bool $previous = false): float
    {
        return $this->cached('aov' . (int) $previous, fn () => (float) $this->trx($previous)->avg('total_amount'));
    }

    public function newMembers(bool $previous = false): int
    {
        return $this->cached('newmembers' . (int) $previous, fn () => (int) $this->newMembersQuery($previous)->count());
    }

    public function expiringSoon(int $days = 30): int
    {
        return $this->cached('expiring' . $days, fn () => (int) DB::table('members')
            ->whereIn('gymkos_id', $this->branchIds())
            ->where('status', 'active')
            ->whereBetween('membership_end_date', [now()->toDateString(), now()->addDays($days)->toDateString()])
            ->count());
    }

    /* ---------- Data chart ---------- */

    /** [gymkos_id => [nilai per bulan sesuai months()]] */
    protected function alignByBranch(Collection $rows): array
    {
        $months = array_keys($this->months());
        $out = [];
        foreach ($this->branches() as $id => $name) {
            $map = $rows->where('gymkos_id', $id)->pluck('total', 'ym');
            $out[$id] = array_map(fn ($ym) => (float) ($map[$ym] ?? 0), $months);
        }

        return $out;
    }

    public function monthlyRevenueByBranch(): array
    {
        return $this->cached('monthly-revenue', fn () => $this->alignByBranch(
            $this->finance('income')
                ->selectRaw("gymkos_id, DATE_FORMAT(`date`, '%Y-%m') as ym, SUM(amount) as total")
                ->groupBy('gymkos_id', 'ym')
                ->get()
        ));
    }

    public function monthlyExpense(): array
    {
        return $this->cached('monthly-expense', function () {
            $rows = $this->finance('expense')
                ->selectRaw("DATE_FORMAT(`date`, '%Y-%m') as ym, SUM(amount) as total")
                ->groupBy('ym')
                ->pluck('total', 'ym');

            return array_map(fn ($ym) => (float) ($rows[$ym] ?? 0), array_keys($this->months()));
        });
    }

    public function monthlyNewMembersByBranch(): array
    {
        return $this->cached('monthly-newmembers', fn () => $this->alignByBranch(
            $this->newMembersQuery()
                ->selectRaw("gymkos_id, DATE_FORMAT(join_date, '%Y-%m') as ym, COUNT(*) as total")
                ->groupBy('gymkos_id', 'ym')
                ->get()
        ));
    }

    /** Snapshot status member saat ini per cabang (tidak terpengaruh filter tanggal). */
    public function memberStatusByBranch(): array
    {
        return $this->cached('member-status', function () {
            $today = now()->toDateString();
            $soon = now()->addDays(30)->toDateString();

            return DB::table('members')
                ->whereIn('gymkos_id', $this->branchIds())
                ->selectRaw("gymkos_id,
                    SUM(CASE WHEN status = 'active' AND (membership_end_date IS NULL OR membership_end_date > ?) THEN 1 ELSE 0 END) AS aktif,
                    SUM(CASE WHEN status = 'active' AND membership_end_date BETWEEN ? AND ? THEN 1 ELSE 0 END) AS segera,
                    SUM(CASE WHEN status = 'active' AND membership_end_date < ? THEN 1 ELSE 0 END) AS lewat,
                    SUM(CASE WHEN status <> 'active' THEN 1 ELSE 0 END) AS nonaktif",
                    [$soon, $today, $soon, $today])
                ->groupBy('gymkos_id')
                ->get()
                ->keyBy('gymkos_id')
                ->map(fn ($r) => [
                    'aktif' => (int) $r->aktif,
                    'segera' => (int) $r->segera,
                    'lewat' => (int) $r->lewat,
                    'nonaktif' => (int) $r->nonaktif,
                ])->all();
        });
    }

    /** [metode => total rupiah] */
    public function paymentMethods(): Collection
    {
        return $this->cached('payment-methods', fn () => $this->trx()
            ->selectRaw('payment_method, SUM(total_amount) as total, COUNT(*) as qty')
            ->groupBy('payment_method')
            ->orderByDesc('total')
            ->get());
    }

    public function topProducts(int $limit = 8): Collection
    {
        return $this->cached('top-products-' . $limit, function () use ($limit) {
            [$s, $e] = $this->range();

            return DB::table('transaction_items as ti')
                ->join('transactions as t', 't.id', '=', 'ti.transaction_id')
                ->where('t.status', 'paid')
                ->whereIn('t.gymkos_id', $this->branchIds())
                ->whereBetween('t.created_at', [$s, $e])
                ->selectRaw('ti.product_name, SUM(ti.subtotal) as total, SUM(ti.quantity) as qty')
                ->groupBy('ti.product_name')
                ->orderByDesc('total')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Komposisi penjualan dari item transaksi.
     * Return: [grup => [gymkos_id => ['total' => x, 'qty' => y]]]
     */
    public function revenueMix(): array
    {
        return $this->cached('revenue-mix', function () {
            [$s, $e] = $this->range();

            $rows = DB::table('transaction_items as ti')
                ->join('transactions as t', 't.id', '=', 'ti.transaction_id')
                ->where('t.status', 'paid')
                ->whereIn('t.gymkos_id', $this->branchIds())
                ->whereBetween('t.created_at', [$s, $e])
                ->selectRaw("t.gymkos_id,
                    CASE
                        WHEN ti.product_name LIKE 'Membership%Bulanan%' THEN 'bulanan'
                        WHEN ti.product_name LIKE 'Membership%Mingguan%' THEN 'mingguan'
                        WHEN ti.product_name LIKE 'Membership%Harian%' THEN 'harian'
                        WHEN ti.product_name LIKE 'Membership%' THEN 'membership_lain'
                        ELSE 'produk'
                    END AS grp,
                    SUM(ti.subtotal) AS total, SUM(ti.quantity) AS qty")
                ->groupBy('t.gymkos_id', 'grp')
                ->get();

            $out = [];
            foreach ($rows as $r) {
                $out[$r->grp][$r->gymkos_id] = ['total' => (float) $r->total, 'qty' => (int) $r->qty];
            }

            return $out;
        });
    }

    /**
     * Rata-rata pendapatan per hari dalam seminggu (dari finances income).
     * Return: [gymkos_id => [7 nilai, 0 = Senin]]
     */
    public function weekdayRevenue(): array
    {
        return $this->cached('weekday-revenue', function () {
            $rows = $this->finance('income')
                ->selectRaw('gymkos_id, DAYOFWEEK(`date`) as d, SUM(amount) as total')
                ->groupBy('gymkos_id', 'd')
                ->get();

            // Berapa kali tiap hari muncul dalam periode (supaya rata-rata adil)
            $counts = array_fill(0, 7, 0);
            $end = $this->end()->lessThan(now()) ? $this->end() : now();
            foreach (CarbonPeriod::create($this->start(), $end->copy()->startOfDay()) as $day) {
                $counts[$day->dayOfWeekIso - 1]++;
            }

            $out = [];
            foreach ($this->branches() as $id => $name) {
                $vals = array_fill(0, 7, 0.0);
                foreach ($rows->where('gymkos_id', $id) as $r) {
                    $i = ($r->d + 5) % 7; // MySQL: 1 = Minggu
                    $vals[$i] = $counts[$i] > 0 ? round($r->total / $counts[$i], 0) : 0.0;
                }
                $out[$id] = $vals;
            }

            return $out;
        });
    }

    /** matrix[0=Senin..6=Minggu][jam] = jumlah transaksi */
    public function heatmap(int $gymkosId): array
    {
        return $this->cached('heatmap-' . $gymkosId, function () use ($gymkosId) {
            [$s, $e] = $this->range();

            $rows = DB::table('transactions')
                ->where('status', 'paid')
                ->where('gymkos_id', $gymkosId)
                ->whereBetween('created_at', [$s, $e])
                ->selectRaw('DAYOFWEEK(created_at) as d, HOUR(created_at) as h, COUNT(*) as c')
                ->groupBy('d', 'h')
                ->get();

            $matrix = [];
            $max = 0;
            $total = 0;
            foreach ($rows as $r) {
                $matrix[($r->d + 5) % 7][(int) $r->h] = (int) $r->c;
                $max = max($max, (int) $r->c);
                $total += (int) $r->c;
            }

            return ['matrix' => $matrix, 'max' => $max, 'total' => $total];
        });
    }

    /* ---------- Analisis singkat ---------- */

    public function insights(): array
    {
        return $this->cached('insights', fn () => $this->buildInsights());
    }

    protected function buildInsights(): array
    {
        $out = [];
        $names = $this->branches();
        $rev = $this->revenue();
        $prev = $this->revenue(true);

        $g = self::pct($rev, $prev);
        $out[] = $g === null
            ? 'Total pendapatan periode ini ' . self::rp($rev) . '.'
            : 'Pendapatan ' . self::rp($rev) . ' ' . ($g >= 0 ? 'naik ' : 'turun ') . abs($g) . '% dibanding periode sebelumnya (' . self::rp($prev) . ').';

        $byBranch = $this->finance('income')
            ->selectRaw('gymkos_id, SUM(amount) as t')
            ->groupBy('gymkos_id')->orderByDesc('t')->get();
        if ($byBranch->count() > 1 && $rev > 0) {
            $top = $byBranch->first();
            $out[] = ($names[$top->gymkos_id] ?? 'Cabang') . ' menyumbang pendapatan terbesar, ' . round($top->t / $rev * 100) . '% dari total.';
        }

        $aov = $this->avgTransaction();
        if ($aov > 0) {
            $out[] = 'Rata-rata nilai per transaksi ' . self::rp($aov) . ' dan rata-rata pendapatan harian ' . self::rp($rev / $this->dayCount()) . '.';
        }

        // Komposisi membership vs produk
        $mix = $this->revenueMix();
        $all = 0.0;
        $member = 0.0;
        foreach ($mix as $grp => $perBranch) {
            $sum = array_sum(array_column($perBranch, 'total'));
            $all += $sum;
            if ($grp !== 'produk') {
                $member += $sum;
            }
        }
        if ($all > 0) {
            $out[] = 'Membership menyumbang ' . round($member / $all * 100) . '% dari nilai item terjual, sisanya ' . round(100 - $member / $all * 100) . '% dari produk eceran.';
        }

        $prod = $this->topProducts(1)->first();
        if ($prod) {
            $out[] = "Produk/layanan dengan pendapatan tertinggi: {$prod->product_name} (" . self::rp($prod->total) . ", {$prod->qty} terjual).";
        }

        $pm = $this->paymentMethods();
        $pmTotal = $pm->sum('total');
        if ($pm->isNotEmpty() && $pmTotal > 0) {
            $first = $pm->first();
            $out[] = 'Metode pembayaran dominan: ' . strtoupper($first->payment_method) . ' (' . round($first->total / $pmTotal * 100) . '% dari nilai transaksi).';
        }

        // Hari terbaik & weekend vs weekday
        $sum = array_fill(0, 7, 0.0);
        foreach ($this->weekdayRevenue() as $vals) {
            foreach ($vals as $i => $v) {
                $sum[$i] += $v;
            }
        }
        if (array_sum($sum) > 0) {
            $best = array_keys($sum, max($sum))[0];
            $out[] = 'Hari dengan rata-rata pendapatan tertinggi: ' . self::DAYS_FULL[$best] . ' (' . self::rp($sum[$best]) . ' per hari).';

            $weekday = array_sum(array_slice($sum, 0, 5)) / 5;
            $weekend = ($sum[5] + $sum[6]) / 2;
            if ($weekday > 0) {
                $d = round(($weekend - $weekday) / $weekday * 100);
                $out[] = 'Akhir pekan rata-rata ' . abs($d) . '% ' . ($d >= 0 ? 'lebih tinggi' : 'lebih rendah') . ' dibanding hari kerja.';
            }
        }

        $peak = $this->trx()
            ->selectRaw('DAYOFWEEK(created_at) as d, HOUR(created_at) as h, COUNT(*) as c')
            ->groupBy('d', 'h')->orderByDesc('c')->first();
        if ($peak) {
            $out[] = 'Jam paling ramai transaksi: ' . self::DAYS_FULL[($peak->d + 5) % 7] . ' sekitar pukul ' . sprintf('%02d:00', $peak->h) . " ({$peak->c} transaksi).";
        }

        $new = $this->newMembers();
        $gm = self::pct($new, $this->newMembers(true));
        $out[] = "Member baru periode ini: {$new}" . ($gm === null ? '.' : ' (' . ($gm >= 0 ? 'naik ' : 'turun ') . abs($gm) . '% dari periode sebelumnya).');

        $newBy = $this->newMembersQuery()->selectRaw('gymkos_id, COUNT(*) as c')
            ->groupBy('gymkos_id')->orderByDesc('c')->get();
        if ($newBy->count() > 1 && $new > 0) {
            $out[] = ($names[$newBy->first()->gymkos_id] ?? 'Cabang') . ' paling banyak menarik member baru (' . $newBy->first()->c . ' orang).';
        }

        $exp = $this->expiringSoon();
        if ($exp > 0) {
            $out[] = "{$exp} member aktif akan berakhir dalam 30 hari ke depan, segera follow up lewat tabel di bawah.";
        }

        $stale = collect($this->memberStatusByBranch())->sum('lewat');
        if ($stale > 0) {
            $out[] = "{$stale} member berstatus aktif tetapi masa berlakunya sudah lewat. Cek apakah status perlu diperbarui.";
        }

        if ($rev > 0) {
            $net = $rev - $this->expense();
            $out[] = 'Laba bersih ' . self::rp($net) . ' (margin ' . round($net / $rev * 100, 1) . '%, dihitung dari pengeluaran yang tercatat).';
        }

        return $out;
    }

    /* ---------- Data untuk ekspor ---------- */

    public function report(): array
    {
        $rev = $this->revenue();
        $exp = $this->expense();
        $branches = $this->branches();

        $monthly = [];
        $revBy = $this->monthlyRevenueByBranch();
        $newBy = $this->monthlyNewMembersByBranch();
        foreach ($branches as $id => $name) {
            $monthly[] = ['name' => "{$name} - Pendapatan (Rp)", 'values' => $revBy[$id] ?? []];
        }
        $monthly[] = ['name' => 'Pengeluaran total (Rp)', 'values' => $this->monthlyExpense()];
        foreach ($branches as $id => $name) {
            $monthly[] = ['name' => "{$name} - Member baru", 'values' => $newBy[$id] ?? []];
        }

        $weekday = [];
        $wd = $this->weekdayRevenue();
        foreach ($branches as $id => $name) {
            $weekday[] = ['name' => $name, 'values' => $wd[$id] ?? []];
        }

        return [
            'periode' => $this->start()->locale('id')->translatedFormat('d M Y') . ' - ' . $this->end()->locale('id')->translatedFormat('d M Y'),
            'cabang' => $branches->values()->implode(', '),
            'dibuat' => now()->locale('id')->translatedFormat('d M Y H:i'),
            'kpi' => [
                ['Pendapatan', self::rp($rev)],
                ['Pengeluaran (tercatat)', self::rp($exp)],
                ['Laba bersih', self::rp($rev - $exp)],
                ['Jumlah transaksi', number_format($this->trxCount(), 0, ',', '.')],
                ['Rata-rata per transaksi', self::rp($this->avgTransaction())],
                ['Rata-rata pendapatan harian', self::rp($rev / $this->dayCount())],
                ['Member baru', number_format($this->newMembers(), 0, ',', '.')],
                ['Segera expired (30 hari)', number_format($this->expiringSoon(), 0, ',', '.')],
            ],
            'insights' => $this->insights(),
            'monthly' => ['labels' => array_values($this->months()), 'series' => $monthly],
            'weekday' => ['labels' => self::DAYS_FULL, 'series' => $weekday],
            'products' => $this->topProducts(15),
            'payments' => $this->paymentMethods(),
        ];
    }
}