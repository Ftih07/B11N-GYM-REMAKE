<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Portal kasir /employee sudah dihapus; role yang dipakai tinggal super_admin, admin, customer.
 * Akun role `employee` diubah menjadi `customer`, dan tautan trainer ke akun tersebut
 * (dulu dipakai untuk login POS) dilepas.
 *
 * Hanya mengubah data, tidak mengubah skema.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function () {
            $employeeIds = DB::table('users')->where('role', 'employee')->pluck('id');

            if ($employeeIds->isEmpty()) {
                return;
            }

            DB::table('trainers')->whereIn('user_id', $employeeIds)->update(['user_id' => null]);

            DB::table('users')->whereIn('id', $employeeIds)->update([
                'role' => 'customer',
                'updated_at' => now(),
            ]);
        });
    }

    public function down(): void
    {
        // Tidak bisa dikembalikan: setelah up() tidak ada catatan akun mana yang dulu `employee`.
        // Pulihkan dari backup bila benar-benar diperlukan.
    }
};
