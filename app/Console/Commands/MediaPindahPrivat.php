<?php

namespace App\Console\Commands;

use App\Support\PrivateMedia;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Pindahkan berkas sensitif lama dari disk `public` (bisa dibuka lewat /storage/...) ke disk
 * privat `local` (SEC-10). Path di database tidak berubah. Aman dijalankan berulang kali.
 *
 * Juga mengarsipkan bukti bayar dari fitur pembayaran online & booking kost yang sudah dihapus
 * (payment_receipts, bukti_pembayaran) ke storage/app/private/arsip/.
 */
class MediaPindahPrivat extends Command
{
    protected $signature = 'media:pindah-privat {--dry-run : Hanya tampilkan jumlah, tanpa memindahkan}';

    protected $description = 'Pindahkan foto wajah, foto progres, dan bukti transfer dari disk public ke disk privat';

    private const ARCHIVE = ['payment_receipts', 'bukti_pembayaran'];

    public function handle(): int
    {
        $public = Storage::disk('public');
        $private = Storage::disk(PrivateMedia::DISK);
        $dry = (bool) $this->option('dry-run');
        $rows = [];

        $targets = array_merge(
            array_map(fn ($d) => [$d, $d], PrivateMedia::DIRECTORIES),
            array_map(fn ($d) => [$d, 'arsip/'.$d], self::ARCHIVE),
        );

        foreach ($targets as [$from, $to]) {
            $moved = $skipped = 0;

            foreach ($public->allFiles($from) as $file) {
                $dest = $to.substr($file, strlen($from));

                if ($private->exists($dest)) {
                    $skipped++;
                    if (! $dry) {
                        $public->delete($file); // sudah ada di privat → hapus salinan publik
                    }

                    continue;
                }

                if (! $dry) {
                    $stream = $public->readStream($file);
                    $private->writeStream($dest, $stream);
                    if (is_resource($stream)) {
                        fclose($stream);
                    }

                    if (! $private->exists($dest)) {
                        $this->error("Gagal memindahkan {$file}");

                        return self::FAILURE;
                    }

                    $public->delete($file);
                }
                $moved++;
            }

            $rows[] = [$from, $to, $moved, $skipped];
        }

        $this->table(['Dari (public)', 'Ke (privat)', $dry ? 'Akan dipindah' : 'Dipindah', 'Sudah ada'], $rows);
        $this->info($dry ? 'Mode --dry-run: tidak ada berkas yang diubah.' : 'Selesai.');

        return self::SUCCESS;
    }
}
