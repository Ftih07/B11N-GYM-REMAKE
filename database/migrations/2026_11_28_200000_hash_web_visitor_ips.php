<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * PRV-05: alamat IP pengunjung lama diubah menjadi hash sha256(salt|ip) — rumus yang sama
 * dengan WebVisitor::hashIp() — sehingga hitungan pengunjung unik tetap sama. Baris yang
 * sudah berupa hash (64 karakter) dilewati, jadi aman bila dijalankan ulang.
 *
 * Tidak bisa dibalik: IP asli tidak disimpan lagi.
 */
return new class extends Migration
{
    public function up(): void
    {
        $salt = (string) config('b1ng.visitors.ip_salt');

        DB::update(
            "UPDATE web_visitors SET ip_address = SHA2(CONCAT(?, '|', ip_address), 256) WHERE CHAR_LENGTH(ip_address) <> 64",
            [$salt]
        );
    }

    public function down(): void
    {
        // Tidak bisa dikembalikan (hash satu arah). Pulihkan dari backup bila perlu.
    }
};
