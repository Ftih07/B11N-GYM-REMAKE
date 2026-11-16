<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ExpiringMembersTable;
use App\Filament\Widgets\MemberStatusChart;
use App\Filament\Widgets\NewMemberChart;
use App\Filament\Widgets\PaymentMethodChart;
use App\Filament\Widgets\RevenueMixChart;
use App\Filament\Widgets\RevenueTrendChart;
use App\Filament\Widgets\SalesHeatmap;
use App\Filament\Widgets\SalesInsights;
use App\Filament\Widgets\SalesStats;
use App\Filament\Widgets\TopProductsChart;
use App\Filament\Widgets\WeekdayRevenueChart;
use App\Services\SalesAnalytics;
use App\Traits\SuperAdminOnly;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Illuminate\Support\Facades\DB;

class SalesAnalysis extends BaseDashboard
{
    use HasFiltersForm;

    protected static string $routePath = 'sales-analysis';
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Analisis Penjualan';
    protected static ?string $title = 'Analisis Penjualan';
    protected static ?int $navigationSort = 90;

    /** Halaman memuat data keuangan: batasi hanya untuk non-customer. */
    use SuperAdminOnly;

    protected static function presetRange(?string $preset): array
    {
        return match ($preset) {
            'today'      => [now(), now()],
            '7d'         => [now()->subDays(6), now()],
            '30d'        => [now()->subDays(29), now()],
            'this_month' => [now()->startOfMonth(), now()],
            'last_month' => [now()->subMonthNoOverflow()->startOfMonth(), now()->subMonthNoOverflow()->endOfMonth()],
            '3m'         => [now()->subMonths(2)->startOfMonth(), now()],
            'this_year'  => [now()->startOfYear(), now()],
            default      => [null, null],
        };
    }

    public function filtersForm(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Select::make('preset')
                    ->label('Periode cepat')
                    ->placeholder('Kustom (pilih tanggal)')
                    ->options([
                        'today'      => 'Hari ini',
                        '7d'         => '7 hari terakhir',
                        '30d'        => '30 hari terakhir',
                        'this_month' => 'Bulan ini',
                        'last_month' => 'Bulan lalu',
                        '3m'         => '3 bulan terakhir',
                        'this_year'  => 'Tahun ini',
                    ])
                    ->live()
                    ->afterStateUpdated(function (?string $state, Set $set) {
                        [$s, $e] = static::presetRange($state);
                        if ($s && $e) {
                            $set('start_date', $s->toDateString());
                            $set('end_date', $e->toDateString());
                        }
                    }),
                DatePicker::make('start_date')
                    ->label('Dari')
                    ->default(SalesAnalytics::defaultStart())
                    ->maxDate(now()),
                DatePicker::make('end_date')
                    ->label('Sampai')
                    ->default(now())
                    ->maxDate(now()),
                Select::make('gymkos_id')
                    ->label('Cabang')
                    ->placeholder('Semua cabang')
                    ->options(fn () => DB::table('gymkos')
                        ->whereIn('id', SalesAnalytics::GYM_IDS)
                        ->pluck('name', 'id')),
            ])->columns(4),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->label('Segarkan data')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action(function () {
                    SalesAnalytics::bust();

                    // Redirect penuh ke URL yang sedang dibuka (filter ikut terbawa),
                    // sehingga widget tidak di-render ulang lewat Livewire.
                    return redirect(request()->header('Referer') ?: static::getUrl());
                }),
            ActionGroup::make([
                Action::make('csv')
                    ->label('Excel (CSV)')
                    ->icon('heroicon-o-table-cells')
                    ->action(fn () => $this->downloadCsv()),
                Action::make('pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(fn () => $this->downloadPdf()),
            ])
                ->label('Ekspor')
                ->icon('heroicon-o-arrow-down-tray')
                ->button(),
        ];
    }

    protected function downloadPdf()
    {
        $data = SalesAnalytics::make($this->filters)->report();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.sales-analysis', $data)
            ->setPaper('a4', 'portrait')
            ->output();

        return response()->streamDownload(
            fn () => print($pdf),
            'analisis-penjualan-' . now()->format('Ymd-His') . '.pdf',
            ['Content-Type' => 'application/pdf']
        );
    }

    protected function downloadCsv()
    {
        $r = SalesAnalytics::make($this->filters)->report();

        return response()->streamDownload(function () use ($r) {
            $o = fopen('php://output', 'w');
            fwrite($o, "\xEF\xBB\xBF"); // BOM supaya Excel membaca UTF-8
            $put = fn (array $row) => fputcsv($o, $row, ';');

            $put(['ANALISIS PENJUALAN']);
            $put(['Periode', $r['periode']]);
            $put(['Cabang', $r['cabang']]);
            $put(['Dibuat', $r['dibuat']]);
            $put([]);

            $put(['RINGKASAN']);
            foreach ($r['kpi'] as $row) {
                $put($row);
            }
            $put([]);

            $put(['ANALISIS SINGKAT']);
            foreach ($r['insights'] as $i) {
                $put([$i]);
            }
            $put([]);

            $put(['TREN BULANAN']);
            $put(array_merge([''], $r['monthly']['labels']));
            foreach ($r['monthly']['series'] as $s) {
                $put(array_merge([$s['name']], $s['values']));
            }
            $put([]);

            $put(['RATA-RATA PENDAPATAN PER HARI (Rp)']);
            $put(array_merge([''], $r['weekday']['labels']));
            foreach ($r['weekday']['series'] as $s) {
                $put(array_merge([$s['name']], $s['values']));
            }
            $put([]);

            $put(['PRODUK TERLARIS']);
            $put(['Produk', 'Jumlah terjual', 'Nilai (Rp)']);
            foreach ($r['products'] as $p) {
                $put([$p->product_name, $p->qty, $p->total]);
            }
            $put([]);

            $put(['METODE PEMBAYARAN']);
            $put(['Metode', 'Jumlah transaksi', 'Nilai (Rp)']);
            foreach ($r['payments'] as $p) {
                $put([strtoupper($p->payment_method), $p->qty, $p->total]);
            }

            fclose($o);
        }, 'analisis-penjualan-' . now()->format('Ymd-His') . '.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function getWidgets(): array
    {
        return [
            SalesStats::class,
            SalesInsights::class,
            RevenueTrendChart::class,
            NewMemberChart::class,
            MemberStatusChart::class,
            WeekdayRevenueChart::class,
            RevenueMixChart::class,
            PaymentMethodChart::class,
            TopProductsChart::class,
            ExpiringMembersTable::class,
            SalesHeatmap::class,
        ];
    }

    public function getColumns(): int|string|array
    {
        return 2;
    }
}