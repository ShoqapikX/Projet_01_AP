<?php

require_once(__DIR__ . '/../config/dbconnect.php');

function login($data) {
    // Obtenir la connexion à la base de données
    $conn = connectDB();

    if (!$conn) {
        echo "Problème de connexion à la base de données.";
        return false;
    }

    try {
        // Requête pour récupérer l'utilisateur par email (sans 'role')
        $stmt = $conn->prepare('SELECT id, nom, email, mot_de_passe, secret FROM utilisateur WHERE email = ?');
        $stmt->execute([$data['email']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier si l'utilisateur existe
        if ($user) {
            // Vérifier le mot de passe (le mot de passe est haché dans la base de données)
            if (password_verify($data['mdp'], $user['mot_de_passe'])) {
                // Stocker les informations utilisateur dans la session (sans 'role')
                $_SESSION['connectedUser'] = [
                    'id' => $user['id'],
                    'nom' => $user['nom'],
                    'email' => $user['email'],
                    'secret' => $user['secret']
                ];

                return true; // Connexion réussie
            } else {
                echo "<div class='error-message'>Mot de passe incorrect.</div>";
                return false; // Mauvais mot de passe
            }
        } else {
            echo "<div class='error-message'>Aucun utilisateur trouvé avec cet email.</div>";
            return false; // Utilisateur non trouvé
        }
    } catch (PDOException $e) {
        echo "Erreur lors de la connexion : " . $e->getMessage();
        return false;
    } finally {
        closeDB($conn);
    }
}

// Nouvelle fonction pour vérifier si l'utilisateur est connecté
function isUserLoggedIn() {
    return isset($_SESSION['connectedUser']);
}
?>
