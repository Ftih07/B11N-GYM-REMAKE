<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Finance extends Model
{
    protected $guarded = [];

    // Casting tipe data agar formatnya benar
    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    // Relasi opsional (jika keuangan berasal dari transaksi POS)
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
    
    public function gymkos()
    {
        return $this->belongsTo(Gymkos::class); // Asumsi ada model Gymkos
    }
}