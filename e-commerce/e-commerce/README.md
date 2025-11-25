# 🛍️ Site E-commerce Nike Basketball

Site e-commerce moderne développé avec PHP, MySQL et Docker, spécialisé dans la vente de chaussures Nike Basketball.

## 📋 Fonctionnalités

### Fonctionnalités principales
- ✅ **Catalogue de produits** - Affichage responsive avec grille
- ✅ **Page de détails produit** - Images, description, prix, avis
- ✅ **Panier d'achat** - Gestion des quantités, validation
- ✅ **Système d'authentification** - Inscription, connexion, 2FA
- ✅ **Paiement Stripe** - Intégration sécurisée
- ✅ **Gestion des commandes** - Historique, détails

### Fonctionnalités avancées
- 🔍 **Recherche AJAX en temps réel** - Suggestions instantanées avec images
- 💡 **Recommandations intelligentes** - "Vous pourriez aussi aimer" basé sur les catégories
- 📊 **Compteur de visites** - Suivi du trafic du site
- ⭐ **Système d'avis** - Notes et commentaires sur les produits
- 👤 **Profil utilisateur** - Gestion des informations personnelles

## 🛠️ Stack Technique

- **Backend** : PHP 8.2
- **Base de données** : MySQL 8.0
- **Frontend** : HTML5, CSS3, JavaScript (Vanilla)
- **Conteneurisation** : Docker + Docker Compose
- **Paiement** : Stripe API
- **Authentification** : 2FA avec Google Authenticator

## 🚀 Installation

### Prérequis
- Docker Desktop installé
- Git (optionnel)

### Étapes

1. **Cloner le projet** (si depuis Git)
```bash
git clone <votre-repo>
cd Projet_01_AP/e-commerce/e-commerce
```

2. **Démarrer Docker**
```bash
docker-compose up -d
```

3. **Importer la base de données**
```bash
docker exec -i e-commerce-db-1 mysql -uecommerceuser -pecommercepass e_commerce < ../../e_commerce.sql
```

4. **Accéder au site**
```
http://localhost:8080
```

## 🔧 Configuration

### Base de données
- **Host** : `db` (dans Docker) ou `localhost:3307` (depuis l'hôte)
- **Database** : `e_commerce`
- **User** : `ecommerceuser`
- **Password** : `ecommercepass`

### Ports
- **Web** : 8080 (externe) → 80 (interne)
- **MySQL** : 3307 (externe) → 3306 (interne)

### Variables d'environnement
Configurez vos clés Stripe dans `.env` :
```env
STRIPE_SECRET_KEY=sk_test_...
STRIPE_PUBLISHABLE_KEY=pk_test_...
```

## 📁 Structure du projet

```
e-commerce/
├── auth/                   # Authentification (login, register, 2FA)
├── config/                 # Configuration (DB, Stripe, compteur)
├── css/                    # Feuilles de style
├── images/                 # Images produits et assets
├── js/                     # Scripts JavaScript
├── panier/                 # Gestion du panier
├── payment/                # Processus de paiement
├── produits/               # Catalogue et détails produits
├── profile/                # Profil utilisateur
├── stripe/                 # Intégration Stripe
├── vendor/                 # Dépendances Composer
├── docker-compose.yml      # Configuration Docker
├── Dockerfile              # Image Docker
└── index.php               # Page d'accueil
```

## 🗄️ Structure de la base de données

### Tables principales
- `produits` - Catalogue de produits
- `utilisateur` - Comptes utilisateurs
- `panier` - Panier d'achat
- `commande` - Commandes passées
- `commande_produits` - Détails des commandes
- `avis` - Avis et notes produits
- `produits_vus` - Historique de navigation
- `visites` - Compteur de visites

## 🎯 Commandes utiles

### Docker
```bash
# Démarrer les conteneurs
docker-compose up -d

# Arrêter les conteneurs
docker-compose down

# Voir les logs
docker-compose logs -f

# Redémarrer
docker-compose restart

# Accéder à MySQL
docker exec -it e-commerce-db-1 mysql -uecommerceuser -pecommercepass e_commerce
```

### Base de données
```bash
# Exporter la base
docker exec e-commerce-db-1 mysqldump -uecommerceuser -pecommercepass e_commerce > backup.sql

# Importer un fichier SQL
docker exec -i e-commerce-db-1 mysql -uecommerceuser -pecommercepass e_commerce < fichier.sql
```

## 🔐 Sécurité

- ✅ Mots de passe hashés (bcrypt)
- ✅ Protection CSRF (tokens)
- ✅ Requêtes préparées (PDO)
- ✅ Authentification 2FA
- ✅ Sessions sécurisées
- ✅ Validation des entrées utilisateur

## 🎨 Personnalisation

### Modifier les styles
Éditez `css/styles.css` pour personnaliser l'apparence du site.

### Ajouter des produits
Connectez-vous à MySQL et insérez de nouveaux produits :
```sql
INSERT INTO produits (nom, marque, description, prix, image_url, image_hover_url, categorie) 
VALUES ('Nike Air Jordan', 'Nike', 'Description...', 150.00, 'images/product.jpg', 'images/product-hover.jpg', 'Basket');
```

## 📱 Responsive Design

Le site est entièrement responsive et s'adapte aux :
- 📱 Mobiles (< 768px)
- 📱 Tablettes (768px - 1024px)
- 💻 Desktop (> 1024px)

## 🐛 Dépannage

### Les produits ne s'affichent pas
1. Vérifiez que Docker est démarré : `docker-compose ps`
2. Vérifiez la connexion à la base : `docker exec -it e-commerce-db-1 mysql -uecommerceuser -pecommercepass e_commerce -e "SELECT COUNT(*) FROM produits;"`
3. Vérifiez les logs : `docker-compose logs web`

### Erreur de connexion MySQL
1. Redémarrez les conteneurs : `docker-compose restart`
2. Vérifiez que le port 3307 n'est pas déjà utilisé
3. Vérifiez les credentials dans `config/dbconnect.php`

### Cache PHP
Si vos modifications ne s'affichent pas :
```bash
docker-compose restart web
```

## 📄 Licence

Projet académique - 2025

## 👥 Contributeurs

Développé dans le cadre du Projet_01_AP

---

**Version** : 1.0.0  
**Dernière mise à jour** : Novembre 2025
