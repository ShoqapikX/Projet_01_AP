<?php
require_once(__DIR__ . '/../config/dbconnect.php');

function getProduits()
{
    $conn = connectDB();
    $sql = "SELECT * FROM produits";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $produits;
}

function getNouveauxProduits()
{
    $conn = connectDB();
    
    // Requête pour récupérer les produits ajoutés dans le dernier mois
    $sql = "SELECT * FROM produits WHERE Creation_produit >= CURRENT_DATE() - INTERVAL 1 MONTH LIMIT 2";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $nouveaux_produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($nouveaux_produits) {
        return $nouveaux_produits;
    } else {
        // Si aucun produit récent, récupère les plus récents quand même
        $sql = "SELECT * FROM produits ORDER BY Creation_produit DESC LIMIT 2";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $nouveaux_produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $nouveaux_produits;
    }
}
