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

    // --- CONSTRUCTOR ---
    // Initializes the export class with the selected period
    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    // --- QUERY DATA ---
    // Fetches survey data filtered by submission date
    public function query()
    {
        return Survey::query()
            ->whereYear('created_at', $this->year)  // Filter Year
            ->whereMonth('created_at', $this->month) // Filter Month
            ->orderBy('created_at', 'desc'); // Sort by newest submission
    }

    // --- MAPPING ---
    // Formats each row for the Excel report
    public function map($survey): array
    {
        // Logic: Translate Promo Interest Codes to Readable Text
        $promo = match ($survey->promo_interest) {
            'paket_a' => 'Paket A (6+2 Bulan)',
            'paket_b' => 'Paket B (9+3 Bulan)',
            'paket_c' => 'Paket C (12+4 Bulan)',
            default => 'Tidak Tertarik / Belum Memilih',
        };

        // Logic: Determine Member Status
        $statusMember = $survey->is_membership ? 'Member Gym' : 'Non-Member (Umum)';

        // Logic: Handle Data Specific to Members
        // If it's a non-member, fill Duration and Renewal Chance with '-'
        $duration = $survey->is_membership ? $survey->member_duration : '-';
        $chance = $survey->is_membership ? $survey->renewal_chance . '/5' : '-';

        return [
            $survey->created_at->format('d/m/Y H:i'), // A. Submission Time
            $survey->name,                            // B. Name
            $survey->phone,                           // C. WhatsApp
            $statusMember,                            // D. Member Status
            $duration,                                // E. Join Duration (Member only)
            $chance,                                  // F. Renewal Chance (Member only)
            $survey->fitness_goal,                    // G. Goal
            $survey->rating_equipment,                // H. Equipment Rating
            $survey->rating_cleanliness,              // I. Cleanliness Rating
            $survey->nps_score,                       // J. NPS Score
            $promo,                                   // K. Promo Interest
            $survey->feedback,                        // L. Feedback/Comments
        ];
    }

    // --- HEADINGS ---
    // Defines the column titles
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
    // Styles the header row with PURPLE background
    public function styles(Worksheet $sheet)
    {
        return [
            // Target Row 1
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['argb' => 'FFFFFFFF'], // White Text
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF9C27B0'], // PURPLE Background
                ],
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }
}
