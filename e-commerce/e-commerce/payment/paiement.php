<?php

// Inclusion des fichiers nécessaires
require_once __DIR__ . '/../config/stripe.php';  // Fichier de configuration Stripe, qui doit contenir la clé API
require_once __DIR__ . '/../panier/FunctionCart.php';  // Fonction pour récupérer les produits du panier
require_once __DIR__ . '/../auth/functionLogin.php';  // Fonction pour vérifier si l'utilisateur est connecté

// Démarrer la session si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté
if (!isUserLoggedIn()) {
    echo '
        <a href="index.php" style="position: absolute; top: 20px; left: 20px; text-decoration: none; font-weight: bold; font-size: 16px; background-color: #f3f3f3; padding: 10px 20px; border-radius: 10px; color: #000; display: inline-flex; align-items: center; gap: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); transition: background-color 0.3s;">&#8592; Retour à l\'accueil</a>

        <div style="display: flex; justify-content: center; align-items: center; height: 100vh; font-size: 32px; font-weight: bold; color: #333; text-align: center;">
            ⚠️ Vous devez être connecté pour passer à la caisse.
        </div>
    ';
    exit;
}

// Récupérer les informations de livraison depuis le formulaire
$adresse = null;
$codePostal = null;
$ville = null;
$pays = null;


// Récupérer les produits du panier pour l'utilisateur connecté
$clientId = $_SESSION['connectedUser']['id'];
$cartProducts = getCartProducts($clientId);

// Vérifier si le panier est vide
if (empty($cartProducts)) {
    echo '
    <a href="index.php" style="position: absolute; top: 20px; left: 20px; text-decoration: none; font-weight: bold; font-size: 16px; background-color: #f3f3f3; padding: 10px 20px; border-radius: 10px; color: #000; display: inline-flex; align-items: center; gap: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); transition: background-color 0.3s;">&#8592; Retour à l\'accueil</a>

    <div style="display: flex; justify-content: center; align-items: center; height: 100vh; font-size: 32px; font-weight: bold; color: #333; text-align: center;">
        ⚠️ Votre panier est vide.
    </div>';
    exit;
}

$total = 0;  // Initialisation du total

// Calculer le total de la commande
foreach ($cartProducts as $product) {
    $total += $product['prix'] * $product['quantite'];
}

// Préparer les articles pour Stripe
$line_items = [];
foreach ($cartProducts as $product) {
    $line_items[] = [
        'price_data' => [
            'currency' => 'eur',
            'product_data' => [
                'name' => $product['nom'],
            ],
            'unit_amount' => intval($product['prix'] * 100),  // Convertir en centimes
        ],
        'quantity' => $product['quantite'],
    ];
}

$YOUR_DOMAIN = 'http://localhost/e-commerce/e-commerce';  // Votre domaine de test

try {
    // Créer une session de paiement Stripe en ajoutant les informations de livraison
    $checkout_session = \Stripe\Checkout\Session::create([
        'line_items' => $line_items,
        'mode' => 'payment',  // Mode de paiement
        'success_url' => $YOUR_DOMAIN . '/payment/success.php?session_id={CHECKOUT_SESSION_ID}',  // URL en cas de succès
        'cancel_url' => $YOUR_DOMAIN . '/payment/cancel.php',  // URL en cas d'annulation
        'shipping_address_collection' => [
            'allowed_countries' => ['US', 'CA', 'FR'], // Exemple : permet de choisir des pays spécifiques
        ],
        'shipping' => [
            'address' => [
                'line1' => $adresse,
                'postal_code' => $codePostal,
                'city' => $ville,
                'country' => $pays,
            ]
        ]
    ]);

    // Rediriger l'utilisateur vers Stripe Checkout pour le paiement
    header("Location: " . $checkout_session->url);
    exit;
} catch (Exception $e) {
    // Si une erreur survient, afficher le message d'erreur
    echo 'Erreur de création de session Stripe: ' . $e->getMessage();
}
?>
