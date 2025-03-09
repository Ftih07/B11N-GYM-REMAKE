<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Filament\Resources\BookingResource\RelationManagers;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookingResource extends Resource
{
    public static function getNavigationBadge(): ?string
    {
        return Booking::count(); // Menampilkan jumlah total data booking
    }

    protected static ?string $model = Booking::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('email')->email()->required(),
                Forms\Components\TextInput::make('phone')->required(),
                Forms\Components\DatePicker::make('date')->required(),
                Forms\Components\Select::make('room_type')
                    ->options([
                        '750rb - AC' => '750rb - AC',
                        '500rb - Non AC' => '500rb - Non AC'
                    ])
                    ->required(),
                Forms\Components\Select::make('room_number')
                    ->options(function () {
                        $bookedRooms = Booking::where('status', 'paid')->pluck('room_number')->toArray();
                        return collect(range(1, 10))->mapWithKeys(fn($num) => ["$num" => "Room $num"])
                            ->except($bookedRooms);
                    })->required(),
                Forms\Components\Select::make('payment')->options([
                    'qris' => 'QRIS',
                    'transfer' => 'Transfer Bank',
                ])->required(),
                Forms\Components\FileUpload::make('payment_proof')->directory('bukti_pembayaran')->required(),
                Forms\Components\Select::make('status')->options([
                    'pending' => 'Pending',
                    'paid' => 'Paid',
                    'cancelled' => 'Cancelled',
                ])->default('pending')->required(),
            ]);
    }
 
    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')->sortable(),
            Tables\Columns\TextColumn::make('name')->sortable(),
            Tables\Columns\TextColumn::make('email')->sortable(),
            Tables\Columns\TextColumn::make('phone'),
            Tables\Columns\TextColumn::make('date')->sortable(),
            Tables\Columns\TextColumn::make('status')->sortable(),
            Tables\Columns\ImageColumn::make('payment_proof')->label('Bukti Pembayaran'),
        ])->filters([
            Tables\Filters\SelectFilter::make('status')->options(['pending' => 'Pending', 'paid' => 'Paid']),
        ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(), // Adds bulk delete action
                ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(), // Adds view action
                Tables\Actions\DeleteAction::make(), // Adds delete action
                Tables\Actions\EditAction::make(), // Adds edit action
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
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
