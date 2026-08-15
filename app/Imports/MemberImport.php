<?php

namespace App\Imports;

use App\Models\Member;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class MemberImport implements ToModel, WithBatchInserts, WithChunkReading, WithStartRow
{
    protected $gymkosId;

    public function __construct($gymkosId = 2)
    {
        $this->gymkosId = $gymkosId;
    }

    public function startRow(): int
    {
        return 2; // Data dimulai dari baris ke-2
    }

    public function model(array $row)
    {
        // 1. Skip jika NAMA (Index 2) kosong
        if (empty($row[2])) {
            return null;
        }

        // 2. Format TGL JOIN (Index 4)
        $joinDate = $this->parseDate($row[4]);
        if (! $joinDate) {
            $joinDate = Carbon::now()->format('Y-m-d');
        }

        // 3. Logika Menentukan Membership End Date
        $membershipEndDate = null;

        // A. Cek dulu kolom END DATE (Index 1) di Excel
        if (! empty($row[1])) {
            $membershipEndDate = $this->parseDate($row[1]);
        }

        // B. Jika END DATE kosong, lakukan fallback cek kolom bulan dari Kanan ke Kiri
        if (! $membershipEndDate) {
            $kolomMulaiBulan = 5; // Index 5 = DESEMBER 2023
            $totalKolom = count($row);
            $lastActiveIndex = null;

            for ($i = $totalKolom - 1; $i >= $kolomMulaiBulan; $i--) {
                if (isset($row[$i]) && ! empty(trim($row[$i]))) {
                    $lastActiveIndex = $i;
                    break;
                }
            }

            if ($lastActiveIndex !== null) {
                $selisihBulan = $lastActiveIndex - $kolomMulaiBulan;

                // --- PENYESUAIAN TANGGAL JOIN ---
                // Ambil tanggal (day) dari join_date (misal: 15, 22, dll)
                $dayOfJoin = Carbon::parse($joinDate)->day;

                // Mulai dari Desember 2023 dengan tanggal sesuai TGL JOIN
                // Ditambah selisih bulan + 1 bulan aktif
                $membershipEndDate = Carbon::create(2023, 12, $dayOfJoin)
                    ->addMonths($selisihBulan + 1)
                    ->format('Y-m-d');
            }
        }

        // 4. Set Status (active/inactive) berdasarkan end_date
        $status = 'active';
        if ($membershipEndDate && Carbon::parse($membershipEndDate)->endOfDay()->isPast()) {
            $status = 'inactive';
        }

        // 5. Simpan ke Database
        return new Member([
            'gymkos_id' => $this->gymkosId,
            'member_code' => trim($row[0] ?? ''),
            'name' => trim($row[2]),
            'phone' => ! empty(trim($row[3] ?? '')) ? trim($row[3]) : null,
            'join_date' => $joinDate,
            'membership_end_date' => $membershipEndDate,
            'status' => $status,
        ]);
    }

    private function parseDate($value)
    {
        if (! $value) {
            return null;
        }
        $value = trim($value);

        try {
            if (is_numeric($value)) {
                return Date::excelToDateTimeObject($value)->format('Y-m-d');
            }

            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    public function batchSize(): int
    {
        return 500;
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
