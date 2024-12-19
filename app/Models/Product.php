<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'description',
        'price',
        'status',
        'image',
        'gymkos_id',
        'stores_id',
        'category_products_id',
        'serving_option',
        'flavour'
    ];

    public function gymkos()
    {
        return $this->belongsTo(Gymkos::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'stores_id');
    }

    public function categoryproduct()
    {
        return $this->belongsTo(CategoryProduct::class, 'category_products_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'ready');
    }
}
