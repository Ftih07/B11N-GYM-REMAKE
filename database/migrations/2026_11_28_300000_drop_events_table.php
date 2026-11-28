<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel `events` tidak punya model maupun migration pembuat (sisa proyek lain) dan hanya
 * berisi 1 baris data percobaan "Lorem Ipsum" (INT-16). down() membuat ulang strukturnya saja.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('events');
    }

    public function down(): void
    {
        if (Schema::hasTable('events')) {
            return;
        }

        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('place_name');
            $table->string('street_name');
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->string('date_events')->nullable();
            $table->string('type_events');
            $table->string('image_events');
            $table->text('desc');
            $table->longText('contact_organizer');
            $table->text('iframe')->nullable();
            $table->timestamps();
        });
    }
};
