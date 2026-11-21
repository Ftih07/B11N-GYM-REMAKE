<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            // verified = sudah dicek / dibuat admin, pending = absen mandiri (QR) yang menunggu dicek,
            // rejected = ditolak admin (tidak dihitung sebagai kunjungan).
            // Data lama otomatis berstatus verified.
            if (!Schema::hasColumn('attendances', 'status')) {
                $table->string('status', 20)->default('verified')->after('method');
            }

            // Nama panggilan yang diketik member saat absen mandiri (untuk dicocokkan admin).
            if (!Schema::hasColumn('attendances', 'nickname')) {
                $table->string('nickname')->nullable()->after('visitor_name');
            }
        });

        Schema::table('attendances', function (Blueprint $table) {
            // Tab "Perlu Dicek" dan badge navigasi
            $table->index(['status', 'check_in_time'], 'att_status_time_idx');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex('att_status_time_idx');
        });

        Schema::table('attendances', function (Blueprint $table) {
            if (Schema::hasColumn('attendances', 'nickname')) {
                $table->dropColumn('nickname');
            }
            if (Schema::hasColumn('attendances', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
