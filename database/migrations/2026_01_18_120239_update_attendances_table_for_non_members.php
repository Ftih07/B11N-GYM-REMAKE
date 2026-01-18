<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            // 1. Ubah member_id jadi boleh NULL
            $table->unsignedBigInteger('member_id')->nullable()->change();

            // 2. Tambah kolom untuk data Non-Member
            $table->string('visitor_name')->nullable()->after('member_id');
            $table->string('visitor_phone')->nullable()->after('visitor_name');

            // 3. Jenis Kunjungan: 'member', 'daily', 'weekly'
            $table->string('visit_type')->default('membership')->after('method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('visitor_name');
            $table->dropColumn('visitor_phone');
            $table->dropColumn('visit_type');
        });
    }
};
