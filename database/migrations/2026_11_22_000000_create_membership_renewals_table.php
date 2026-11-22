<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Hanya MENAMBAH tabel baru; tidak mengubah tabel yang sudah ada.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_renewals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->foreignId('gymkos_id')->constrained('gymkos')->cascadeOnDelete();

            $table->date('paid_at');              // tanggal bayar
            $table->date('period_month');         // bulan pertama yang dibayar (selalu tanggal 1)
            $table->unsignedTinyInteger('months')->default(1); // jumlah bulan yang dibayar
            $table->date('valid_until')->nullable();           // masa berlaku member setelah perpanjangan (snapshot)
            $table->string('payment_method', 20)->nullable();  // cash|qris|transfer|cash_qris|other
            $table->string('source', 20)->default('admin');    // admin|online|manual
            $table->unsignedBigInteger('payment_id')->nullable()->unique(); // payment_membership.id (cegah ganda approve online)
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['gymkos_id', 'period_month'], 'renew_gym_period_idx');
            $table->index(['member_id', 'paid_at'], 'renew_member_paid_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_renewals');
    }
};
