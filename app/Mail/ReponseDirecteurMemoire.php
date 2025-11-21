<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReponseDirecteurMemoire extends Mailable
{
    use Queueable, SerializesModels;

    public $etudiant;
    public $directeur;
    public $accepte;
    public $raison;

    /**
     * Create a new message instance.
     */
    public function __construct(User $etudiant, User $directeur, bool $accepte, ?string $raison = null)
    {
        $this->etudiant = $etudiant;
        $this->directeur = $directeur;
        $this->accepte = $accepte;
        $this->raison = $raison;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->accepte ? 'Votre demande d\'encadrement a été acceptée' : 'Votre demande d\'encadrement a été refusée',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reponse-directeur-memoire',
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
