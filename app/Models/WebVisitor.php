<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebVisitor extends Model
{
    use HasFactory;

    // Supaya bisa diisi lewat script Middleware tadi
    protected $fillable = [
        'ip_address',
        'visit_date',
        'user_agent',
    ];

    // Opsional: Biar kolom visit_date otomatis jadi objek Carbon (enak buat format tanggal)
    protected $casts = [
        'visit_date' => 'date',
    ];
}
