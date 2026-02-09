<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($transaction) {
            $transaction->code = 'TRX-' . time();
        });
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    public function gymkos()
    {
        return $this->belongsTo(Gymkos::class);
    }
    
    public function payable()
    {
        return $this->morphTo();
    }

    // Relasi ke tabel Finance (One to One)
    public function finance()
    {
        return $this->hasOne(Finance::class);
    }
}
