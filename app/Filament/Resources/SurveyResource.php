<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SurveyResource\Pages;
use App\Models\Survey;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use App\Exports\SurveyExport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\Action;
use Carbon\Carbon;

class SurveyResource extends Resource
{
    public static function getNavigationBadge(): ?string
    {
        return Survey::count();
    }

    protected static ?string $model = Survey::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Data Survey';
    protected static ?string $navigationGroup = 'Gym Management';
    protected static ?int $navigationSort = 3;

    // 1. Agar tombol "New Survey" hilang (karena data dari public form)
    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identitas Responden')
                    ->schema([
                        Forms\Components\TextInput::make('name')->label('Nama'),
                        Forms\Components\TextInput::make('phone')->label('WhatsApp'),
                        Forms\Components\Toggle::make('is_membership')
                            ->label('Member Aktif?')
                            ->inline(false),
                    ])->columns(3),

                Forms\Components\Section::make('Detail Keanggotaan')
                    ->schema([
                        Forms\Components\TextInput::make('member_duration')->label('Lama Member'),
                        Forms\Components\TextInput::make('renewal_chance')->label('Peluang Perpanjang (1-5)'),
                    ])
                    ->visible(fn($record) => $record?->is_membership ?? false)
                    ->columns(2),

                Forms\Components\Section::make('Hasil Survey')
                    ->schema([
                        Forms\Components\TextInput::make('fitness_goal')->label('Tujuan'),
                        Forms\Components\TextInput::make('rating_equipment')->label('Rating Alat'),
                        Forms\Components\TextInput::make('rating_cleanliness')->label('Kebersihan'),

                        // Perbaikan: Hapus ->colors() agar tidak error
                        Forms\Components\TextInput::make('nps_score')
                            ->label('NPS (1-10)')
                            ->numeric(),

                        Forms\Components\Textarea::make('feedback')->columnSpanFull(),
                    ])->columns(4),

                Forms\Components\Section::make('Marketing')
                    ->schema([
                        Forms\Components\TextInput::make('promo_interest')
                            ->label('Minat Promo')
                            ->formatStateUsing(fn(string $state): string => match ($state) {
                                'paket_a' => 'Paket A (6+2)',
                                'paket_b' => 'Paket B (9+3)',
                                'paket_c' => 'Paket C (12+4)',
                                default => 'Tidak Tertarik',
                            }),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y H:i') // Tambah jam biar detail
                    ->label('Waktu')
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->description(fn(Survey $record): string => $record->phone),

                Tables\Columns\IconColumn::make('is_membership')
                    ->label('Member?')
                    ->boolean(),

                Tables\Columns\TextColumn::make('promo_interest')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'paket_c' => 'success',
                        'paket_a', 'paket_b' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('nps_score')
                    ->label('NPS')
                    ->numeric()
                    ->sortable(),
            ])
            ->headerActions([
                Action::make('export_excel')
                    ->label('Export Data Survey')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('primary') // Warna Ungu biasanya ikut Primary atau bisa set custom hex di theme
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
                            new SurveyExport($data['month'], $data['year']),
                            'Hasil-Survey-Gym-' . $data['month'] . '-' . $data['year'] . '.xlsx'
                        );
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('promo_interest')
                    ->options([
                        'paket_a' => 'Paket A',
                        'paket_b' => 'Paket B',
                        'paket_c' => 'Paket C',
                    ])->label('Filter Minat Promo'),

                Tables\Filters\Filter::make('is_membership')
                    ->query(fn($query) => $query->where('is_membership', true))
                    ->label('Hanya Member'),
            ])
            ->actions([
                // 2. Ubah EditAction jadi ViewAction (Modal Popup)
                Tables\Actions\ViewAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSurveys::route('/'),
            // 3. Hapus baris 'create' dan 'edit' di sini
        ];
    }
}
