<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use App\Filament\Resources\AttendanceResource;
use App\Models\Attendance;
use App\Models\Gymkos;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;

    // Tab "Perlu Dicek" (absen mandiri yang menunggu verifikasi), semua cabang,
    // lalu tab per cabang. Tab cabang hanya muncul untuk cabang yang sudah punya data absensi.
    public function getTabs(): array
    {
        $pending = Attendance::where('status', Attendance::STATUS_PENDING)->count();

        $tabs = [
            'pending' => Tab::make('Perlu Dicek')
                ->icon('heroicon-o-exclamation-triangle')
                ->badge($pending > 0 ? $pending : null)
                ->badgeColor('warning')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', Attendance::STATUS_PENDING)),

            'all' => Tab::make('Semua Cabang')->icon('heroicon-o-building-storefront'),
        ];

        $gyms = Gymkos::query()
            ->whereIn('id', fn($sub) => $sub->select('gymkos_id')->from('attendances'))
            ->orderBy('id')
            ->get();

        foreach ($gyms as $gym) {
            $tabs['gym_' . $gym->id] = Tab::make($gym->name)
                ->icon('heroicon-o-map-pin')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('gymkos_id', $gym->id));
        }

        return $tabs;
    }

    // Buka langsung di "Perlu Dicek" kalau ada yang menunggu, supaya karyawan tidak perlu mencari.
    // Hapus method ini kalau ingin selalu mulai dari "Semua Cabang".
    public function getDefaultActiveTab(): string | int | null
    {
        return Attendance::where('status', Attendance::STATUS_PENDING)->exists() ? 'pending' : 'all';
    }
}
