# Changelog

Toutes les modifications notables de ce projet seront documentées dans ce fichier.

## [1.0.0] - 2025-11-25

### Ajouté
- ✨ Système de recherche AJAX en temps réel avec suggestions
- 💡 Recommandations de produits basées sur les catégories vues
- 📊 Compteur de visites avec stockage en base de données
- ⭐ Système d'avis et de notes pour les produits
- 🎨 Design responsive pour mobile, tablette et desktop
- 🔐 Authentification à deux facteurs (2FA) avec Google Authenticator
- 💳 Intégration du paiement Stripe
- 🛒 Gestion complète du panier d'achat
- 👤 Profil utilisateur avec historique des commandes

### Structure
- 📁 Organisation modulaire du code (auth, config, panier, produits, etc.)
- 🐳 Conteneurisation avec Docker et Docker Compose
- 🗄️ Base de données MySQL avec 8 tables principales
- 🎨 Styles CSS organisés et responsive

### Technique
- ✅ Requêtes préparées PDO pour la sécurité
- ✅ Gestion des sessions PHP
- ✅ Validation des données côté serveur
- ✅ Code commenté et documenté
- ✅ Séparation des préoccupations (MVC-like)

### Sécurité
- 🔒 Hashage des mots de passe (bcrypt)
- 🔒 Protection contre les injections SQL
- 🔒 Authentification 2FA
- 🔒 Sessions sécurisées
- 🔒 Validation des entrées utilisateur

---

## Format du changelog

Ce changelog suit les principes de [Keep a Changelog](https://keepachangelog.com/fr/1.0.0/)
et adhère au [Semantic Versioning](https://semver.org/lang/fr/).

### Types de changements
- `Ajouté` pour les nouvelles fonctionnalités
- `Modifié` pour les changements dans les fonctionnalités existantes
- `Déprécié` pour les fonctionnalités bientôt retirées
- `Retiré` pour les fonctionnalités supprimées
- `Corrigé` pour les corrections de bugs
- `Sécurité` pour les vulnérabilités corrigées
