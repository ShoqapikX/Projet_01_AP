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
    
    // Récupérer uniquement les produits récents (ajoutés dans les 60 derniers jours)
    // ET limiter à 4 produits maximum
    // Triés par date de sortie décroissante (les plus récents en premier)
    $sql = "SELECT * FROM produits 
            WHERE date_sortie >= DATE_SUB(CURDATE(), INTERVAL 60 DAY)
            ORDER BY date_sortie DESC 
            LIMIT 4";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $nouveaux_produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Si aucun produit récent (moins de 60 jours), prendre les 3 plus récents quand même
    if (empty($nouveaux_produits)) {
        $sql = "SELECT * FROM produits ORDER BY date_sortie DESC LIMIT 3";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $nouveaux_produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    closeDB($conn);
    return $nouveaux_produits;
}
