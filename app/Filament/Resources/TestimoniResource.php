<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimoniResource\Pages;
use App\Models\Testimoni;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TestimoniResource extends Resource
{
    // --- PENGATURAN NAVIGASI ---
    protected static ?string $navigationGroup = 'Manajemen Website';
    protected static ?string $navigationLabel = 'Testimoni';
    protected static ?string $pluralModelLabel = 'Data Testimoni';
    protected static ?int $navigationSort = 5;
    protected static ?string $model = Testimoni::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left'; // Ikon: Balon Obrolan

    // --- KONFIGURASI FORM ---
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Testimoni')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Pelanggan')
                            ->required(),

                        Forms\Components\Textarea::make('description')
                            ->label('Isi Ulasan')
                            ->required()
                            ->maxLength(65535),

                        // Upload Gambar
                        Forms\Components\FileUpload::make('image')
                            ->label('Foto Profil / Bukti')
                            ->image()
                            ->required()
                            ->directory('testimoni'), // Simpan ke folder 'testimoni'

                        // Pilihan Rating (1-5 Bintang)
                        Forms\Components\Select::make('rating')
                            ->label('Penilaian (Rating)')
                            ->required()
                            ->options([
                                1 => '1 Bintang ⭐',
                                2 => '2 Bintang ⭐⭐',
                                3 => '3 Bintang ⭐⭐⭐',
                                4 => '4 Bintang ⭐⭐⭐⭐',
                                5 => '5 Bintang ⭐⭐⭐⭐⭐',
                            ])
                            ->default(5), // Default ke 5 Bintang aja biar positif vibes hehe

                        // Relasi ke Cabang Gym
                        Forms\Components\Select::make('gymkos_id')
                            ->label('Cabang (Gym/Kos)')
                            ->relationship('gymkos', 'name')
                            ->required(),
                    ])
            ]);
    }

    // --- KONFIGURASI TABEL ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')->label('Foto'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Pelanggan')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Ulasan')
                    ->limit(50),

                // Format Kustom untuk Kolom Rating
                Tables\Columns\TextColumn::make('rating')
                    ->sortable()
                    ->label('Rating')
                    ->formatStateUsing(fn($record) => match ($record->rating) {
                        1 => '1 Bintang ⭐',
                        2 => '2 Bintang ⭐⭐',
                        3 => '3 Bintang ⭐⭐⭐',
                        4 => '4 Bintang ⭐⭐⭐⭐',
                        5 => '5 Bintang ⭐⭐⭐⭐⭐',
                        default => '-',
                    }),

                Tables\Columns\TextColumn::make('gymkos.name')
                    ->label('Nama Cabang')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y'),
            ])
            ->filters([
                // 1. Filter Berdasarkan Rating (Bintang)
                Tables\Filters\SelectFilter::make('rating')
                    ->label('Filter Rating')
                    ->options([
                        5 => '⭐⭐⭐⭐⭐ (5 Bintang)',
                        4 => '⭐⭐⭐⭐ (4 Bintang)',
                        3 => '⭐⭐⭐ (3 Bintang)',
                        2 => '⭐⭐ (2 Bintang)',
                        1 => '⭐ (1 Bintang)',
                    ]),

                // 2. Filter Berdasarkan Lokasi Gym/Kos
                Tables\Filters\SelectFilter::make('gymkos_id')
                    ->relationship('gymkos', 'name')
                    ->label('Lokasi Gym/Kos')
                    ->searchable()
                    ->preload(),

                // 3. Filter Rentang Waktu Ulasan Dibuat
                Tables\Filters\Filter::make('created_at')
                    ->label('Rentang Waktu')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('created_until')->label('Sampai Tanggal'),
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data): \Illuminate\Database\Eloquent\Builder {
                        return $query
                            ->when($data['created_from'], fn($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'], fn($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->actions([
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTestimonis::route('/'),
            'create' => Pages\CreateTestimoni::route('/create'),
            'edit' => Pages\EditTestimoni::route('/{record}/edit'),
        ];
    }
}
