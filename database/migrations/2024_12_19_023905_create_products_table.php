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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->longText('description');
            $table->decimal('price', 15, 2);
            $table->enum('status', ['ready', 'soldout']);
            $table->string('image');

            $table->string('serving_option'); // Nama penyajian (contoh: 1kg, 1 scoop, 1 sajian dll.)
            $table->string('flavour')->nullable(); // rasa contoh, coklat, vanilla, campuran
            
            $table->foreignId('gymkos_id')
                ->constrained('gymkos')
                ->onDelete('cascade');
            $table->foreignId('stores_id')
                ->constrained('stores')
                ->onDelete('cascade');
            $table->foreignId('category_products_id')
                ->constrained('category_products')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
