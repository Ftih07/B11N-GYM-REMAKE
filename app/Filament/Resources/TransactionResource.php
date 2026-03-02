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

class TransactionResource extends Resource
{
    // --- NAVIGATION SETTINGS ---
    protected static ?string $model = Transaction::class;
    protected static ?string $navigationIcon = 'heroicon-o-receipt-refund'; // Icon: Receipt
    protected static ?string $navigationLabel = 'Rekap Transaksi';
    protected static ?string $pluralModelLabel = 'Laporan Transaksi';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?int $navigationSort = 1;

    // --- FORM CONFIGURATION (POS Input) ---
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Section 1: Transaction Info
                Section::make('Informasi Transaksi')
                    ->columns(2)
                    ->schema([
                        // Cashier Selection
                        Select::make('trainer_id')
                            ->relationship('trainer', 'name')
                            ->required()
                            ->label('Kasir Bertugas'),

                        // Gym Branch Selection
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

                // Section 2: Shopping Cart (Repeater)
                Section::make('Keranjang Belanja')
                    ->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->schema([
                                // Product Selection
                                Select::make('product_id')
                                    ->label('Produk')
                                    ->options(Product::all()->pluck('name', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->reactive() // Trigger update immediately
                                    ->afterStateUpdated(function ($state, Set $set) {
                                        // Auto-fill Price & Name when product selected
                                        $product = Product::find($state);
                                        if ($product) {
                                            $set('price', $product->price);
                                            $set('product_name', $product->name);
                                        }
                                    }),

                                Forms\Components\Hidden::make('product_name'),

                                // Quantity Input (Live Calculation)
                                TextInput::make('quantity')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0)
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        // 1. Calculate Subtotal per Item
                                        $price = $get('price') ?? 0;
                                        $subtotal = $state * $price;
                                        $set('subtotal', $subtotal);

                                        // 2. Calculate Grand Total
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
                            // Recalculate Total if Item Removed/Added
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $items = $get('items');
                                $sum = collect($items)->sum(fn($item) => $item['subtotal'] ?? 0);
                                $set('total_amount', $sum);

                                // Auto-fill Paid Amount if Cashless
                                if (in_array($get('payment_method'), ['qris', 'transfer'])) {
                                    $set('paid_amount', $sum);
                                    $set('change_amount', 0);
                                }
                            }),
                    ]),

                // Section 3: Payment Details
                Section::make('Pembayaran')
                    ->columns(2)
                    ->schema([
                        TextInput::make('total_amount')
                            ->label('Total Tagihan')
                            ->numeric()
                            ->prefix('Rp')
                            ->readOnly()
                            ->dehydrated(),

                        // Payment Method Logic
                        Select::make('payment_method')
                            ->options([
                                'cash' => 'Tunai',
                                'qris' => 'QRIS',
                                'transfer' => 'Transfer Bank',
                            ])
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                // If Cashless -> Paid Amount = Total (No Change)
                                if (in_array($state, ['qris', 'transfer'])) {
                                    $total = $get('total_amount');
                                    $set('paid_amount', $total);
                                    $set('change_amount', 0);
                                } else {
                                    // If Cash -> Reset for manual input
                                    $set('paid_amount', 0);
                                    $set('change_amount', 0 - $get('total_amount'));
                                }
                            }),

                        // Paid Amount Input (Calculates Change)
                        TextInput::make('paid_amount')
                            ->label('Uang Diterima')
                            ->numeric()
                            ->prefix('Rp')
                            ->live(onBlur: true) // Calc only after typing finishes
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

    // --- TABLE CONFIGURATION (List View) ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // 1. Transaction Code
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode TRX')
                    ->weight('bold')
                    ->copyable()
                    ->searchable()
                    ->sortable(),

                // 2. Source Badge (Online vs Offline)
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
                    ->label('Customer')
                    ->placeholder('-'),

                // 3. Total Amount with Summary
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable()
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->money('IDR')->label('Total Omset')),

                // 4. Payment Method Badge
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Metode')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => strtoupper($state))
                    ->color(fn(string $state): string => match ($state) {
                        'cash' => 'success',
                        'qris' => 'info',
                        'transfer' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'cancelled' => 'danger',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')

            // --- HEADER ACTION: EXCEL EXPORT ---
            ->headerActions([
                Tables\Actions\Action::make('export_excel')
                    ->label('Export Data Penjualan')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->form([
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
                                $years = range(Carbon::now()->year - 2, Carbon::now()->year + 1);
                                return array_combine($years, $years);
                            })
                            ->default(now()->year)
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        return Excel::download(
                            new TransactionExport($data['month'], $data['year']),
                            'Laporan-Transaksi-POS-' . $data['month'] . '-' . $data['year'] . '.xlsx'
                        );
                    }),
            ])

            // --- FILTERS ---
            ->filters([
                Tables\Filters\SelectFilter::make('gymkos_id')
                    ->relationship('gymkos', 'name')
                    ->label('Cabang')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('status')
                    ->options(['paid' => 'Lunas', 'pending' => 'Pending', 'cancelled' => 'Batal']),

                Tables\Filters\SelectFilter::make('payment_method')
                    ->label('Metode Bayar')
                    ->options(['cash' => 'Tunai', 'qris' => 'QRIS', 'transfer' => 'Transfer']),

                // DATE RANGE FILTER
                Tables\Filters\Filter::make('created_at')
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
                    ->label('Bukti')
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

                // --- PRINT STRUK ACTION ---
                Tables\Actions\Action::make('print')
                    ->label('Cetak')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->visible(fn(Transaction $record) => $record->status === 'paid') // Only show if paid
                    ->url(fn(Transaction $record) => route('print.struk', $record->code)) // Opens route for printing
                    ->openUrlInNewTab(),

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

    // --- INFOLIST VIEW (Detail Popup) ---
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

                // List Items (Detail Belanjaan)
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
                            ->columns(2)
                    ]),

                // Summary Total
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
