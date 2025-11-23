<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réponse à votre candidature</title>
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
            background-color: #e53e3e;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f7fafc;
            padding: 30px;
            border: 1px solid #e2e8f0;
        }
        .info-box {
            background-color: white;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #e53e3e;
            border-radius: 4px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #4299e1;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #718096;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Réponse à votre candidature</h1>
    </div>
    
    <div class="content">
        <p>Bonjour {{ $candidature->etudiant->name }},</p>
        
        <p>Nous vous informons que votre candidature pour le stage <strong>{{ $candidature->offre->titre }}</strong> n'a malheureusement pas été retenue.</p>
        
        <div class="info-box">
            <h3>Détails de l'offre :</h3>
            <p><strong>Entreprise :</strong> {{ $candidature->offre->entreprise->nom }}</p>
            <p><strong>Titre :</strong> {{ $candidature->offre->titre }}</p>
            <p><strong>Type :</strong> {{ $candidature->offre->type_stage }}</p>
        </div>
        
        <p>Nous vous encourageons à continuer à postuler à d'autres offres qui correspondent à votre profil. De nombreuses opportunités vous attendent !</p>
        
        <p>Vous pouvez consulter d'autres offres disponibles en cliquant sur le bouton ci-dessous :</p>
        
        <a href="{{ route('offres.index') }}" class="button">Voir les offres disponibles</a>
        
        <p>Nous vous souhaitons bonne chance dans vos recherches !</p>
        
        <p>Cordialement,<br>L'équipe de gestion de stages</p>
    </div>
    
    <div class="footer">
        <p>Cet email a été envoyé automatiquement. Merci de ne pas y répondre.</p>
    </div>
</body>
</html>

