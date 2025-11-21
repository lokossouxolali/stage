<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Validée</title>
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
        
        <p>Nous avons le plaisir de vous informer que votre inscription sur la plateforme de gestion de stages a été <strong>validée</strong> par l'administrateur.</p>
        
        <p>Vous pouvez maintenant vous connecter à votre compte en utilisant les identifiants que vous avez fournis lors de votre inscription :</p>
        
        <ul>
            <li><strong>Email :</strong> {{ $user->email }}</li>
            <li><strong>Rôle :</strong> 
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
        
        <p>Vous pouvez maintenant accéder à toutes les fonctionnalités de la plateforme selon votre rôle.</p>
        
        <div style="text-align: center;">
            <a href="{{ url('/login') }}" class="button">Se connecter</a>
        </div>
        
        <p style="margin-top: 30px;">Si vous avez des questions, n'hésitez pas à nous contacter.</p>
        
        <p>Cordialement,<br>
        <strong>L'équipe de gestion des stages</strong></p>
    </div>
    
    <div class="footer">
        <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
    </div>
</body>
</html>



