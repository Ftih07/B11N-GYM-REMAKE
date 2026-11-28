<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebVisitor extends Model
{
    use HasFactory;

    // Allow mass assignment for these fields (Security)
    protected $fillable = [
        'ip_address',
        'visit_date',
        'user_agent',
    ];

    /**
     * IP tidak disimpan mentah (PRV-05): sha256(salt|ip), 64 karakter heksadesimal.
     * Rumusnya sama dengan migration hash_web_visitor_ips (SHA2(CONCAT(salt, '|', ip), 256)).
     */
    public static function hashIp(?string $ip): string
    {
        return hash('sha256', config('b1ng.visitors.ip_salt').'|'.$ip);
    }

    // Data Casting
    // Automatically converts 'visit_date' to a Carbon object when retrieved
    protected $casts = [
        'visit_date' => 'date',
    ];
}
