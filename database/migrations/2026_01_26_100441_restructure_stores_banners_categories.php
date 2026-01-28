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
        // 1. Update Tabel STORES
        Schema::table('stores', function (Blueprint $table) {
            // Tambah kolom baru
            $table->string('subheading')->nullable()->after('title');
            $table->text('location')->nullable()->after('subheading');

            // Ubah image jadi nullable (Wajib install doctrine/dbal kalo Laravel < 10)
            $table->string('image')->nullable()->change();
        });

        // 2. Hapus Tabel BANNERS
        Schema::dropIfExists('banners');

        // 3. Update Tabel CATEGORY PRODUCTS (Hapus store_id)
        if (Schema::hasColumn('category_products', 'store_id')) {
            Schema::table('category_products', function (Blueprint $table) {
                // Hapus Foreign Key dulu (Syntax array: ['nama_kolom'])
                // Laravel otomatis nyari nama constraintnya: category_products_store_id_foreign
                $table->dropForeign(['store_id']);

                // Baru hapus kolomnya
                $table->dropColumn('store_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Rollback STORES
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn(['subheading', 'location']);
            // Kembalikan image jadi tidak boleh null (opsional, hati-hati jika data sudah ada yg null)
            // $table->string('image')->nullable(false)->change(); 
        });

        // 2. Rollback BANNERS (Buat ulang tabelnya jika di-rollback)
        if (!Schema::hasTable('banners')) {
            Schema::create('banners', function (Blueprint $table) {
                $table->id();
                $table->string('image');
                $table->string('title')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // 3. Rollback CATEGORY PRODUCTS
        Schema::table('category_products', function (Blueprint $table) {
            $table->unsignedBigInteger('store_id')->nullable()->after('id');
            // Asumsi tabel stores masih ada
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
        });
    }
};
