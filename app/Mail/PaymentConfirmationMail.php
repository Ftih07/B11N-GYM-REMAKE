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
    public $membershipImage;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;

        // Tentukan gambar berdasarkan membership
        $images = [
            'Member Harian' => 'images/harian.png',
            'Member Mingguan' => 'images/mingguan.png',
            'Member Bulanan' => 'images/bulanan.png',
        ];

        $this->membershipImage = $images[$payment->membership_type] ?? 'images/default.png';
    }

    public function build()
    {
        return $this->subject('Konfirmasi Pembayaran')
            ->view('emails.payment_confirmation')
            ->with(['membershipImage' => $this->membershipImage]);
    }
}
