<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class About extends Model
{
    //
    use HasFactory;

    protected $fillable = ['title', 'description', 'image', 'gymkos_id'];

    public function gymkos()
    {
        return $this->belongsTo(Gymkos::class);
    }
}
