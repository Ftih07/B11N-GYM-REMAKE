<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Testimoni;
use Illuminate\Support\Facades\File;

class TestimoniSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil file JSON dari public
        $jsonPath = public_path('testimoni_kinggym.json');

        if (!File::exists($jsonPath)) {
            dd("File JSON tidak ditemukan di: " . $jsonPath);
        }

        $jsonData = File::get($jsonPath);
        $testimonis = json_decode($jsonData, true);

        if ($testimonis === null) {
            dd("JSON tidak valid atau kosong!");
        }

        foreach ($testimonis as $data) {
            Testimoni::create([
                'name' => $data['name'] ?? 'Anonim',
                'description' => $data['text'] ?? 'Tidak ada deskripsi.',
                'image' => $data['reviewerPhotoUrl'] ?? null, // Gunakan foto reviewer jika ada
                'gymkos_id' => 2, // Default gymkos_id = 1
                'rating' => $data['stars'] ?? 0
            ]);
        }

        echo "Seeder berhasil dijalankan!";
    }
}
