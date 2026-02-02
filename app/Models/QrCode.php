<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    use HasFactory;

    // Agar kolom ini bisa diisi massal
    protected $fillable = [
        'name',
        'target_url',
        'qr_path',
    ];
}
