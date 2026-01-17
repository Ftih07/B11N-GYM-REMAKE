<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'booking_kost';
    
    use HasFactory;
    protected $fillable = ['name', 'email', 'phone', 'date', 'room_type', 'payment', 'payment_proof', 'status'];
}
