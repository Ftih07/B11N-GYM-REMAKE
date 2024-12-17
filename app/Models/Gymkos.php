<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gymkos extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'name',
        'created_at',
    ];

    public function logos()
    {
        return $this->hasMany(Logo::class);
    }
}
