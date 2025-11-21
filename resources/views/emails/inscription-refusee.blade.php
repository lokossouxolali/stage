<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Refusée</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #dc3545;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 5px 5px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 12px;
        }
        .info-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>❌ Inscription Refusée</h1>
    </div>
    <div class="content">
        <p>Bonjour <strong>{{ $user->name }}</strong>,</p>
        
        <p>Nous vous informons que votre demande d'inscription sur la plateforme de gestion de stages a été <strong>refusée</strong> par l'administrateur.</p>
        
        <div class="info-box">
            <p><strong>Informations sur votre compte :</strong></p>
            <ul>
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
        </div>
        
        <p>Si vous pensez qu'il s'agit d'une erreur ou si vous souhaitez obtenir plus d'informations concernant ce refus, nous vous invitons à contacter l'administrateur de la plateforme.</p>
        
        <p>Vous pouvez également soumettre une nouvelle demande d'inscription si vous le souhaitez.</p>
        
        <p style="margin-top: 30px;">Cordialement,<br>
        <strong>L'équipe de gestion des stages</strong></p>
    </div>
    
    <div class="footer">
        <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
    </div>
</body>
</html>




