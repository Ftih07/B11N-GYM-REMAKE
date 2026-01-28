<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subheading', // TAMBAHAN BARU
        'location',   // TAMBAHAN BARU
        'description',
        'image',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'stores_id');
    }
}