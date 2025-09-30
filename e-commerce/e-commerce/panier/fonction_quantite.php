<?php
require_once(__DIR__ . "/../config/dbconnect.php");

function suppression($produit_id, $user_id) {
    $conn = connectDB();

    // Vérifier la quantité actuelle du produit dans le panier
    $sqlCheckQuantity = "SELECT quantite FROM panier WHERE user_id = :user_id AND produit_id = :produit_id";
    $stmtCheck = $conn->prepare($sqlCheckQuantity);
    $stmtCheck->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmtCheck->bindParam(':produit_id', $produit_id, PDO::PARAM_INT);
    $stmtCheck->execute();

    $result = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $currentQuantity = $result['quantite'];

        if ($currentQuantity > 1) {
            // Diminuer la quantité de 1
            $newQuantity = $currentQuantity - 1;
            $sqlUpdate = "UPDATE panier SET quantite = :newQuantity WHERE user_id = :user_id AND produit_id = :produit_id";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->bindParam(':newQuantity', $newQuantity, PDO::PARAM_INT);
            $stmtUpdate->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmtUpdate->bindParam(':produit_id', $produit_id, PDO::PARAM_INT);
            $stmtUpdate->execute();
        } else {
            // Supprimer le produit du panier
            $sqlDelete = "DELETE FROM panier WHERE user_id = :user_id AND produit_id = :produit_id";
            $stmtDelete = $conn->prepare($sqlDelete);
            $stmtDelete->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmtDelete->bindParam(':produit_id', $produit_id, PDO::PARAM_INT);
            $stmtDelete->execute();
        }
    }
}
?>
