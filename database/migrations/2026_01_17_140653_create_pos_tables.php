<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Transactions (Header Struk)
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // TRX-2024001
            $table->foreignId('trainer_id')->constrained('trainers');
            $table->foreignId('gymkos_id')->constrained('gymkos'); // Lokasi Gym
            $table->string('payment_method'); // Cash, QRIS, Transfer
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('change_amount', 15, 2)->default(0); // Kembalian (Opsional tapi bagus)
            $table->string('status')->default('pending'); // pending, paid, cancelled
            $table->timestamps();
        });

        // 2. Tabel Transaction Items (Detail Barang)
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->cascadeOnDelete();
            $table->uuid('product_id');
            $table->foreign('product_id')->references('id')->on('products');
            $table->string('product_name'); // Snapshot nama (jika nama produk berubah nanti)
            $table->integer('quantity');
            $table->decimal('price', 15, 2); // Snapshot harga saat beli
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });

        // 3. Tabel Finances (Laporan Keuangan)
        Schema::create('finances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gymkos_id')->constrained('gymkos');
            $table->foreignId('transaction_id')->nullable()->constrained('transactions')->cascadeOnDelete();
            $table->string('type'); // income / expense
            $table->decimal('amount', 15, 2);
            $table->text('description');
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pos_tables');
    }
};
