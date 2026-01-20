<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipments', function (Blueprint $table) {
            $table->id(); // Mewakili UniqueID
            $table->foreignId('gymkos_id')->constrained('gymkos')->cascadeOnDelete(); // Pastikan tabel 'gymkos' sudah ada
            $table->string('name');
            $table->string('category'); // e.g., 'Cardio', 'Strength', 'Furniture'
            $table->text('description')->nullable();
            $table->string('video_url')->nullable(); // Jika host external (YouTube)
            $table->string('status')->default('active'); // active, maintenance, broken
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipments');
    }
};
