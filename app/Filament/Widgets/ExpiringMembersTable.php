<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use App\Services\SalesAnalytics;
use Carbon\Carbon;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ExpiringMembersTable extends TableWidget
{
    use InteractsWithPageFilters;

    protected static bool $isDiscovered = false;

    /** Dimuat langsung (tidak lazy) supaya tidak error saat halaman di-refresh. */
    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    protected function baseQuery(): Builder
    {
        $branch = $this->filters['gymkos_id'] ?? null;
        $ids = $branch ? [(int) $branch] : SalesAnalytics::GYM_IDS;

        return Member::query()
            ->select('members.*')
            ->selectSub(
                DB::table('gymkos')->select('name')->whereColumn('gymkos.id', 'members.gymkos_id')->limit(1),
                'gymkos_name'
            )
            ->whereIn('members.gymkos_id', $ids)
            ->whereNotNull('members.membership_end_date');
    }

    protected static function waUrl($record): string
    {
        $digits = preg_replace('/\D+/', '', (string) $record->phone);
        if (str_starts_with($digits, '0')) {
            $digits = '62' . substr($digits, 1);
        } elseif (str_starts_with($digits, '8')) {
            $digits = '62' . $digits;
        }

        $end = Carbon::parse($record->membership_end_date)->locale('id')->translatedFormat('d F Y');
        $text = "Halo {$record->name}, masa membership kamu di {$record->gymkos_name} berakhir pada {$end}. "
            . 'Yuk perpanjang supaya latihanmu tetap lancar 💪';

        return 'https://wa.me/' . $digits . '?text=' . rawurlencode($text);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Follow-up Perpanjangan Member')
            ->description('Daftar member yang akan atau baru saja berakhir. Klik WhatsApp untuk mengirim pesan.')
            ->query(fn () => $this->baseQuery())
            ->defaultSort('membership_end_date')
            ->paginated([10, 25, 50])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->filters([
                SelectFilter::make('range')
                    ->label('Tampilkan')
                    ->options([
                        '7'       => 'Berakhir ≤ 7 hari',
                        '14'      => 'Berakhir ≤ 14 hari',
                        '30'      => 'Berakhir ≤ 30 hari',
                        'expired' => 'Baru expired (30 hari terakhir)',
                    ])
                    ->default('30')
                    ->selectablePlaceholder(false)
                    ->query(function (Builder $q, array $data) {
                        $v = $data['value'] ?? '30';

                        if ($v === 'expired') {
                            $q->whereBetween('members.membership_end_date', [
                                now()->subDays(30)->toDateString(),
                                now()->subDay()->toDateString(),
                            ]);

                            return;
                        }

                        $q->where('members.status', 'active')
                            ->whereBetween('members.membership_end_date', [
                                now()->toDateString(),
                                now()->addDays((int) $v)->toDateString(),
                            ]);
                    }),
            ])
            ->columns([
                TextColumn::make('member_code')->label('Kode')->searchable(),
                TextColumn::make('name')->label('Nama')->searchable()->weight('medium'),
                TextColumn::make('phone')->label('Telepon')->searchable(),
                TextColumn::make('gymkos_name')->label('Cabang')->badge()->color('gray'),
                TextColumn::make('membership_end_date')
                    ->label('Berakhir')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('days_left')
                    ->label('Sisa')
                    ->state(fn ($record) => (int) today()->diffInDays(Carbon::parse($record->membership_end_date)->startOfDay(), false))
                    ->formatStateUsing(fn (int $state) => match (true) {
                        $state < 0   => 'Lewat ' . abs($state) . ' hari',
                        $state === 0 => 'Hari ini',
                        default      => $state . ' hari',
                    })
                    ->badge()
                    ->color(fn (int $state) => match (true) {
                        $state < 0  => 'danger',
                        $state <= 7 => 'warning',
                        default     => 'success',
                    }),
            ])
            ->actions([
                Action::make('wa')
                    ->label('WhatsApp')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->url(fn ($record) => static::waUrl($record))
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => filled($record->phone)),
            ])
            ->emptyStateHeading('Tidak ada member pada rentang ini');
    }
}