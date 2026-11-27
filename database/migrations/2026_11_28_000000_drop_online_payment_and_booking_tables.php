<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fitur pembayaran membership online (payment_membership) dan booking kost (booking_kost)
 * dihapus karena tidak pernah dipakai; pendaftaran/perpanjangan/sewa kini lewat WhatsApp.
 *
 * Yang TIDAK diubah: baris `transactions` lama yang pernah dibuat fitur ini tetap ada
 * (kolom payable_type/payable_id dibiarkan sebagai catatan asal), begitu pula finances-nya.
 *
 * down() hanya membuat ulang STRUKTUR tabel (sama seperti sebelum dihapus) — datanya
 * tidak kembali; pulihkan dari backup bila perlu.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('payment_membership');
        Schema::dropIfExists('booking_kost');
    }

    public function down(): void
    {
        if (! Schema::hasTable('payment_membership')) {
            Schema::create('payment_membership', function (Blueprint $table) {
                $table->id();
                $table->string('order_id')->unique('payments_order_id_unique');
                $table->foreignId('member_id')->nullable()->constrained('members')->cascadeOnDelete();
                $table->string('name');
                $table->string('email');
                $table->string('phone');
                $table->string('image');
                $table->string('payment');
                $table->string('membership_type');
                $table->decimal('price', 15, 2)->default(0);
                $table->string('status')->default('pending');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('booking_kost')) {
            Schema::create('booking_kost', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email');
                $table->string('phone');
                $table->date('date');
                $table->date('end_date')->nullable();
                $table->enum('room_type', ['750rb - AC', '500rb - Non AC']);
                $table->decimal('price', 15, 2)->default(0);
                $table->integer('room_number');
                $table->string('payment');
                $table->string('payment_proof')->nullable();
                $table->enum('status', ['pending', 'paid'])->default('pending');
                $table->timestamps();
            });
        }
    }
};
