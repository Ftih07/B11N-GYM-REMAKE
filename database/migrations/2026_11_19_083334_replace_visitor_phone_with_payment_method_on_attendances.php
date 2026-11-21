<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Metode pembayaran tamu (non-member). Boleh kosong.
            if (!Schema::hasColumn('attendances', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('visit_type');
            }

            // No. HP tamu sudah tidak dipakai.
            if (Schema::hasColumn('attendances', 'visitor_phone')) {
                $table->dropColumn('visitor_phone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            if (!Schema::hasColumn('attendances', 'visitor_phone')) {
                $table->string('visitor_phone')->nullable()->after('visitor_name');
            }

            if (Schema::hasColumn('attendances', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
        });
    }
};
