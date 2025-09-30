<?php
require_once("functionInsription.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/register.css">

</head>

<body>
    <div class="signup-container">
        <h2>Inscription</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = htmlspecialchars(trim($_POST['nom_utilisateurs']));
            $email = htmlspecialchars(trim($_POST['email']));
            $pwd = $_POST['mdp'];

            if (!empty($nom) && !empty($email) && !empty($pwd)) {
                // Ne hachez PAS le mot de passe ici, supprimez cette ligne:
                // $hashed_password = password_hash($pwd, PASSWORD_BCRYPT);

                $data = [
                    'nom' => $nom,  // Assurez-vous que cette clé correspond à celle utilisée dans insertUser
                    'email' => $email,
                    'mdp' => $pwd   // Envoyez le mot de passe non haché
                ];

                if (insertUser($data)) {
                    header('Location: ../index.php');
                    exit;
                } else {
                    echo "<div class='error-message'> Un problème est survenu. Veuillez réessayer plus tard. </div>";
                }
            } else {
                echo "<div class='error-message'> Tous les champs sont obligatoires </div>"; // Correction du tag div
            }
        }

        ?>
        <form action="#" method="post">
            <div class="form-group">
                <label for="nom_utilisateurs">Nom:</label>
                <input type="text" id="nom_utilisateurs" name="nom_utilisateurs" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="mdp">Mot de passe:</label>
                <input type="password" id="mdp" name="mdp" required>
            </div>
            <button type="submit"> S'inscrire</button>

        </form>

        <p>Déjà un compte ? <a href="login.php"> Se connecter</a></p>
    </div>
</body>
</html>