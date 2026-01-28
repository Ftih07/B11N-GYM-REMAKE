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
        // 1. Revisi Tabel PRODUCTS
        if (Schema::hasColumn('products', 'gymkos_id')) {
            Schema::table('products', function (Blueprint $table) {
                // Hapus Foreign Key dulu (format default: namaTabel_namaKolom_foreign)
                // Kita pakai array syntax biar aman: dropForeign(['nama_kolom'])
                $table->dropForeign(['gymkos_id']); 
                
                // Baru hapus kolomnya
                $table->dropColumn('gymkos_id');
            });
        }

        // 2. Revisi Tabel CATEGORY PRODUCTS
        // Cek dulu, nama kolomnya 'gymkos_id' atau 'store_id' (sesuai request kamu tadi)
        if (Schema::hasColumn('category_products', 'gymkos_id')) {
            Schema::table('category_products', function (Blueprint $table) {
                $table->dropForeign(['gymkos_id']);
                $table->dropColumn('gymkos_id');
            });
        }
        // Jaga-jaga kalau namanya 'store_id' seperti yang kamu sebut
        if (Schema::hasColumn('category_products', 'store_id')) {
            Schema::table('category_products', function (Blueprint $table) {
                $table->dropForeign(['store_id']);
                $table->dropColumn('store_id');
            });
        }

        // 3. Revisi Tabel BANNERS
        if (Schema::hasColumn('banners', 'gymkos_id')) {
            Schema::table('banners', function (Blueprint $table) {
                $table->dropForeign(['gymkos_id']);
                $table->dropColumn('gymkos_id');
            });
        }

        // 4. Revisi Tabel STORES (Jika ada)
        if (Schema::hasColumn('stores', 'gymkos_id')) {
            Schema::table('stores', function (Blueprint $table) {
                $table->dropForeign(['gymkos_id']);
                $table->dropColumn('gymkos_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     * (Buat balikin lagi kalo mau rollback)
     */
    public function down(): void
    {
        // Kembalikan kolom gymkos_id jika di-rollback
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('gymkos_id')->nullable()->constrained('gymkos')->cascadeOnDelete();
        });

        Schema::table('category_products', function (Blueprint $table) {
            $table->foreignId('gymkos_id')->nullable()->constrained('gymkos')->cascadeOnDelete();
        });

        Schema::table('banners', function (Blueprint $table) {
            $table->foreignId('gymkos_id')->nullable()->constrained('gymkos')->cascadeOnDelete();
        });
        
        Schema::table('stores', function (Blueprint $table) {
            $table->foreignId('gymkos_id')->nullable()->constrained('gymkos')->cascadeOnDelete();
        });
    }
};