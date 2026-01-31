<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Update tabel Users: Tambah google_id & avatar
        Schema::table('users', function (Blueprint $table) {
            $table->string('google_id')->nullable()->after('email'); // Buat simpan ID unik dari Google
            $table->string('profile_picture')->nullable()->after('role'); // Opsional, buat nampilin foto Google
        });

        // 2. Update tabel Members: Tambah user_id (Relasi ke Users)
        Schema::table('members', function (Blueprint $table) {
            // Nullable karena member baru belum tentu punya akun user
            $table->foreignId('user_id')->nullable()->after('gymkos_id')->constrained('users')->onDelete('set null');
        });

        // 3. Buat tabel Member Measurements (Progress Report)
        Schema::create('member_measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');

            // Semua dibuat nullable sesuai request biar user bebas isi yg mana aja
            $table->decimal('weight', 5, 2)->nullable();       // Berat Badan
            $table->decimal('waist_size', 5, 2)->nullable();   // Lingkar Pinggang
            $table->decimal('arm_size', 5, 2)->nullable();     // Lingkar Lengan
            $table->decimal('thigh_size', 5, 2)->nullable();   // Lingkar Paha

            $table->date('measured_at')->default(now());       // Tanggal pengukuran
            $table->text('notes')->nullable();                 // Catatan tambahan
            $table->string('progress_photo')->nullable();      // Foto progress

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('member_measurements');

        Schema::table('members', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['google_id', 'profile_picture']);
        });
    }
};
