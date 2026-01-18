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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gymkos_id')->constrained('gymkos')->cascadeOnDelete(); // Sesuaikan nama tabel gymkos kamu
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->date('join_date');
            $table->date('membership_end_date')->nullable();
            $table->string('status')->default('active'); // active, inactive
            $table->string('picture')->nullable(); // Foto profil/wajah referensi
            $table->text('face_descriptor')->nullable(); // Data vektor wajah (JSON)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members_tables');
    }
};
