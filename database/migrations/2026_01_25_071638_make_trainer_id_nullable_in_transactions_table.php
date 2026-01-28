<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Ubah jadi nullable (boleh kosong)
            // Pastikan tipe datanya sama (biasanya unsignedBigInteger)
            $table->unsignedBigInteger('trainer_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Balikin jadi tidak boleh kosong (kalau rollback)
            $table->unsignedBigInteger('trainer_id')->nullable(false)->change();
        });
    }
};
