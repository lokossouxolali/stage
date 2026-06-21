<?php

namespace App\Notifications;

use App\Models\PropositionTheme;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PropositionThemeSubmittedNotification extends Notification
{
    use Queueable;

    public function __construct(private PropositionTheme $proposition)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nouvelle proposition de thème à examiner')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Nom de l’étudiant : ' . $this->proposition->etudiant->name)
            ->line('Titre du thème : ' . $this->proposition->titre)
            ->line('Description : ' . $this->proposition->description)
            ->line('Date de soumission : ' . $this->proposition->date_soumission->format('d/m/Y H:i'))
            ->action('Voir la proposition', route('propositions.show', $this->proposition))
            ->line('Merci de traiter cette proposition depuis la plateforme.');
    }
}
