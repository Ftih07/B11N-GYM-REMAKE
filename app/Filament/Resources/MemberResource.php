<?php

namespace App\Filament\Resources;

use App\Exports\MemberExport;
use App\Filament\Resources\MemberResource\Pages;
use App\Models\Member;
use Filament\Forms;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Facades\Excel;

class MemberResource extends Resource
{
    public static function getNavigationBadge(): ?string
    {
        return Member::count();
    }

    protected static ?string $model = Member::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Membership & Absensi';

    protected static ?string $navigationLabel = 'Manajemen Membership';

    protected static ?string $pluralModelLabel = 'Data Membership';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Member')
                    ->columns(2)
                    ->schema([
                        // 1. CABANG (GYM/KOS)
                        Forms\Components\Select::make('gymkos_id')
                            ->relationship('gymkos', 'name', fn (Builder $query) => $query->whereIn('id', [1, 2]))
                            ->label('Cabang Gym')
                            ->required()
                            ->live()
                            ->disabled(fn (string $operation): bool => $operation === 'edit')
                            ->dehydrated()
                            ->afterStateUpdated(function ($state, Forms\Set $set, string $operation) {
                                if ($operation === 'create' && $state) {
                                    $gymkosId = (int) $state;
                                    $prefix = ($gymkosId === 1) ? 'B-' : 'K-';

                                    $lastMember = Member::where('gymkos_id', $gymkosId)
                                        ->where('member_code', 'like', "{$prefix}%")
                                        ->orderByRaw('CAST(SUBSTRING(member_code, 3) AS UNSIGNED) DESC')
                                        ->first();

                                    $nextNumber = 1;
                                    if ($lastMember && ! empty($lastMember->member_code)) {
                                        $lastNumber = (int) substr($lastMember->member_code, 2);
                                        $nextNumber = $lastNumber + 1;
                                    }

                                    $set('member_code', $prefix.str_pad($nextNumber, 5, '0', STR_PAD_LEFT));
                                }
                            }),

                        // 2. ID MEMBER
                        Forms\Components\TextInput::make('member_code')
                            ->label('ID Member')
                            ->placeholder('Pilih cabang untuk generate otomatis')
                            ->maxLength(50)
                            ->readOnly() // Dibuat read-only agar urutan selalu konsisten
                            ->dehydrated()
                            ->helperText('Dibuat otomatis oleh sistem berdasarkan cabang.'),

                        // 3. NAMA LENGKAP
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),

                        // 4. ALAMAT EMAIL
                        Forms\Components\TextInput::make('email')
                            ->label('Alamat Email')
                            ->helperText('Opsional')
                            ->email(),

                        // 5. NOMOR HP / WA
                        Forms\Components\TextInput::make('phone')
                            ->label('Nomor HP / WhatsApp')
                            ->helperText('Opsional')
                            ->tel(),

                        // 6. ALAMAT
                        Forms\Components\Textarea::make('address')
                            ->label('Alamat Lengkap')
                            ->rows(2)
                            ->helperText('Opsional')
                            ->columnSpanFull(),

                        // 7. TGL BERGABUNG
                        Forms\Components\DatePicker::make('join_date')
                            ->label('Tanggal Bergabung')
                            ->default(now())
                            ->required(),

                        // 8. MASA BERLAKU
                        Forms\Components\DatePicker::make('membership_end_date')
                            ->label('Berlaku Sampai')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if ($state) {
                                    $isActive = \Carbon\Carbon::parse($state)->endOfDay()->isFuture() || \Carbon\Carbon::parse($state)->isToday();
                                    $set('status', $isActive ? 'active' : 'inactive');
                                }
                            }),

                        // 9. STATUS
                        Forms\Components\Select::make('status')
                            ->label('Status Membership')
                            ->options([
                                'active' => 'Aktif',
                                'inactive' => 'Tidak Aktif',
                            ])
                            ->default('active')
                            ->required()
                            ->dehydrated(),

                        // 10. WEBCAM & FACE DESCRIPTOR
                        ViewField::make('picture')
                            ->view('filament.forms.components.webcam-input')
                            ->viewData([
                                'descriptorField' => 'face_descriptor',
                            ])
                            ->label('Foto Wajah')
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('face_descriptor')
                            ->label('Deskriptor Wajah (Dibuat Otomatis)')
                            ->rows(3)
                            ->readOnly()
                            ->helperText('Otomatis terisi saat wajah terdeteksi di kamera.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    // --- KONFIGURASI TABEL (List View) ---
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('updated_at', 'desc')
            ->columns([
                // 1. ID MEMBER
                Tables\Columns\TextColumn::make('member_code')
                    ->label('ID Member')
                    ->badge()
                    ->color('gray')
                    ->placeholder('-')
                    ->sortable(),

                // 2. FOTO
                Tables\Columns\ImageColumn::make('picture')
                    ->label('Foto')
                    ->circular()
                    ->defaultImageUrl('https://ui-avatars.com/api/?name=Member&background=0D8ABC&color=fff'),

                // 3. NAMA MEMBER (KITA PASANG SEARCH MULTI-KATA DI SINI)
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Member')
                    ->sortable()
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        // Pecah kata kunci berdasarkan spasi (misal: "rizki 6559" / "B-02243 rizki")
                        $words = array_filter(explode(' ', trim($search)));

                        return $query->where(function (Builder $subQuery) use ($words) {
                            foreach ($words as $word) {
                                $subQuery->where(function (Builder $wordQuery) use ($word) {
                                    $wordQuery->where('member_code', 'like', "%{$word}%")
                                        ->orWhere('name', 'like', "%{$word}%")
                                        ->orWhere('phone', 'like', "%{$word}%");
                                });
                            }
                        });
                    }),

                // 4. NO. HP / TELP
                Tables\Columns\TextColumn::make('phone')
                    ->label('No. HP / WA')
                    ->placeholder('-')
                    ->copyable()
                    ->copyMessage('Nomor HP berhasil disalin'),

                // 5. TANGGAL JOIN
                Tables\Columns\TextColumn::make('join_date')
                    ->label('Tgl Join')
                    ->date('d M Y')
                    ->sortable(),

                // 6. END DATE / MASA BERLAKU
                Tables\Columns\TextColumn::make('membership_end_date')
                    ->label('End Date')
                    ->date('d M Y')
                    ->description(fn (Member $record) => $record->membership_end_date?->diffForHumans())
                    ->sortable(),

                // 7. STATUS
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        default => 'secondary'
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'active' => 'Aktif',
                        'inactive' => 'Expired',
                        default => $state
                    }),

                // 8. LOKASI CABANG
                Tables\Columns\TextColumn::make('gymkos.name')
                    ->label('Lokasi Cabang')
                    ->badge()
                    ->color('primary')
                    ->sortable(),
            ])

            // --- HEADER ACTIONS (Export Excel) ---
            ->headerActions([
                Action::make('export_excel')
                    ->label('Export Data Member')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info')
                    ->form([
                        Select::make('gymkos_id')
                            ->label('Cabang Gym')
                            ->options(\App\Models\Gymkos::whereIn('id', [1, 2])->pluck('name', 'id'))
                            ->placeholder('Semua Cabang (B11N & K1NG)')
                            ->default(null),

                        Radio::make('mode')
                            ->label('Pilih Tipe Export')
                            ->options([
                                'all' => 'Semua Data Member (Keseluruhan)',
                                'period' => 'Filter Berdasarkan Bulan Bergabung',
                            ])
                            ->default('all')
                            ->live(),

                        Select::make('month')
                            ->label('Bulan Bergabung')
                            ->options([
                                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                                '04' => 'April',   '05' => 'Mei',      '06' => 'Juni',
                                '07' => 'Juli',    '08' => 'Agustus',  '09' => 'September',
                                '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
                            ])
                            ->default(now()->format('m'))
                            ->visible(fn (Get $get) => $get('mode') === 'period')
                            ->required(fn (Get $get) => $get('mode') === 'period'),

                        Select::make('year')
                            ->label('Tahun Bergabung')
                            ->options(function () {
                                $years = range(\Carbon\Carbon::now()->year - 5, \Carbon\Carbon::now()->year + 1);

                                return array_combine($years, $years);
                            })
                            ->default(now()->year)
                            ->visible(fn (Get $get) => $get('mode') === 'period')
                            ->required(fn (Get $get) => $get('mode') === 'period'),
                    ])
                    ->action(function (array $data) {
                        $gymkosId = $data['gymkos_id'] ?? null;
                        $gymName = $gymkosId ? \App\Models\Gymkos::find($gymkosId)->name : 'Semua-Cabang';

                        if ($data['mode'] === 'all') {
                            $filename = "Semua-Data-Member-{$gymName}-".date('d-m-Y').'.xlsx';
                            $month = null;
                            $year = null;
                        } else {
                            $filename = "Data-Member-Join-{$gymName}-".$data['month'].'-'.$data['year'].'.xlsx';
                            $month = $data['month'];
                            $year = $data['year'];
                        }

                        return Excel::download(
                            new MemberExport($data['mode'], $month, $year, $gymkosId),
                            $filename
                        );
                    }),
            ])

            ->filters([
                Tables\Filters\SelectFilter::make('gymkos_id')
                    ->relationship('gymkos', 'name', fn (Builder $query) => $query->whereIn('id', [1, 2]))
                    ->label('Cabang Gym')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Aktif',
                        'inactive' => 'Tidak Aktif (Expired)',
                    ])
                    ->label('Status Membership'),
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
        return [];
    }

    // --- GLOBAL SEARCH (Header Atas Filament) ---
    protected static ?string $recordTitleAttribute = 'name';

    public static function getGloballySearchableAttributes(): array
    {
        return ['member_code', 'name', 'phone'];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['gymkos']);
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return ($record->member_code ? "[{$record->member_code}] " : '').$record->name;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'No. HP' => $record->phone ?? '-',
            'Cabang' => $record->gymkos ? $record->gymkos->name : '-',
            'Status' => $record->status === 'active' ? '🟢 Aktif' : '🔴 Expired',
            'Expired Pada' => $record->membership_end_date ? \Carbon\Carbon::parse($record->membership_end_date)->format('d M Y') : '-',
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }
}
