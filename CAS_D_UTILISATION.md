# Cas d'utilisation par acteur du système

Ce document décrit les cas d'utilisation de chaque acteur dans le système de gestion de stages.

## Acteurs identifiés

Le système comprend 4 types d'acteurs :

- **Administrateur (admin)**
- **Entreprise**
- **Étudiant (etudiant)**
- **Enseignant**

---

## 1. ADMINISTRATEUR

### Accès complet au système

#### Gestion des utilisateurs
- ✅ Valider l'inscription d'un utilisateur (entreprise, étudiant, enseignant)
- ✅ Refuser l'inscription d'un utilisateur
- ✅ Consulter la liste de tous les utilisateurs
- ✅ Supprimer un utilisateur

#### Gestion des entreprises
- ✅ Consulter les entreprises
- ✅ Créer, modifier, supprimer des entreprises

#### Gestion des rapports
- ✅ Consulter les rapports de stage
- ✅ Valider un rapport de stage
- ✅ Refuser un rapport de stage

#### Notifications
- ✅ Consulter les notifications

#### Statistiques et rapports
- ✅ Accéder au dashboard avec statistiques globales
- ✅ Consulter les statistiques détaillées
- ✅ Générer des rapports d'analyse
- ✅ Consulter les rapports de performance

---

## 2. ENTREPRISE

### Gestion des offres et candidatures

#### Gestion des offres
- ✅ Consulter ses propres offres (`/mes-offres`)
- ✅ Créer de nouvelles offres de stage
- ✅ Modifier ses offres
- ✅ Supprimer ses offres
- ✅ Consulter toutes les offres (lecture seule)

#### Gestion des candidatures
- ✅ Consulter les candidatures reçues (`/candidatures-recues`)
- ✅ Accepter des candidatures
- ✅ Refuser des candidatures
- ✅ Consulter les détails d'une candidature

#### Fonctionnalités communes
- ✅ Accéder au dashboard avec statistiques spécifiques :
  - Nombre de ses offres
  - Nombre de candidatures reçues
- ✅ Gestion du profil personnel
- ✅ Consulter les notifications

---

## 3. ÉTUDIANT

### Recherche de stage et candidatures

#### Consultation des offres
- ✅ Consulter les offres disponibles (`/offres-disponibles`)
- ✅ Voir les détails d'une offre

#### Gestion des candidatures
- ✅ Consulter ses candidatures (`/mes-candidatures`)
- ✅ Postuler à une offre de stage
- ✅ Consulter le statut de ses candidatures
- ✅ Supprimer une candidature (uniquement si en attente)

#### Gestion des stages
- ✅ Consulter les rapports de stage (`/mes-rapports`)
- ✅ Envoyer son rapport à l'administrateur
- ✅ Envoyer son rapport au Directeur de mémoire
- ✅ Consulter le statut de son rapport

#### Gestion du directeur de mémoire
- ✅ Choisir son directeur de mémoire parmi les Enseignants

#### Fonctionnalités communes
- ✅ Consulter les notifications
- ✅ Gestion du profil personnel

---

## 4. ENSEIGNANT

### Encadrement des stages

#### Consultation des rapports
- ✅ Consulter les rapports des stages des étudiants encadrés (`/rapports-encadres`)
- ✅ Consulter les mémoires des étudiants encadrés
- ✅ Ajouter des commentaires sur les rapports et mémoires des étudiants encadrés

#### Gestion des rapports
- ✅ Consulter les rapports de stage
- ✅ Valider des rapports
- ✅ Rejeter des rapports

#### Fonctionnalités communes
- ✅ Gestion du profil personnel
- ✅ Consulter les notifications

---

## Fonctionnalités communes à tous les acteurs

### Authentification
- ✅ Se connecter
- ✅ Se déconnecter
- ✅ S'inscrire (selon les règles d'accès)
  - Les nouvelles inscriptions sont en attente de validation par l'administrateur
  - Seuls les comptes validés peuvent se connecter

### Profil utilisateur
- ✅ Consulter son profil
- ✅ Modifier son profil
- ✅ Changer son mot de passe

### Dashboard
- ✅ Accéder au tableau de bord
- ✅ Voir les statistiques selon le rôle

### Notifications
- ✅ Consulter les notifications
- ✅ Marquer une notification comme lue
- ✅ Marquer toutes les notifications comme lues

---

## Flux de travail principal

### 1. Inscription (Tous les utilisateurs)
1. Utilisateur s'inscrit avec son rôle (entreprise, étudiant, enseignant)
2. Compte créé avec statut `en_attente`
3. Utilisateur ne peut pas se connecter tant que le compte n'est pas validé

### 2. Validation d'inscription (Administrateur)
1. Administrateur consulte la liste des utilisateurs en attente
2. Administrateur valide ou refuse l'inscription
3. Si validé : compte activé, utilisateur peut se connecter
4. Si refusé : compte désactivé

### 3. Création d'une offre (Entreprise)
1. Entreprise crée une offre de stage
2. Offre publiée (statut: active)

### 4. Candidature (Étudiant)
1. Étudiant consulte les offres disponibles
2. Étudiant postule à une offre
3. Candidature créée (statut: en_attente)
4. Étudiant peut supprimer sa candidature si elle est en attente

### 5. Traitement de la candidature (Entreprise)
1. Entreprise consulte les candidatures reçues
2. Entreprise accepte ou refuse la candidature
3. Statut mis à jour (acceptee/refusee)

### 6. Choix du directeur de mémoire (Étudiant)
1. Étudiant choisit son directeur de mémoire parmi les enseignants
2. Directeur de mémoire assigné à l'étudiant

### 7. Suivi des rapports de stage
1. Rapports de stage créés par l'étudiant
2. Étudiant choisit le destinataire (admin, directeur de mémoire, ou les deux)
3. Rapports validés/rejetés par l'enseignant ou l'administrateur
4. Enseignant peut ajouter des commentaires sur les rapports

---

## Matrice des permissions

| Fonctionnalité | Admin | Entreprise | Étudiant | Enseignant |
|----------------|-------|------------|----------|-----------|
| **Valider/Refuser inscription** | ✅ | ❌ | ❌ | ❌ |
| **Créer offre** | ✅ | ✅ | ❌ | ❌ |
| **Modifier offre** | ✅ | ✅ (ses offres) | ❌ | ❌ |
| **Postuler** | ❌ | ❌ | ✅ | ❌ |
| **Supprimer candidature** | ❌ | ❌ | ✅ (ses candidatures en attente) | ❌ |
| **Accepter/Refuser candidature** | ✅ | ✅ (ses offres) | ❌ | ❌ |
| **Choisir directeur mémoire** | ❌ | ❌ | ✅ | ❌ |
| **Créer rapport** | ❌ | ❌ | ✅ | ❌ |
| **Valider/Rejeter rapport** | ✅ | ❌ | ❌ | ✅ |
| **Commenter rapport** | ❌ | ❌ | ❌ | ✅ |
| **Consulter rapports encadrés** | ✅ | ❌ | ❌ | ✅ |

---

## Notes importantes

- Les **administrateurs** ont accès à toutes les fonctionnalités de gestion
- Les **entreprises** ne peuvent modifier que leurs propres offres
- Les **étudiants** ne peuvent supprimer que leurs candidatures en attente
- Les **enseignants** peuvent encadrer, évaluer et commenter les rapports des étudiants
- Toutes les nouvelles inscriptions doivent être validées par un administrateur avant de pouvoir se connecter
- Les étudiants peuvent choisir leur directeur de mémoire parmi les enseignants disponibles
- Les rapports peuvent être envoyés à l'administrateur, au directeur de mémoire, ou aux deux
