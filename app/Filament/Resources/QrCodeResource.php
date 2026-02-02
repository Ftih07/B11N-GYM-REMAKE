<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QrCodeResource\Pages;
use App\Models\QrCode;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
// Import untuk Action di tabel
use Filament\Tables\Actions\Action;

class QrCodeResource extends Resource
{
    protected static ?string $model = QrCode::class;

    protected static ?string $navigationIcon = 'heroicon-o-qr-code';
    protected static ?string $navigationLabel = 'QR Code Generator';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi QR Code')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama QR Code')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('target_url')
                            ->label('Target URL')
                            ->url()
                            ->required()
                            ->maxLength(65535)
                            ->columnSpanFull()
                            ->helperText('Masukkan URL lengkap, contoh: https://google.com'),

                        // Kita tampilkan preview gambar jika sedang mode Edit dan gambarnya ada
                        Forms\Components\ViewField::make('preview_qr')
                            ->view('filament.forms.components.qr-preview')
                            ->hiddenOn('create') // Sembunyikan saat membuat baru
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Menampilkan Thumbnail Gambar di tabel
                Tables\Columns\ImageColumn::make('qr_path')
                    ->label('QR Image')
                    ->disk('public')
                    ->square(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('target_url')
                    ->limit(30)
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // --- TOMBOL PRINT KHUSUS ---
                Action::make('print')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    // Ini akan membuka route khusus di tab baru
                    ->url(fn(QrCode $record): string => route('qr-code.print', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    // ... sisa file relations dan pages biarkan default
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQrCodes::route('/'),
            'create' => Pages\CreateQrCode::route('/create'),
            'edit' => Pages\EditQrCode::route('/{record}/edit'),
        ];
    }
}
