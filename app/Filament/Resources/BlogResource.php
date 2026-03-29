<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Models\Blog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BlogResource extends Resource
{
    // --- PENGATURAN NAVIGASI ---

    // Menampilkan total jumlah blog di sebelah menu sidebar
    public static function getNavigationBadge(): ?string
    {
        return Blog::count() ?: null;
    }

    protected static ?string $navigationGroup = 'Manajemen Website';
    protected static ?string $navigationLabel = 'Artikel / Blog';
    protected static ?string $pluralModelLabel = 'Data Artikel';
    protected static ?int $navigationSort = 5;
    protected static ?string $model = Blog::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    // --- KONFIGURASI FORM (Tambah/Edit) ---
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Judul Artikel')
                    ->required()
                    ->maxLength(255),

                // Konfigurasi Slug
                Forms\Components\TextInput::make('slug')
                    ->label('Tautan (Slug)')
                    ->unique(ignoreRecord: true) // Pastikan unik tapi abaikan record saat ini saat edit
                    ->disabled() // Cegah edit manual oleh admin
                    ->dehydrated(), // WAJIB: Memastikan nilai tersimpan ke DB meskipun disabled

                Forms\Components\Select::make('status')
                    ->label('Status Publikasi')
                    ->options([
                        'unpublish' => 'Sembunyikan (Draft)',
                        'publish' => 'Publikasikan',
                    ])
                    ->default('publish')
                    ->required(),

                Forms\Components\FileUpload::make('image')
                    ->label('Gambar Sampul')
                    ->required()
                    ->directory('blog')
                    ->columnSpanFull(),

                Forms\Components\RichEditor::make('content')
                    ->label('Isi Artikel')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    // --- KONFIGURASI TABEL (Tampilan List) ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Gambar'),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'publish' => 'success',
                        'unpublish' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'publish' => 'Dipublikasi',
                        'unpublish' => 'Draft',
                        default => $state,
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                // Filter dropdown untuk status
                Tables\Filters\SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'unpublish' => 'Sembunyikan (Draft)',
                        'publish' => 'Dipublikasi',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Edit'),
                Tables\Actions\DeleteAction::make()->label('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Hapus Pilihan'),
                ]),
            ])
            ->defaultSort('created_at', 'desc'); // Urutkan artikel terbaru
    }

    public static function getRelations(): array
    {
        return [];
    }

    // --- PENDAFTARAN ROUTE HALAMAN ---
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
        ];
    }
}
