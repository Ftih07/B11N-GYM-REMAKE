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
    // --- NAVIGATION SETTINGS ---
    protected static ?string $navigationGroup = 'Tools';
    protected static ?int $navigationSort = 8;
    protected static ?string $model = QrCode::class;
    protected static ?string $navigationIcon = 'heroicon-o-qr-code';
    protected static ?string $navigationLabel = 'QR Code Generator';

    // --- FORM CONFIGURATION ---
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

                        // URL Input
                        Forms\Components\TextInput::make('target_url')
                            ->label('Target URL')
                            ->url() // Validate URL format
                            ->required()
                            ->maxLength(65535)
                            ->columnSpanFull()
                            ->helperText('Masukkan URL lengkap, contoh: https://google.com'),

                        // CUSTOM VIEW: Preview QR Code
                        // This loads a Blade file to show the QR image dynamically
                        Forms\Components\ViewField::make('preview_qr')
                            ->view('filament.forms.components.qr-preview')
                            ->hiddenOn('create') // Hide when creating new (since no data yet)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    // --- TABLE CONFIGURATION ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Display Generated QR Image
                Tables\Columns\ImageColumn::make('qr_path')
                    ->label('QR Image')
                    ->disk('public')
                    ->square(), // Force square aspect ratio

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

                // --- CUSTOM ACTION: PRINT QR ---
                Action::make('print')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    // Open a specific route/controller to handle printing
                    ->url(fn(QrCode $record): string => route('qr-code.print', $record))
                    ->openUrlInNewTab(), // Open in new tab for printing
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
            'index' => Pages\ListQrCodes::route('/'),
            'create' => Pages\CreateQrCode::route('/create'),
            'edit' => Pages\EditQrCode::route('/{record}/edit'),
        ];
    }
}
