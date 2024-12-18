<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryTraining extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'title',
        'gymkos_id'
    ];

    public function gymkos()
    {
        return $this->belongsTo(Gymkos::class);
    }

    public function TrainingProgram()
    {
        return $this->belongsTo(TrainingProgram::class);
    }
}
