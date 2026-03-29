<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QrCodeResource\Pages;
use App\Models\QrCode;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;

class QrCodeResource extends Resource
{
    // --- PENGATURAN NAVIGASI ---
    protected static ?string $navigationGroup = 'Tools'; 
    protected static ?string $navigationLabel = 'Pembuat QR Code'; // Mengubah menu di navbar
    protected static ?string $pluralModelLabel = 'Data QR Code'; // Judul di dalam halaman
    protected static ?int $navigationSort = 8;
    protected static ?string $model = QrCode::class;
    protected static ?string $navigationIcon = 'heroicon-o-qr-code';

    // --- KONFIGURASI FORM ---
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

                        // Input URL
                        Forms\Components\TextInput::make('target_url')
                            ->label('URL Tujuan')
                            ->url() // Validasi format URL
                            ->required()
                            ->maxLength(65535)
                            ->columnSpanFull()
                            ->helperText('Masukkan URL lengkap, contoh: https://google.com'),

                        // VIEW KUSTOM: Preview QR Code
                        // Ini memuat file Blade untuk menampilkan gambar QR secara dinamis
                        Forms\Components\ViewField::make('preview_qr')
                            ->label('Pratinjau QR Code')
                            ->view('filament.forms.components.qr-preview')
                            ->hiddenOn('create') // Sembunyikan saat membuat baru (karena datanya belum ada)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    // --- KONFIGURASI TABEL ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Menampilkan Gambar QR yang Dihasilkan
                Tables\Columns\ImageColumn::make('qr_path')
                    ->label('Gambar QR')
                    ->disk('public')
                    ->square(), // Memaksa rasio aspek kotak (1:1)

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),

                Tables\Columns\TextColumn::make('target_url')
                    ->label('URL Tujuan')
                    ->limit(30)
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Edit'),
                Tables\Actions\DeleteAction::make()->label('Hapus'), // Tambah tombol hapus

                // --- AKSI KUSTOM: CETAK QR ---
                Action::make('print')
                    ->label('Cetak')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    // Membuka route/controller spesifik untuk menangani pencetakan
                    ->url(fn(QrCode $record): string => route('qr-code.print', $record))
                    ->openUrlInNewTab(), // Buka di tab baru untuk mencetak
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
            'index' => Pages\ListQrCodes::route('/'),
            'create' => Pages\CreateQrCode::route('/create'),
            'edit' => Pages\EditQrCode::route('/{record}/edit'),
        ];
    }
}
