<?php
/**
 * Page d'accueil du site e-commerce Nike Basketball
 * 
 * Fonctionnalités :
 * - Affichage du catalogue de produits
 * - Slider de présentation
 * - Recherche AJAX en temps réel
 * - Compteur de visites
 * - Gestion du panier
 * 
 * @package E-Commerce
 * @version 1.0.0
 */

// Activer l'affichage des erreurs en mode développement (à désactiver en production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

$errorMessage = null;

try {
    require_once('./produits/listeProduits.php');
    require_once('./auth/functionLogin.php');
    require_once('panier/FunctionCart.php');
    require_once('./config/compteur_visites.php');

    // Initialiser la table des visites si nécessaire
    try {
        initTableVisites();
        
        // Incrémenter le compteur de visites si nouvelle visite
        if (estNouvelleVisite()) {
            incrementerVisites();
        }
        
        // Récupérer le nombre total de visites
        $nombreVisites = getNombreVisites();
    } catch (Exception $e) {
        // Si erreur sur le compteur, continuer quand même
        $nombreVisites = 0;
    }

    // Si l'utilisateur est connecté, on récupère son ID
    $clientId = isset($_SESSION['connectedUser']['id']) ? $_SESSION['connectedUser']['id'] : null;
    $cartProducts = $clientId ? getCartProducts($clientId) : [];
    $AllProduits = getProduits();
    $searchResults = []; // Initialisation vide

    // Gestion de la recherche
    if (isset($_GET['query']) && !empty(trim($_GET['query']))) {
        $search = strtolower(trim($_GET['query']));
        $searchResults = array_filter($AllProduits, function ($prod) use ($search) {
            return strpos(strtolower($prod['nom']), $search) !== false;
        });
    }
} catch (Exception $e) {
    $errorMessage = $e->getMessage();
    $AllProduits = [];
    $cartProducts = [];
    $nombreVisites = 0;
    $clientId = null;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nike Basketball</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<?php if ($errorMessage): ?>
<div style="background-color: #f8d7da; color: #721c24; padding: 20px; margin: 20px; border: 1px solid #f5c6cb; border-radius: 5px;">
    <h2>⚠️ Erreur de connexion à la base de données</h2>
    <p><strong>Message d'erreur :</strong> <?= htmlspecialchars($errorMessage) ?></p>
    <p><strong>Solutions :</strong></p>
    <ul>
        <li>Vérifiez que Docker est lancé : <code>docker-compose up -d</code></li>
        <li>Vérifiez que MySQL/MariaDB est actif</li>
        <li>Vérifiez les identifiants dans <code>config/dbconnect.php</code></li>
        <li>Importez le fichier SQL dans votre base de données</li>
    </ul>
    <div style="margin-top: 15px;">
        <a href="test_config.php" style="display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px;">🔧 Tester la configuration</a>
        <a href="test_debug.php" style="display: inline-block; padding: 10px 20px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px;">🔍 Diagnostic complet</a>
    </div>
</div>
<?php endif; ?>

<?php if (empty($AllProduits) && !$errorMessage): ?>
<div style="background-color: #fff3cd; color: #856404; padding: 20px; margin: 20px; border: 1px solid #ffeeba; border-radius: 5px;">
    <h2>📦 Aucun produit trouvé</h2>
    <p>La connexion à la base de données fonctionne, mais aucun produit n'est disponible.</p>
    <p><strong>Solutions :</strong></p>
    <ul>
        <li>Importez le fichier <code>e_commerce.sql</code> dans votre base de données</li>
        <li>Ajoutez des produits via phpMyAdmin</li>
        <li>Vérifiez que la table 'produits' contient des données</li>
    </ul>
    <div style="margin-top: 15px;">
        <a href="test_debug.php" style="display: inline-block; padding: 10px 20px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px;">🔍 Diagnostic complet</a>
    </div>
</div>
<?php endif; ?>

<header>
    <nav>
        <div class="nav-container">
            <div class="logo">
                <img src="images/logo.png" alt="Logo Nike" class="logo-img">
            </div>
            <ul class="nav-links">
                <li><a href="index.php">Accueil</a></li>
                <li><a href="index.php#new-arrivals">Nouveautés</a></li>
                <li><a href="index.php#products">Produits</a></li>
                <li><a href="index.php#contact">Contact</a></li>
            </ul>

            <div class="account" style="display: flex; align-items: center; gap: 15px;">
                <div class="cart" style="position: relative; width: 24px; height: 24px;">
                    <img src="images/cart_reel.png" alt="Panier" class="cart-icon" style="width: 100%; height: 100%;">
                    <span id="cart-count" style="position: absolute; top: -10px; right: -10px; background-color: red; color: white; border-radius: 50%; padding: 2px 6px; font-size: 12px; font-weight: bold;">
                        <?= $clientId ? getCartCount($clientId) : 0 ?>
                    </span>
                </div>

                <img src="images/account-icon.webp" alt="Compte" class="account-icon" id="account-icon">
                <div style="display: flex; flex-direction: column;">
                    <?php if (isUserLoggedIn()): ?>
                        <span style="font-size: 14px;"><?= htmlspecialchars($_SESSION['connectedUser']['nom']); ?></span>
                        <div class="account-dropdown" id="account-dropdown">
                            <ul>
                                <li><a href="./profile/profile.php">Mon profil</a></li>
                                <li><a href="./config/logout.php">Se Déconnecter</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="./auth/login.php">Se connecter</a>
                    <?php endif; ?>
                </div>
            </div>

            <div id="cart-modal" class="cart-popup">
                <div class="cart-popup-content">
                    <span class="close-popup"> &times;</span>
                    <h3>Votre Panier</h3>
                    <div id="cart-items">
                        <?php if (!empty($cartProducts)): ?>
                            <?php foreach ($cartProducts as $product): ?>
                                <p>Nom du produit : <?= htmlspecialchars($product['nom']); ?></p>
                                <p>Prix : <?= htmlspecialchars($product['prix']); ?> €</p>
                                <p>Quantité : <?= htmlspecialchars($product['quantite']); ?></p> 
                                <form action="panier/controller_quantite.php" method="POST" class="update-quantity-form">
                                    <input type="hidden" name="Id_produits" value="<?= htmlspecialchars($product['id']); ?>">
                                    <input type="hidden" name="Id_Clients" value="<?= htmlspecialchars($clientId); ?>">
                                    <button type="submit" class="add-to-cart" name="enlever">-</button>
                                </form>
                                <hr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Votre panier est vide</p>
                        <?php endif; ?>
                    </div>
                    <form action="payment/detailcommand.php" method="POST" style="display: inline-block;">
                        <button type="submit" id="valider-panier">Passer à la caisse</button>
                    </form>
                    <form action="./panier/vider_panier.php" method="POST" style="display: inline-block;">
                        <button type="submit" id="vider-panier">Vider le panier</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
</header>

<section id="home">
    <div class="slider">
        <div class="slide active">
            <img src="images/basket1.jpg" alt="Nike Basket 1">
            <div class="caption">Nike Air Zoom BB NXT</div>
        </div>
        <div class="slide">
            <img src="images/basket2.jpg" alt="Nike Basket 2">
            <div class="caption">Nike LeBron 18</div>
        </div>
        <div class="slide">
            <img src="images/basket3.jpg" alt="Nike Basket 3">
            <div class="caption">Nike KD 14</div>
        </div>
        <button class="prev">❮</button>
        <button class="next">❯</button>
    </div>
</section>
    <section id="new-arrivals">
    <form method="GET" action="index.php" class="search-form" style="position: relative;">
        <input type="text" 
               name="query" 
               id="search-input-ajax"
               placeholder="Rechercher un produit..." 
               value="<?= isset($_GET['query']) ? htmlspecialchars($_GET['query']) : '' ?>" 
               class="search-input"
               autocomplete="off">
        <button type="submit" class="search-button">🔍</button>
        <div id="search-suggestions" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #ddd; border-top: none; max-height: 400px; overflow-y: auto; z-index: 1000; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"></div>
    </form>
    <?php if (isset($_GET['query']) && !empty(trim($_GET['query']))): ?>
        <section id="search-results">
            <h2>Résultats pour "<?= htmlspecialchars($_GET['query']) ?>"</h2>
            <div class="product-grid">
                <?php if (!empty($searchResults)): ?>
                    <?php foreach ($searchResults as $prod): ?>
                        <div class="product">
                            <img src="<?= $prod['image_url']; ?>" alt="<?= htmlspecialchars($prod['nom']); ?>" class="product-image" data-hover="<?= $prod['image_hover_url']; ?>">
                            <p><?= htmlspecialchars($prod['nom']); ?></p>
                            <p><?= number_format($prod['prix'], 2, ',', ' '); ?> €</p>
                            <a href="./produits/produit_details.php?id=<?= $prod['id'] ?>" class="details-button">Afficher les détails</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucun produit trouvé.</p>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>
    </section>
<section id="new-arrivals">
    <h2>Nouveautés</h2>
    <div class="product-grid">
        <?php foreach ($AllProduits as $prod): ?>  
            <div class="product">
                <img src="<?= $prod['image_url']; ?>" alt="<?= htmlspecialchars($prod['nom']); ?>" class="product-image" data-hover="<?= $prod['image_hover_url']; ?>">
                <p><?= htmlspecialchars($prod['nom']); ?></p>
                <p><?= number_format($prod['prix'], 2, ',', ' '); ?> €</p>    
                <a href="./produits/produit_details.php?id=<?= $prod['id'] ?>" class="details-button">Afficher les détails</a>
            </div>  
        <?php endforeach; ?>
    </div>
</section>

<section id="products">
    <h2>Tous les Produits</h2>
    <div class="product-grid">
        <?php foreach ($AllProduits as $prod): ?> 
            <div class="product">
                <img src="<?= $prod['image_url']; ?>" alt="<?= htmlspecialchars($prod['nom']); ?>" class="product-image" data-hover="<?= $prod['image_hover_url']; ?>">
                <p><?= htmlspecialchars($prod['nom']); ?></p>
                <p><?= number_format($prod['prix'], 2, ',', ' '); ?> €</p>    
                <a href="./produits/produit_details.php?id=<?= $prod['id'] ?>" class="details-button">Afficher les détails</a>
            </div>  
        <?php endforeach; ?>
    </div>
    
</section>

<section id="contact">
    <h2>Contactez-nous</h2>
    <form id="contact-form">
        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" id="name" name="name" placeholder="Votre nom" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Votre email" required>
        </div>
        <div class="form-group">
            <label for="message">Message</label>
            <textarea id="message" name="message" rows="5" placeholder="Votre message" required></textarea>
        </div>
        <button type="submit">Envoyer</button>
    </form>
</section>

<footer>
    <div class="footer-container">
        <div class="footer-links">
            <a href="#home">Accueil</a>
            <a href="#new-arrivals">Nouveautés</a>
            <a href="#products">Produits</a>
            <a href="#contact">Contact</a>
        </div>
        <div class="footer-social">
            <a href="https://facebook.com" target="_blank"><img src="images/facebook-icon.png" alt="Facebook"></a>
            <a href="https://twitter.com" target="_blank"><img src="images/x-icon.png" alt="x"></a>
        </div>
    </div>
    <p>&copy; 2024 Nike Basketball. Tous droits réservés.</p>
    <p style="font-size: 14px; color: #888; margin-top: 10px;">👥 Nombre de visites : <strong><?= number_format($nombreVisites, 0, ',', ' ') ?></strong></p>
</footer>

<script src="js/script.js"></script>
</body>
</html>
