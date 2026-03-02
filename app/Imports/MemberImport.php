<?php

namespace App\Imports;

use App\Models\Member;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class MemberImport implements ToModel, WithStartRow, WithBatchInserts, WithChunkReading
{
    protected $gymkosId;

    // Kita bikin constructor buat nerima ID cabang gym-nya (misal B11N Gym = 1)
    public function __construct($gymkosId = 2)
    {
        $this->gymkosId = $gymkosId;
    }

    public function startRow(): int
    {
        return 5; // Melewati 4 baris header yang ada di file URUT.csv
    }

    public function model(array $row)
    {
        // 1. Skip kalau kolom nama (Index 1) kosong
        if (empty($row[1])) {
            return null;
        }

        // 2. Format join_date (Wajib ada karena di DB Not Null)
        $joinDate = $this->parseDate($row[3]);
        if (!$joinDate) {
            // Kalau di excel kosong, kita kasih default hari ini biar ngga error database
            $joinDate = Carbon::now()->format('Y-m-d');
        }

        // 3. Logika mencari membership_end_date dari Kanan ke Kiri
        $kolomMulaiBulan = 4; // Index 4 = Desember 2023 di excel kamu
        $totalKolom = count($row);
        $lastActiveIndex = null;

        for ($i = $totalKolom - 1; $i >= $kolomMulaiBulan; $i--) {
            if (!empty($row[$i]) && trim($row[$i]) !== '') {
                $lastActiveIndex = $i;
                break;
            }
        }

        $membershipEndDate = null;
        if ($lastActiveIndex !== null) {
            $selisihBulan = $lastActiveIndex - $kolomMulaiBulan;
            $tanggalBayar = Carbon::parse($joinDate)->format('d');

            // Hitung membership_end_date: Mulai dari Desember 2023 ditambah selisih kolomnya
            // Ditambah 1 bulan karena diasumsikan bulan yang diceklis adalah bulan dia aktif, 
            // jadi inactive-nya di bulan berikutnya.
            $membershipEndDate = Carbon::create(2024, 11, $tanggalBayar)
                ->addMonths($selisihBulan + 1)
                ->format('Y-m-d');
        }

        // 4. Update status otomatis kalau end_date-nya sudah lewat
        $status = 'active';
        if ($membershipEndDate && Carbon::parse($membershipEndDate)->isPast()) {
            $status = 'inactive'; // atau 'non-active', sesuaikan dgn enum kamu
        }

        // 5. Insert ke tabel sesuai kolom database
        return new Member([
            'gymkos_id'           => $this->gymkosId,
            'name'                => $row[1],
            'phone'               => !empty(trim($row[2] ?? '')) ? trim($row[2]) : null,
            'join_date'           => $joinDate,
            'membership_end_date' => $membershipEndDate,
            'status'              => $status,
        ]);
    }

    private function parseDate($value)
    {
        if (!$value) return null;
        $value = trim($value);

        try {
            // 1. Cek kalau Excel ngirim format Angka Serial (misal: 45330)
            if (is_numeric($value)) {
                return Date::excelToDateTimeObject($value)->format('Y-m-d');
            }

            // 2. Pecah string tanggal berdasarkan garis miring (/) atau strip (-)
            $parts = preg_split('/[\/\-]/', $value);

            if (count($parts) === 3) {
                $day = $parts[0];
                $month = $parts[1];
                $year = $parts[2];

                // Cek kalau posisinya Y-m-d (misal: 2024-12-26), berarti angka pertama (day) itu 4 digit
                if (strlen($day) === 4) {
                    $year = $parts[0]; // Tahun aslinya di depan
                    $day = $parts[2];  // Tanggal aslinya di belakang
                }

                // Cek kalau tahunnya cuma 2 digit (misal: 23), kita sulap jadi 2023
                if (strlen($year) === 2) {
                    $year = "20" . $year;
                }

                // Gabungkan pakai Carbon biar divalidasi dan formatnya pasti Y-m-d
                return Carbon::createFromDate($year, $month, $day)->format('Y-m-d');
            }

            // 3. Fallback (cadangan) kalau ada teks format bahasa inggris dsb
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            // Kalau admin salah ketik parah di Excel (misal ngetik bulan 34), 
            // biarin aja jadi null biar proses import nggak berhenti / crash
            return null;
        }
    }

    public function batchSize(): int
    {
        return 100;
    }
    public function chunkSize(): int
    {
        return 100;
    }
}
