<?php

namespace App\Models;

use App\Support\PrivateMedia;
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

    public function renewals(): HasMany
    {
        return $this->hasMany(MembershipRenewal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** URL foto wajah lewat route terlindungi (khusus admin), atau null. */
    protected function photoUrl(): Attribute
    {
        return Attribute::get(fn () => $this->picture ? route('media.member-photo', $this) : null);
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

                    // 2. Decode, dan pastikan memang gambar JPEG/PNG (SEC-17)
                    $image = base64_decode($image, true);
                    $info = $image !== false ? @getimagesizefromstring($image) : false;
                    if (! $info || ! in_array($info['mime'] ?? null, ['image/jpeg', 'image/png'], true)) {
                        return $this->getRawOriginal('picture'); // abaikan data rusak, foto lama dipertahankan
                    }

                    // 3. Create unique filename
                    $filename = 'member-photos/' . Str::random(40) . '.jpg';

                    // 4. Simpan di disk privat — tidak bisa dibuka lewat /storage (SEC-10)
                    Storage::disk(PrivateMedia::DISK)->put($filename, $image);

                    // 5. Return only the path to DB
                    return $filename;
                }

                // If not Base64 (e.g. existing path), leave it alone
                return $value;
            }
        );
    }

    // Status kedaluwarsa (active → inactive) kini diperbarui massal oleh
    // App\Services\MembershipStatusService, bukan lagi saat data dibaca (PERF-05).
}
