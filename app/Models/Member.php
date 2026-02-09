<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Member extends Model
{
    protected $guarded = [];

    protected $casts = [
        'face_descriptor' => 'array', // Convert JSON to Array automatically
        'join_date' => 'date',
        'membership_end_date' => 'date',
    ];

    public function gymkos(): BelongsTo
    {
        return $this->belongsTo(Gymkos::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function measurements()
    {
        return $this->hasMany(MemberMeasurement::class);
    }

    // --- MUTATOR: Handle Webcam Base64 Image ---
    // Automatically decodes Base64 string to an image file when saving 'picture'
    protected function picture(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                // Check if value is a Base64 string
                if (is_string($value) && Str::startsWith($value, 'data:image')) {

                    // 1. Clean header
                    $image = preg_replace('/^data:image\/\w+;base64,/', '', $value);

                    // 2. Decode
                    $image = base64_decode($image);

                    // 3. Create unique filename
                    $filename = 'member-photos/' . Str::random(40) . '.jpg';

                    // 4. Save to Storage
                    Storage::disk('public')->put($filename, $image);

                    // 5. Return only the path to DB
                    return $filename;
                }

                // If not Base64 (e.g. existing path), leave it alone
                return $value;
            }
        );
    }

    // --- AUTO-EXPIRE CHECK ---
    // Check expiration every time member data is retrieved
    protected static function booted()
    {
        static::retrieved(function ($member) {
            if ($member->membership_end_date && $member->status === 'active') {
                if ($member->membership_end_date->endOfDay()->isPast()) {
                    // Update status silently (without triggering 'saved' events)
                    $member->status = 'inactive';
                    $member->saveQuietly();
                }
            }
        });
    }
}
