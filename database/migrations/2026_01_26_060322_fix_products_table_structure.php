<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // 1. Ubah kolom jadi nullable (boleh kosong)
            // Pastikan install doctrine/dbal jika belum: composer require doctrine/dbal
            $table->string('image')->nullable()->change();
            $table->string('serving_option')->nullable()->change();

            // Ubah Foreign Key jadi nullable
            $table->unsignedBigInteger('category_products_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Balikin ke kondisi awal (Not Null)
            $table->string('image')->nullable(false)->change();
            $table->string('serving_option')->nullable(false)->change();
            $table->unsignedBigInteger('category_products_id')->nullable(false)->change();
        });
    }
};
