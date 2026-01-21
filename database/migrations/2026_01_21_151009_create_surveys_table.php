<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            // Identitas & Status
            $table->boolean('is_membership')->default(false);
            $table->string('name');
            $table->string('email');
            $table->string('phone');

            // Khusus Member (Nullable)
            $table->string('member_duration')->nullable();
            $table->integer('renewal_chance')->nullable(); // Skala 1-5

            // Survey Umum
            $table->string('fitness_goal'); // e.g., 'weight_loss', 'muscle'
            $table->integer('rating_equipment'); // 1-5
            $table->integer('rating_cleanliness'); // 1-5
            $table->integer('nps_score'); // 1-10
            $table->text('feedback')->nullable();
            $table->string('promo_interest')->nullable(); // Disimpan kodenya aja

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surveys');
    }
};
