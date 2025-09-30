<?php 
require_once (__DIR__. '/../auth/FunctionLogin.php');
?>  

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon profil</title>
    <link rel="stylesheet" href="../css/profile.css">
</head> 

<body>  
<div class="container"> 

    <div class="sidebar">   
    <h2> Mon Profil</h2>    
    <ul>    
    <li><a href="../index.php">Accueil</a></li> 
        <li><a href="#orders">Commandes</a></li>
        <li><a href="#history">Historique des achats</a></li>   
        <!--<li><a href="#payment-methods">Méthodes de paiement</a></li>  -->  
        <li><a href="#profile-info">Informations personnelles</a></li>  
        <li><a href="../config/logout.php" class="logout-btn">Se Déconnecter</a></li>
    </ul>
    </div>
    <div class="main-content">  
        <h1>Bienvenue,  <?php echo htmlspecialchars($_SESSION['connectedUser']['nom']); ?></h1>
    
        <?php
require_once('profile_info.php');

// Assurez-vous que l'utilisateur est connecté et récupérer son ID
$userId = $_SESSION['connectedUser']['id'] ?? null;

if ($userId) {
    // Récupérer les commandes via user_id
    $orders = getUserOrders($userId);
} else {
    echo "Vous devez être connecté pour voir vos commandes.";
}
?>

<h2>Mes Commandes</h2>

<?php if (!empty($orders)): ?>
    <?php foreach ($orders as $order): ?>
        <div class="order-item">
            <h3>Commande #<?php echo htmlspecialchars($order['id']); ?></h3>
            <p>Statut : <?php echo htmlspecialchars($order['statut']); ?></p>
            <p>Total : €<?php echo htmlspecialchars($order['montant_total']); ?></p>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Vous n'avez aucune commande.</p>
<?php endif; ?>

        <!-- section historique des achats -->
        <!--<div id="history" class="orders">   
            <h2>Historique des Achats</h2>  
            <p>Aucun achat précédent</p>
        </div>  -->

        <!-- section méthode de paiement -->    
        <div id="payment-methods" class="payment-methods"> 
            <h2>Méthodes de paiement</h2>   
            <!-- ajoutez méthode de paiement ici -->    
            <p>Carte Visa (**** **** **** 4242)</p>
        </div> 

        <!-- section informations personnelles -->  
        <div id="profile-info" class="profile-info">   
            <h2>Informations Personnelles</h2>  
            <p>Nom: <?php echo htmlspecialchars($_SESSION['connectedUser']['nom']); ?></p>  
            <p>Email: <?php echo htmlspecialchars($_SESSION['connectedUser']['email']); ?></p>  
            <!--<button class="edit-btn">Modifier Profil</button>-->
        </div>    
    </div>  
</div>


</body>