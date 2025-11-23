<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle candidature reçue</title>
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
            background-color: #2d3748;
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
            border-left: 4px solid #4299e1;
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
        <h1>Nouvelle candidature reçue</h1>
    </div>
    
    <div class="content">
        <p>Bonjour,</p>
        
        <p>Vous avez reçu une nouvelle candidature pour votre offre de stage : <strong>{{ $candidature->offre->titre }}</strong></p>
        
        <div class="info-box">
            <h3>Informations du candidat :</h3>
            <p><strong>Nom :</strong> {{ $candidature->etudiant->name }}</p>
            <p><strong>Email :</strong> {{ $candidature->etudiant->email }}</p>
            @if($candidature->etudiant->niveau_etude)
                <p><strong>Niveau :</strong> {{ $candidature->etudiant->niveau_etude }}</p>
            @endif
            @if($candidature->etudiant->filiere)
                <p><strong>Filière :</strong> {{ $candidature->etudiant->filiere }}</p>
            @endif
        </div>
        
        <div class="info-box">
            <h3>Informations sur l'offre :</h3>
            <p><strong>Titre :</strong> {{ $candidature->offre->titre }}</p>
            <p><strong>Type :</strong> {{ $candidature->offre->type_stage }}</p>
            <p><strong>Durée :</strong> {{ $candidature->offre->duree }} mois</p>
        </div>
        
        <p>Vous pouvez consulter les détails de cette candidature et prendre une décision en cliquant sur le bouton ci-dessous :</p>
        
        <a href="{{ route('candidatures.show', $candidature) }}" class="button">Voir la candidature</a>
        
        <p>Cordialement,<br>L'équipe de gestion de stages</p>
    </div>
    
    <div class="footer">
        <p>Cet email a été envoyé automatiquement. Merci de ne pas y répondre.</p>
    </div>
</body>
</html>

