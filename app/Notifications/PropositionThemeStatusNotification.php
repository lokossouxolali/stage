<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PropositionTheme;

class PropositionThemeStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $proposition;
    protected $statut;
    protected $commentaires;

    /**
     * Create a new notification instance.
     */
    public function __construct(PropositionTheme $proposition, string $statut, ?string $commentaires = null)
    {
        $this->proposition = $proposition;
        $this->statut = $statut;
        $this->commentaires = $commentaires;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $proposition = $this->proposition;
        $statut = $this->statut;
        $commentaires = $this->commentaires;

        $subject = $statut === 'valide' ? 'Votre proposition de thème a été validée' : 'Votre proposition de thème a été rejetée';

        $mail = (new MailMessage)
            ->subject($subject)
            ->greeting('Bonjour ' . $notifiable->name . ',');

        if ($statut === 'valide') {
            $mail->line('Félicitations ! Votre proposition de thème "' . $proposition->titre . '" a été **validée**.')
                 ->line('Vous pouvez maintenant procéder aux prochaines étapes de votre projet de stage.');
        } else {
            $mail->line('Nous regrettons de vous informer que votre proposition de thème "' . $proposition->titre . '" a été **rejetée**.');
            if ($commentaires) {
                $mail->line('**Motif du rejet :**')
                     ->line($commentaires);
            }
            $mail->line('Vous pouvez soumettre une nouvelle proposition en modifiant votre demande actuelle ou en créant une nouvelle proposition.');
        }

        $mail->line('**Détails de votre proposition :**')
             ->line('**Titre :** ' . $proposition->titre)
             ->line('**Soumise le :** ' . $proposition->date_soumission->format('d/m/Y'))
             ->line('**Directeur de mémoire :** ' . ($proposition->directeurMemoire ? $proposition->directeurMemoire->name : 'Non assigné'))
             ->action('Voir ma proposition', route('propositions.show', $proposition->id))
             ->line('Si vous avez des questions, n\'hésitez pas à contacter votre directeur de mémoire ou l\'administration.')
             ->salutation('Cordialement,')
             ->salutation('L\'équipe de gestion des stages');

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
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
