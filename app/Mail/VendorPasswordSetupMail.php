<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VendorPasswordSetupMail extends Mailable
{
    use Queueable, SerializesModels;

    public $passwordSetupUrl;
    // public $vendorFirstName;
    /**
     * Create a new message instance.
     */
    public function __construct($passwordSetupUrl)
    {
        $this->passwordSetupUrl = $passwordSetupUrl;
        // $this->$vendorFirstName = $vendorFirstName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Vendor Password Setup Mail',
        );
    }

    /**
     * Get the message content definition.
     */
        public function content(): Content
        {
            return new Content(
                view: 'emails.vendor_password_setup',
                with: [
                    'passwordSetupUrl' => $this->passwordSetupUrl,
                    // 'vendor_name' => $this->vendorFirstName,
                  
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
