<?php

namespace App\Exports;

use App\Models\Survey;
use Maatwebsite\Excel\Concerns\FromQuery; // <-- Kita kembali pakai FromQuery
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SurveyExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    use Exportable;

    protected $month;
    protected $year;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    // --- QUERY DATA ---
    public function query()
    {
        // LOGIKA EKSPLISIT: Jika yang diminta 'all', keluarkan SEMUA data
        if ($this->year === 'all' || $this->month === 'all') {
            return Survey::query()->orderBy('created_at', 'desc');
        }

        // Jika bukan 'all', berarti ini dari form filter
        return Survey::query()
            ->whereYear('created_at', $this->year)
            ->whereMonth('created_at', $this->month)
            ->orderBy('created_at', 'desc');
    }

    // --- MAPPING ---
    public function map($survey): array
    {
        $promo = match ($survey->promo_interest) {
            'paket_a' => 'Paket A (6+2 Bulan)',
            'paket_b' => 'Paket B (9+3 Bulan)',
            'paket_c' => 'Paket C (12+4 Bulan)',
            default => 'Tidak Tertarik / Belum Memilih',
        };

        $statusMember = $survey->is_membership ? 'Member Gym' : 'Non-Member (Umum)';
        $duration = $survey->is_membership ? $survey->member_duration : '-';
        $chance = $survey->is_membership ? $survey->renewal_chance . '/5' : '-';

        // SOLUSI UTAMA: Cek apakah created_at null atau tidak sebelum diformat
        $waktuSubmit = $survey->created_at ? $survey->created_at->format('d/m/Y H:i') : 'Tanggal Tidak Diketahui';

        return [
            $waktuSubmit,                             // A. Aman dari error format() on null
            $survey->name ?? '-',                     // B. Tambahan ?? '-' agar aman dari data kosong
            $survey->phone ?? '-',                    // C. WhatsApp
            $statusMember,                            // D. Member Status
            $duration,                                // E. Join Duration
            $chance,                                  // F. Renewal Chance
            $survey->fitness_goal ?? '-',             // G. Goal
            $survey->rating_equipment ?? '-',         // H. Equipment Rating
            $survey->rating_cleanliness ?? '-',       // I. Cleanliness Rating
            $survey->nps_score ?? '-',                // J. NPS Score
            $promo,                                   // K. Promo Interest
            $survey->feedback ?? '-',                 // L. Feedback/Comments
        ];
    }

    // --- HEADINGS ---
    public function headings(): array
    {
        return [
            'Waktu Submit',        // Submission Time
            'Nama Responden',      // Respondent Name
            'No. WhatsApp',        // Phone
            'Status Keanggotaan',  // Membership Status
            'Lama Bergabung',      // Duration
            'Peluang Renewal',     // Renewal Chance
            'Tujuan Fitness',      // Fitness Goal
            'Rating Alat',         // Equipment Rating
            'Rating Kebersihan',   // Cleanliness Rating
            'NPS Score (1-10)',    // NPS
            'Minat Promo',         // Promo Interest
            'Feedback / Masukan',  // Feedback
        ];
    }

    // --- STYLING ---
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['argb' => 'FFFFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF9C27B0'],
                ],
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }
}
