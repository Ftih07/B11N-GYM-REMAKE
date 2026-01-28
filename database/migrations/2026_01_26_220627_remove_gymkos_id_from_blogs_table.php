<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            // Jika sebelumnya ada foreign key, hapus dulu constraint-nya
            $table->dropForeign(['gymkos_id']);

            // Baru kemudian hapus kolomnya
            $table->dropColumn('gymkos_id');
        });
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            // Untuk rollback: tambahkan lagi kolomnya
            $table->foreignId('gymkos_id')->nullable()->constrained();
        });
    }
};
