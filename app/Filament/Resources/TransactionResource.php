<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use App\Models\Product;
use Filament\Forms\Get;
use Filament\Forms\Set;

use Filament\Infolists;
use Filament\Infolists\Infolist;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-refund';
    protected static ?string $navigationLabel = 'Rekap Transaksi';
    protected static ?string $pluralModelLabel = 'Laporan Transaksi';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Transaksi')
                    ->columns(2)
                    ->schema([
                        Select::make('trainer_id')
                            ->relationship('trainer', 'name')
                            ->required()
                            ->label('Kasir Bertugas'),

                        Select::make('gymkos_id')
                            ->relationship('gymkos', 'name')
                            ->required(),

                        TextInput::make('customer_name')
                            ->label('Customer Name')
                            ->placeholder('Optional')
                            ->maxLength(255),

                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Lunas',
                                'cancelled' => 'Batal',
                            ])
                            ->default('pending')
                            ->required(),

                        Forms\Components\DateTimePicker::make('created_at')
                            ->label('Tanggal Transaksi')
                            ->default(now()),
                    ]),

                Section::make('Keranjang Belanja')
                    ->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Select::make('product_id')
                                    ->label('Produk')
                                    ->options(Product::all()->pluck('name', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, Set $set) {
                                        $product = Product::find($state);
                                        if ($product) {
                                            $set('price', $product->price);
                                            $set('product_name', $product->name);
                                        }
                                    }),

                                Forms\Components\Hidden::make('product_name'),

                                TextInput::make('quantity')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0)
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        $price = $get('price') ?? 0;
                                        $subtotal = $state * $price;
                                        $set('subtotal', $subtotal);

                                        // HITUNG TOTAL LANGSUNG
                                        $items = $get('../../items');
                                        $total = collect($items)->sum(fn($item) => $item['subtotal'] ?? 0);
                                        $set('../../total_amount', $total);
                                    }),

                                TextInput::make('price')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->readOnly()
                                    ->dehydrated(),

                                TextInput::make('subtotal')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->readOnly()
                                    ->dehydrated(),
                            ])
                            ->columns(4)
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $items = $get('items');
                                $sum = collect($items)->sum(fn($item) => $item['subtotal'] ?? 0);
                                $set('total_amount', $sum);

                                // Tambahan: Update otomatis paid amount jika metode bayar sudah dipilih (opsional tapi bagus)
                                if (in_array($get('payment_method'), ['qris', 'transfer'])) {
                                    $set('paid_amount', $sum);
                                    $set('change_amount', 0);
                                }
                            }),
                    ]),

                Section::make('Pembayaran')
                    ->columns(2)
                    ->schema([
                        TextInput::make('total_amount')
                            ->label('Total Tagihan')
                            ->numeric()
                            ->prefix('Rp')
                            ->readOnly()
                            ->dehydrated(),

                        // FIX: Logika Otomatis QRIS/Transfer
                        Select::make('payment_method')
                            ->options([
                                'cash' => 'Tunai',
                                'qris' => 'QRIS',
                                'transfer' => 'Transfer Bank',
                            ])
                            ->required()
                            ->live() // Gunakan live agar langsung trigger
                            ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                // Jika pilih QRIS atau Transfer, samakan Uang Diterima dengan Total
                                if (in_array($state, ['qris', 'transfer'])) {
                                    $total = $get('total_amount');
                                    $set('paid_amount', $total);
                                    $set('change_amount', 0);
                                } else {
                                    // Jika balik ke Cash, kosongkan biar kasir input manual
                                    $set('paid_amount', 0);
                                    $set('change_amount', 0 - $get('total_amount'));
                                }
                            }),

                        // FIX: Mengatasi Bug Input Angka Hilang
                        TextInput::make('paid_amount')
                            ->label('Uang Diterima')
                            ->numeric()
                            ->prefix('Rp')
                            ->live(onBlur: true) // PENTING: Hitung hanya setelah selesai ketik/klik luar
                            ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                $total = $get('total_amount');
                                $change = $state - $total;
                                $set('change_amount', $change);
                            }),

                        TextInput::make('change_amount')
                            ->label('Kembalian')
                            ->numeric()
                            ->prefix('Rp')
                            ->readOnly(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // 1. Kode Transaksi (Bisa dicopy & Searchable)
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode TRX')
                    ->weight('bold')
                    ->copyable() // Biar bisa dicopy admin
                    ->searchable()
                    ->sortable(),

                // 2. Nama Kasir (Ambil dari relasi trainer)
                Tables\Columns\TextColumn::make('trainer.name')
                    ->label('Kasir')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Customer')
                    ->placeholder('-'),

                // 3. Cabang Gym (Disembunyikan default biar ga penuh, bisa ditoggle)
                Tables\Columns\TextColumn::make('gymkos.name')
                    ->label('Cabang')
                    ->toggleable(isToggledHiddenByDefault: true),

                // 4. Total Uang (Ada Sumary di bawah tabel otomatis)
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable()
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->money('IDR')->label('Total Omset')),

                // 5. Metode Bayar (Pakai Badge warna)
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Metode')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => strtoupper($state))
                    ->color(fn(string $state): string => match ($state) {
                        'cash' => 'success',   // Hijau
                        'qris' => 'info',      // Biru
                        'transfer' => 'warning', // Kuning
                        default => 'gray',
                    }),

                // 6. Status Transaksi
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'cancelled' => 'danger',
                    }),

                // 7. Tanggal Transaksi
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i') // Format tgl jam indonesia
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc') // Yang terbaru paling atas
            ->filters([
                // Filter Status (Lunas/Pending)
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'paid' => 'Lunas',
                        'pending' => 'Pending',
                        'cancelled' => 'Batal',
                    ]),

                // Filter Metode Bayar
                Tables\Filters\SelectFilter::make('payment_method')
                    ->label('Metode Bayar')
                    ->options([
                        'cash' => 'Tunai',
                        'qris' => 'QRIS',
                        'transfer' => 'Transfer',
                    ]),

                // Filter Tanggal (PENTING BUAT KASIR CEK OMSET HARIAN)
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('created_until')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date) => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date) => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\Action::make('print')
                    ->label('Cetak')
                    ->icon('heroicon-o-printer')
                    ->color('info') // Warna biru
                    ->url(fn(Transaction $record) => route('print.struk', $record->code))
                    ->openUrlInNewTab(), // Buka tab baru biar admin panel gak ketutup               
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Transaksi')
                    ->schema([
                        Infolists\Components\Split::make([
                            Infolists\Components\Grid::make(2)
                                ->schema([
                                    Infolists\Components\TextEntry::make('code')->label('Kode TRX')->weight('bold'),
                                    Infolists\Components\TextEntry::make('created_at')->label('Tanggal')->dateTime('d M Y H:i'),
                                    Infolists\Components\TextEntry::make('trainer.name')->label('Kasir'),
                                    Infolists\Components\TextEntry::make('payment_method')->label('Pembayaran')->badge(),
                                ]),
                        ])
                    ]),

                Infolists\Components\Section::make('Daftar Barang')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('items')
                            ->schema([
                                Infolists\Components\Grid::make(4)
                                    ->schema([
                                        Infolists\Components\TextEntry::make('product.name')->label('Produk'),
                                        Infolists\Components\TextEntry::make('quantity')->label('Qty'),
                                        Infolists\Components\TextEntry::make('price')->label('Harga')->money('IDR'),
                                        Infolists\Components\TextEntry::make('subtotal')->label('Subtotal')->money('IDR')->weight('bold'),
                                    ]),
                            ])
                            ->columns(2) // Tampilan grid
                    ]),

                Infolists\Components\Section::make('Total')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('total_amount')->label('Total Tagihan')->money('IDR')->size(Infolists\Components\TextEntry\TextEntrySize::Large)->weight('bold'),
                                Infolists\Components\TextEntry::make('paid_amount')->label('Bayar')->money('IDR'),
                                Infolists\Components\TextEntry::make('change_amount')->label('Kembalian')->money('IDR'),
                            ]),
                    ])
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
