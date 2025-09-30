<?php

require_once(__DIR__ . '/../config/dbconnect.php');

// Fonction pour obtenir le nombre total d’articles dans le panier
function getCartCount($user_id) {
    if (!$user_id) return 0;

    $conn = connectDB();

    try {
        $stmt = $conn->prepare("SELECT SUM(quantite) AS total_quantity FROM panier WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result && $result['total_quantity'] ? (int)$result['total_quantity'] : 0;
    } catch (PDOException $e) {
        echo "Erreur lors de la récupération du nombre d'articles dans le panier : " . $e->getMessage();
        return 0;
    } finally {
        closeDB($conn);
    }
}

// Fonction pour récupérer tous les articles d’un panier (brut)
function getCartItems($user_id) {
    if (!$user_id) return [];

    $conn = connectDB();

    try {
        $stmt = $conn->prepare("SELECT * FROM panier WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erreur lors de la récupération des articles dans le panier : " . $e->getMessage();
        return [];
    } finally {
        closeDB($conn);
    }
}

// Fonction pour récupérer les détails produits avec quantités
function getCartProducts($user_id) {
    if (!$user_id) return [];

    $conn = connectDB();

    try {
        $sql = "SELECT p.id, p.nom, p.prix, panier.quantite 
                FROM panier
                INNER JOIN produits p ON panier.produit_id = p.id
                WHERE panier.user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erreur lors de la récupération des produits dans le panier : " . $e->getMessage();
        return [];
    } finally {
        closeDB($conn);
    }
}
?>
