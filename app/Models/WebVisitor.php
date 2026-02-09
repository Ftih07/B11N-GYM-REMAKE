<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebVisitor extends Model
{
    use HasFactory;

    // Allow mass assignment for these fields (Security)
    protected $fillable = [
        'ip_address',
        'visit_date',
        'user_agent',
    ];

    // Data Casting
    // Automatically converts 'visit_date' to a Carbon object when retrieved
    protected $casts = [
        'visit_date' => 'date',
    ];
}
