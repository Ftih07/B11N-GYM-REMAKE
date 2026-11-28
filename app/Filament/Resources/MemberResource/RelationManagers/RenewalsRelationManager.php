<?php

namespace App\Filament\Resources\MemberResource\RelationManagers;

use App\Models\MembershipRenewal;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class RenewalsRelationManager extends RelationManager
{
    protected static string $relationship = 'renewals';

    protected static ?string $title = 'Riwayat Perpanjangan';

    protected static ?string $modelLabel = 'perpanjangan';

    // Form tambah/ubah manual (mencatat pembayaran lama TANPA mengubah masa berlaku member).
    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\DatePicker::make('paid_at')
                ->label('Tanggal Bayar')
                ->default(now())
                ->required(),
            Forms\Components\DatePicker::make('period_month')
                ->label('Untuk Bulan')
                ->native(false)
                ->displayFormat('F Y')
                ->default(now()->startOfMonth())
                ->required(),
            Forms\Components\Select::make('months')
                ->label('Jumlah Bulan')
                ->options(self::monthOptions())
                ->default(1)
                ->required(),
            Forms\Components\Select::make('payment_method')
                ->label('Metode Pembayaran')
                ->options(MembershipRenewal::PAYMENT_METHODS)
                ->required(),
            Forms\Components\Textarea::make('notes')
                ->label('Catatan')
                ->rows(2)
                ->columnSpanFull(),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('paid_at')
            ->defaultSort('paid_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Tanggal Bayar')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('period_month')
                    ->label('Untuk Bulan')
                    ->state(fn (MembershipRenewal $r) => collect($r->coveredMonths())
                        ->map(fn ($ym) => Carbon::createFromFormat('!Y-m', $ym)->locale('id')->translatedFormat('M Y'))
                        ->implode(', ')),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Metode')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => MembershipRenewal::PAYMENT_METHODS[$state] ?? '-')
                    ->color('info'),
                Tables\Columns\TextColumn::make('valid_until')
                    ->label('Berlaku Sampai')
                    ->date('d M Y')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('source')
                    ->label('Sumber')
                    ->formatStateUsing(fn (?string $state) => MembershipRenewal::SOURCES[$state] ?? $state)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('notes')
                    ->label('Catatan')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Catat Pembayaran Lama')
                    ->modalHeading('Catat Pembayaran (tanpa mengubah masa berlaku)')
                    ->mutateFormDataUsing(function (array $data) {
                        $data['source'] = 'manual';
                        $data['recorded_by'] = auth()->id();

                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Edit'),
                Tables\Actions\DeleteAction::make()->label('Hapus'),
            ]);
    }

    public static function monthOptions(): array
    {
        return collect(range(1, 12))->mapWithKeys(fn ($n) => [$n => "{$n} bulan"])->all();
    }
}
