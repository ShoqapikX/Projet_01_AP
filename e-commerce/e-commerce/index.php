<?php
require_once('./produits/listeProduits.php');
require_once('./auth/functionLogin.php');
require_once('panier/FunctionCart.php');

// Si l'utilisateur est connecté, on récupère son ID
$clientId = isset($_SESSION['connectedUser']['id']) ? $_SESSION['connectedUser']['id'] : null;
$cartProducts = $clientId ? getCartProducts($clientId) : [];
$AllProduits = getProduits();
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

<header>
    <nav>
        <div class="nav-container">
            <div class="logo">
                <img src="images/logo.png" alt="Logo Nike" class="logo-img">
            </div>

            <!-- Visitor counter: integrated badge (count + label) -->
            <div id="visitor-counter" class="visitor-counter" aria-hidden="false" title="personnes ayant visité le site">
                <div class="visitor-badge" role="status" aria-live="polite">
                    <span class="visitor-count">0</span>
                    <span class="visitor-text">personnes ayant visité le site</span>
                </div>
            </div>
            <ul class="nav-links">
                <li><a href="index.php">Accueil</a></li>
                <li><a href="index.php#new-arrivals">Nouveautés</a></li>
                <li><a href="index.php#products">Produits</a></li>
                	<li><a href="index.php#contact">Contact</a></li>
                	<!-- Search: icone loupe + zone de recherche qui apparaît au survol -->
                	<li class="search-container">
                	    <button class="search-icon" aria-label="Recherche" id="search-toggle" type="button">
                	        <!-- Simple SVG loupe -->
                	        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                	            <path d="M21 21l-4.35-4.35" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                	            <circle cx="11" cy="11" r="6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                	        </svg>
                	    </button>
                	    <div class="search-box" id="search-box">
                	        <input type="text" id="search-input" placeholder="Rechercher des produits..." autocomplete="off">
                	        <ul id="search-suggestions" class="search-suggestions" role="listbox" aria-label="Suggestions de recherche"></ul>
                	    </div>
                	</li>
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
</footer>

<script src="js/script.js"></script>
</body>
</html>
