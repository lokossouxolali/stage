<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Demande d'encadrement de mémoire</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #2d3748;">Demande d'encadrement de mémoire</h2>
        
        <p>Bonjour {{ $directeur->name }},</p>
        
        <p>L'étudiant <strong>{{ $etudiant->name }}</strong> ({{ $etudiant->email }}) souhaite que vous soyez son directeur de mémoire.</p>
        
        <p>Vous pouvez accepter ou refuser cette demande en vous connectant à la plateforme.</p>
        
        <div style="margin-top: 30px; padding: 15px; background-color: #f8f9fa; border-left: 4px solid #2d3748;">
            <p style="margin: 0;"><strong>Informations de l'étudiant :</strong></p>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>Nom : {{ $etudiant->name }}</li>
                <li>Email : {{ $etudiant->email }}</li>
                @if($etudiant->niveau_etude)
                    <li>Niveau : {{ $etudiant->niveau_etude }}</li>
                @endif
                @if($etudiant->filiere)
                    <li>Filière : {{ $etudiant->filiere }}</li>
                @endif
            </ul>
        </div>
        
        <p style="margin-top: 30px;">
            <a href="{{ route('demandes-encadrement.index') }}" style="display: inline-block; padding: 10px 20px; background-color: #2d3748; color: #ffffff; text-decoration: none; border-radius: 5px;">
                Voir la demande sur la plateforme
            </a>
        </p>
        
        <p style="margin-top: 30px; font-size: 12px; color: #666;">
            Cordialement,<br>
            L'équipe de gestion de stages
        </p>
    </div>
</body>
</html>

