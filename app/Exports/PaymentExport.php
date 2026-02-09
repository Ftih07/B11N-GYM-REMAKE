<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\Storage;

class PaymentExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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
    // Fetches payment records filtered by transaction date
    public function query()
    {
        return Payment::query()
            ->with('member') // Eager load 'member' relationship to avoid N+1 queries
            ->whereYear('created_at', $this->year)  // Filter Year
            ->whereMonth('created_at', $this->month) // Filter Month
            ->orderBy('created_at', 'desc'); // Sort by newest transaction
    }

    // --- MAPPING ---
    // Formats each row for the Excel report
    public function map($payment): array
    {
        // Logic: Translate Payment Status to Indonesian/Readable format
        $status = match ($payment->status) {
            'pending' => 'MENUNGGU VERIFIKASI',
            'confirmed' => 'LUNAS (CONFIRMED)',
            'rejected' => 'DITOLAK',
            default => strtoupper($payment->status),
        };

        // Logic: Determine User Type (New vs Renewal)
        // If member_id exists, it's a renewal. If null, it's a new registration.
        $tipeUser = $payment->member_id
            ? 'Member Lama (Perpanjang)'
            : 'Pendaftar Baru';

        // Logic: Generate Full URL for Payment Proof Image
        // Uses Laravel's Storage facade to create a clickable link
        $buktiBayar = $payment->image
            ? url(Storage::url($payment->image))
            : 'Tidak ada bukti';

        // Logic: Determine Name Source
        // Prioritize Member Name if linked, otherwise use the name from the payment form
        $nama = $payment->member_id && $payment->member
            ? $payment->member->name
            : $payment->name;

        return [
            $payment->created_at->format('d/m/Y H:i'), // Transaction Date
            $payment->order_id,                        // Order ID (Unique)
            $nama,                                     // Payer Name
            $tipeUser,                                 // User Type
            $payment->membership_type,                 // Package Name
            $payment->payment,                         // Payment Method
            $payment->phone,                           // WhatsApp Number
            $status,                                   // Payment Status
            $buktiBayar,                               // Proof of Payment Link
        ];
    }

    // --- HEADINGS ---
    // Defines the column titles
    public function headings(): array
    {
        return [
            'Waktu Transaksi',      // Transaction Time
            'Order ID',             // Unique Order ID
            'Nama Member/Pendaftar', // Payer Name
            'Tipe Pendaftar',       // Registration Type
            'Paket Membership',     // Membership Package
            'Metode Pembayaran',    // Payment Method
            'No. WhatsApp',         // Contact
            'Status',               // Status
            'Link Bukti Transfer',  // Proof Link
        ];
    }

    // --- STYLING ---
    // Styles the header row with BLUE background (Financial theme)
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
                    'startColor' => ['argb' => 'FF2196F3'], // MATERIAL BLUE Background
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Center align
                ],
            ],
        ];
    }
}
