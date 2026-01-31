<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute; // <--- Ini jawaban pertanyaanmu

class Member extends Model
{
    protected $guarded = [];

    protected $casts = [
        'face_descriptor' => 'array', // Auto convert JSON ke Array saat diambil
        'join_date' => 'date',
        'membership_end_date' => 'date', // Pastikan dicast ke date
    ];

    public function gymkos(): BelongsTo
    {
        return $this->belongsTo(Gymkos::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Measurements
    public function measurements()
    {
        return $this->hasMany(MemberMeasurement::class);
    }

    // --- MUTATOR PICTURE ---
    protected function picture(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                // 1. Cek apakah yang masuk adalah data Base64 gambar?
                //    (Kalau cuma update nama member dan foto ga diganti, ini bakal false)
                if (is_string($value) && Str::startsWith($value, 'data:image')) {

                    // 2. Bersihkan header base64 (contoh: "data:image/jpeg;base64,")
                    //    Kita pakai regex sederhana biar aman
                    $image = preg_replace('/^data:image\/\w+;base64,/', '', $value);

                    // 3. Decode teks panjang itu jadi file binary asli
                    $image = base64_decode($image);

                    // 4. Buat nama file acak biar ga bentrok
                    $filename = 'member-photos/' . Str::random(40) . '.jpg';

                    // 5. Simpan file asli ke folder storage/app/public/member-photos
                    Storage::disk('public')->put($filename, $image);

                    // 6. Kembalikan PATH-nya saja ke database (cuma sekitar 50 karakter)
                    return $filename;
                }

                // Kalau bukan base64 (misal null atau path lama), biarkan apa adanya
                return $value;
            }
        );
    }

    // Opsi B (Rekomendasi): Event 'Booted'
    // Setiap kali data member diambil (retrieved) dari DB, kita cek expired-nya.
    // Jika expired tapi status masih active, kita update di DB saat itu juga.
    protected static function booted()
    {
        static::retrieved(function ($member) {
            if ($member->membership_end_date && $member->status === 'active') {
                if ($member->membership_end_date->endOfDay()->isPast()) {
                    // Update status diam-diam tanpa mentrigger event 'saved' lagi
                    $member->status = 'inactive';
                    $member->saveQuietly();
                }
            }
        });
    }
}
