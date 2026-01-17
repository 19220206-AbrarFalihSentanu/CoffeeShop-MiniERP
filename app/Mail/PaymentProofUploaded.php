<?php
// File: app/Mail/PaymentProofUploaded.php
// Jalankan: php artisan make:mail PaymentProofUploaded

namespace App\Mail;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentProofUploaded extends Mailable implements ShouldQueue
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
            subject: 'Bukti Pembayaran Diterima - Order ' . $this->payment->order->order_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-proof-uploaded',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
