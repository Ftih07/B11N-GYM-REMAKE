<?php

namespace App\Observers;

use App\Models\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
// Import Facade dari paket Simple QrCode
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeGenerator;

class QrCodeObserver
{
    /**
     * Handle the QrCode "created" event.
     */
    public function created(QrCode $qrCode): void
    {
        // 1. Tentukan nama file dan path penyimpanan (di folder storage/app/public/qr-codes)
        $path = 'qr-codes/' . Str::random(40) . '.png';

        // 2. Generate gambar QR Code
        // format png, ukuran 400x400, margin tipis
        $image = QrCodeGenerator::format('png')
            ->size(400)
            ->margin(2)
            ->generate($qrCode->target_url);

        // 3. Simpan gambar ke storage public
        Storage::disk('public')->put($path, $image);

        // 4. Update record database dengan path gambar yang baru dibuat
        // Gunakan updateQuietly agar tidak memicu event observer lagi (looping)
        $qrCode->updateQuietly(['qr_path' => $path]);
    }

    // Optional: Hapus gambar jika data dihapus
    public function deleted(QrCode $qrCode): void
    {
        if ($qrCode->qr_path && Storage::disk('public')->exists($qrCode->qr_path)) {
            Storage::disk('public')->delete($qrCode->qr_path);
        }
    }
}
