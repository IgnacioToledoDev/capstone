<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;

class RecoveryPasswordMailable extends Mailable
{
    public function construct() {
        /** function not implemented yet */
    }

    public function envelope(): Envelope {
        return new Envelope(
            from: new Address('noreply@autominder.cl', 'Autominder'),
            subject: 'Recuperar contraseña'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.recoveryPasswordEmail',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
