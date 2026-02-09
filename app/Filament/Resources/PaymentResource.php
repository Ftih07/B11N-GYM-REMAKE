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
    // --- NAVIGATION SETTINGS ---
    
    // Badge: Alert admin about 'pending' payments count
    public static function getNavigationBadge(): ?string
    {
        return Payment::where('status', 'pending')->count();
    }

    protected static ?string $model = Payment::class;
    protected static ?string $navigationIcon = 'heroicon-o-credit-card'; // Icon: Credit Card
    protected static ?string $navigationLabel = 'Online Membership';

    // --- FORM CONFIGURATION (View/Edit Payment) ---
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Member ID (Only visible if payment is linked to existing member)
                TextInput::make('member_id')
                    ->label('Member ID Linked')
                    ->disabled() // Read-only
                    ->visible(fn($record) => $record?->member_id !== null),

                TextInput::make('name')->required(),
                TextInput::make('email')->required(),
                TextInput::make('phone')->required(),
                
                // Payment Proof Upload
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

    // --- TABLE CONFIGURATION (List View) ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_id')->searchable(),

                // Linked Member Column
                TextColumn::make('member.name')
                    ->label('Member Linked')
                    // Logic: Show "ID" if linked, "New Register" if null
                    ->description(fn(Payment $record) => $record->member_id ? "ID: " . $record->member_id : "New Register")
                    ->color('info')
                    ->searchable(),

                TextColumn::make('name')->searchable(),
                TextColumn::make('payment'),
                TextColumn::make('membership_type'),

                // Status Badge
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'success',
                        'rejected' => 'danger',
                    }),

                TextColumn::make('created_at')->label('Date')->date(),
            ])
            
            // --- HEADER ACTION: EXCEL EXPORT ---
            ->headerActions([
                Action::make('export_excel')
                    ->label('Export Data Transaksi')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info')
                    ->form([
                        Select::make('month')
                            ->label('Bulan Transaksi')
                            ->options([
                                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                                '04' => 'April', '05' => 'Mei', '06' => 'Juni',
                                '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
                                '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
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
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                // --- CUSTOM ACTION: APPROVE & EXTEND MEMBER ---
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn(Payment $record) => $record->status === 'pending') // Only show if Pending
                    ->action(function (Payment $record) {

                        // 1. Update Payment Status
                        $record->update(['status' => 'confirmed']);

                        // 2. LOGIC: Extend Membership (If linked to a member)
                        if ($record->member_id && $record->member) {
                            $member = $record->member;

                            // Check current expiry date
                            $currentEnd = $member->membership_end_date ? Carbon::parse($member->membership_end_date) : now();

                            // Logic: If expired -> Start from TODAY. If active -> Add to LAST EXPIRY date.
                            if ($currentEnd->isPast()) {
                                $newEndDate = now()->addDays(30);
                            } else {
                                $newEndDate = $currentEnd->addDays(30);
                            }

                            // Update Member Data
                            $member->update([
                                'membership_end_date' => $newEndDate,
                                'status' => 'active'
                            ]);

                            // Send Success Notification
                            Notification::make()
                                ->title('Success')
                                ->body("Payment confirmed & Member diperpanjang sampai " . $newEndDate->format('d M Y'))
                                ->success()
                                ->send();
                        } else {
                            // If New Register / No Member Linked
                            Notification::make()
                                ->title('Success')
                                ->body("Payment confirmed (User Baru / Non-Member)")
                                ->success()
                                ->send();
                        }
                    }),

                // --- CUSTOM ACTION: REJECT ---
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