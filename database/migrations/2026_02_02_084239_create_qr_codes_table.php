<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qr_codes', function (Blueprint $table) {
            // Kita gunakan 'id' standar Laravel sebagai UniqueID (Primary Key)
            $table->id();
            $table->string('name');
            $table->text('target_url'); // Gunakan text agar bisa menampung URL panjang
            $table->string('qr_path')->nullable(); // Nullable karena diisi setelah create
            $table->timestamps(); // Ini mencakup timestamp yang diminta
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qr_codes');
    }
};
