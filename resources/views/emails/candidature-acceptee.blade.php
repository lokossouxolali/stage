<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidature acceptée</title>
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
            background-color: #48bb78;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #ffffff;
            padding: 30px;
            border: 1px solid #e2e8f0;
        }
        .info-box {
            background-color: white;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #48bb78;
            border-radius: 4px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #48bb78;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #31527f;
            font-size: 12px;
        }
    </style>
</head>
<body>
    {{-- <div class="header">
        <h1>🎉 Félicitations !</h1>
    </div> --}}
    
    <div class="content">
        <p>Bonjour {{ $candidature->etudiant->name }},</p>
        
        <p>Nous avons le plaisir de vous informer que votre candidature pour le stage <strong>{{ $candidature->offre->titre }}</strong> a été <strong>acceptée</strong> !</p>
        
        <div class="info-box">
            <h3>Détails de l'offre :</h3>
            <p><strong>Entreprise :</strong> {{ $candidature->offre->entreprise->nom }}</p>
            <p><strong>Titre :</strong> {{ $candidature->offre->titre }}</p>
            <p><strong>Type :</strong> {{ $candidature->offre->type_stage }}</p>
            <p><strong>Durée :</strong> {{ $candidature->offre->duree }} mois</p>
            @if($candidature->offre->date_debut)
                <p><strong>Date de début :</strong> {{ $candidature->offre->date_debut->format('d/m/Y') }}</p>
            @endif
        </div>
        
        <p>L'entreprise vous contactera prochainement pour discuter des prochaines étapes.</p>
        
        <p>Vous pouvez consulter les détails de votre candidature en cliquant sur le bouton ci-dessous :</p>
        
        <a href="{{ route('candidatures.show', $candidature) }}" class="button">Voir ma candidature</a>
        
        <p>Nous vous souhaitons une excellente expérience de stage !</p>
        
        <p>Cordialement,<br>L'équipe de gestion de stages</p>
    </div>
    
    <div class="footer">
        <p>Cet email a été envoyé automatiquement. Merci de ne pas y répondre.</p>
    </div>
</body>
</html>


