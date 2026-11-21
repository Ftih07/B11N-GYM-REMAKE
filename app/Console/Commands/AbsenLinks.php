<?php

namespace App\Console\Commands;

use App\Models\Gymkos;
use App\Services\AttendanceService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AbsenLinks extends Command
{
    protected $signature = 'absen:links {--rotate : Ganti token (URL lama langsung mati, QR harus dibuat ulang)}';

    protected $description = 'Tampilkan URL absen mandiri per cabang (untuk diisi ke qr_codes.target_url)';

    public function handle(): int
    {
        $table = (new Gymkos)->getTable();

        if (
            $this->option('rotate')
            && !$this->confirm('URL lama akan mati dan QR harus dibuat ulang. Lanjutkan?')
        ) {
            return self::SUCCESS;
        }

        $rows = [];

        foreach (AttendanceService::GYM_IDS as $id) {
            $gym = DB::table($table)->where('id', $id)->first(['id', 'name', 'absen_token']);

            if (!$gym) {
                continue;
            }

            $token = $gym->absen_token;

            if (!$token || $this->option('rotate')) {
                $token = Str::lower(Str::random(24));
                DB::table($table)->where('id', $id)->update(['absen_token' => $token]);
            }

            $rows[] = [$gym->name, route('self-checkin', ['token' => $token])];
        }

        $this->table(['Cabang', 'URL untuk QR'], $rows);

        return self::SUCCESS;
    }
}
