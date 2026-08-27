<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // 1. Import ini

class Trainer extends Model
{
    use HasFactory, SoftDeletes; // 2. Tambahkan SoftDeletes di sini

    protected $fillable = [
        'name',
        'description',
        'urls',
        'image',
        'gymkos_id',
        'user_id',
    ];

    protected $casts = [
        'urls' => 'array',
    ];

    public function gymkos()
    {
        return $this->belongsTo(Gymkos::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
