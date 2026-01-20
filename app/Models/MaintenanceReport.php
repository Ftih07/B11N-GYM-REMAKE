<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceReport extends Model
{
    protected $table = 'maintenance_reports';
    protected $guarded = ['id'];

    protected $casts = [
        'fixed_at' => 'datetime',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class, 'equipment_id');
    }

    public function gymkos()
    {
        return $this->belongsTo(Gymkos::class);
    }
}
