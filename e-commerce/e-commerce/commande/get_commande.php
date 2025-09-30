<?php
session_start(); // Assurez-vous que la session est démarrée
if (!isset($_SESSION['connectedUser'])) {
    echo '
    <div style="
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        font-size: 24px;
        font-weight: bold;
        text-align: center;
    ">
        Vous devez être connecté pour passer une commande.
    </div>
    ';
    exit;
}
require_once(__DIR__ . '/../config/dbconnect.php');

function validerCommande() {
    // Connecter à la base de données
    $conn = connectDB();
    $clientId = $_SESSION['connectedUser']['Id_Clients']; // ID de l'utilisateur connecté

    try {
        // 1. Récupérer les articles dans le panier de l'utilisateur connecté
        $stmtPanier = $conn->prepare("SELECT * FROM panier WHERE Id_clients = ?");
        $stmtPanier->execute([$clientId]);
        $panier = $stmtPanier->fetchAll(PDO::FETCH_ASSOC);

        if (empty($panier)) {
            echo "Votre panier est vide !";
            return;
        }

        // 2. Calculer le montant total de la commande
        $montantTotal = 0;
        foreach ($panier as $article) {
            $montantTotal += $article['prix'] * $article['quantite'];
        }

        // 3. Insérer une nouvelle commande dans la table `commande`
        $stmtCommande = $conn->prepare("INSERT INTO commande (user_id, montant_total, statut) VALUES (?, ?, 'en attente')");
        $stmtCommande->execute([$clientId, $montantTotal]);

        // Récupérer l'ID de la commande nouvellement créée
        $commandeId = $conn->lastInsertId();

        // 4. Insérer chaque produit du panier dans la table `commande_produits`
        $stmtCommandeProduits = $conn->prepare("INSERT INTO commande_produits (commande_id, produit_id, quantite, prix_unitaire) VALUES (?, ?, ?, ?)");

        foreach ($panier as $article) {
            $stmtCommandeProduits->execute([
                $commandeId,
                $article['Id_produits'],
                $article['quantite'],
                $article['prix']
            ]);
        }   

        $stmtStock = $conn->prepare(" ");


        // 5. Optionnel : Vider le panier après la validation de la commande
        $stmtViderPanier = $conn->prepare("DELETE FROM panier WHERE Id_clients = ?");
        $stmtViderPanier->execute([$clientId]);

        // Redirection après la validation
        header('Location: ../index.php?commande=success');
        exit;

    } catch (PDOException $e) {
        echo "Erreur lors de la validation de la commande : " . $e->getMessage();
    } finally {
        closeDB($conn);
    }
}   



// Appel de la fonction si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validerCommande(); // Appel de la fonction pour valider la commande
}   


?>
