<?php
require_once("./config/dbconnect.php"); 
require_once("./panier/fonction_ajout_cart.php");

if (!isset($_SESSION['connectedUser'])) {
    echo '
    <a href="index.php" style="
        position: absolute;
        top: 20px;
        left: 20px;
        text-decoration: none;
        font-weight: bold;
        font-size: 16px;
        background-color: #f3f3f3;
        padding: 10px 20px;
        border-radius: 10px;
        color: #000;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        transition: background-color 0.3s;
    ">
        &#8592; Retour à l\'accueil
    </a>

    <div style="
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        font-size: 32px;
        font-weight: bold;
        color: #333;
        text-align: center;
    ">
        ⚠️ Il faut être connecté pour ajouter au panier.
    </div>
';
    exit;
}   

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Récupération des données
    $produit_id = intval($_POST['Id_produits']);
    $quantite = isset($_POST['quantite']) ? intval($_POST['quantite']) : 1;
    $user_id = $_SESSION['connectedUser']['id'];
    $prix = floatval($_POST['prix']); 

    // Création du tableau pour la fonction
    $product = [
        'produit_id' => $produit_id,
        'quantite' => $quantite,
        'user_id' => $user_id,
        'prix' => $prix
    ];  
    
    // Ajout au panier
    if ($product) {
        var_dump($product);
     
        if (ajoutProduitPanier($product)) {
            header('Location: ./index.php');
            exit();
        }
    }   
}   
?>
