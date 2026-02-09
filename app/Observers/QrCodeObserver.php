<?php

namespace App\Observers;

use App\Models\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeGenerator;

class QrCodeObserver
{
    /**
     * Logic: Triggered AFTER a QrCode record is created in DB.
     */
    public function created(QrCode $qrCode): void
    {
        // 1. Define file path (storage/app/public/qr-codes)
        $path = 'qr-codes/' . Str::random(40) . '.png';

        // 2. Generate the QR Image from 'target_url'
        $image = QrCodeGenerator::format('png')
            ->size(400)
            ->margin(2)
            ->generate($qrCode->target_url);

        // 3. Save file to public disk
        Storage::disk('public')->put($path, $image);

        // 4. Update the DB record with the file path
        // IMPORTANT: Use updateQuietly() to prevent infinite loops (observer triggering itself)
        $qrCode->updateQuietly(['qr_path' => $path]);
    }

    // Logic: Cleanup file when record is deleted
    public function deleted(QrCode $qrCode): void
    {
        if ($qrCode->qr_path && Storage::disk('public')->exists($qrCode->qr_path)) {
            Storage::disk('public')->delete($qrCode->qr_path);
        }
    }
}
