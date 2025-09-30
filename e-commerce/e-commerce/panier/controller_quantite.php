<?php
// Inclure la connexion à la base de données et les fonctions du panier
require_once(__DIR__ . '/../config/dbconnect.php');
require_once('fonction_quantite.php');

session_start(); // Démarrer la session

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['connectedUser'])) {
    header("Location: ../index.php");
    exit;
}

// Récupérer l'ID de l'utilisateur connecté
$user_id = $_SESSION['connectedUser']['id'];

// Vérifier si l'ID du produit est présent dans la requête POST
if (isset($_POST['Id_produits'])) {
    $produit_id = intval($_POST['Id_produits']); // Sécuriser la donnée

    // Appeler la fonction de suppression du produit du panier
    suppression($produit_id, $user_id);

    // Redirection vers la page principale après suppression
    header("Location: ../index.php");
    exit;
} else {
    // En cas d'erreur : produit non spécifié
    header("Location: ../panier.php?error=produit_non_trouve");
    exit;
}
?>