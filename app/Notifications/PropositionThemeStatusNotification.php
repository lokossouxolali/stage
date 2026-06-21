<?php

namespace App\Notifications;

use App\Models\PropositionTheme;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PropositionThemeStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected PropositionTheme $proposition,
        protected string $statut,
        protected ?string $commentaires = null
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = match ($this->statut) {
            'valide_dm' => 'Votre proposition de thème a été validée par votre Directeur de Mémoire',
            'valide' => 'Votre thème a été validé',
            default => 'Votre thème a été rejeté',
        };

        $mail = (new MailMessage)
            ->subject($message)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line($message)
            ->line('Titre : ' . $this->proposition->titre);

        if (!in_array($this->statut, ['valide', 'valide_dm'], true) && $this->commentaires) {
            $mail->line('Motif : ' . $this->commentaires);
        }

        return $mail
            ->action('Voir ma proposition', route('propositions.show', $this->proposition->id))
            ->line('Merci de consulter la plateforme pour plus de détails.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'proposition_id' => $this->proposition->id,
            'titre' => $this->proposition->titre,
            'statut' => $this->statut,
            'commentaires' => $this->commentaires,
        ];
    }
}
