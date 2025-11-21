# Système de Gestion de Stages

Une application Laravel complète pour gérer le cycle complet des stages : publication d'offres, candidatures, suivi, dépôt de rapports et évaluations.

## 🚀 Fonctionnalités

### Authentification sécurisée
- Inscription et connexion avec rôles multiples
- Authentification par tokens (Laravel Sanctum)
- Gestion des permissions par rôle

### Gestion des offres de stage
- Publication d'offres par les entreprises
- Filtrage et recherche d'offres
- Gestion des dates limites et places disponibles

### Système de candidatures
- Postulation aux offres par les étudiants
- Upload de CV et lettres de motivation
- Acceptation/refus des candidatures par les entreprises

### Suivi des stages
- Création et gestion des stages
- Attribution d'encadreurs (entreprise et académique)
- Suivi du statut des stages

### Dépôt et versioning des rapports
- Upload de rapports de stage
- Système de versioning
- Validation par les encadreurs

### Système d'évaluation
- Évaluation par les encadreurs
- Notes sur différents critères
- Commentaires et recommandations

### Soutenances
- Planification des soutenances
- Gestion des jurys
- Notes finales

## 👥 Rôles utilisateurs

- **Administrateur** : Accès complet à toutes les fonctionnalités
- **Responsable des stages** : Gestion globale des stages
- **Enseignant** : Encadrement et évaluation des stagiaires
- **Étudiant** : Candidature et suivi de ses stages
- **Entreprise** : Publication d'offres et gestion des candidatures
- **Jury** : Participation aux soutenances

## 🛠️ Installation

### Prérequis
- PHP 8.2+
- Composer
- MySQL/PostgreSQL
- Node.js (pour les assets)

### Installation

1. **Cloner le projet**
```bash
git clone <repository-url>
cd stage
```

2. **Installer les dépendances**
```bash
composer install
npm install
```

3. **Configuration de l'environnement**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configuration de la base de données**
Modifiez le fichier `.env` avec vos paramètres de base de données :
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=stage_management
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. **Exécuter les migrations et seeders**
```bash
php artisan migrate
php artisan db:seed
```

6. **Démarrer le serveur**
```bash
php artisan serve
```

L'application sera accessible sur `http://localhost:8000`

## 📚 API Documentation

L'API est documentée dans le fichier `API_DOCUMENTATION.md`. Elle fournit :

- Endpoints d'authentification
- Gestion des offres
- Système de candidatures
- Gestion des stages
- Dépôt de rapports
- Système d'évaluation

### Exemple d'utilisation

```bash
# Connexion
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email": "jean.dupont@student.com", "password": "password"}'

# Lister les offres
curl -X GET http://localhost:8000/api/offres \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## 🔒 Sécurité

L'application implémente plusieurs mesures de sécurité :

- **Authentification par tokens** (Laravel Sanctum)
- **Headers de sécurité** (XSS, CSRF protection)
- **Validation des données** d'entrée
- **Contrôle d'accès** basé sur les rôles
- **Protection des fichiers** uploadés
- **Rate limiting** (configurable)

## 🧪 Tests

Exécuter les tests :
```bash
php artisan test
```

Tests spécifiques :
```bash
php artisan test tests/Feature/ApiAuthTest.php
```

## 📊 Base de données

### Tables principales
- `users` : Utilisateurs avec rôles
- `entreprises` : Informations des entreprises
- `offres` : Offres de stage
- `candidatures` : Candidatures des étudiants
- `stages` : Stages en cours
- `rapports` : Rapports de stage avec versioning
- `evaluations` : Évaluations des stagiaires
- `soutenances` : Soutenances de stage

### Relations
- Un utilisateur peut appartenir à une entreprise
- Une offre appartient à une entreprise
- Une candidature lie un étudiant à une offre
- Un stage découle d'une candidature acceptée
- Un stage peut avoir plusieurs rapports et évaluations

## 🚀 Déploiement

### Production
1. Configurer les variables d'environnement
2. Optimiser l'application :
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
3. Configurer le serveur web (Apache/Nginx)
4. Configurer SSL/TLS
5. Mettre en place les sauvegardes automatiques

### Docker (optionnel)
Un fichier `Dockerfile` peut être créé pour containeriser l'application.

## 📝 Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## 🤝 Contribution

1. Fork le projet
2. Créer une branche feature (`git checkout -b feature/AmazingFeature`)
3. Commit les changements (`git commit -m 'Add some AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

## 📞 Support

Pour toute question ou problème, veuillez ouvrir une issue sur le repository.

## 🔄 Changelog

### Version 1.0.0
- Authentification complète avec rôles
- Gestion des offres de stage
- Système de candidatures
- Upload de fichiers sécurisé
- API REST complète
- Tests unitaires et fonctionnels
- Documentation complète