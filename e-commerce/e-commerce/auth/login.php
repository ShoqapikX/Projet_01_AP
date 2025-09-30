<?php
// Activer l'affichage des erreurs pour déboguer (désactiver en production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "./functionLogin.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer et nettoyer les données du formulaire
    $email = htmlspecialchars(trim($_POST['email']));
    $pwd = $_POST['mdp'];

    if (!empty($email) && !empty($pwd)) {
        $data = [
            'email' => $email,
            'mdp' => $pwd
        ];

        if (login($data)) {
            // Redirection vers la vérification 2FA (et non pas vers les dashboards ici)
            header('Location: verify_2af.php');
            exit;
        } else {
            echo "<div class='error-message'>Email ou mot de passe incorrect.</div>";
        }
    } else {
        echo "<div class='error-message'>Tous les champs sont obligatoires.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <div class="login-container">
        <h2>Se connecter</h2>

        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="Entrez votre email">
            </div>

            <div class="form-group">
                <label for="mdp">Mot de passe</label>
                <input type="password" id="mdp" name="mdp" required placeholder="Entrez votre mot de passe">
            </div>

            <button type="submit">Connexion</button>
        </form>

        <p>Pas encore de compte ? <a href="register.php">S'inscrire</a></p>
    </div>
</body>
</html>
