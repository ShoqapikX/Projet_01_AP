# 📘 GUIDE COMPLET DU PROJET E-COMMERCE

> **Guide de référence rapide pour comprendre et utiliser le projet**

---

## 📑 Table des Matières

1. [Vue d'ensemble](#vue-densemble)
2. [Trois missions accomplies](#trois-missions-accomplies)
3. [Démarrage rapide](#démarrage-rapide)
4. [Structure du projet](#structure-du-projet)
5. [Fonctionnalités principales](#fonctionnalités-principales)
6. [Guide de développement](#guide-de-développement)
7. [Dépannage](#dépannage)
8. [Ressources](#ressources)

---

## 🎯 Vue d'ensemble

### Le Projet
Site e-commerce de vente de baskets Nike développé en PHP avec Docker, MySQL, et intégration Stripe pour les paiements.

### Technologies
- **Backend** : PHP 8.2, PDO
- **Frontend** : HTML5, CSS3, JavaScript Vanilla
- **Base de données** : MySQL 8.0
- **Infrastructure** : Docker, Docker Compose
- **Paiements** : Stripe API
- **Sécurité** : Google Authenticator (2FA)

### Statistiques
- 📂 **8 modules** (auth, panier, produits, payment, etc.)
- 🗄️ **11 tables** dans la base de données
- 🎨 **5 feuilles de style** CSS
- 🔒 **2FA** activé pour tous les comptes
- 💳 **Stripe** intégré pour paiements sécurisés

---

## 🏆 Trois Missions Accomplies

### Mission 1️⃣ : Compteur de Visites ✅

**Objectif** : Afficher le nombre de visiteurs sur la page d'accueil avec stockage en base de données.

**Fichier créé** :
```
config/compteur_visites.php
```

**Comment ça marche** :
1. Une table `visites` stocke le compteur global
2. Au chargement de `index.php`, on vérifie la session
3. Si c'est une nouvelle session → compteur +1
4. Affichage dans le footer : "X visites"

**Code d'utilisation** :
```php
// Dans index.php
require_once('./config/compteur_visites.php');

initTableVisites();              // Crée la table si besoin
if (estNouvelleVisite()) {       // Vérifie session
    incrementerVisites();         // +1 au compteur
}
$nombreVisites = getNombreVisites(); // Récupère le total
```

**Fonctionnalités** :
- ✅ Comptage unique par session (pas de +1 sur F5)
- ✅ Table auto-créée si inexistante
- ✅ Gestion d'erreurs (try-catch)
- ✅ Affichage dynamique dans footer

**État actuel** : **6 visites** enregistrées

---

### Mission 2️⃣ : Recommandations de Produits (IA-like) ✅

**Objectif** : Afficher "Vous pourriez aussi aimer" basé sur les catégories des produits déjà vus.

**Fichier créé** :
```
produits/recommandations.php
```

**Comment ça marche** :
1. Quand un utilisateur consulte un produit → enregistrement dans `produits_vus`
2. Analyse des catégories consultées (ex: "Basket Nike")
3. Recherche d'autres produits dans ces catégories
4. Exclusion des produits déjà vus
5. Tri aléatoire et limitation à 4 produits

**Code d'utilisation** :
```php
// Dans produit_details.php
require_once('recommandations.php');

// Enregistrer la consultation
enregistrerProduitVu($produitId, $userId, $sessionId);

// Afficher les recommandations
afficherRecommandations($produitId, $userId, $sessionId);
```

**Algorithme** :
```
Étape 1: SELECT categories FROM produits_vus WHERE user=X
         GROUP BY categorie ORDER BY MAX(date_vue)
         
Étape 2: SELECT * FROM produits 
         WHERE categorie IN (categories_vues)
         AND id NOT IN (produits_deja_vus)
         ORDER BY RAND() LIMIT 4
```

**Fonctionnalités** :
- ✅ Support utilisateurs connectés (via user_id)
- ✅ Support utilisateurs anonymes (via session_id)
- ✅ Exclusion des produits déjà consultés
- ✅ Variété grâce au tri aléatoire
- ✅ Section HTML complète avec styles

**État actuel** : Section **"Vous pourriez aussi aimer"** visible sur les pages produits

---

### Mission 3️⃣ : Recherche AJAX en Temps Réel ✅

**Objectif** : Mettre en place une recherche en temps réel avec suggestions.

**Fichiers créés** :
```
produits/recherche_ajax.php    (Backend endpoint)
js/script.js                   (Ajout section AJAX)
```

**Comment ça marche** :
1. L'utilisateur tape dans le champ de recherche
2. Après 300ms sans frappe (debounce) → requête AJAX
3. Backend cherche dans nom, marque, description, catégorie
4. Retour JSON avec max 8 produits
5. Affichage suggestions avec images + prix

**Code Backend** :
```php
// produits/recherche_ajax.php
header('Content-Type: application/json');

$query = trim($_GET['q']);
$sql = "SELECT id, nom, marque, prix, image_url 
        FROM produits 
        WHERE nom LIKE ? OR marque LIKE ? 
        LIMIT 8";

$stmt->execute(["%$query%", "%$query%"]);
echo json_encode($stmt->fetchAll());
```

**Code Frontend** :
```javascript
// js/script.js
searchInput.addEventListener('input', function() {
    const query = this.value.trim();
    
    if (query.length < 2) return; // Minimum 2 caractères
    
    // Debounce de 300ms
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        fetch(`./produits/recherche_ajax.php?q=${query}`)
            .then(res => res.json())
            .then(data => {
                // Afficher les suggestions
                searchSuggestions.innerHTML = generateHTML(data);
            });
    }, 300);
});
```

**Fonctionnalités** :
- ✅ Recherche multi-critères (4 colonnes)
- ✅ Debouncing (300ms) pour limiter requêtes
- ✅ Suggestions avec images, nom, marque, prix
- ✅ Limitation à 8 résultats max
- ✅ Message si aucun résultat
- ✅ Fermeture suggestions (clic dehors)
- ✅ Format JSON propre

**État actuel** : Recherche **fonctionnelle** sans erreurs

---

## 🚀 Démarrage Rapide

### Prérequis
```bash
✅ Docker Desktop installé
✅ Git installé
✅ 4 Go RAM libre
✅ Ports 8080 et 3307 disponibles
```

### Installation en 4 étapes

#### 1. Cloner le projet
```bash
cd ~/Documents/Projet_01_AP
# Le projet est déjà dans e-commerce/e-commerce/
```

#### 2. Démarrer Docker
```bash
cd e-commerce/e-commerce
docker-compose up -d
```

Vérification :
```bash
docker ps
# Doit afficher :
# - e-commerce-web-1 (port 8080:80)
# - e-commerce-db-1 (port 3307:3306)
```

#### 3. Importer la base de données
```bash
docker exec -i e-commerce-db-1 mysql \
  -uecommerceuser -pecommercepass e_commerce \
  < ../../e_commerce.sql
```

Vérification :
```bash
docker exec -it e-commerce-db-1 mysql \
  -uecommerceuser -pecommercepass e_commerce \
  -e "SHOW TABLES;"
# Doit afficher 11 tables
```

#### 4. Accéder au site
```bash
open http://localhost:8080
```

✅ **Vous devriez voir** :
- Slider avec images Nike
- Grille de produits
- Champ de recherche (AJAX)
- Footer avec "X visites"

---

## 📁 Structure du Projet

### Arborescence Principale

```
e-commerce/
├── auth/               # Authentification (login, register, 2FA)
├── commande/           # Gestion commandes
├── config/             # Configuration (DB, Stripe, compteur)
├── css/                # Styles CSS
├── images/             # Images produits et icons
├── js/                 # Scripts JavaScript
├── panier/             # Gestion panier
├── payment/            # Processus paiement Stripe
├── produits/           # Catalogue et recommandations
├── profile/            # Profil utilisateur
├── stripe/             # SDK Stripe
├── vendor/             # Dépendances Composer
├── index.php           # Page d'accueil
├── docker-compose.yml  # Configuration Docker
└── README.md           # Documentation
```

### Fichiers des 3 Missions

```
Fichiers NOUVEAUX :
├── config/compteur_visites.php      # Mission 1
├── produits/recommandations.php     # Mission 2
├── produits/recherche_ajax.php      # Mission 3
└── js/script.js                     # Modifié pour Mission 3

Fichiers MODIFIÉS :
├── index.php                        # Intégration des 3 missions
├── css/styles.css                   # Styles grille produits
├── produits/produit_details.php     # Intégration recommandations
├── docker-compose.yml               # Fix database name
└── config/dbconnect.php             # Fix database name
```

---

## 🎨 Fonctionnalités Principales

### 1. Catalogue Produits
- **Fichier** : `index.php`, `produits/listeProduits.php`
- Affichage grille responsive
- Effet hover sur images (changement image)
- Prix et marque affichés
- Lien vers page détails

### 2. Authentification
- **Fichiers** : `auth/login.php`, `auth/register.php`
- Login/Register avec validation
- 2FA avec Google Authenticator
- QR Code pour configuration 2FA
- Sessions sécurisées

### 3. Panier d'Achat
- **Fichiers** : `panier/FunctionCart.php`, `panier/controller_quantite.php`
- Ajout/Suppression produits
- Modification quantités
- Total dynamique
- Modal popup panier

### 4. Paiement Stripe
- **Fichiers** : `payment/paiement.php`, `stripe/create-checkout-session.php`
- Intégration Stripe Checkout
- Pages success/cancel
- Confirmation commande
- Historique commandes

### 5. Profil Utilisateur
- **Fichiers** : `profile/profile.php`
- Informations personnelles
- Historique commandes
- Modification profil

### 6. Slider de Présentation
- **Fichier** : `index.php`, `js/script.js`
- Carrousel automatique (3 secondes)
- Boutons précédent/suivant
- Transition smooth

---

## 💻 Guide de Développement

### Ajouter un Nouveau Produit

#### Via SQL
```sql
INSERT INTO produits (nom, marque, prix, description, image_url, image_hover_url, stock, categorie) 
VALUES (
    'Nike Dunk Low',
    'Nike',
    119.99,
    'Basket rétro confortable',
    './images/dunk1.jpg',
    './images/dunk2.jpg',
    50,
    'Basket'
);
```

#### Via Docker
```bash
docker exec -it e-commerce-db-1 mysql \
  -uecommerceuser -pecommercepass e_commerce
```

Puis coller le INSERT ci-dessus.

### Modifier le Compteur de Visites

#### Réinitialiser à 0
```sql
UPDATE visites SET nombre_visites = 0 WHERE id = 1;
```

#### Voir les statistiques
```sql
SELECT nombre_visites, date_derniere_visite FROM visites;
```

### Tester la Recherche AJAX

#### Via Curl
```bash
curl "http://localhost:8080/produits/recherche_ajax.php?q=nike"
```

Résultat attendu :
```json
[
  {
    "id": 2,
    "nom": "Nike Air Force 1",
    "marque": "Nike",
    "prix": "110.00",
    "image_url": "./images/nike1.jpg",
    "categorie": "Basket"
  }
]
```

### Voir les Produits Vus (Recommandations)

```sql
SELECT 
    pv.id,
    u.nom AS utilisateur,
    p.nom AS produit,
    pv.date_vue
FROM produits_vus pv
LEFT JOIN utilisateur u ON pv.utilisateur_id = u.id
JOIN produits p ON pv.produit_id = p.id
ORDER BY pv.date_vue DESC
LIMIT 10;
```

---

## 🔧 Dépannage

### Problème 1 : Produits non affichés

**Symptôme** : Page blanche ou produits invisibles

**Solutions** :
```bash
# 1. Vérifier erreurs PHP
docker logs e-commerce-web-1

# 2. Vérifier base de données
docker exec -it e-commerce-db-1 mysql \
  -uecommerceuser -pecommercepass e_commerce \
  -e "SELECT COUNT(*) FROM produits;"

# 3. Restart Docker
docker-compose restart web

# 4. Vérifier CSS
curl http://localhost:8080/css/styles.css | grep "product-grid"
```

### Problème 2 : Erreur "Table doesn't exist"

**Symptôme** : `Table 'e_commerce.produits' doesn't exist`

**Solution** :
```bash
# Réimporter la base
cd ~/Documents/Projet_01_AP
docker exec -i e-commerce-db-1 mysql \
  -uecommerceuser -pecommercepass e_commerce \
  < e_commerce.sql
```

### Problème 3 : AJAX ne retourne rien

**Symptôme** : Aucune suggestion à la recherche

**Solutions** :
```bash
# 1. Tester l'endpoint directement
curl "http://localhost:8080/produits/recherche_ajax.php?q=test"

# 2. Vérifier erreurs JavaScript
# Ouvrir DevTools (F12) > Console

# 3. Vérifier query minimale (2 caractères)
# Taper au moins 2 lettres dans le champ

# 4. Vérifier headers
curl -I http://localhost:8080/produits/recherche_ajax.php?q=nike
# Doit afficher: Content-Type: application/json
```

### Problème 4 : Docker ne démarre pas

**Symptôme** : `docker-compose up` échoue

**Solutions** :
```bash
# 1. Vérifier ports disponibles
lsof -i :8080
lsof -i :3307

# 2. Arrêter tous les containers
docker-compose down

# 3. Supprimer volumes si nécessaire
docker-compose down -v

# 4. Rebuild complet
docker-compose up -d --build
```

### Problème 5 : "Headers already sent"

**Symptôme** : Warning PHP sur headers

**Cause** : Espace blanc avant `<?php` ou après `?>`

**Solution** :
```bash
# Vérifier les espaces
cat -A config/dbconnect.php | head -1
cat -A config/dbconnect.php | tail -5

# Le fichier doit commencer DIRECTEMENT par <?php
# Et NE PAS avoir de ?> à la fin
```

---

## 📊 Base de Données

### Tables Principales

| Table | Rôle | Lignes |
|-------|------|--------|
| `produits` | Catalogue produits | ~3 |
| `utilisateur` | Comptes utilisateurs | Variable |
| `panier` | Paniers actifs | Variable |
| `commande` | Commandes passées | Variable |
| `visites` | Compteur visites | 1 |
| `produits_vus` | Historique navigation | Variable |
| `avis` | Notes et commentaires | 0 (préparé) |

### Schéma Simplifié

```
utilisateur (id, nom, email, password)
     │
     ├──► panier (utilisateur_id, produit_id, quantite)
     │
     ├──► commande (utilisateur_id, total, statut)
     │
     └──► produits_vus (utilisateur_id, produit_id, date_vue)

produits (id, nom, prix, categorie, image_url)
     │
     ├──► panier.produit_id
     │
     └──► produits_vus.produit_id

visites (id=1, nombre_visites, date_derniere)
```

### Requêtes Utiles

```sql
-- Produit le plus vu
SELECT p.nom, COUNT(*) as vues
FROM produits_vus pv
JOIN produits p ON pv.produit_id = p.id
GROUP BY p.id
ORDER BY vues DESC
LIMIT 1;

-- Catégorie la plus populaire
SELECT categorie, COUNT(*) as consultations
FROM produits_vus pv
JOIN produits p ON pv.produit_id = p.id
GROUP BY categorie
ORDER BY consultations DESC;

-- Dernier utilisateur inscrit
SELECT nom, email, created_at
FROM utilisateur
ORDER BY created_at DESC
LIMIT 1;
```

---

## 📚 Ressources

### Documentation du Projet

| Document | Description |
|----------|-------------|
| **README.md** | Guide d'installation et utilisation |
| **CHANGELOG.md** | Historique des versions |
| **ARCHITECTURE.md** | Documentation technique détaillée |
| **RECAPITULATIF_FINAL.md** | Récap des 3 missions |
| **GUIDE_PROJET.md** | Ce fichier (guide complet) |

### Documentation Externe

- [PHP 8.2 Documentation](https://www.php.net/docs.php)
- [MySQL 8.0 Reference](https://dev.mysql.com/doc/refman/8.0/en/)
- [Docker Docs](https://docs.docker.com/)
- [Stripe API](https://stripe.com/docs/api)
- [MDN Web Docs](https://developer.mozilla.org/) (HTML/CSS/JS)

### Commandes Docker Utiles

```bash
# Voir les logs en temps réel
docker-compose logs -f web

# Accéder au shell du container web
docker exec -it e-commerce-web-1 bash

# Accéder à MySQL
docker exec -it e-commerce-db-1 mysql -uecommerceuser -pecommercepass e_commerce

# Restart un service spécifique
docker-compose restart web

# Arrêter tout
docker-compose down

# Rebuild complet
docker-compose up -d --build
```

### Commandes MySQL Utiles

```sql
-- Voir toutes les tables
SHOW TABLES;

-- Structure d'une table
DESCRIBE produits;

-- Nombre de lignes
SELECT COUNT(*) FROM produits;

-- 10 derniers produits vus
SELECT * FROM produits_vus ORDER BY date_vue DESC LIMIT 10;

-- Réinitialiser compteur
UPDATE visites SET nombre_visites = 0;
```

---

## ✅ Checklist de Vérification

### Après Installation

- [ ] Docker containers démarrés (`docker ps`)
- [ ] Base de données importée (`SHOW TABLES;`)
- [ ] Site accessible sur http://localhost:8080
- [ ] Produits visibles sur page d'accueil
- [ ] Compteur de visites affiché dans footer
- [ ] Recherche AJAX fonctionne (taper "nike")
- [ ] Pas d'erreurs dans `docker logs e-commerce-web-1`

### Avant Développement

- [ ] Lire ARCHITECTURE.md
- [ ] Comprendre structure des dossiers
- [ ] Tester toutes les fonctionnalités manuellement
- [ ] Créer un compte de test
- [ ] Voir les logs en temps réel (`docker-compose logs -f`)

### Avant Déploiement Production

- [ ] Changer mots de passe MySQL
- [ ] Configurer vraies clés Stripe (`.env`)
- [ ] Désactiver `display_errors` PHP
- [ ] Activer HTTPS (certificat SSL)
- [ ] Configurer backups automatiques DB
- [ ] Tester sur environnement de staging

---

## 🎓 Concepts Clés

### 1. Requêtes Préparées (PDO)

**Pourquoi ?** Protection contre injection SQL

**Exemple** :
```php
// ❌ MAUVAIS (injection SQL possible)
$sql = "SELECT * FROM produits WHERE nom = '$nom'";

// ✅ BON (sécurisé)
$stmt = $conn->prepare("SELECT * FROM produits WHERE nom = ?");
$stmt->execute([$nom]);
```

### 2. Debouncing (AJAX)

**Pourquoi ?** Limiter les requêtes serveur

**Exemple** :
```javascript
let timeout;
input.addEventListener('input', () => {
    clearTimeout(timeout);  // Annule requête précédente
    timeout = setTimeout(() => {
        fetch('api.php');    // Lance après 300ms de pause
    }, 300);
});
```

### 3. Sessions PHP

**Pourquoi ?** Stocker données utilisateur côté serveur

**Exemple** :
```php
session_start();
$_SESSION['user_id'] = 42;           // Stocker
echo $_SESSION['user_id'];           // Lire
unset($_SESSION['user_id']);         // Supprimer
```

### 4. Foreign Keys (MySQL)

**Pourquoi ?** Intégrité référentielle

**Exemple** :
```sql
CREATE TABLE panier (
    id INT PRIMARY KEY,
    utilisateur_id INT,
    FOREIGN KEY (utilisateur_id) 
        REFERENCES utilisateur(id) 
        ON DELETE CASCADE  -- Supprime panier si utilisateur supprimé
);
```

---

## 🚀 Prochaines Étapes

### Court Terme (1-2 semaines)

1. **Ajouter plus de produits**
   ```sql
   -- Ajouter 20+ produits variés
   INSERT INTO produits ...
   ```

2. **Personnaliser le design**
   - Logo personnalisé
   - Couleurs de la marque
   - Footer avec infos légales

3. **Tester 2FA**
   - Créer compte
   - Activer 2FA
   - Vérifier avec app Google Authenticator

### Moyen Terme (1 mois)

1. **Système de filtres**
   - Filtre par prix
   - Filtre par marque
   - Filtre par catégorie

2. **Wishlist**
   - Table `wishlist`
   - Bouton "♥ Ajouter aux favoris"
   - Page mes favoris

3. **Système d'avis**
   - Utiliser table `avis` existante
   - Formulaire notation 1-5 étoiles
   - Affichage avis sur produit_details.php

### Long Terme (3-6 mois)

1. **Dashboard Admin**
   - Gestion produits (CRUD)
   - Gestion commandes
   - Statistiques ventes

2. **API REST**
   - Endpoints JSON
   - Documentation Swagger
   - App mobile possible

3. **Machine Learning**
   - Recommandations avancées
   - Prédiction stocks
   - Détection fraudes

---

## 📞 Support

### En Cas de Problème

1. **Vérifier les logs**
   ```bash
   docker-compose logs -f
   ```

2. **Consulter la documentation**
   - README.md
   - ARCHITECTURE.md
   - Ce guide

3. **Tester en isolation**
   ```bash
   # Test endpoint AJAX seul
   curl http://localhost:8080/produits/recherche_ajax.php?q=test
   ```

4. **Restart propre**
   ```bash
   docker-compose down
   docker-compose up -d
   ```

---

**Version** : 1.0.0  
**Dernière mise à jour** : 2025  
**Auteur** : Yacine  

✨ **Bon développement !** ✨
