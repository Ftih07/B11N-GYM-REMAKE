<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Finance extends Model
{
    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
    
    public function gymkos()
    {
        return $this->belongsTo(Gymkos::class); 
    }
}