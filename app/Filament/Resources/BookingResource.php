<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Get; // Tambahan
use Filament\Forms\Set; // Tambahan
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon; // Tambahan untuk tanggal

class BookingResource extends Resource
{
    // Badge Notification (Tetap pertahankan)
    public static function getNavigationBadge(): ?string
    {
        return Booking::where('status', 'pending')->count() ?: null;
    }

    protected static ?string $model = Booking::class;

    // --- UBAH ICON ---
    // Ganti 'calendar' jadi 'users' (karena manage orang) atau 'home-modern' (manage properti)
    protected static ?string $navigationIcon = 'heroicon-o-users';

    // --- UBAH LABEL SIDEBAR ---
    // Opsi 1: "Manajemen Penghuni" (Terasa profesional)
    // Opsi 2: "Data Sewa Kost" (Jelas & to the point)
    protected static ?string $navigationLabel = 'Manajemen Penghuni';

    // --- TAMBAHAN: UBAH LABEL TOMBOL ---
    // Biar tombolnya jadi "Tambah Penghuni", bukan "New Booking"
    public static function getModelLabel(): string
    {
        return 'Penghuni Kost';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Data Penghuni';
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('email')->email()->required(),
                Forms\Components\TextInput::make('phone')->required(),

                // --- UPDATE: LOGIC TANGGAL OTOMATIS ---
                Forms\Components\DatePicker::make('date')
                    ->label('Tanggal Mulai')
                    ->required()
                    ->live() // Agar reaktif
                    ->afterStateUpdated(function ($state, Set $set) {
                        if ($state) {
                            // Otomatis set tanggal selesai 1 bulan kedepan
                            $set('end_date', Carbon::parse($state)->addMonth()->format('Y-m-d'));
                        }
                    }),

                Forms\Components\DatePicker::make('end_date')
                    ->label('Tanggal Selesai')
                    ->required()
                    ->helperText('Diisi 1 bulan dari tanggal mulai.'),

                Forms\Components\Select::make('room_type')
                    ->options([
                        '750rb - AC' => '750rb - AC',
                        '500rb - Non AC' => '500rb - Non AC'
                    ])
                    ->required(),

                Forms\Components\Select::make('room_number')
                    ->options(function () {
                        // Cek kamar yang statusnya PAID dan tanggal sewanya BELUM BERAKHIR
                        $occupiedRooms = Booking::where('status', 'paid')
                            ->where('end_date', '>=', now())
                            ->pluck('room_number')
                            ->toArray();

                        return collect(range(1, 10))->mapWithKeys(fn($num) => ["$num" => "Room $num"])
                            ->except($occupiedRooms);
                    })->required(),

                Forms\Components\Select::make('payment')->options([
                    'qris' => 'QRIS',
                    'transfer' => 'Transfer Bank',
                    'cash' => 'Cash / Tunai', // Tambahan opsional
                ])->required(),

                Forms\Components\FileUpload::make('payment_proof')
                    ->directory('bukti_pembayaran')
                    ->columnSpanFull(), // Biar agak lebar gambarnya

                Forms\Components\Select::make('status')->options([
                    'pending' => 'Pending',
                    'paid' => 'Paid',
                    'cancelled' => 'Cancelled',
                ])->default('pending')->required(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            // Urutkan dari yang terbaru (biar tagihan baru muncul di atas)
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('room_number')
                    ->label('Kamar')
                    ->sortable(),

                // Kolom Tanggal Mulai
                Tables\Columns\TextColumn::make('date')
                    ->label('Mulai')
                    ->date('d M Y')
                    ->sortable(),

                // Kolom Tanggal Selesai (Baru)
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Selesai')
                    ->date('d M Y')
                    ->sortable(),

                // --- BADGE STATUS HUNI (LOGIC PENGHUNI AKTIF) ---
                Tables\Columns\TextColumn::make('status_huni')
                    ->label('Status Huni')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        $today = now()->startOfDay();
                        $endDate = Carbon::parse($record->end_date)->startOfDay();

                        if ($record->status === 'paid') {
                            if ($endDate >= $today) {
                                return 'Aktif';
                            } else {
                                return 'Habis Masa Sewa';
                            }
                        }
                        return 'Belum Lunas'; // Jika pending/cancelled
                    })
                    ->colors([
                        'success' => 'Aktif',
                        'danger' => 'Habis Masa Sewa',
                        'warning' => 'Belum Lunas',
                    ]),
                // -----------------------------------------------

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'cancelled' => 'danger',
                    }),

                Tables\Columns\ImageColumn::make('payment_proof')
                    ->label('Bukti')
                    ->circular(),
            ])
            ->filters([
                // Filter Bawaan Status Pembayaran
                Tables\Filters\SelectFilter::make('status')
                    ->options(['pending' => 'Pending', 'paid' => 'Paid']),

                // --- FILTER BARU: HANYA YANG AKTIF ---
                Tables\Filters\Filter::make('active_tenants')
                    ->label('Penghuni Aktif Saja')
                    ->query(
                        fn(Builder $query) => $query
                            ->where('status', 'paid')
                            ->where('end_date', '>=', now())
                    )
                    ->default(), // Default nyala, biar admin ga pusing liat data lama
                // -------------------------------------
            ])
            ->actions([
                // --- TOMBOL PERPANJANG (LOGIC BARU: REPLICATE) ---
                Tables\Actions\Action::make('perpanjang')
                    ->label('Perpanjang')
                    ->icon('heroicon-m-arrow-path') // Ikon refresh/cycle
                    ->color('info')
                    ->visible(fn(Booking $record) => $record->status === 'paid') // Hanya muncul kalau status paid
                    ->requiresConfirmation()
                    ->modalHeading('Perpanjang Sewa Penghuni')
                    ->modalDescription('Sistem akan membuat TAGIHAN BARU untuk bulan berikutnya. Data lama tetap tersimpan sebagai history.')
                    ->modalSubmitActionLabel('Ya, Buat Tagihan Baru')
                    ->action(function (Booking $record) {
                        // 1. Hitung tanggal baru
                        $lastEndDate = Carbon::parse($record->end_date);
                        $newStartDate = $lastEndDate->copy()->addDay(); // Mulai besoknya
                        $newEndDate = $newStartDate->copy()->addMonth(); // Sampai bulan depan

                        // 2. DUPLIKASI DATA (PENTING!)
                        $newBooking = $record->replicate();

                        // 3. Update data duplikat dengan info baru
                        $newBooking->date = $newStartDate;
                        $newBooking->end_date = $newEndDate;
                        $newBooking->status = 'pending'; // Reset jadi pending biar ditagih
                        $newBooking->payment_proof = null; // Kosongkan bukti bayar
                        $newBooking->created_at = now();
                        $newBooking->updated_at = now();

                        // 4. Simpan (Ini akan men-trigger 'booted' di Model Booking -> Buat Transaction Baru)
                        $newBooking->save();

                        // Notifikasi
                        \Filament\Notifications\Notification::make()
                            ->title('Tagihan Perpanjangan Dibuat')
                            ->body('Penghuni diperpanjang s/d ' . $newEndDate->format('d M Y'))
                            ->success()
                            ->send();
                    }),
                // -------------------------------------------------

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

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
