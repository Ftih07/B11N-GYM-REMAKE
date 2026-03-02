<?php

namespace App\Imports;

use App\Models\Survey;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date; 

class SurveyImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        // Cegah error jika baris kosong
        if (!isset($row[1])) {
            return null;
        }

        // --- 0. FIX LOGIKA TANGGAL (CREATED_AT) ---
        $rawDate = $row[0];
        $createdAt = now(); 

        if (!empty($rawDate)) {
            if (is_numeric($rawDate)) {
                $createdAt = Carbon::instance(Date::excelToDateTimeObject($rawDate));
            } else {
                try {
                    $createdAt = Carbon::parse(str_replace('/', '-', $rawDate));
                } catch (\Exception $e) {
                    $createdAt = now(); 
                }
            }
        }

        // --- 1. LOGIKA PROMO ---
        $promoText = strtolower($row[12] ?? '');
        $promoValue = 'none';
        if (str_contains($promoText, 'paket a')) {
            $promoValue = 'paket_a';
        } elseif (str_contains($promoText, 'paket b')) {
            $promoValue = 'paket_b';
        } elseif (str_contains($promoText, 'paket c')) {
            $promoValue = 'paket_c';
        }

        // --- 2. LOGIKA FEEDBACK ---
        $feedbackSatu = $row[8] ?? ''; 
        $feedbackDua = $row[11] ?? ''; 
        $gabunganFeedback = "Hal yang perlu dipertahankan/diperbaiki: {$feedbackSatu}\n\nKritik/Saran: {$feedbackDua}";

        // --- 3. MAPPING DATA KE DATABASE ---
        return new Survey([
            'created_at'         => $createdAt, 
            'updated_at'         => $createdAt, 

            // Kolom 1, 2, 3: Biodata
            'name'               => $row[1],
            'email'              => $row[2],
            'phone'              => $row[3],

            // Kolom 4: Lama member
            'is_membership'      => !empty($row[4]) ? true : false,
            'member_duration'    => $row[4] ?? null,

            // Kolom 5: Tujuan Fitness
            'fitness_goal'       => $row[5],

            // Kolom 6, 7: Rating Alat & Kebersihan
            'rating_equipment'   => (int) ($row[6] ?? 0),
            'rating_cleanliness' => (int) ($row[7] ?? 0),

            // Kolom 9: Kemungkinan perpanjang (1-5)
            'renewal_chance'     => !empty($row[9]) ? (int) $row[9] : null,

            // Kolom 10: NPS Score
            'nps_score'          => (int) ($row[10] ?? 0),

            // Feedback
            'feedback'           => trim($gabunganFeedback),

            // Promo
            'promo_interest'     => $promoValue,
        ]);
    }
}
