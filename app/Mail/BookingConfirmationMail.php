<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking; // Variable to hold booking data

    // Constructor: Receive data from Controller
    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    // Build the email message
    public function build()
    {
        return $this->subject('Konfirmasi Booking Kost Istana Merdeka 3') // Email Subject
            ->view('emails.booking_confirmation'); // Load view: resources/views/emails/booking_confirmation.blade.php
    }
}
