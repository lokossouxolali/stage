# Configuration de l'envoi d'emails

Ce document explique comment configurer l'envoi d'emails pour les notifications d'inscription.

## Fonctionnalités implémentées

Le système envoie automatiquement des emails dans les cas suivants :
- ✅ **Validation d'inscription** : Un email est envoyé lorsque l'administrateur valide une inscription
- ✅ **Refus d'inscription** : Un email est envoyé lorsque l'administrateur refuse une inscription

## Configuration

### 1. Configuration dans le fichier `.env`

Ajoutez ou modifiez les variables suivantes dans votre fichier `.env` :

```env
# Configuration Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=votre_username
MAIL_PASSWORD=votre_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@votre-domaine.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 2. Options de configuration

#### Pour le développement (Mailtrap - Recommandé)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=votre_username_mailtrap
MAIL_PASSWORD=votre_password_mailtrap
MAIL_ENCRYPTION=tls
```

#### Pour la production (Gmail)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre_email@gmail.com
MAIL_PASSWORD=votre_mot_de_passe_application
MAIL_ENCRYPTION=tls
```

#### Pour la production (SMTP personnalisé)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.votre-serveur.com
MAIL_PORT=587
MAIL_USERNAME=votre_email@votre-domaine.com
MAIL_PASSWORD=votre_mot_de_passe
MAIL_ENCRYPTION=tls
```

#### Pour les tests (Log - Les emails sont écrits dans les logs)
```env
MAIL_MAILER=log
```

### 3. Configuration Gmail (si vous utilisez Gmail)

Si vous utilisez Gmail, vous devez :
1. Activer l'authentification à deux facteurs sur votre compte Gmail
2. Générer un mot de passe d'application :
   - Allez dans les paramètres de votre compte Google
   - Sécurité → Authentification à deux facteurs → Mots de passe des applications
   - Créez un nouveau mot de passe d'application
   - Utilisez ce mot de passe dans `MAIL_PASSWORD`

### 4. Vérification de la configuration

Après avoir configuré les variables d'environnement, testez l'envoi d'emails :

```bash
php artisan tinker
```

Puis dans tinker :
```php
Mail::raw('Test email', function ($message) {
    $message->to('votre-email@test.com')
            ->subject('Test');
});
```

## Emails envoyés

### Email de validation d'inscription
- **Sujet** : "Votre inscription a été validée"
- **Contenu** : Confirmation que le compte est activé et informations de connexion
- **Destinataire** : L'utilisateur dont l'inscription a été validée

### Email de refus d'inscription
- **Sujet** : "Votre inscription a été refusée"
- **Contenu** : Notification du refus avec informations de contact
- **Destinataire** : L'utilisateur dont l'inscription a été refusée

## Gestion des erreurs

Le système gère automatiquement les erreurs d'envoi d'emails :
- Si l'envoi échoue, l'erreur est enregistrée dans les logs
- L'opération (validation/refus) continue même si l'email ne peut pas être envoyé
- Les logs sont disponibles dans `storage/logs/laravel.log`

## Personnalisation des emails

Les templates d'emails se trouvent dans :
- `resources/views/emails/inscription-validee.blade.php`
- `resources/views/emails/inscription-refusee.blade.php`

Vous pouvez modifier ces fichiers pour personnaliser le contenu et le style des emails.

## Notes importantes

- En développement, utilisez Mailtrap pour tester sans envoyer de vrais emails
- En production, configurez un serveur SMTP fiable
- Vérifiez que les emails ne sont pas marqués comme spam
- Testez régulièrement l'envoi d'emails pour vous assurer que tout fonctionne

