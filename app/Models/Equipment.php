<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Equipment extends Model
{
    protected $table = 'equipments';
    protected $guarded = ['id'];

    public function gallery(): HasMany
    {
        return $this->hasMany(GalleryEquipment::class, 'equipment_id');
    }

    public function maintenanceReports(): HasMany
    {
        return $this->hasMany(MaintenanceReport::class, 'equipment_id');
    }

    public function gymkos(): BelongsTo
    {
        return $this->belongsTo(Gymkos::class);
    }
}
