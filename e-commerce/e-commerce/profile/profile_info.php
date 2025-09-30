<?php
require_once(__DIR__ . '/../config/dbconnect.php');

function getUserOrders($clientId) {
    $conn = connectDB();

    try {
        // Utilisation du bon champ Id_Clients
        $stmt = $conn->prepare("SELECT * FROM commande WHERE user_id = ?");
        $stmt->execute([$clientId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erreur lors de la récupération des commandes : " . $e->getMessage();
        return [];
    } finally {
        closeDB($conn);
    }
}
?>
