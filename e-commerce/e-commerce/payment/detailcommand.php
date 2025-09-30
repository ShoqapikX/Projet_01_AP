
<?php
require_once('../produits/listeProduits.php');
require_once('../auth/functionLogin.php');
require_once('../panier/FunctionCart.php');

// Si l'utilisateur est connecté, on récupère son ID
$clientId = $_SESSION['connectedUser']['id'];
$cartProducts = getCartProducts($clientId);
// Si le panier est vide, redirige l'utilisateur vers la page d'accueil
if (empty($cartProducts)) {
    header('Location: ../index.php?message=Votre panier est vide.');
    exit;
}

// Récupérer les produits du panier
$total = 0; // Initialisation du total


// Calculer le total de la commande
foreach ($cartProducts as $product) {
    $total += $product['prix'] * $product['quantite'];
}

// Vérifier si l'utilisateur est connecté pour afficher le formulaire d'adresse
$clientId = $_SESSION['connectedUser']['id'] ?? null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Résumé de la commande</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

    <a href="../index.php"><button class="back-button">← Retour à l'accueil</button></a>

    <div class="container">
        <h2>Résumé de votre commande</h2>

        <div id="cart-items">
            <?php foreach ($cartProducts as $product): 
                $sousTotal = $product['prix'] * $product['quantite'];
            ?>
                <p>Nom du produit : <?= htmlspecialchars($product['nom']); ?></p>
                <p>Prix : <?= htmlspecialchars($product['prix']); ?> €</p>
                <p>Quantité : <?= htmlspecialchars($product['quantite']); ?></p>
                <p>Sous-total : <?= number_format($sousTotal, 2); ?> €</p>
                <hr>
            <?php endforeach; ?>

            <p><strong>Total de la commande : <?= number_format($total, 2); ?> €</strong></p>
        </div>

        <h3>Adresse de livraison</h3>
        <form action="paiement.php" method="POST">
            <button type="submit" id="passer-a-la-caisse">Passer à la caisse</button>
        </form>
    </div>

</body>
</html>
