<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimoni extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'gymkos_id',
        'rating',
    ];
    
    public function gymkos()
    {
        return $this->belongsTo(Gymkos::class);
    }
}
