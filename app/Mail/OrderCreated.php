<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderCreated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Order $order;
    public string $recipientType; // 'customer' or 'admin'

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, string $recipientType = 'customer')
    {
        $this->order = $order;
        $this->recipientType = $recipientType;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->recipientType === 'customer'
            ? 'Konfirmasi Pesanan - ' . $this->order->order_number
            : 'Pesanan Baru Menunggu Approval - ' . $this->order->order_number;

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order-created',
            with: [
                'order' => $this->order,
                'recipientType' => $this->recipientType,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
