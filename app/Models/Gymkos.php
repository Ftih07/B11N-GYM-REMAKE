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
        'address',
        'created_at',
    ];
}
