<?php

use App\Models\Gymkos;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    private function table(): string
    {
        return (new Gymkos)->getTable();
    }

    public function up(): void
    {
        Schema::table($this->table(), function (Blueprint $table) {
            // Token acak untuk URL QR absen mandiri (/absen/{token}). Bisa diganti kapan saja.
            $table->string('absen_token', 40)->nullable()->unique()->after('id');
        });

        // Isi token untuk B11N GYM (1) dan K1NG GYM (2)
        foreach ([1, 2] as $id) {
            DB::table($this->table())->where('id', $id)->update([
                'absen_token' => Str::lower(Str::random(24)),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table($this->table(), function (Blueprint $table) {
            $table->dropUnique(['absen_token']);
            $table->dropColumn('absen_token');
        });
    }
};
