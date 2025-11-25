# 🏗️ ARCHITECTURE DU PROJET E-COMMERCE

## 📐 Vue d'Ensemble

### Stack Technique
```
Frontend:
├── HTML5 (structure)
├── CSS3 (styles responsive)
└── JavaScript Vanilla (AJAX, animations)

Backend:
├── PHP 8.2 (logique métier)
├── PDO (accès base de données)
└── Composer (gestion dépendances)

Base de Données:
└── MySQL 8.0 (stockage persistant)

Infrastructure:
├── Docker (containerisation)
├── Docker Compose (orchestration)
└── Apache 2.4 (serveur web)

Paiements & Sécurité:
├── Stripe API (paiements)
└── Google Authenticator (2FA)
```

---

## 🗂️ Structure des Dossiers

```
e-commerce/
│
├── auth/                          # Authentification & Sécurité
│   ├── functionInsription.php     # Logique inscription
│   ├── functionLogin.php          # Logique connexion
│   ├── login.php                  # Page de connexion
│   ├── register.php               # Page d'inscription
│   ├── show_qrcode.php            # QR Code pour 2FA
│   └── verify_2af.php             # Vérification 2FA
│
├── commande/                      # Gestion des Commandes
│   └── get_commande.php           # Récupération commandes
│
├── config/                        # Configuration & Utilitaires
│   ├── dbconnect.php              # Connexion base de données
│   ├── logout.php                 # Déconnexion
│   ├── stripe.php                 # Configuration Stripe
│   └── compteur_visites.php       # ✨ Compteur de visites (NOUVEAU)
│
├── css/                           # Feuilles de Style
│   ├── csspro.css                 # Styles professionnels
│   ├── dashboard.css              # Dashboard admin
│   ├── login.css                  # Page login
│   ├── profile.css                # Page profil
│   ├── register.css               # Page inscription
│   └── styles.css                 # 🎨 Styles globaux (amélioré)
│
├── images/                        # Ressources Visuelles
│   ├── logo.png
│   ├── nike*.jpg                  # Images produits Nike
│   ├── basket*.jpg                # Images baskets
│   └── icons (account, cart, etc.)
│
├── js/                            # Scripts JavaScript
│   └── script.js                  # 🚀 Script principal (AJAX search ajouté)
│
├── panier/                        # Gestion du Panier
│   ├── FunctionCart.php           # Fonctions panier
│   ├── fonction_ajout_cart.php    # Ajout au panier
│   ├── fonction_quantite.php      # Gestion quantités
│   ├── controller_quantite.php    # Contrôleur quantités
│   └── vider_panier.php           # Vidage panier
│
├── payment/                       # Processus de Paiement
│   ├── paiement.php               # Page paiement
│   ├── confirmation.php           # Confirmation commande
│   ├── success.php                # Paiement réussi
│   ├── cancel.php                 # Paiement annulé
│   └── detailcommand.php          # Détails commande
│
├── produits/                      # Catalogue Produits
│   ├── listeProduits.php          # Liste tous les produits
│   ├── produit_details.php        # Détails d'un produit
│   ├── ajouter_avis.php           # Ajouter un avis
│   ├── recommandations.php        # 🤖 Recommandations IA-like (NOUVEAU)
│   └── recherche_ajax.php         # 🔍 Recherche AJAX (NOUVEAU)
│
├── profile/                       # Gestion Profil Utilisateur
│   ├── profile.php                # Page profil
│   └── profile_info.php           # Informations profil
│
├── stripe/                        # Intégration Stripe
│   └── create-checkout-session.php # Session paiement Stripe
│
├── vendor/                        # Dépendances Composer
│   ├── stripe/stripe-php/         # SDK Stripe
│   └── sonata-project/google-authenticator/ # 2FA
│
├── index.php                      # 🏠 Page d'accueil
├── ajout_cart.php                 # Ajout rapide au panier
│
├── docker-compose.yml             # Configuration Docker
├── Dockerfile                     # Image Docker web
├── composer.json                  # Dépendances PHP
│
├── README.md                      # Documentation projet
├── CHANGELOG.md                   # Historique versions
├── RECAPITULATIF_FINAL.md         # Récapitulatif des missions
└── ARCHITECTURE.md                # Ce fichier
```

---

## 🗄️ Schéma de Base de Données

### Diagramme ER (Entity-Relationship)

```
┌─────────────────┐
│   utilisateur   │
├─────────────────┤
│ id (PK)         │
│ nom             │
│ prenom          │
│ email           │◄──────┐
│ password        │       │
│ secret_2fa      │       │
│ created_at      │       │
└─────────────────┘       │
         │                │
         │                │
         │1              n│
         ▼                │
┌─────────────────┐       │
│    commande     │       │
├─────────────────┤       │
│ id (PK)         │       │
│ utilisateur_id (FK)     │
│ total           │       │
│ statut          │       │
│ date_creation   │       │
└─────────────────┘       │
                          │
┌─────────────────┐       │
│     panier      │       │
├─────────────────┤       │
│ id (PK)         │       │
│ utilisateur_id (FK)◄────┘
│ produit_id (FK) │───┐
│ quantite        │   │
│ date_ajout      │   │
└─────────────────┘   │
                      │
┌─────────────────┐   │
│    produits     │◄──┘
├─────────────────┤
│ id (PK)         │◄──────┐
│ nom             │       │
│ marque          │       │
│ prix            │       │
│ description     │       │
│ image_url       │       │
│ image_hover_url │       │
│ stock           │       │
│ categorie       │ ✨     │
└─────────────────┘       │
         │                │
         │1              n│
         ▼                │
┌─────────────────┐       │
│  produits_vus   │ ✨    │
├─────────────────┤       │
│ id (PK)         │       │
│ utilisateur_id  │       │
│ session_id      │       │
│ produit_id (FK) │───────┘
│ date_vue        │
└─────────────────┘

┌─────────────────┐
│     visites     │ ✨
├─────────────────┤
│ id (PK)         │
│ nombre_visites  │
│ date_derniere   │
└─────────────────┘

┌─────────────────┐
│      avis       │ ✨
├─────────────────┤
│ id (PK)         │
│ produit_id (FK) │
│ utilisateur_id (FK)
│ note (1-5)      │
│ commentaire     │
│ date_creation   │
└─────────────────┘
```

**Légende :** ✨ = Nouveau (ajouté dans ce projet)

---

## 🔄 Flux de Données

### 1. Flux d'Authentification

```
┌────────┐
│ Client │
└───┬────┘
    │ 1. Accès login.php
    ▼
┌───────────────┐
│  login.php    │
└───┬───────────┘
    │ 2. Soumission formulaire
    ▼
┌─────────────────────┐
│ functionLogin.php   │
└───┬─────────────────┘
    │ 3. Vérification DB
    ▼
┌─────────────────┐
│   MySQL (user)  │
└───┬─────────────┘
    │ 4. Si 2FA activé
    ▼
┌─────────────────────┐
│   verify_2af.php    │
└───┬─────────────────┘
    │ 5. Google Auth
    ▼
┌─────────────────────────┐
│ Session créée → index.php│
└─────────────────────────┘
```

### 2. Flux de Recherche AJAX (✨ NOUVEAU)

```
┌────────────────┐
│  Input Search  │
└───────┬────────┘
        │ Saisie utilisateur
        │ (debounce 300ms)
        ▼
┌─────────────────────┐
│    script.js        │
│  (event listener)   │
└───────┬─────────────┘
        │ fetch()
        ▼
┌──────────────────────────┐
│  recherche_ajax.php      │
│  ┌────────────────────┐  │
│  │ SQL: LIKE %query%  │  │
│  └────────────────────┘  │
└───────┬──────────────────┘
        │ JSON response
        ▼
┌─────────────────────────┐
│  DOM: suggestions div   │
│  • Image produit        │
│  • Nom + Marque         │
│  • Prix                 │
└─────────────────────────┘
```

### 3. Flux de Recommandations (✨ NOUVEAU)

```
┌─────────────────────────┐
│  produit_details.php    │
└────────┬────────────────┘
         │ 1. Enregistrement vue
         ▼
┌─────────────────────────┐
│ enregistrerProduitVu()  │
│  INSERT produits_vus    │
└────────┬────────────────┘
         │ 2. Récupération catégories
         ▼
┌──────────────────────────┐
│ getCategoriesProduitsVus│
│  GROUP BY categorie     │
└────────┬─────────────────┘
         │ 3. Recommandations
         ▼
┌──────────────────────────┐
│ getProduitsRecommandes  │
│  WHERE categorie IN (…) │
│  AND id NOT IN (vus)    │
│  ORDER BY RAND()        │
│  LIMIT 4                │
└────────┬─────────────────┘
         │ 4. Affichage
         ▼
┌─────────────────────────┐
│ Section "Vous pourriez  │
│  aussi aimer"           │
└─────────────────────────┘
```

### 4. Flux du Compteur de Visites (✨ NOUVEAU)

```
┌─────────────────┐
│   index.php     │
└────────┬────────┘
         │ 1. Include compteur_visites.php
         ▼
┌─────────────────────┐
│ initTableVisites()  │
│  CREATE IF NOT EXISTS
└────────┬────────────┘
         │ 2. Check session
         ▼
┌──────────────────────┐
│ estNouvelleVisite() │
│  if (!$_SESSION[…]) │
└────────┬─────────────┘
         │ 3. Si nouvelle visite
         ▼
┌──────────────────────┐
│ incrementerVisites() │
│  UPDATE visites SET  │
│  nombre = nombre + 1 │
└────────┬─────────────┘
         │ 4. Affichage
         ▼
┌──────────────────────┐
│ getNombreVisites()   │
│  SELECT nombre_visites
└────────┬─────────────┘
         │ 5. Footer
         ▼
┌──────────────────────┐
│ "X visites"          │
└──────────────────────┘
```

---

## 🔐 Sécurité

### Mesures Implémentées

#### 1. Injection SQL
```php
// ✅ Requêtes préparées partout
$stmt = $conn->prepare("SELECT * FROM produits WHERE id = ?");
$stmt->execute([$id]);
```

#### 2. XSS (Cross-Site Scripting)
```php
// ✅ Échappement des sorties
echo htmlspecialchars($nomProduit, ENT_QUOTES, 'UTF-8');
```

#### 3. CSRF (Cross-Site Request Forgery)
```php
// ⚠️ TODO: Implémenter tokens CSRF sur formulaires
```

#### 4. Authentification 2FA
```php
// ✅ Google Authenticator via sonata-project
require_once 'vendor/sonata-project/google-authenticator';
```

#### 5. Hashage Mots de Passe
```php
// ✅ password_hash() et password_verify()
$hash = password_hash($password, PASSWORD_DEFAULT);
```

---

## ⚡ Performance

### Optimisations Actuelles

#### 1. Debouncing AJAX
```javascript
// Évite surcharge serveur
let searchTimeout;
clearTimeout(searchTimeout);
searchTimeout = setTimeout(() => fetch(...), 300);
```

#### 2. Limitation Résultats
```sql
-- Maximum 8 résultats de recherche
SELECT * FROM produits WHERE ... LIMIT 8;

-- Maximum 4 recommandations
SELECT * FROM produits WHERE ... LIMIT 4;
```

#### 3. Sessions PHP
```php
// Compteur basé sur session (évite requêtes DB multiples)
if (!isset($_SESSION['visite_enregistree'])) {
    incrementerVisites();
}
```

### Optimisations Futures Recommandées

#### 1. Indexes Base de Données
```sql
-- Accélérer recherches
CREATE INDEX idx_produits_nom ON produits(nom);
CREATE INDEX idx_produits_categorie ON produits(categorie);
CREATE INDEX idx_produits_vus_user ON produits_vus(utilisateur_id);
```

#### 2. Cache Redis
```php
// Cache recommandations pendant 1 heure
$redis->setex("recommendations:user:{$userId}", 3600, json_encode($produits));
```

#### 3. CDN pour Images
```html
<!-- Servir images depuis CDN -->
<img src="https://cdn.example.com/images/nike1.jpg">
```

#### 4. Lazy Loading Images
```html
<img loading="lazy" src="...">
```

---

## 🧪 Tests

### Tests Manuels Effectués

#### 1. Compteur de Visites
```bash
✅ curl http://localhost:8080/index.php | grep "visites"
# Résultat: "6 visites" → Fonctionnel
```

#### 2. Recherche AJAX
```bash
✅ curl "http://localhost:8080/produits/recherche_ajax.php?q=nike"
# Résultat: [{"id":2,"nom":"Nike Air Force 1",...}]
```

#### 3. Recommandations
```bash
✅ Testé via navigateur sur produit_details.php
# Résultat: Section "Vous pourriez aussi aimer" visible
```

### Tests Automatisés (TODO)

```php
// phpunit.xml
class CompteurVisitesTest extends TestCase {
    public function testIncrementationVisite() {
        // Assert visites incrémente correctement
    }
}

class RecommandationsTest extends TestCase {
    public function testRecommandationsParCategorie() {
        // Assert recommandations filtrées par catégorie
    }
}
```

---

## 📦 Déploiement

### Environnement de Développement

```bash
# 1. Cloner le repo
git clone <url>

# 2. Démarrer Docker
docker-compose up -d

# 3. Importer la base
docker exec -i e-commerce-db-1 mysql -u... -p... e_commerce < e_commerce.sql

# 4. Accéder au site
open http://localhost:8080
```

### Environnement de Production

#### Prérequis
- VPS (DigitalOcean, AWS, OVH)
- Nom de domaine
- Certificat SSL (Let's Encrypt)

#### Steps
```bash
# 1. Installer Docker sur serveur
curl -fsSL https://get.docker.com -o get-docker.sh
sh get-docker.sh

# 2. Cloner le projet
git clone <url> /var/www/ecommerce

# 3. Variables d'environnement
cp .env.example .env
# Éditer .env avec vraies clés Stripe, DB prod

# 4. Build & Run
docker-compose -f docker-compose.prod.yml up -d

# 5. Nginx reverse proxy
# Configurer Nginx pour SSL + proxy vers :8080
```

---

## 🔧 Maintenance

### Logs à Surveiller

```bash
# Logs Apache
docker logs e-commerce-web-1

# Logs MySQL
docker logs e-commerce-db-1

# Erreurs PHP
tail -f /var/log/apache2/error.log
```

### Backup Base de Données

```bash
# Backup quotidien (cron)
0 2 * * * docker exec e-commerce-db-1 mysqldump -u... -p... e_commerce > /backups/e_commerce_$(date +\%Y\%m\%d).sql
```

### Monitoring

```yaml
# docker-compose.yml - ajout Prometheus
services:
  prometheus:
    image: prom/prometheus
    ports:
      - "9090:9090"
  
  grafana:
    image: grafana/grafana
    ports:
      - "3000:3000"
```

---

## 📚 Dépendances

### PHP (composer.json)

```json
{
  "require": {
    "php": "^8.0",
    "stripe/stripe-php": "^10.0",
    "sonata-project/google-authenticator": "^2.3"
  }
}
```

### JavaScript

- **Aucune dépendance externe** (Vanilla JS uniquement)
- Fetch API native (pas de jQuery)
- ES6+ moderne

---

## 🎨 Design System

### Palette de Couleurs

```css
:root {
  --primary-color: #000000;      /* Noir Nike */
  --secondary-color: #FFFFFF;    /* Blanc */
  --accent-color: #FA5C00;       /* Orange Nike */
  --text-color: #111111;
  --bg-color: #F5F5F5;
  --border-color: #E5E5E5;
}
```

### Typographie

```css
body {
  font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
  font-size: 16px;
  line-height: 1.6;
}

h1 { font-size: 2.5rem; font-weight: 700; }
h2 { font-size: 2rem; font-weight: 600; }
```

### Breakpoints Responsive

```css
/* Mobile */
@media (max-width: 768px) { ... }

/* Tablet */
@media (min-width: 769px) and (max-width: 1024px) { ... }

/* Desktop */
@media (min-width: 1025px) { ... }
```

---

## 🚀 Évolutions Futures

### Phase 1 (Court Terme)
- [ ] Ajouter système de filtres (prix, marque, taille)
- [ ] Implémenter wishlist (liste de souhaits)
- [ ] Système de notation produits (étoiles)
- [ ] Newsletter avec envoi emails

### Phase 2 (Moyen Terme)
- [ ] Dashboard admin complet
- [ ] Gestion stock en temps réel
- [ ] Multi-devises (EUR, USD, GBP)
- [ ] Multi-langues (FR, EN, ES)

### Phase 3 (Long Terme)
- [ ] Application mobile (React Native)
- [ ] API RESTful documentée
- [ ] Machine Learning pour recommandations avancées
- [ ] Chatbot service client (IA)

---

**Version** : 1.0.0  
**Date** : 2025  
**Auteur** : Yacine
