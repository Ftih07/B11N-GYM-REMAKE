<?php

namespace App\Exports;

use App\Models\Survey;
use Maatwebsite\Excel\Concerns\FromQuery;
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

    public function query()
    {
        return Survey::query()
            ->whereYear('created_at', $this->year)
            ->whereMonth('created_at', $this->month)
            ->orderBy('created_at', 'desc');
    }

    public function map($survey): array
    {
        // 1. Translate Promo Interest
        $promo = match ($survey->promo_interest) {
            'paket_a' => 'Paket A (6+2 Bulan)',
            'paket_b' => 'Paket B (9+3 Bulan)',
            'paket_c' => 'Paket C (12+4 Bulan)',
            default => 'Tidak Tertarik / Belum Memilih',
        };

        // 2. Logic Status Member
        $statusMember = $survey->is_membership ? 'Member Gym' : 'Non-Member (Umum)';

        // 3. Logic Data Khusus Member (Biar rapi kalau null)
        $duration = $survey->is_membership ? $survey->member_duration : '-';
        $chance = $survey->is_membership ? $survey->renewal_chance . '/5' : '-';

        return [
            $survey->created_at->format('d/m/Y H:i'), // A. Waktu Isi
            $survey->name,                            // B. Nama
            $survey->phone,                           // C. WhatsApp
            $statusMember,                            // D. Status
            $duration,                                // E. Lama Join (Member Only)
            $chance,                                  // F. Peluang Perpanjang (Member Only)
            $survey->fitness_goal,                    // G. Tujuan
            $survey->rating_equipment,                // H. Rating Alat
            $survey->rating_cleanliness,              // I. Rating Kebersihan
            $survey->nps_score,                       // J. NPS
            $promo,                                   // K. Minat Promo
            $survey->feedback,                        // L. Masukan
        ];
    }

    public function headings(): array
    {
        return [
            'Waktu Submit',
            'Nama Responden',
            'No. WhatsApp',
            'Status Keanggotaan',
            'Lama Bergabung',
            'Peluang Renewal',
            'Tujuan Fitness',
            'Rating Alat',
            'Rating Kebersihan',
            'NPS Score (1-10)',
            'Minat Promo',
            'Feedback / Masukan',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Header: Background UNGU, Text Putih
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['argb' => 'FFFFFFFF'], // Putih
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF9C27B0'], // BACKGROUND UNGU
                ],
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }
}
