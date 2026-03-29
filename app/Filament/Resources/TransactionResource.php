<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Models\Transaction;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use App\Exports\TransactionExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TransactionResource extends Resource
{
    // --- PENGATURAN NAVIGASI ---
    protected static ?string $model = Transaction::class;
    protected static ?string $navigationIcon = 'heroicon-o-receipt-refund'; // Ikon: Struk Kasir
    protected static ?string $navigationLabel = 'Rekap Transaksi';
    protected static ?string $pluralModelLabel = 'Laporan Transaksi';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?int $navigationSort = 1;

    // --- KONFIGURASI FORM (Input POS/Kasir) ---
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Bagian 1: Info Transaksi
                Section::make('Informasi Transaksi')
                    ->columns(2)
                    ->schema([
                        // Pilihan Kasir
                        Select::make('trainer_id')
                            ->relationship('trainer', 'name')
                            ->required()
                            ->label('Kasir Bertugas'),

                        // Pilihan Cabang Gym
                        Select::make('gymkos_id')
                            ->relationship('gymkos', 'name')
                            ->label('Cabang (Gym/Kos)')
                            ->required(),

                        TextInput::make('customer_name')
                            ->label('Nama Pelanggan')
                            ->placeholder('Opsional (Boleh dikosongkan)')
                            ->maxLength(255),

                        Select::make('status')
                            ->label('Status Pembayaran')
                            ->options([
                                'pending' => 'Menunggu Pembayaran',
                                'paid' => 'Lunas',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->default('pending')
                            ->required(),

                        Forms\Components\DateTimePicker::make('created_at')
                            ->label('Tanggal Transaksi')
                            ->default(now()),
                    ]),

                // Bagian 2: Keranjang Belanja (Repeater)
                Section::make('Keranjang Belanja')
                    ->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->label('Daftar Belanja')
                            ->schema([
                                // Pilihan Produk
                                Select::make('product_id')
                                    ->label('Produk')
                                    ->options(
                                        Product::all()->mapWithKeys(function ($product) {
                                            // Format tampilannya: "Nama Produk (Rp 13.000)"
                                            return [
                                                $product->id => "{$product->name} (Rp " . number_format($product->price, 0, ',', '.') . ")"
                                            ];
                                        })
                                    )
                                    ->required()
                                    ->searchable()
                                    ->reactive() // Memicu update seketika
                                    ->afterStateUpdated(function ($state, Set $set) {
                                        // Auto-isi Harga & Nama saat produk dipilih
                                        $product = Product::find($state);
                                        if ($product) {
                                            $set('price', $product->price);
                                            $set('product_name', $product->name);
                                        }
                                    }),

                                Forms\Components\Hidden::make('product_name'),

                                // Input Jumlah (Kalkulasi Langsung)
                                TextInput::make('quantity')
                                    ->label('Jumlah')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0)
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        // 1. Hitung Subtotal per Barang
                                        $price = $get('price') ?? 0;
                                        $subtotal = $state * $price;
                                        $set('subtotal', $subtotal);

                                        // 2. Hitung Total Keseluruhan
                                        $items = $get('../../items');
                                        $total = collect($items)->sum(fn($item) => $item['subtotal'] ?? 0);
                                        $set('../../total_amount', $total);
                                    }),

                                TextInput::make('price')
                                    ->label('Harga Satuan')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->readOnly()
                                    ->dehydrated(),

                                TextInput::make('subtotal')
                                    ->label('Subtotal')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->readOnly()
                                    ->dehydrated(),
                            ])
                            ->columns(4)
                            ->live()
                            // Hitung ulang Total jika barang dihapus/ditambah
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $items = $get('items');
                                $sum = collect($items)->sum(fn($item) => $item['subtotal'] ?? 0);
                                $set('total_amount', $sum);

                                // Auto-isi Uang Diterima jika Cashless
                                if (in_array($get('payment_method'), ['qris', 'transfer'])) {
                                    $set('paid_amount', $sum);
                                    $set('change_amount', 0);
                                }
                            })
                            ->addActionLabel('Tambah Barang'),
                    ]),

                // Bagian 3: Detail Pembayaran
                Section::make('Pembayaran')
                    ->columns(2)
                    ->schema([
                        TextInput::make('total_amount')
                            ->label('Total Tagihan')
                            ->numeric()
                            ->prefix('Rp')
                            ->readOnly()
                            ->dehydrated(),

                        // Logika Metode Pembayaran
                        Select::make('payment_method')
                            ->label('Metode Pembayaran')
                            ->options([
                                'cash' => 'Tunai',
                                'qris' => 'QRIS',
                                'transfer' => 'Transfer Bank',
                            ])
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                // Jika Cashless -> Uang Diterima = Total (Tanpa Kembalian)
                                if (in_array($state, ['qris', 'transfer'])) {
                                    $total = $get('total_amount');
                                    $set('paid_amount', $total);
                                    $set('change_amount', 0);
                                } else {
                                    // Jika Cash -> Reset untuk input manual
                                    $set('paid_amount', 0);
                                    $set('change_amount', 0 - $get('total_amount'));
                                }
                            }),

                        // Input Uang Diterima (Menghitung Kembalian)
                        TextInput::make('paid_amount')
                            ->label('Uang Diterima')
                            ->numeric()
                            ->prefix('Rp')
                            ->live(onBlur: true) // Kalkulasi hanya setelah selesai mengetik
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

                        FileUpload::make('proof_of_payment')
                            ->label('Bukti Pembayaran')
                            ->image()
                            ->directory('transaction-proofs')
                            ->nullable()
                            // --- FITUR KOMPRESI BAWAAN FILAMENT ---
                            ->imageResizeMode('contain') // Menjaga proporsi asli, tidak dicrop
                            ->imageResizeTargetWidth('1024') // Batasi maksimal lebar
                            ->imageResizeTargetHeight('1024') // Batasi maksimal tinggi
                            // --------------------------------------
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    // --- KONFIGURASI TABEL (Tampilan List) ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // 1. Kode Transaksi
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode TRX')
                    ->weight('bold')
                    ->copyable()
                    ->searchable()
                    ->sortable(),

                // 2. Badge Asal (Online vs Offline)
                Tables\Columns\TextColumn::make('source_label')
                    ->label('Asal')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        if ($record->payable_type === 'App\Models\Booking') return 'Booking Kost';
                        elseif ($record->payable_type === 'App\Models\Payment') return 'Member Online';
                        else return 'Kasir / POS';
                    })
                    ->colors([
                        'info'    => 'Booking Kost',
                        'success' => 'Member Online',
                        'gray'    => 'Kasir / POS',
                    ])
                    ->icon(fn($state) => match ($state) {
                        'Booking Kost' => 'heroicon-m-home',
                        'Member Online' => 'heroicon-m-identification',
                        default => 'heroicon-m-computer-desktop',
                    }),

                Tables\Columns\TextColumn::make('trainer.name')
                    ->label('Kasir')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->placeholder('-'),

                // 3. Total Belanja dengan Ringkasan
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total Belanja')
                    ->money('IDR', locale: 'id')
                    ->sortable()
                    ->searchable()
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->money('IDR', locale: 'id')->label('Total Omset')),

                // 4. Badge Metode Pembayaran
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Metode Bayar')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => strtoupper($state))
                    ->color(fn(string $state): string => match ($state) {
                        'cash' => 'success',
                        'qris' => 'info',
                        'transfer' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'paid' => 'Lunas',
                        'pending' => 'Menunggu',
                        'cancelled' => 'Batal',
                        default => $state,
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'cancelled' => 'danger',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')

            // --- AKSI HEADER: EXPORT EXCEL ---
            ->headerActions([
                Tables\Actions\Action::make('export_excel')
                    ->label('Export Data Penjualan')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->form([
                        Select::make('gymkos_id')
                            ->label('Cabang (Gym/Kos)')
                            ->options(\App\Models\Gymkos::all()->pluck('name', 'id'))
                            ->placeholder('Semua Cabang') // Kosong berarti 'All'
                            ->default(null),

                        Select::make('month')
                            ->label('Bulan')
                            ->options([
                                '01' => 'Januari',
                                '02' => 'Februari',
                                '03' => 'Maret',
                                '04' => 'April',
                                '05' => 'Mei',
                                '06' => 'Juni',
                                '07' => 'Juli',
                                '08' => 'Agustus',
                                '09' => 'September',
                                '10' => 'Oktober',
                                '11' => 'November',
                                '12' => 'Desember',
                            ])
                            ->default(now()->format('m'))
                            ->required(),

                        Select::make('year')
                            ->label('Tahun')
                            ->options(function () {
                                $years = range(now()->year - 2, now()->year + 1);
                                return array_combine($years, $years);
                            })
                            ->default(now()->year)
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        // Ambil variabel
                        $month = $data['month'];
                        $year = $data['year'];
                        $gymkosId = $data['gymkos_id'] ?? null;

                        // Bikin nama file dinamis
                        $gymName = $gymkosId ? \App\Models\Gymkos::find($gymkosId)->name : 'Semua-Cabang';
                        $fileName = "Laporan-Transaksi-POS-{$gymName}-{$month}-{$year}.xlsx";

                        return Excel::download(
                            new TransactionExport($month, $year, $gymkosId),
                            $fileName
                        );
                    }),
            ])

            // --- FILTER ---
            ->filters([
                Tables\Filters\SelectFilter::make('gymkos_id')
                    ->relationship('gymkos', 'name')
                    ->label('Cabang')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('source')
                    ->label('Asal Transaksi')
                    ->options([
                        'App\Models\Booking' => 'Booking Kost',
                        'App\Models\Payment' => 'Member Online',
                        'pos' => 'Kasir / POS',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (empty($data['value'])) {
                            return $query;
                        }

                        if ($data['value'] === 'pos') {
                            return $query->where(function ($q) {
                                $q->whereNull('payable_type')
                                    ->orWhereNotIn('payable_type', [
                                        'App\Models\Booking',
                                        'App\Models\Payment'
                                    ]);
                            });
                        }

                        return $query->where('payable_type', $data['value']);
                    }),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(['paid' => 'Lunas', 'pending' => 'Menunggu Pembayaran', 'cancelled' => 'Dibatalkan']),

                Tables\Filters\SelectFilter::make('payment_method')
                    ->label('Metode Bayar')
                    ->options(['cash' => 'Tunai', 'qris' => 'QRIS', 'transfer' => 'Transfer Bank']),

                // FILTER RENTANG TANGGAL
                Tables\Filters\Filter::make('created_at')
                    ->label('Rentang Tanggal')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('created_until')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['created_from'], fn($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'], fn($q, $date) => $q->whereDate('created_at', '<=', $date));
                    })
            ])
            ->actions([
                Tables\Actions\Action::make('view_proof')
                    ->label('Lihat Bukti')
                    ->icon('heroicon-o-photo')
                    ->color('success')
                    ->visible(fn(Transaction $record) => $record->proof_of_payment !== null)
                    ->modalHeading('Bukti Pembayaran')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->infolist([
                        Infolists\Components\ImageEntry::make('proof_of_payment')
                            ->hiddenLabel()
                            ->extraImgAttributes([
                                'alt' => 'Bukti Pembayaran',
                                'style' => 'border-radius: 8px; max-height: 500px; width: auto; margin: 0 auto; display: block;',
                            ]),
                    ]),

                // --- AKSI CETAK STRUK ---
                Tables\Actions\Action::make('print')
                    ->label('Cetak Struk')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->visible(fn(Transaction $record) => $record->status === 'paid') // Hanya muncul jika lunas
                    ->url(fn(Transaction $record) => route('print.struk', $record->code)) // Buka route cetak
                    ->openUrlInNewTab(),

                Tables\Actions\ViewAction::make()->label('Detail'),
                Tables\Actions\EditAction::make()->label('Edit'),
                Tables\Actions\DeleteAction::make()->label('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Hapus Pilihan'),
                ]),
            ]);
    }

    // --- TAMPILAN INFOLIST (Popup Detail) ---
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
                                    Infolists\Components\TextEntry::make('created_at')->label('Tanggal')->dateTime('d M Y, H:i'),
                                    Infolists\Components\TextEntry::make('trainer.name')->label('Kasir'),
                                    Infolists\Components\TextEntry::make('payment_method')->label('Metode Pembayaran')->badge()
                                        ->formatStateUsing(fn(string $state): string => strtoupper($state)),
                                ]),
                        ])
                    ]),

                // Daftar Barang Belanjaan
                Infolists\Components\Section::make('Daftar Barang')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('items')
                            ->label('')
                            ->schema([
                                Infolists\Components\Grid::make(4)
                                    ->schema([
                                        Infolists\Components\TextEntry::make('product.name')->label('Produk'),
                                        Infolists\Components\TextEntry::make('quantity')->label('Jumlah'),
                                        Infolists\Components\TextEntry::make('price')->label('Harga Satuan')->money('IDR', locale: 'id'),
                                        Infolists\Components\TextEntry::make('subtotal')->label('Subtotal')->money('IDR', locale: 'id')->weight('bold'),
                                    ]),
                            ])
                            ->columns(2)
                    ]),

                // Ringkasan Total
                Infolists\Components\Section::make('Ringkasan Total')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('total_amount')->label('Total Tagihan')->money('IDR', locale: 'id')->size(Infolists\Components\TextEntry\TextEntrySize::Large)->weight('bold'),
                                Infolists\Components\TextEntry::make('paid_amount')->label('Uang Diterima')->money('IDR', locale: 'id'),
                                Infolists\Components\TextEntry::make('change_amount')->label('Kembalian')->money('IDR', locale: 'id'),
                            ]),
                    ])
            ]);
    }

    // --- PENGATURAN PENCARIAN GLOBAL ---
    // 1. Kolom utama untuk pencarian (wajib diisi agar global search aktif)
    protected static ?string $recordTitleAttribute = 'code';

    // 2. Daftarkan kolom apa saja yang bisa dicari (Kode TRX & Nama Pelanggan)
    public static function getGloballySearchableAttributes(): array
    {
        return ['code', 'customer_name'];
    }

    // 3. Format judul yang muncul di hasil pencarian dropdown
    public static function getGlobalSearchResultTitle(Model $record): string
    {
        $customerName = $record->customer_name ? " - {$record->customer_name}" : "";
        return "{$record->code}{$customerName}";
    }

    // 4. (Opsional) Tambahkan detail tambahan di bawah judul pencarian
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Pelanggan' => $record->customer_name ?? '-',
            'Total Belanja' => 'Rp ' . number_format($record->total_amount, 0, ',', '.'),
            'Status' => match ($record->status) {
                'paid' => 'LUNAS',
                'pending' => 'MENUNGGU PEMBAYARAN',
                'cancelled' => 'DIBATALKAN',
                default => strtoupper($record->status),
            },
        ];
    }

    public static function getRelations(): array
    {
        return [];
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
