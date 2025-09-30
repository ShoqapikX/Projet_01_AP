<?php
session_start();
require_once("../vendor/autoload.php");
require_once("../config/dbconnect.php");

use Sonata\GoogleAuthenticator\GoogleAuthenticator;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération du code 2FA soumis par l'utilisateur
    $code = trim($_POST['code']);
    $email = $_SESSION['connectedUser']['email'] ?? null;

    if ($email) {
        // Connexion à la base de données
        $conn = connectDB();
        
        // Récupération du secret de l'utilisateur
        $stmt = $conn->prepare("SELECT secret FROM utilisateur WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Fermeture de la connexion à la base de données
        closeDB($conn);

        // Vérification si l'utilisateur existe et possède un secret
        if ($user && isset($user['secret'])) {
            $secret = $user['secret'];

            // Vérification du code 2FA
            $gAuth = new GoogleAuthenticator();
            if ($gAuth->checkCode($secret, $code)) {
                // Redirection vers la page d'accueil
                header('Location: ../index.php');
                exit;
            } else {
                $error = "Code 2FA invalide. Veuillez réessayer.";
            }
        } else {
            $error = "Utilisateur introuvable ou secret 2FA non défini.";
        }
    } else {
        // Si l'email n'est pas dans la session, rediriger vers la page de connexion
        header('Location: login.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vérification 2FA</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <div class="verify-2af-container">
        <h2>Vérification de la double authentification</h2>
        
        <?php if (isset($error)): ?>
            <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form method="POST" action="verify_2af.php">
            <div class="form-group">
                <label for="code">Code 2FA :</label>
                <input type="text" id="code" name="code" required placeholder="Entrez le code">
            </div>
            <button type="submit">Vérifier</button>
        </form>
    </div>
</body>
</html>
