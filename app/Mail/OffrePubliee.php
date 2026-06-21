<?php

namespace App\Mail;

use App\Models\Offre;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OffrePubliee extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Offre $offre,
        public User $destinataire
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouvelle offre de stage publiée',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.offre-publiee',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
