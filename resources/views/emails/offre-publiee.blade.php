<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle offre de stage</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #0b1f4d; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { background-color: #ffffff; padding: 30px; border: 1px solid #e2e8f0; }
        .info-box { background-color: #f8fafc; padding: 15px; margin: 15px 0; border-left: 4px solid #0b1f4d; border-radius: 4px; }
        .button { display: inline-block; padding: 12px 24px; background-color: #0b1f4d; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #31527f; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Nouvelle offre de stage</h1>
    </div>

    <div class="content">
        <p>Bonjour {{ $destinataire->name }},</p>

        <p>Une nouvelle offre de stage vient d'être publiée.</p>

        <div class="info-box">
            <p><strong>Entreprise :</strong> {{ $offre->entreprise->nom ?? 'Non spécifiée' }}</p>
            <p><strong>Titre :</strong> {{ $offre->titre }}</p>
            <p><strong>Type :</strong> {{ $offre->type_stage }}</p>
            <p><strong>Durée :</strong> {{ $offre->duree }} mois</p>
            @if($offre->lieu)
                <p><strong>Lieu :</strong> {{ $offre->lieu }}</p>
            @endif
            @if($offre->date_limite_candidature)
                <p><strong>Date limite :</strong> {{ $offre->date_limite_candidature->format('d/m/Y') }}</p>
            @endif
        </div>

        <a href="{{ route('offres.show', $offre) }}" class="button">Voir l'offre</a>

        <p>Cordialement,<br>L'équipe de gestion de stages</p>
    </div>

    <div class="footer">
        <p>Cet email a été envoyé automatiquement. Merci de ne pas y répondre.</p>
    </div>
</body>
</html>
