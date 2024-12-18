<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Blog extends Model
{
    //
    use HasFactory;

    protected $fillable = ['title', 'content', 'image', 'status', 'gymkos_id'];

    public function gymkos()
    {
        return $this->belongsTo(Gymkos::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'publish');
    }
}
