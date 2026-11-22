<?php

namespace App\Filament\Resources\MemberResource\Pages;

use App\Filament\Resources\MemberResource;
use App\Services\MembershipRenewalService;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditMember extends EditRecord
{
    protected static string $resource = MemberResource::class;

    /** Disiapkan saat simpan, dipakai setelah data tersimpan. */
    private ?array $renewal = null;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    // Alur tetap sama: admin mengubah "Berlaku Sampai" + "Metode Pembayaran" lalu Simpan.
    // Di sini metode diambil untuk riwayat dan TIDAK ikut disimpan ke kolom members.payment_method,
    // sehingga field selalu kosong lagi untuk perpanjangan berikutnya.
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->renewal = null;
        $method = $data['payment_method'] ?? null;
        $data['payment_method'] = null;

        $member = $this->getRecord();
        $oldEnd = $member->membership_end_date; // masih nilai lama (belum disimpan)
        $newEnd = ! empty($data['membership_end_date']) ? Carbon::parse($data['membership_end_date']) : null;

        if (! $newEnd || ! in_array((int) $member->gymkos_id, MembershipRenewalService::GYM_IDS, true)) {
            return $data;
        }

        $extended = MembershipRenewalService::isExtension($oldEnd, $newEnd);

        if ($extended && ! $method) {
            Notification::make()
                ->title('Metode pembayaran belum diisi')
                ->body('"Berlaku Sampai" dimajukan, jadi pilih Metode Pembayaran dulu agar tercatat di riwayat perpanjangan.')
                ->danger()
                ->send();
            $this->halt();
        }

        if ($extended) {
            $this->renewal = ['old' => $oldEnd, 'new' => $newEnd, 'method' => $method];
        }

        return $data;
    }

    protected function afterSave(): void
    {
        if ($this->renewal) {
            app(MembershipRenewalService::class)->recordFromEdit(
                $this->getRecord(), $this->renewal['old'], $this->renewal['new'], $this->renewal['method']
            );
            $this->renewal = null;
        }

        // Kosongkan lagi field metode pembayaran & tampilkan riwayat terbaru.
        $this->fillForm();
    }
}
