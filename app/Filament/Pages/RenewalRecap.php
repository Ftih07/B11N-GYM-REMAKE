<?php

namespace App\Filament\Pages;

use App\Exports\RenewalRecapExport;
use App\Models\Gymkos;
use App\Services\MembershipRenewalService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Maatwebsite\Excel\Facades\Excel;

class RenewalRecap extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-table-cells';
    protected static ?string $navigationLabel = 'Rekap Perpanjangan';
    protected static ?string $title = 'Rekap Perpanjangan Member';
    protected static ?string $navigationGroup = 'Membership & Absensi';
    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.renewal-recap';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'gymkos_id' => MembershipRenewalService::GYM_IDS[0],
            'year' => now()->year,
            'month' => null,
            'search' => null,
        ]);
    }

    public static function monthNames(): array
    {
        return [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->columns(4)
            ->schema([
                Select::make('gymkos_id')
                    ->label('Cabang Gym')
                    ->options(Gymkos::whereIn('id', MembershipRenewalService::GYM_IDS)->pluck('name', 'id'))
                    ->required()
                    ->live(),
                Select::make('year')
                    ->label('Tahun')
                    ->options(function () {
                        $years = range(now()->year - 5, now()->year + 1);

                        return array_combine($years, $years);
                    })
                    ->required()
                    ->live(),
                Select::make('month')
                    ->label('Bulan')
                    ->options(self::monthNames())
                    ->placeholder('Semua bulan')
                    ->live(),
                TextInput::make('search')
                    ->label('Cari member')
                    ->placeholder('Nama / kode member')
                    ->live(debounce: 500),
            ]);
    }

    /** Dipakai blade. */
    public function getRecap(): array
    {
        $d = $this->data;
        $gym = (int) ($d['gymkos_id'] ?? 0);

        if (! in_array($gym, MembershipRenewalService::GYM_IDS, true)) {
            return ['rows' => [], 'months' => range(1, 12)];
        }

        return app(MembershipRenewalService::class)->recap(
            $gym,
            (int) ($d['year'] ?? now()->year),
            ! empty($d['month']) ? (int) $d['month'] : null,
            $d['search'] ?? null,
        );
    }

    public function export()
    {
        $d = $this->data;
        $gym = (int) ($d['gymkos_id'] ?? 0);
        if (! in_array($gym, MembershipRenewalService::GYM_IDS, true)) {
            return null;
        }

        $name = Gymkos::find($gym)?->name ?? 'Gym';

        return Excel::download(
            new RenewalRecapExport($this->getRecap(), $name, (int) $d['year']),
            'Rekap-Perpanjangan-'.str_replace(' ', '-', $name).'-'.$d['year'].'.xlsx'
        );
    }
}
