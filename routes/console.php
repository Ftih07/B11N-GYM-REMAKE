<?php

use Illuminate\Support\Facades\Schedule;

// Opsional: hanya berjalan bila hosting menjalankan cron `php artisan schedule:run` tiap menit.
// Tanpa cron pun status tetap diperbarui otomatis (maks. sekali per jam) saat panel admin /
// portal member dibuka — lihat App\Http\Middleware\ExpireOverdueMemberships.
Schedule::command('member:expire')->hourly();
