<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trainer extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'urls',
        'image',
        'gymkos_id',
    ];

    protected $casts = [
        'urls' => 'array', // Cast kolom JSON menjadi array
    ];
    
    public function gymkos()
    {
        return $this->belongsTo(Gymkos::class);
    }
}
