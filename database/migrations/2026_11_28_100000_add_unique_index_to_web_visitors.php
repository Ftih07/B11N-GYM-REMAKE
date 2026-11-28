<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * PERF-01: web_visitors tidak punya index sama sekali, padahal TrackVisitor mencari
 * (ip_address, visit_date) di setiap request. firstOrCreate juga sempat menghasilkan baris
 * ganda saat dua request datang bersamaan. Hapus baris ganda (sisakan id terkecil), lalu
 * pasang index unik agar TrackVisitor bisa memakai insertOrIgnore.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement(
            'DELETE w1 FROM web_visitors w1
             JOIN web_visitors w2
               ON w1.ip_address = w2.ip_address
              AND w1.visit_date = w2.visit_date
              AND w1.id > w2.id'
        );

        Schema::table('web_visitors', function (Blueprint $table) {
            $table->unique(['visit_date', 'ip_address'], 'web_visitors_date_ip_unique');
        });
    }

    public function down(): void
    {
        Schema::table('web_visitors', function (Blueprint $table) {
            $table->dropUnique('web_visitors_date_ip_unique');
        });
    }
};
