<?php
session_start();
require_once __DIR__ . '/../config/stripe.php';  // Inclure la configuration Stripe
require_once('../produits/listeProduits.php');
require_once('../auth/functionLogin.php');
require_once('../panier/FunctionCart.php');

// Vérifier si l'ID de la session est passé dans l'URL
$session_id = $_GET['session_id'] ?? null;

if (!$session_id) {
    echo "Erreur : session de paiement non trouvée.";
    exit;
}

try {
    // Récupérer la session de paiement depuis Stripe
    $session = \Stripe\Checkout\Session::retrieve($session_id);


    // Vérifier si l'adresse de livraison est présente
    if (isset($session->shipping_details)) {
        $shipping_info = $session->shipping_details->address ?? null;

        if ($shipping_info) {
            $adresse = $shipping_info->line1 ?? 'Adresse non fournie';
            $codePostal = $shipping_info->postal_code ?? 'Code postal non fourni';
            $ville = $shipping_info->city ?? 'Ville non fournie';
            $pays = $shipping_info->country ?? 'Pays non fourni';

            $conn = connectDB();
            $clientId = $_SESSION['connectedUser']['id'];

            $stmtCommande = $conn->prepare("INSERT INTO commande (user_id, montant_total, statut, adresse, code_postal, ville, pays) 
                                           VALUES (?, ?, 'confirmée', ?, ?, ?, ?)");
            $stmtCommande->execute([$clientId, $session->amount_total / 100, $adresse, $codePostal, $ville, $pays]);

            $commandeId = $conn->lastInsertId();

            // Insérer les produits de la commande
            $stmtCommandeProduits = $conn->prepare("INSERT INTO commande_produits (commande_id, produit_id, quantite, prix_unitaire) 
                                                    VALUES (?, ?, ?, ?)");
            foreach ($session->line_items->data as $item) {
                $stmtCommandeProduits->execute([
                    $commandeId,
                    $item->price->product,
                    $item->quantity,
                    $item->price->unit_amount / 100  // Convertir de centimes à euros
                ]);
            }

            // Vider le panier (si nécessaire)
            $stmtVider = $conn->prepare("DELETE FROM panier WHERE user_id = ?");
            $stmtVider->execute([$clientId]);

            // Rediriger vers la page de confirmation
            header("Location: confirmation.php");
            exit;
        } else {
            echo "Erreur : l'adresse de livraison n'a pas été fournie.";
            exit;
        }
    } else {
        echo "Erreur : les détails de l'expédition ne sont pas disponibles.";
        exit;
    }

} catch (\Stripe\Exception\ApiErrorException $e) {
    echo 'Erreur Stripe : ' . $e->getMessage();
}
?>
