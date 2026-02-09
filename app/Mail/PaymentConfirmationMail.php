<?php

namespace App\Mail;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class PaymentConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $payment;
    public $membershipImage; // Variable for dynamic image path

    // Constructor: Setup data before sending
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;

        // Logic: Select Image based on Membership Type
        $images = [
            'Member Harian' => 'images/harian.png',
            'Member Mingguan' => 'images/mingguan.png',
            'Member Bulanan' => 'images/bulanan.png',
        ];

        // Set image path, fallback to 'default.png' if type not found
        $this->membershipImage = $images[$payment->membership_type] ?? 'images/default.png';
    }

    // Build the email message
    public function build()
    {
        return $this->subject('Konfirmasi Pembayaran')
            ->view('emails.payment_confirmation')
            ->with(['membershipImage' => $this->membershipImage]); // Pass custom variable to view
    }
}
