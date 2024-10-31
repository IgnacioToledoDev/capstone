<?php

namespace App\Mail;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationMaintenanceMailable extends Mailable
{
    use Queueable;
    use SerializesModels;

    public User $mechanic;
    public Reservation $reservation;
    public User $client;

    public function __construct($mechanic, $reservation, $client)
    {
        $this->reservation = $reservation;
        $this->mechanic = $mechanic;
        $this->client = $client;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Felicidades, tienes una reservación',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reservationMaintenanceEmail',
            with: [
                'mechanicName' => $this->mechanic->getFullNameAttribute(),
                'reservationDate' => $this->reservation->date_reservation->format('d/m/Y'),
                'reservationTime' => $this->reservation->date_reservation->format('H:i'),
                'clientName' => $this->client->getFullNameAttribute(),
                'clientPhone' => $this->client->phone,
                'clientEmail' => $this->client->email
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
