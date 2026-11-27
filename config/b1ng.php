<?php

/**
 * Data bisnis B1NG Empire yang dipakai di banyak halaman.
 * Ubah di sini (atau lewat .env) — tidak perlu menyisir view satu per satu.
 */
$whatsapp = env('B1NG_WHATSAPP', '6281226110988'); // format internasional tanpa "+"

return [
    'contact' => [
        // Nomor admin untuk semua unit (B11N, K1NG, Kost, Toko)
        'whatsapp' => $whatsapp,
        'whatsapp_url' => 'https://wa.me/'.$whatsapp,
        'phone' => env('B1NG_PHONE_DISPLAY', '0812-2611-0988'), // untuk ditampilkan
        'phone_e164' => '+'.$whatsapp, // untuk data terstruktur (schema.org)
    ],
];
