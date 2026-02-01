<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\Action; // Import Action
use Filament\Notifications\Notification; // Import Notifikasi
use Carbon\Carbon; // Import Carbon untuk tanggal
use App\Exports\PaymentExport;
use Maatwebsite\Excel\Facades\Excel;

class PaymentResource extends Resource
{
    public static function getNavigationBadge(): ?string
    {
        return Payment::where('status', 'pending')->count(); // Badge hitung yg pending aja biar admin aware
    }

    protected static ?string $model = Payment::class;
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'Online Membership';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Menampilkan Member ID (Readonly) jika ada
                TextInput::make('member_id')
                    ->label('Member ID Linked')
                    ->disabled()
                    ->visible(fn($record) => $record?->member_id !== null),

                TextInput::make('name')->required(),
                TextInput::make('email')->required(),
                TextInput::make('phone')->required(),
                FileUpload::make('image')
                    ->image()
                    ->directory('payment_receipts')
                    ->visibility('public')
                    ->required(),

                Select::make('membership_type')
                    ->options([
                        'Member Harian' => 'Member Harian',
                        'Member Mingguan' => 'Member Mingguan',
                        'Member Bulanan' => 'Member Bulanan',
                    ])->required(),

                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'rejected' => 'Rejected',
                    ])->required(),

                Select::make('payment')->options([
                    'qris' => 'QRIS',
                    'transfer' => 'Transfer Bank',
                ])->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_id')->searchable(),

                // 1. Field Member Linked (Menampilkan Nama Member jika ada relasi)
                TextColumn::make('member.name')
                    ->label('Member Linked')
                    ->description(fn(Payment $record) => $record->member_id ? "ID: " . $record->member_id : "New Register")
                    ->color('info')
                    ->searchable(),

                TextColumn::make('name')->searchable(),
                TextColumn::make('payment'),
                TextColumn::make('membership_type'),

                // Status dengan warna
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'success',
                        'rejected' => 'danger',
                    }),

                TextColumn::make('created_at')->label('Date')->date(),
            ])
            ->headerActions([
                Action::make('export_excel')
                    ->label('Export Data Transaksi')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info') // Tombol warna Biru
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
                // Filter status
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->actions([
                // Tombol Edit Bawaan
                Tables\Actions\EditAction::make(),

                // 2. Button APPROVE (Logic Perpanjang Member)
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation() // Minta konfirmasi dulu biar ga kepencet
                    ->visible(fn(Payment $record) => $record->status === 'pending') // Hanya muncul jika status pending
                    ->action(function (Payment $record) {

                        // A. Update Status Payment jadi Confirmed
                        $record->update(['status' => 'confirmed']);

                        // B. Logic Perpanjang Member (Jika ada member_id)
                        if ($record->member_id && $record->member) {
                            $member = $record->member;

                            // Cek tanggal expire sekarang
                            $currentEnd = $member->membership_end_date ? Carbon::parse($member->membership_end_date) : now();

                            // Jika sudah kadaluarsa, mulai dari HARI INI. Jika belum, tambah dari tanggal expire lama.
                            if ($currentEnd->isPast()) {
                                $newEndDate = now()->addDays(30);
                            } else {
                                $newEndDate = $currentEnd->addDays(30);
                            }

                            // Update Tabel Member
                            $member->update([
                                'membership_end_date' => $newEndDate,
                                'status' => 'active' // Pastikan status jadi active
                            ]);

                            Notification::make()
                                ->title('Success')
                                ->body("Payment confirmed & Member diperpanjang sampai " . $newEndDate->format('d M Y'))
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Success')
                                ->body("Payment confirmed (User Baru / Non-Member)")
                                ->success()
                                ->send();
                        }
                    }),

                // 3. Button REJECT
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn(Payment $record) => $record->status === 'pending')
                    ->action(function (Payment $record) {
                        $record->update(['status' => 'rejected']);

                        Notification::make()
                            ->title('Rejected')
                            ->body('Payment has been rejected.')
                            ->danger()
                            ->send();
                    }),
            ])
            ->defaultSort('created_at', 'desc'); // Biar yang terbaru di atas
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
