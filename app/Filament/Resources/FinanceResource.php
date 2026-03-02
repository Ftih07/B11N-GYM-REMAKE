<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FinanceResource\Pages;
use App\Models\Finance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Exports\FinanceExport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\Action;
use Carbon\Carbon;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FinanceResource extends Resource
{
    // --- NAVIGATION SETTINGS ---
    protected static ?string $model = Finance::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes'; // Icon: Banknotes
    protected static ?string $navigationLabel = 'Rekap Keuangan';
    protected static ?string $pluralModelLabel = 'Laporan Keuangan';
    protected static ?string $navigationGroup = 'Laporan'; // Grouped under "Laporan"
    protected static ?int $navigationSort = 1;

    // --- FORM CONFIGURATION (Input Income/Expense) ---
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Input Keuangan')
                    ->schema([
                        // Type Selection: Income vs Expense
                        Forms\Components\Select::make('type')
                            ->options([
                                'income' => 'Pemasukan (Income)',
                                'expense' => 'Pengeluaran (Expense)',
                            ])
                            ->required()
                            ->default('expense'), // Default to expense

                        // Relationship: Link finance record to a specific Gym Branch
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
                            ->prefix('Rp') // Visual prefix
                            ->label('Nominal'),

                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->maxLength(255)
                            ->label('Keterangan')
                            ->placeholder('Contoh: Bayar Listrik Bulan Juni'),
                    ])
            ]);
    }

    // --- TABLE CONFIGURATION (Report View) ---
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

                // Type Badge: Green for Income, Red for Expense
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'income' => 'success', // Green
                        'expense' => 'danger', // Red
                    })
                    ->formatStateUsing(fn(string $state): string => ucfirst($state)),

                // Amount Column: Formatted as IDR currency
                Tables\Columns\TextColumn::make('amount')
                    ->money('IDR')
                    ->sortable()
                    ->color(fn($record) => $record->type === 'expense' ? 'danger' : 'success'),
            ])
            ->defaultSort('date', 'desc') // Sort by newest date

            // --- HEADER ACTION: EXCEL EXPORT ---
            ->headerActions([
                Action::make('export_excel')
                    ->label('Export Laporan')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->form([
                        // Modal Form: Select Month & Year
                        Select::make('month')
                            ->label('Bulan')
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
                                $years = range(Carbon::now()->year - 5, Carbon::now()->year + 1);
                                return array_combine($years, $years);
                            })
                            ->default(now()->year)
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        // Trigger download using 'FinanceExport' class
                        return Excel::download(
                            new FinanceExport($data['month'], $data['year']),
                            'Laporan-Keuangan-' . $data['month'] . '-' . $data['year'] . '.xlsx'
                        );
                    }),
            ])

            // --- GROUPING CONFIGURATION ---
            ->groups([
                // Group rows by Gym Name (Collapsible)
                Tables\Grouping\Group::make('gymkos.name')
                    ->label('Cabang')
                    ->collapsible(),
            ])
            ->defaultGroup('gymkos.name') // Enable grouping by default

            ->filters([
                // Filter dropdown: Show only Income or Expense
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'income' => 'Income',
                        'expense' => 'Expense',
                    ]),

                // --- TAMBAHAN: Filter Bulan dan Tahun ---
                Filter::make('bulan_tahun')
                    ->form([
                        Select::make('month')
                            ->label('Bulan')
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
                            ->placeholder('Semua Bulan'),
                        Select::make('year')
                            ->label('Tahun')
                            ->options(function () {
                                $years = range(Carbon::now()->year - 5, Carbon::now()->year + 1);
                                return array_combine($years, $years);
                            })
                            ->placeholder('Semua Tahun'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['month'] ?? null, // <-- Tambahkan "?? null" di sini
                                fn(Builder $query, $month): Builder => $query->whereMonth('date', $month),
                            )
                            ->when(
                                $data['year'] ?? null, // <-- Tambahkan "?? null" di sini juga
                                fn(Builder $query, $year): Builder => $query->whereYear('date', $year),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['month'] ?? null) {
                            $monthName = Carbon::createFromFormat('m', $data['month'])->translatedFormat('F');
                            $indicators[] = 'Bulan: ' . $monthName;
                        }
                        if ($data['year'] ?? null) {
                            $indicators[] = 'Tahun: ' . $data['year'];
                        }
                        return $indicators;
                    }),
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
