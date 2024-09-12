<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;

class RecoveryPasswordMailable extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct()
    {
        // You can pass any data needed for the email here
    }

    /**
     * Get the message envelope definition.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Password Recovery',
        );
    }

    /**
     * Get the message content definition.
     * @return Content;
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.recoveryPasswordEmail',
        );
    }

    /**
     * Get the attachments for the message.
     * @return array[]
     */
    public function attachments(): array
    {
        return [];
    }
}
