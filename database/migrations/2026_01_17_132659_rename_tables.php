<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Mengubah nama tabel
        Schema::rename('bookings', 'booking_kost');
        Schema::rename('payments', 'payment_membership');
    }

    public function down(): void
    {
        // Rollback ke nama semula jika ada error
        Schema::rename('booking_kost', 'bookings');
        Schema::rename('payment_membership', 'payments');
    }
};
