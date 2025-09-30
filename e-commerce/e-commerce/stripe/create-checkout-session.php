<?php
require_once('../vendor/autoload.php');
require_once('../config/dbconnect.php');
session_start();

\Stripe\Stripe::setApiKey('sk_test_VOTRE_CLE_SECRETE'); // ⚠️ À sécuriser

if (!isset($_SESSION['connectedUser'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Utilisateur non connecté.']);
    exit;
}

$userId = $_SESSION['connectedUser']['id'];

$conn = connectDB();

// Récupération des produits du panier avec leur nom réel
$stmt = $conn->prepare("
    SELECT p.*, pr.nom 
    FROM panier p
    JOIN produits pr ON p.produit_id = pr.id
    WHERE p.user_id = ?
");
$stmt->execute([$userId]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Préparation des données Stripe
$line_items = [];

foreach ($items as $item) {
    $line_items[] = [
        'price_data' => [
            'currency' => 'eur',
            'product_data' => ['name' => $item['nom']],
            'unit_amount' => intval($item['prix'] * 100), // Stripe veut des centimes
        ],
        'quantity' => $item['quantite'],
    ];
}

$YOUR_DOMAIN = 'http://localhost/e-commerce';

$checkout_session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'line_items' => $line_items,
    'mode' => 'payment',
    'success_url' => $YOUR_DOMAIN . '/success.html',
    'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
]);

echo json_encode(['id' => $checkout_session->id]);
