<?php

namespace App\Services;

use App\Models\Member;
use Illuminate\Support\Facades\Cache;

/**
 * Status member `active` → `inactive` bila `membership_end_date` sudah lewat (PERF-05).
 *
 * Dulu dikerjakan oleh event Member::retrieved: setiap member yang DIBACA (daftar, ekspor,
 * widget) dan kedaluwarsa memicu satu UPDATE, dan member yang tidak pernah dibuka tetap
 * `active` sehingga hitungan "member aktif" terlalu besar. Sekarang satu UPDATE massal.
 *
 * Dipanggil (a) otomatis maksimal sekali per jam saat panel admin / portal member dibuka
 * (middleware ExpireOverdueMemberships) dan (b) opsional lewat cron `php artisan member:expire`.
 */
class MembershipStatusService
{
    private const THROTTLE_KEY = 'members:expire-overdue';

    /** @return int jumlah member yang diubah menjadi inactive */
    public function expireOverdue(): int
    {
        return Member::query()
            ->where('status', 'active')
            ->whereNotNull('membership_end_date')
            ->where('membership_end_date', '<', today()->toDateString())
            ->update(['status' => 'inactive']);
    }

    /** Jalankan expireOverdue() paling banyak sekali per $minutes menit. */
    public function expireOverdueThrottled(int $minutes = 60): ?int
    {
        // Cache::add hanya berhasil bila kunci belum ada → satu request saja yang menjalankan
        if (! Cache::add(self::THROTTLE_KEY, now()->toDateTimeString(), now()->addMinutes($minutes))) {
            return null;
        }

        return $this->expireOverdue();
    }
}
