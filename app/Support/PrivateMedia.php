<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

/**
 * Berkas sensitif (foto wajah member, foto progres tubuh, bukti transfer) disimpan di disk
 * privat `local` (storage/app/private), tidak bisa dibuka lewat /storage/... (SEC-10).
 * Disajikan lewat PrivateMediaController setelah cek hak akses.
 *
 * Berkas lama yang belum dipindah (`php artisan media:pindah-privat`) masih dicari di disk
 * `public` sebagai cadangan, jadi tampilan tetap jalan selama masa transisi.
 */
class PrivateMedia
{
    public const DISK = 'local';

    /** Folder yang dianggap sensitif (path relatif yang tersimpan di database). */
    public const DIRECTORIES = ['member-photos', 'progress_photos', 'transaction-proofs'];

    public static function response(?string $path): Response
    {
        abort_if(blank($path) || str_contains($path, '..'), 404);

        foreach ([self::DISK, 'public'] as $disk) {
            if (Storage::disk($disk)->exists($path)) {
                return Storage::disk($disk)->response($path, null, [
                    'Cache-Control' => 'private, max-age=3600',
                ]);
            }
        }

        abort(404);
    }

    public static function delete(?string $path): void
    {
        if (blank($path)) {
            return;
        }

        Storage::disk(self::DISK)->delete($path);
        Storage::disk('public')->delete($path); // sisa masa transisi
    }
}
