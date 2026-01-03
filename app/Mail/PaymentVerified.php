<?php
// File: app/Mail/PaymentVerified.php
// Jalankan: php artisan make:mail PaymentVerified

namespace App\Mail;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentVerified extends Mailable
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
            subject: 'Pembayaran Diverifikasi - Order ' . $this->payment->order->order_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-verified',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
