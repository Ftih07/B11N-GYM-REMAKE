<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'gymkos_id'
    ];

    public function gymkos()
    {
        return $this->belongsTo(Gymkos::class);
    }
}
