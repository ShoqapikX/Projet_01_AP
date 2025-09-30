<?php
require_once('../config/dbconnect.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['connectedUser'])) {
    echo '
        <a href="../index.php" style="
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
        ">
            &#8592; Retour à l\'accueil
        </a>

        <div style="
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-size: 28px;
            font-weight: bold;
            text-align: center;
        ">
            ⚠️ Vous devez être connecté pour vider votre panier.
        </div>
    ';
    exit;
}

$user_id = $_SESSION['connectedUser']['id'];

$conn = connectDB();
$stmt = $conn->prepare("DELETE FROM panier WHERE user_id = ?");
$stmt->execute([$user_id]);

header("Location: ../index.php?panier=vide");
exit;
