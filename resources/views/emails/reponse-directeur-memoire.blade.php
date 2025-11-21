<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Réponse à votre demande d'encadrement</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #2d3748;">
            @if($accepte)
                Demande d'encadrement acceptée
            @else
                Demande d'encadrement refusée
            @endif
        </h2>
        
        <p>Bonjour {{ $etudiant->name }},</p>
        
        @if($accepte)
            <p>Nous avons le plaisir de vous informer que <strong>{{ $directeur->name }}</strong> a accepté votre demande d'encadrement de mémoire.</p>
            <p>Vous pouvez maintenant commencer à travailler avec votre directeur de mémoire sur votre projet.</p>
        @else
            <p>Nous vous informons que <strong>{{ $directeur->name }}</strong> a refusé votre demande d'encadrement de mémoire.</p>
            @if($raison)
                <div style="margin-top: 20px; padding: 15px; background-color: #fff3cd; border-left: 4px solid #ffc107;">
                    <p style="margin: 0;"><strong>Raison du refus :</strong></p>
                    <p style="margin: 10px 0 0 0;">{{ $raison }}</p>
                </div>
            @endif
            <p style="margin-top: 20px;">Vous pouvez choisir un autre directeur de mémoire en vous connectant à la plateforme.</p>
        @endif
        
        <p style="margin-top: 30px;">
            <a href="{{ route('profile.show') }}" style="display: inline-block; padding: 10px 20px; background-color: #2d3748; color: #ffffff; text-decoration: none; border-radius: 5px;">
                Accéder à mon profil
            </a>
        </p>
        
        <p style="margin-top: 30px; font-size: 12px; color: #666;">
            Cordialement,<br>
            L'équipe de gestion de stages
        </p>
    </div>
</body>
</html>

