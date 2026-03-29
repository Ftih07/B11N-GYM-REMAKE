<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use App\Exports\BookingExport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\Action;

class BookingResource extends Resource
{
    // --- PENGATURAN NAVIGASI ---

    // Badge Notifikasi: Menampilkan jumlah booking 'pending' di sidebar
    public static function getNavigationBadge(): ?string
    {
        return Booking::where('status', 'pending')->count() ?: null;
    }

    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-users'; // Ikon Manajemen User
    protected static ?string $navigationLabel = 'Manajemen Penghuni'; // Label Sidebar

    // Mengubah label standar tombol "New Booking" menjadi "Penghuni Kost"
    public static function getModelLabel(): string
    {
        return 'Penghuni Kost';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Data Penghuni';
    }

    // --- KONFIGURASI FORM (Tambah/Edit) ---
    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->label('Alamat Email')
                    ->email()
                    ->required(),
                Forms\Components\TextInput::make('phone')
                    ->label('Nomor WhatsApp / HP')
                    ->required(),

                // LOGIKA TANGGAL OTOMATIS
                Forms\Components\DatePicker::make('date')
                    ->label('Tanggal Mulai')
                    ->required()
                    ->live() // Memantau perubahan
                    ->afterStateUpdated(function ($state, Set $set) {
                        if ($state) {
                            // Otomatis set 'end_date' ke 1 bulan setelah 'start_date'
                            $set('end_date', Carbon::parse($state)->addMonth()->format('Y-m-d'));
                        }
                    }),

                Forms\Components\DatePicker::make('end_date')
                    ->label('Tanggal Selesai')
                    ->required()
                    ->helperText('Otomatis diisi 1 bulan dari tanggal mulai.'),

                Forms\Components\Select::make('room_type')
                    ->label('Tipe Kamar')
                    ->options([
                        '750rb - AC' => '750rb - AC',
                        '500rb - Non AC' => '500rb - Non AC'
                    ])
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set) {
                        if ($state === '750rb - AC') {
                            $set('price', 750000);
                        } elseif ($state === '500rb - Non AC') {
                            $set('price', 500000);
                        } else {
                            $set('price', null);
                        }
                    }),

                Forms\Components\TextInput::make('price')
                    ->label('Total Harga')
                    ->numeric()
                    ->required()
                    ->readOnly()
                    ->prefix('Rp'),

                // LOGIKA KETERSEDIAAN KAMAR DINAMIS
                Forms\Components\Select::make('room_number')
                    ->label('Nomor Kamar')
                    ->options(function () {
                        // 1. Ambil daftar kamar yang sudah terisi (Status 'paid' DAN sewa belum habis)
                        $occupiedRooms = Booking::where('status', 'paid')
                            ->where('end_date', '>=', now())
                            ->pluck('room_number')
                            ->toArray();

                        // 2. Buat daftar 1-10, lalu hapus kamar yang sudah terisi
                        return collect(range(1, 10))->mapWithKeys(fn($num) => ["$num" => "Kamar $num"])
                            ->except($occupiedRooms);
                    }),

                Forms\Components\Select::make('payment')
                    ->label('Metode Pembayaran')
                    ->options([
                        'qris' => 'QRIS',
                        'transfer' => 'Transfer Bank',
                        'cash' => 'Tunai / Cash',
                    ])->required(),

                Forms\Components\FileUpload::make('payment_proof')
                    ->label('Bukti Pembayaran')
                    ->directory('bukti_pembayaran')
                    ->columnSpanFull(),

                Forms\Components\Select::make('status')
                    ->label('Status Pembayaran')
                    ->options([
                        'pending' => 'Menunggu Pembayaran',
                        'paid' => 'Lunas',
                        'cancelled' => 'Dibatalkan',
                    ])->default('pending')->required(),
            ]);
    }

    // --- KONFIGURASI TABEL (Tampilan List) ---
    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->defaultSort('created_at', 'desc') // Tampilkan data terbaru di atas
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('room_number')
                    ->label('Kamar')
                    ->sortable()
                    ->formatStateUsing(fn(string $state): string => "Kamar $state"),

                Tables\Columns\TextColumn::make('date')
                    ->label('Mulai')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_date')
                    ->label('Selesai')
                    ->date('d M Y')
                    ->sortable(),

                // KOLOM STATUS HUNI (Aktif vs Habis Masa Sewa)
                Tables\Columns\TextColumn::make('status_huni')
                    ->label('Status Huni')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        $today = now()->startOfDay();
                        $endDate = Carbon::parse($record->end_date)->startOfDay();

                        if ($record->status === 'paid') {
                            if ($endDate >= $today) {
                                return 'Aktif'; // Penghuni Aktif
                            } else {
                                return 'Habis Masa Sewa'; // Sewa Habis
                            }
                        }
                        return 'Belum Lunas'; // Menunggu Pembayaran
                    })
                    ->colors([
                        'success' => 'Aktif',
                        'danger' => 'Habis Masa Sewa',
                        'warning' => 'Belum Lunas',
                    ]),

                // Badge Status Pembayaran
                Tables\Columns\TextColumn::make('status')
                    ->label('Pembayaran')
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

                Tables\Columns\ImageColumn::make('payment_proof')
                    ->label('Bukti')
                    ->circular(),
            ])
            // --- AKSI HEADER: EXPORT EXCEL ---
            ->headerActions([
                Action::make('export_excel')
                    ->label('Export Data Kost')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->form([
                        // Modal Form untuk memilih Bulan/Tahun
                        Select::make('month')
                            ->label('Bulan Masuk')
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
                        // Memicu Download Excel menggunakan Maatwebsite
                        return Excel::download(
                            new BookingExport($data['month'], $data['year']),
                            'Data-Penghuni-Kost-' . $data['month'] . '-' . $data['year'] . '.xlsx'
                        );
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status Pembayaran')
                    ->options([
                        'pending' => 'Menunggu Pembayaran',
                        'paid' => 'Lunas'
                    ]),

                // FILTER KUSTOM: Hanya Penghuni Aktif
                Tables\Filters\Filter::make('active_tenants')
                    ->label('Penghuni Aktif Saja')
                    ->query(
                        fn(Builder $query) => $query
                            ->where('status', 'paid')
                            ->where('end_date', '>=', now())
                    )
                    ->default(),
            ])
            ->actions([
                // --- AKSI KUSTOM: PERPANJANG SEWA ---
                Tables\Actions\Action::make('perpanjang')
                    ->label('Perpanjang')
                    ->icon('heroicon-m-arrow-path')
                    ->color('info')
                    ->visible(fn(Booking $record) => $record->status === 'paid') // Hanya muncul jika sudah lunas
                    ->requiresConfirmation()
                    ->modalHeading('Perpanjang Sewa Penghuni')
                    ->modalDescription('Sistem akan membuat tagihan baru untuk bulan berikutnya. Lanjutkan?')
                    ->modalSubmitActionLabel('Ya, Buat Tagihan Baru')
                    ->action(function (Booking $record) {
                        // 1. Hitung tanggal baru (Mulai besoknya, Selesai +1 Bulan)
                        $lastEndDate = Carbon::parse($record->end_date);
                        $newStartDate = $lastEndDate->copy()->addDay();
                        $newEndDate = $newStartDate->copy()->addMonth();

                        // 2. REPLIKASI LOGIKA: Kloning data yang ada
                        $newBooking = $record->replicate();

                        // 3. Update kloningan dengan detail baru
                        $newBooking->date = $newStartDate;
                        $newBooking->end_date = $newEndDate;
                        $newBooking->status = 'pending'; // Kembalikan ke pending untuk pembayaran baru
                        $newBooking->payment_proof = null; // Hapus bukti pembayaran lama
                        $newBooking->created_at = now();
                        $newBooking->updated_at = now();

                        // 4. Simpan (Membuat record baru di Database)
                        $newBooking->save();

                        // 5. Kirim Notifikasi
                        \Filament\Notifications\Notification::make()
                            ->title('Tagihan Perpanjangan Berhasil Dibuat')
                            ->body('Masa sewa penghuni diperpanjang s/d ' . $newEndDate->format('d M Y'))
                            ->success()
                            ->send();
                    }),

                Tables\Actions\ViewAction::make()->label('Lihat'),
                Tables\Actions\EditAction::make()->label('Edit'),
                Tables\Actions\DeleteAction::make()->label('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Hapus Pilihan'),
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
