# Documentation API - Système de Gestion de Stages

## Vue d'ensemble

Cette API permet de gérer le cycle complet des stages : publication d'offres, candidatures, suivi, dépôt de rapports et évaluations.

## Authentification

L'API utilise Laravel Sanctum pour l'authentification par tokens.

### Endpoints d'authentification

#### POST /api/auth/login
Connexion d'un utilisateur

**Body:**
```json
{
    "email": "user@example.com",
    "password": "password"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Connexion réussie",
    "user": {...},
    "token": "1|abc123...",
    "token_type": "Bearer"
}
```

#### POST /api/auth/register
Inscription d'un nouvel utilisateur

**Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password",
    "password_confirmation": "password",
    "role": "etudiant",
    "telephone": "+33123456789",
    "niveau_etude": "M2",
    "filiere": "Informatique"
}
```

#### POST /api/auth/logout
Déconnexion (nécessite un token)

#### GET /api/auth/user
Récupérer les informations de l'utilisateur connecté

## Gestion des offres

### GET /api/offres
Lister toutes les offres (avec filtres)

**Query Parameters:**
- `statut`: active, fermee, suspendue
- `type_stage`: obligatoire, optionnel, projet_fin_etudes
- `niveau_etude`: L1, L2, L3, M1, M2, Doctorat
- `search`: recherche par titre ou description

### POST /api/offres
Créer une nouvelle offre (entreprises uniquement)

**Body:**
```json
{
    "titre": "Développeur Full Stack",
    "description": "Stage de développement web",
    "missions": "Développement d'applications web",
    "competences_requises": "PHP, Laravel, JavaScript",
    "duree": "3 mois",
    "date_debut": "2024-01-01",
    "date_fin": "2024-03-31",
    "lieu": "Paris",
    "type_stage": "obligatoire",
    "niveau_etude": "M2",
    "remuneration": "Gratifié",
    "date_limite_candidature": "2023-12-15",
    "nombre_places": 2
}
```

### GET /api/offres/{id}
Récupérer une offre spécifique

### PUT /api/offres/{id}
Modifier une offre

### DELETE /api/offres/{id}
Supprimer une offre

### GET /api/offres/{id}/candidatures
Lister les candidatures pour une offre

### GET /api/offres-publiques
Lister les offres publiques (sans authentification)

## Gestion des candidatures

### GET /api/candidatures
Lister les candidatures (filtrées par rôle)

### POST /api/candidatures
Postuler à une offre

**Body:**
```json
{
    "offre_id": 1,
    "lettre_motivation": "Je suis très intéressé par ce stage...",
    "commentaires_etudiant": "Disponible à partir de janvier"
}
```

**Files:**
- `cv`: fichier PDF/DOC (optionnel)
- `lettre_recommandation`: fichier PDF/DOC (optionnel)

### GET /api/candidatures/{id}
Récupérer une candidature

### PUT /api/candidatures/{id}
Modifier une candidature

### DELETE /api/candidatures/{id}
Supprimer une candidature

### POST /api/candidatures/{id}/accepter
Accepter une candidature (entreprises)

### POST /api/candidatures/{id}/refuser
Refuser une candidature (entreprises)

## Gestion des stages

### GET /api/stages
Lister les stages

### POST /api/stages
Créer un nouveau stage

### GET /api/stages/{id}
Récupérer un stage

### PUT /api/stages/{id}
Modifier un stage

### DELETE /api/stages/{id}
Supprimer un stage

### POST /api/stages/{id}/upload-rapport
Déposer un rapport de stage

## Gestion des rapports

### GET /api/rapports
Lister les rapports

### POST /api/rapports
Créer un nouveau rapport

### GET /api/rapports/{id}
Récupérer un rapport

### PUT /api/rapports/{id}
Modifier un rapport

### DELETE /api/rapports/{id}
Supprimer un rapport

### POST /api/rapports/{id}/valider
Valider un rapport

### POST /api/rapports/{id}/rejeter
Rejeter un rapport

## Gestion des évaluations

### GET /api/evaluations
Lister les évaluations

### POST /api/evaluations
Créer une évaluation

### GET /api/evaluations/{id}
Récupérer une évaluation

### PUT /api/evaluations/{id}
Modifier une évaluation

### DELETE /api/evaluations/{id}
Supprimer une évaluation

### POST /api/evaluations/{id}/finaliser
Finaliser une évaluation

## Rôles et permissions

### Étudiant
- Peut consulter les offres publiques
- Peut postuler aux offres
- Peut gérer ses candidatures
- Peut déposer des rapports
- Peut consulter ses stages

### Entreprise
- Peut créer et gérer ses offres
- Peut consulter les candidatures pour ses offres
- Peut accepter/refuser des candidatures
- Peut gérer les stages de ses stagiaires

### Enseignant
- Peut encadrer des stages
- Peut évaluer les stagiaires
- Peut consulter les rapports

### Responsable des stages
- Peut gérer tous les stages
- Peut consulter toutes les données
- Peut assigner des encadreurs

### Administrateur
- Accès complet à toutes les fonctionnalités
- Peut gérer les utilisateurs
- Peut gérer les entreprises

## Codes de réponse

- `200`: Succès
- `201`: Créé avec succès
- `400`: Erreur de requête
- `401`: Non authentifié
- `403`: Non autorisé
- `404`: Ressource non trouvée
- `422`: Erreur de validation
- `500`: Erreur serveur

## Sécurité

L'API implémente plusieurs mesures de sécurité :

- **Authentification par tokens** (Laravel Sanctum)
- **Headers de sécurité** (XSS, CSRF protection)
- **Validation des données** d'entrée
- **Contrôle d'accès** basé sur les rôles
- **Protection des fichiers** uploadés
- **Rate limiting** (à configurer)

## Exemples d'utilisation

### Connexion et récupération des offres

```bash
# 1. Se connecter
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email": "jean.dupont@student.com", "password": "password"}'

# 2. Utiliser le token pour accéder aux offres
curl -X GET http://localhost:8000/api/offres \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Postuler à une offre

```bash
curl -X POST http://localhost:8000/api/candidatures \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "offre_id": 1,
    "lettre_motivation": "Je suis très motivé pour ce stage...",
    "commentaires_etudiant": "Disponible immédiatement"
  }'
```
