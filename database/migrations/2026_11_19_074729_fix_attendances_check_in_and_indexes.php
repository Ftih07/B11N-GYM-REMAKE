<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // check_in_time sebelumnya ON UPDATE CURRENT_TIMESTAMP: jam masuk ikut berubah
        // setiap kali baris absensi di-update. Cukup DEFAULT CURRENT_TIMESTAMP.
        DB::statement('ALTER TABLE attendances MODIFY check_in_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');

        Schema::table('attendances', function (Blueprint $table) {
            // Rekap per cabang + rentang waktu
            $table->index(['gymkos_id', 'check_in_time'], 'att_gym_time_idx');
            // Cek "sudah absen hari ini" dan rekap per member
            $table->index(['member_id', 'check_in_time'], 'att_member_time_idx');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex('att_gym_time_idx');
            $table->dropIndex('att_member_time_idx');
        });

        DB::statement('ALTER TABLE attendances MODIFY check_in_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
    }
};
