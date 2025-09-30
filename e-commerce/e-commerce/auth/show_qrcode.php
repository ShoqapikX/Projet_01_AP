<?php
session_start();
require_once("../vendor/autoload.php");
require_once("../config/dbconnect.php");

use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;

if (!isset($_SESSION['connectedUser'])) {
    header("Location: login.php");
    exit;
}

$g = new GoogleAuthenticator();
$email = $_SESSION['connectedUser']['email'];
$userId = $_SESSION['connectedUser']['id'];
$secret = $_SESSION['connectedUser']['secret'] ?? null;

if (!$secret) {
    $secret = $g->generateSecret();
    $_SESSION['connectedUser']['secret'] = $secret;

    // Enregistrement en base de données
    $conn = connectDB();
    $stmt = $conn->prepare("UPDATE utilisateurs SET secret = ? WHERE id = ?");
    $stmt->execute([$secret, $userId]);
    closeDB($conn);
}

$qrCodeUrl = GoogleQrUrl::generate($email, $secret, 'Nike Basketball');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Configuration 2FA</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <div class="qrcode-container">
        <h2>Configuration de la double authentification</h2>
        <img src="<?php echo $qrCodeUrl; ?>" alt="QR Code Google Authenticator">
        <p>Scannez ce QR Code avec l’application Google Authenticator pour activer la 2FA.</p>
        <p>Ou entrez manuellement cette clé : <strong><?php echo $secret; ?></strong></p>
        <a href="login.php">→ Retour à la connexion</a>
    </div>
</body>
</html>
