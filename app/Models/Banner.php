<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'title',
        'subheading',
        'description',
        'location',
        'image',
        'gymkos_id',
        'stores_id'
    ];

    public function gymkos()
    {
        return $this->belongsTo(Gymkos::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'stores_id');
    }
}
