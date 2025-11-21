# Guide de Configuration Email - Résolution des Problèmes

## 🔴 Problème Actuel

L'erreur indique : **"Failed to authenticate on SMTP server with username "STAGE POUR TOUS"**

Cela signifie que :
1. Le `MAIL_USERNAME` dans votre fichier `.env` contient "STAGE POUR TOUS" au lieu d'un identifiant SMTP valide
2. Les identifiants SMTP ne sont pas correctement configurés

## ✅ Solutions

### Option 1 : Configuration avec Mailtrap (Recommandé pour le développement)

1. Créez un compte gratuit sur [Mailtrap.io](https://mailtrap.io)
2. Dans votre fichier `.env`, configurez :

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=votre_username_mailtrap
MAIL_PASSWORD=votre_password_mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@stage.com
MAIL_FROM_NAME="Gestion de Stages"
```

**Important** : Remplacez `votre_username_mailtrap` et `votre_password_mailtrap` par les vraies valeurs de votre compte Mailtrap.

### Option 2 : Configuration avec Gmail

1. Activez l'authentification à deux facteurs sur votre compte Gmail
2. Générez un mot de passe d'application :
   - Allez dans : Compte Google → Sécurité → Authentification à deux facteurs → Mots de passe des applications
   - Créez un nouveau mot de passe d'application
3. Dans votre fichier `.env` :

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre_email@gmail.com
MAIL_PASSWORD=votre_mot_de_passe_application
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=votre_email@gmail.com
MAIL_FROM_NAME="Gestion de Stages"
```

### Option 3 : Mode Log (Pour tester sans serveur SMTP)

Si vous voulez juste tester sans configurer SMTP, utilisez le mode log :

```env
MAIL_MAILER=log
```

Les emails seront écrits dans `storage/logs/laravel.log` au lieu d'être envoyés.

## 🔧 Vérification

Après avoir modifié le `.env`, exécutez :

```bash
php artisan config:clear
php artisan cache:clear
```

Puis testez l'envoi d'email :

```bash
php artisan tinker
```

Dans tinker :
```php
Mail::raw('Test email', function ($message) {
    $message->to('votre-email@test.com')
            ->subject('Test');
});
```

## 📝 Notes Importantes

- **MAIL_USERNAME** doit être un identifiant valide (email ou username), PAS un nom comme "STAGE POUR TOUS"
- **MAIL_PASSWORD** doit être le mot de passe SMTP, pas votre mot de passe Gmail normal (utilisez un mot de passe d'application)
- Vérifiez que votre serveur peut se connecter au serveur SMTP (pas de firewall bloquant)
- En développement, Mailtrap est la meilleure option car il capture tous les emails sans les envoyer réellement

