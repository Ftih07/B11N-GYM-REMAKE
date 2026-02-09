<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Equipment extends Model
{
    protected $table = 'equipments';
    protected $guarded = ['id'];

    protected static function booted()
    {
        // On Create: Generate slug from name
        static::creating(function ($equipment) {
            $equipment->slug = Str::slug($equipment->name);
        });

        // On Update: Regenerate slug only if name changes
        static::updating(function ($equipment) {
            if ($equipment->isDirty('name')) {
                $equipment->slug = Str::slug($equipment->name);
            }
        });
    }

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
