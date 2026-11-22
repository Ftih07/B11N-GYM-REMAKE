<?php

namespace App\Filament\Resources\MemberResource\Pages;

use App\Filament\Resources\MemberResource;
use App\Services\MembershipRenewalService;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;

class CreateMember extends CreateRecord
{
    protected static string $resource = MemberResource::class;

    private ?string $method = null;

    // Metode bayar saat daftar masuk ke riwayat (pembayaran pertama), bukan ke kolom members.payment_method.
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->method = $data['payment_method'] ?? null;
        $data['payment_method'] = null;

        return $data;
    }

    protected function afterCreate(): void
    {
        $member = $this->getRecord();

        if (! $this->method || ! $member->membership_end_date) {
            return;
        }

        $join = Carbon::parse($member->join_date ?? today());
        $p = MembershipRenewalService::inferPeriod($join, Carbon::parse($member->membership_end_date), $join);

        app(MembershipRenewalService::class)->record($member, [
            'paid_at' => $join,
            'period_month' => $p['period_month'],
            'months' => $p['months'],
            'valid_until' => $member->membership_end_date,
            'payment_method' => $this->method,
            'source' => 'admin',
        ]);
    }
}
