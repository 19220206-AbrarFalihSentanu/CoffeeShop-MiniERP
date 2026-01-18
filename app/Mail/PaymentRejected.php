<?php
// File: app/Mail/PaymentRejected.php
// Jalankan: php artisan make:mail PaymentRejected

namespace App\Mail;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentRejected extends Mailable 
{
    use Queueable, SerializesModels;

    public Payment $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pembayaran Ditolak - Order ' . $this->payment->order->order_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-rejected',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

