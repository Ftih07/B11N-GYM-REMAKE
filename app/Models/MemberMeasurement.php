<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberMeasurement extends Model
{
    use HasFactory;

    protected $guarded = ['id']; // Semua field boleh diisi kecuali ID

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}