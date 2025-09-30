<?php
session_start();
require_once __DIR__ . '/../config/dbconnect.php';

$conn = connectDB();

// Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['connectedUser']['id'])) {
    header('Location: auth/login.php?message=Veuillez vous connecter pour laisser un avis');
    exit;
}

// Récupération des données du formulaire
$idClient = $_SESSION['connectedUser']['id'];
$idProduit = $_POST['id_produit'] ?? null;
$note = $_POST['note'] ?? null;
$commentaire = trim($_POST['commentaire'] ?? '');

if (!$idProduit || !$note || !$commentaire) {
    header("Location: produit_details.php?id=$idProduit&message=Champs manquants pour l'avis");
    exit;
}

$stmt = $conn->prepare("SELECT COUNT(*) FROM avis WHERE id_utilisateur = ? AND id_produit = ?");
$stmt->execute([$idClient, $idProduit]);
$existe = $stmt->fetchColumn();

if ($existe > 0) {
    header("Location: produit_details.php?id=$idProduit&message=Vous avez déjà laissé un avis pour ce produit.");
    exit;
}

// Insertion de l'avis avec la colonne correcte pour la date
$stmt = $conn->prepare("INSERT INTO avis (id_utilisateur, id_produit, note, commentaire, date_avis) VALUES (?, ?, ?, ?, NOW())");
$stmt->execute([$idClient, $idProduit, $note, $commentaire]);

header("Location: produit_details.php?id=$idProduit&message=Merci pour votre avis, il sera publié après validation !");
exit;
?>
