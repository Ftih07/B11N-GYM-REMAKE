<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str; // <--- Jangan lupa import ini

class Blog extends Model
{
    protected $guarded = [];

    // Ini fungsi ajaibnya
    protected static function booted()
    {
        // Event saat "Creating" (Sedang dibuat)
        static::creating(function ($blog) {
            // Otomatis bikin slug dari title
            $blog->slug = Str::slug($blog->title);
        });

        // Opsional: Kalau kamu edit title, slug ikutan berubah
        static::updating(function ($blog) {
            if ($blog->isDirty('title')) {
                $blog->slug = Str::slug($blog->title);
            }
        });
    }   

    public function scopePublished($query)
    {
        return $query->where('status', 'publish');
    }
}
