<?php
require_once(__DIR__."/../config/dbconnect.php"); 

function ajoutProduitPanier($product) {
    $pdo = connectDB();

    // Récupération et validation des données
    $produit_id = isset($product['produit_id']) ? intval($product['produit_id']) : null;
    $quantite = isset($product['quantite']) ? intval($product['quantite']) : null;
    $user_id = isset($product['user_id']) ? intval($product['user_id']) : null;

    if (!$produit_id || !$quantite || !$user_id) {
        return ['error' => 'Paramètres manquants ou invalides'];
    }

    try {
        // Récupérer le prix du produit depuis la table "produits"
        $stmt = $pdo->prepare("SELECT prix FROM produits WHERE id = :id");
        $stmt->execute(['id' => $produit_id]);
        $produit = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$produit) {
            return ['error' => 'Produit introuvable'];
        }

        $prix = $produit['prix'];

        // Vérifier si l'article est déjà dans le panier
        $stmt = $pdo->prepare("SELECT id, quantite FROM panier WHERE user_id = :user_id AND produit_id = :produit_id");
        $stmt->execute([
            'user_id' => $user_id,
            'produit_id' => $produit_id
        ]);

        if ($stmt->rowCount() > 0) {
            // Mise à jour de la quantité
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $nouvelle_quantite = $result['quantite'] + $quantite;

            $updateStmt = $pdo->prepare("UPDATE panier SET quantite = :quantite WHERE id = :id");
            $updateStmt->execute([
                'quantite' => $nouvelle_quantite,
                'id' => $result['id']
            ]);

            return ['success' => 'Quantité mise à jour dans le panier'];
        } else {
            // Insertion d’un nouvel article
            $insertStmt = $pdo->prepare("INSERT INTO panier (produit_id, quantite, user_id, prix, dateset)
                                         VALUES (:produit_id, :quantite, :user_id, :prix, NOW())");
            $insertStmt->execute([
                'produit_id' => $produit_id,
                'quantite' => $quantite,
                'user_id' => $user_id,
                'prix' => $prix
            ]);

            return ['success' => 'Produit ajouté au panier'];
        }

    } catch (PDOException $e) {
        return ['error' => "Erreur lors de l'ajout au panier : " . $e->getMessage()];
    }
}
?>
