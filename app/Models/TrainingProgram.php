<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingProgram extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'title',
        'gymkos_id',
        'description',
        'image',
        'category_trainings_id'
    ];

    public function gymkos()
    {
        return $this->belongsTo(Gymkos::class);
    }

    public function categorytraining()
    {
        return $this->belongsTo(CategoryTraining::class, 'category_trainings_id');
    }
    
}
