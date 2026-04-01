<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Carbon\Carbon;
use App\Exports\PaymentExport;
use Maatwebsite\Excel\Facades\Excel;

class PaymentResource extends Resource
{
    // --- PENGATURAN NAVIGASI ---

    // Badge: Memberi alert ke admin tentang jumlah pembayaran 'pending'
    public static function getNavigationBadge(): ?string
    {
        return Payment::where('status', 'pending')->count() ?: null;
    }

    protected static ?string $model = Payment::class;
    protected static ?string $navigationIcon = 'heroicon-o-credit-card'; // Ikon: Kartu Kredit
    protected static ?string $navigationLabel = 'Membership Online'; // Label di Sidebar
    protected static ?string $pluralModelLabel = 'Data Membership Online';

    // --- KONFIGURASI FORM (Lihat/Edit Pembayaran) ---
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // ID Member (Hanya muncul jika pembayaran tertaut ke member yang sudah ada)
                TextInput::make('member_id')
                    ->label('ID Member Tertaut')
                    ->disabled() // Hanya bisa dibaca (Read-only)
                    ->visible(fn($record) => $record?->member_id !== null),

                TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required(),
                TextInput::make('email')
                    ->label('Alamat Email')
                    ->required(),
                TextInput::make('phone')
                    ->label('Nomor HP / WhatsApp')
                    ->required(),

                // Upload Bukti Pembayaran
                FileUpload::make('image')
                    ->label('Bukti Pembayaran')
                    ->image()
                    ->directory('payment_receipts')
                    ->visibility('public')
                    ->required(),

                Select::make('membership_type')
                    ->label('Tipe Membership')
                    ->options([
                        'Member Harian' => 'Member Harian',
                        'Member Mingguan' => 'Member Mingguan',
                        'Member Bulanan' => 'Member Bulanan',
                    ])->required(),

                Select::make('status')
                    ->label('Status Pembayaran')
                    ->options([
                        'pending' => 'Menunggu Pembayaran',
                        'confirmed' => 'Dikonfirmasi',
                        'rejected' => 'Ditolak',
                    ])->required(),

                Select::make('payment')
                    ->label('Metode Pembayaran')
                    ->options([
                        'qris' => 'QRIS',
                        'transfer' => 'Transfer Bank',
                    ])->required(),
            ]);
    }

    // --- KONFIGURASI TABEL (Tampilan List) ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_id')
                    ->label('ID Pesanan')
                    ->searchable()
                    ->copyable(),

                // Kolom Member Tertaut
                TextColumn::make('member.name')
                    ->label('Member Tertaut')
                    // Logika: Tampilkan "ID" jika tertaut, "Pendaftar Baru" jika null
                    ->description(fn(Payment $record) => $record->member_id ? "ID: " . $record->member_id : "Pendaftar Baru")
                    ->color('info')
                    ->searchable(),

                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('payment')
                    ->label('Metode Pembayaran')
                    ->searchable(),
                TextColumn::make('membership_type')
                    ->label('Tipe Membership')
                    ->searchable(),

                // Badge Status
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->searchable()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'confirmed' => 'Dikonfirmasi',
                        'rejected' => 'Ditolak',
                        default => $state,
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->date('d M Y'),
            ])

            // --- AKSI HEADER: EXPORT EXCEL ---
            ->headerActions([
                Action::make('export_excel')
                    ->label('Export Data Membership Online')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info')
                    ->form([
                        Select::make('month')
                            ->label('Bulan Transaksi')
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
                            new PaymentExport($data['month'], $data['year']),
                            'Rekap-Online-Membership-' . $data['month'] . '-' . $data['year'] . '.xlsx'
                        );
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'pending' => 'Menunggu Pembayaran',
                        'confirmed' => 'Dikonfirmasi',
                        'rejected' => 'Ditolak',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Edit'),

                // --- AKSI KUSTOM: SETUJUI & PERPANJANG MEMBER ---
                Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Pembayaran')
                    // UBAH BAGIAN INI: Menggunakan Closure (fn) untuk mengecek apakah punya member_id
                    ->modalDescription(
                        fn(Payment $record) =>
                        $record->member_id
                            ? 'Apakah Anda yakin ingin menyetujui pembayaran ini? Masa aktif member akan otomatis ditambahkan.'
                            : 'Apakah Anda yakin ingin menyetujui pembayaran pendaftar baru ini?'
                    )
                    ->modalSubmitActionLabel('Ya, Setujui')
                    ->visible(fn(Payment $record) => $record->status === 'pending') // Hanya muncul jika masih Pending
                    ->action(function (Payment $record) {

                        // 1. Update Status Pembayaran
                        $record->update(['status' => 'confirmed']);

                        // 2. LOGIKA: Perpanjang Membership (Jika tertaut ke member)
                        if ($record->member_id && $record->member) {
                            $member = $record->member;

                            // Cek tanggal expired saat ini
                            $currentEnd = $member->membership_end_date ? Carbon::parse($member->membership_end_date) : now();

                            // Logika: Jika sudah expired -> Mulai dari HARI INI. Jika masih aktif -> Tambahkan ke tanggal EXPIRED TERAKHIR.
                            if ($currentEnd->isPast()) {
                                $newEndDate = now()->addDays(30);
                            } else {
                                $newEndDate = $currentEnd->addDays(30);
                            }

                            // Update Data Member
                            $member->update([
                                'membership_end_date' => $newEndDate,
                                'status' => 'active'
                            ]);

                            // Kirim Notifikasi Sukses
                            Notification::make()
                                ->title('Berhasil')
                                ->body("Pembayaran dikonfirmasi & Member diperpanjang sampai " . $newEndDate->format('d M Y'))
                                ->success()
                                ->send();
                        } else {
                            // Jika Pendaftar Baru / Tidak Tertaut Member
                            Notification::make()
                                ->title('Berhasil')
                                ->body("Pembayaran dikonfirmasi (Pendaftar Baru / Non-Member)")
                                ->success()
                                ->send();
                        }
                    }),

                // --- AKSI KUSTOM: TOLAK ---
                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Pembayaran')
                    ->modalDescription('Apakah Anda yakin ingin menolak pembayaran ini?')
                    ->modalSubmitActionLabel('Ya, Tolak')
                    ->visible(fn(Payment $record) => $record->status === 'pending')
                    ->action(function (Payment $record) {
                        $record->update(['status' => 'rejected']);

                        Notification::make()
                            ->title('Ditolak')
                            ->body('Pembayaran telah berhasil ditolak.')
                            ->danger()
                            ->send();
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
