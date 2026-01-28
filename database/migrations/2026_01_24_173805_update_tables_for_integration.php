<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Jalankan: php artisan make:migration update_tables_for_integration
    // Lalu edit file migrationnya:

    public function up()
    {
        // 1. Tambah Price di Booking
        Schema::table('booking_kost', function (Blueprint $table) {
            $table->decimal('price', 15, 2)->default(0)->after('room_type');
        });

        // 2. Tambah Price di Payment (Membership)
        Schema::table('payment_membership', function (Blueprint $table) {
            $table->decimal('price', 15, 2)->default(0)->after('membership_type');
        });

        // 3. Tambah kolom Polymorphic di Transactions
        Schema::table('transactions', function (Blueprint $table) {
            // Ini akan membuat kolom 'payable_id' dan 'payable_type'
            $table->nullableMorphs('payable');

            // Pastikan kolom lain yang dibutuhkan ada (sesuaikan dengan strukturmu)
            // $table->string('customer_name')->nullable();
            // $table->string('customer_email')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
