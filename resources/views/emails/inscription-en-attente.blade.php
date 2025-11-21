<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription en Attente</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .content {
            background-color: #f8f9fa;
            padding: 30px;
            border-radius: 5px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="content">
        <p>Bonjour <strong>{{ $user->name }}</strong>,</p>
        
        <p>Nous avons bien reçu votre demande d'inscription sur la plateforme de gestion de stages.</p>
        
        <p>Votre inscription est actuellement <strong>en attente de validation</strong> par un administrateur. Vous recevrez un email de confirmation une fois que votre compte aura été validé.</p>
        
        <p><strong>Informations de votre inscription :</strong></p>
        <ul>
            <li><strong>Nom :</strong> {{ $user->name }}</li>
            <li><strong>Email :</strong> {{ $user->email }}</li>
            <li><strong>Rôle demandé :</strong> 
                @switch($user->role)
                    @case('etudiant')
                        Étudiant
                        @break
                    @case('entreprise')
                        Entreprise
                        @break
                    @case('enseignant')
                        Enseignant
                        @break
                    @default
                        {{ ucfirst($user->role) }}
                @endswitch
            </li>
        </ul>
        
        <p><strong>Prochaines étapes :</strong></p>
        <ol>
            <li>Un administrateur va examiner votre demande d'inscription</li>
            <li>Vous recevrez un email de confirmation une fois votre compte validé</li>
            <li>Vous pourrez alors vous connecter à la plateforme</li>
        </ol>
        
        <p>Le délai de traitement est généralement de 24 à 48 heures. Si vous n'avez pas reçu de réponse après ce délai, n'hésitez pas à nous contacter.</p>
        
        <p style="margin-top: 30px;">Cordialement,<br>
        <strong>L'équipe de gestion des stages</strong></p>
    </div>
    
    <div class="footer">
        <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
    </div>
</body>
</html>

