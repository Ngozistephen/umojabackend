<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class VendorSetupAccountMail extends Mailable
{
    use Queueable, SerializesModels;

    // public $passwordSetupUrl;
    public $user;
    public $verificationCode;
    // public $vendorFirstName;
    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $verificationCode)
    {
        $this->user = $user;
        $this->verificationCode = $verificationCode;

     
    }
   

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to Umoja E-commerce Platform',
        );
    }

    /**
     * Get the message content definition.
     */
        public function content(): Content
        {
            return new Content(
                view: 'emails.vendor_setup_account_email',
                with: [
                    'userName' => $this->user->first_name,
                     'verificationCode' => $this->verificationCode,

                        
                   
                  
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
