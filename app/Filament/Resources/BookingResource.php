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
    // --- NAVIGATION SETTINGS ---

    // Notification Badge: Shows count of 'pending' bookings in sidebar
    public static function getNavigationBadge(): ?string
    {
        return Booking::where('status', 'pending')->count() ?: null;
    }

    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-users'; // Icon for User Management
    protected static ?string $navigationLabel = 'Manajemen Penghuni'; // Sidebar Label

    // Rename standard "New Booking" button to "Penghuni Kost"
    public static function getModelLabel(): string
    {
        return 'Penghuni Kost';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Data Penghuni';
    }

    // --- FORM CONFIGURATION (Create/Edit) ---
    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('email')->email()->required(),
                Forms\Components\TextInput::make('phone')->required(),

                // AUTO-CALCULATE DATE LOGIC
                Forms\Components\DatePicker::make('date')
                    ->label('Tanggal Mulai')
                    ->required()
                    ->live() // Watch for changes
                    ->afterStateUpdated(function ($state, Set $set) {
                        if ($state) {
                            // Automatically set 'end_date' to 1 month after 'start_date'
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

                // DYNAMIC ROOM AVAILABILITY LOGIC
                Forms\Components\Select::make('room_number')
                    ->options(function () {
                        // 1. Get list of occupied rooms (Status 'paid' AND lease not expired)
                        $occupiedRooms = Booking::where('status', 'paid')
                            ->where('end_date', '>=', now())
                            ->pluck('room_number')
                            ->toArray();

                        // 2. Generate list 1-10, then remove occupied rooms
                        return collect(range(1, 10))->mapWithKeys(fn($num) => ["$num" => "Room $num"])
                            ->except($occupiedRooms);
                    })->required(),

                Forms\Components\Select::make('payment')->options([
                    'qris' => 'QRIS',
                    'transfer' => 'Transfer Bank',
                    'cash' => 'Cash / Tunai',
                ])->required(),

                Forms\Components\FileUpload::make('payment_proof')
                    ->directory('bukti_pembayaran')
                    ->columnSpanFull(),

                Forms\Components\Select::make('status')->options([
                    'pending' => 'Pending',
                    'paid' => 'Paid',
                    'cancelled' => 'Cancelled',
                ])->default('pending')->required(),
            ]);
    }

    // --- TABLE CONFIGURATION (List View) ---
    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->defaultSort('created_at', 'desc') // Show newest bookings first
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('room_number')
                    ->label('Kamar')
                    ->sortable(),

                Tables\Columns\TextColumn::make('date')
                    ->label('Mulai')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_date')
                    ->label('Selesai')
                    ->date('d M Y')
                    ->sortable(),

                // COMPUTED STATUS COLUMN (Active vs Expired)
                Tables\Columns\TextColumn::make('status_huni')
                    ->label('Status Huni')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        $today = now()->startOfDay();
                        $endDate = Carbon::parse($record->end_date)->startOfDay();

                        if ($record->status === 'paid') {
                            if ($endDate >= $today) {
                                return 'Aktif'; // Active Tenant
                            } else {
                                return 'Habis Masa Sewa'; // Expired Lease
                            }
                        }
                        return 'Belum Lunas'; // Payment Pending
                    })
                    ->colors([
                        'success' => 'Aktif',
                        'danger' => 'Habis Masa Sewa',
                        'warning' => 'Belum Lunas',
                    ]),

                // Payment Status Badge
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
            // --- HEADER ACTION: EXCEL EXPORT ---
            ->headerActions([
                Action::make('export_excel')
                    ->label('Export Data Kost')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->form([
                        // Modal Form for selecting Month/Year
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
                        // Trigger Excel Download using Maatwebsite
                        return Excel::download(
                            new BookingExport($data['month'], $data['year']),
                            'Data-Penghuni-Kost-' . $data['month'] . '-' . $data['year'] . '.xlsx'
                        );
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(['pending' => 'Pending', 'paid' => 'Paid']),

                // CUSTOM FILTER: Active Tenants Only (Default ON)
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
                // --- CUSTOM ACTION: EXTEND LEASE (PERPANJANG) ---
                Tables\Actions\Action::make('perpanjang')
                    ->label('Perpanjang')
                    ->icon('heroicon-m-arrow-path')
                    ->color('info')
                    ->visible(fn(Booking $record) => $record->status === 'paid') // Only visible if paid
                    ->requiresConfirmation()
                    ->modalHeading('Perpanjang Sewa Penghuni')
                    ->modalDescription('System will create a NEW INVOICE for the next month.')
                    ->modalSubmitActionLabel('Ya, Buat Tagihan Baru')
                    ->action(function (Booking $record) {
                        // 1. Calculate new dates (Start tomorrow, End +1 Month)
                        $lastEndDate = Carbon::parse($record->end_date);
                        $newStartDate = $lastEndDate->copy()->addDay();
                        $newEndDate = $newStartDate->copy()->addMonth();

                        // 2. REPLICATE LOGIC: Clone existing data
                        $newBooking = $record->replicate();

                        // 3. Update clone with new details
                        $newBooking->date = $newStartDate;
                        $newBooking->end_date = $newEndDate;
                        $newBooking->status = 'pending'; // Reset to pending for payment
                        $newBooking->payment_proof = null; // Clear old proof
                        $newBooking->created_at = now();
                        $newBooking->updated_at = now();

                        // 4. Save (Creates new record in DB)
                        $newBooking->save();

                        // 5. Send Notification
                        \Filament\Notifications\Notification::make()
                            ->title('Tagihan Perpanjangan Dibuat')
                            ->body('Penghuni diperpanjang s/d ' . $newEndDate->format('d M Y'))
                            ->success()
                            ->send();
                    }),

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
