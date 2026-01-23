<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FinanceResource\Pages;
use App\Filament\Resources\FinanceResource\RelationManagers;
use App\Models\Finance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FinanceResource extends Resource
{
    protected static ?string $model = Finance::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Rekap Keuangan';
    protected static ?string $pluralModelLabel = 'Laporan Keuangan';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Input Keuangan')
                    ->schema([
                        // Pilih Tipe: Pemasukan / Pengeluaran
                        Forms\Components\Select::make('type')
                            ->options([
                                'income' => 'Pemasukan (Income)',
                                'expense' => 'Pengeluaran (Expense)',
                            ])
                            ->required()
                            ->default('expense'), // Default ke expense karena income biasanya otomatis dari POS

                        // Pilih Gym (Penting untuk tahu pengeluaran cabang mana)
                        Forms\Components\Select::make('gymkos_id')
                            ->relationship('gymkos', 'name')
                            ->required()
                            ->label('Cabang Gym'),

                        Forms\Components\DatePicker::make('date')
                            ->required()
                            ->default(now())
                            ->label('Tanggal'),

                        Forms\Components\TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->label('Nominal'),

                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->maxLength(255)
                            ->label('Keterangan')
                            ->placeholder('Contoh: Bayar Listrik Bulan Juni'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->date('d M Y')
                    ->sortable()
                    ->label('Tanggal'),

                Tables\Columns\TextColumn::make('gymkos.name')
                    ->label('Cabang')
                    ->searchable(),

                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->limit(30),

                // Badge Tipe
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'income' => 'success', // Hijau
                        'expense' => 'danger', // Merah
                    })
                    ->formatStateUsing(fn(string $state): string => ucfirst($state)),

                // Nominal
                Tables\Columns\TextColumn::make('amount')
                    ->money('IDR')
                    ->sortable()
                    ->color(fn($record) => $record->type === 'expense' ? 'danger' : 'success'),
            ])
            ->defaultSort('date', 'desc')
            ->groups([
                Tables\Grouping\Group::make('gymkos.name')
                    ->label('Cabang')
                    ->collapsible(), // Biar bisa dibuka tutup per cabang
            ])
            ->defaultGroup('gymkos.name') // Default langsung terkelompok
            ->filters([
                // Filter biar gampang cari Income doang / Expense doang
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'income' => 'Income',
                        'expense' => 'Expense',
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
            'index' => Pages\ListFinances::route('/'),
            'create' => Pages\CreateFinance::route('/create'),
            'edit' => Pages\EditFinance::route('/{record}/edit'),
        ];
    }
}
