<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // database/migrations/xxxx_xx_xx_create_maintenance_reports_table.php
    public function up(): void
    {
        Schema::create('maintenance_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gymkos_id')->constrained('gymkos');
            $table->foreignId('equipment_id')->constrained('equipments');
            $table->string('reporter_name');
            $table->text('description');
            $table->string('evidence_photo')->nullable();
            $table->string('severity'); // low, medium, high, critical
            $table->dateTime('fixed_at')->nullable();
            $table->string('status')->default('pending'); // pending, in_progress, resolved
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_reports');
    }
};
