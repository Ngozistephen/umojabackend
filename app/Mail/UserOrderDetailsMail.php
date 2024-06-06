<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserOrderDetailsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $user;
    public $trackingNumber;
    /**
     * Create a new message instance.
     */
    public function __construct($order, $user, $trackingNumber)
    {
        $this->order = $order;
        $this->user = $user;
        $this->trackingNumber = $trackingNumber;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'User Order Details Mail',
        );

        
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {

        return new Content(
            view: 'emails.user_order_details',
            with: [
                'orderNumber' => $this->order->order_number,
                'trackingNumber' => $this->trackingNumber,
                'order' => $this->order,
                'user' => $this->user,                              
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
