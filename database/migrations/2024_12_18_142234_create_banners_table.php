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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subheading')->nullable();
            $table->text('description')->nullable();
            $table->string('location');
            $table->string('image');
            $table->foreignId('gymkos_id')
                ->constrained('gymkos')
                ->onDelete('cascade');
            $table->foreignId('stores_id')
                ->constrained('stores')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
