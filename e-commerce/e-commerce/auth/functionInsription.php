<?php

require_once (__DIR__. '/../config/dbconnect.php');
require_once (__DIR__. '/../vendor/autoload.php');

use Sonata\GoogleAuthenticator\GoogleAuthenticator;

function insertUser($data)
{
    // Obtenir la connexion à la base de données
    $link = connectDB();

    if (!$link) {
        echo "Problème de connexion à la base de données.";
        return false;
    }

    $gAuth = new GoogleAuthenticator();
    $secret = $gAuth->generateSecret();
    $data['secret'] = $secret;

    // Vérifier si l'utilisateur existe déjà
    if (!checkExistUser($data['email'])) {
        // Requête d'insertion sans 'role'
        $req = "INSERT INTO utilisateur (nom, email, mot_de_passe, secret, created_at) VALUES (?, ?, ?, ?, ?)";
        $stmt = $link->prepare($req);
        
        if ($stmt->execute([
            $data['nom'], 
            $data['email'], 
            password_hash($data['mdp'], PASSWORD_DEFAULT),
            $secret,  // Ajouter le secret ici
            date('Y-m-d H:i:s')
        ])) {
            // Récupérer l'ID du dernier utilisateur inséré
            $idUser = $link->lastInsertId(); // Utiliser l'ID de l'utilisateur nouvellement inséré

            // Fusionner les données pour l'utilisateur
            $user = array_merge(['id' => $idUser], $data);

            // Stocker les informations de l'utilisateur dans la session
            $_SESSION['connectedUser'] = [
                'id' => $idUser,  // Le champ correspond au nouvel ID d'utilisateur
                'nom' => $data['nom'],
                'email' => $data['email'],
                'secret' => $secret  // Secret pour Google Authenticator
            ];

            
            header('Location: show_qrcode.php'); // Rediriger vers la page du QR Code
            exit;

            return true;
        } else {
            // Gestion des erreurs SQL
            var_dump($stmt->errorInfo());
            return false;
        }
    } else {
        echo "<div class='error-message'>L'utilisateur existe déjà.</div>";
        return false;
    }
}

function checkExistUser($email)
{
    // Obtenir la connexion à la base de données
    $link = connectDB();

    if (!$link) {
        echo "Problème de connexion à la base de données.";
        return false;
    }

    // Requête pour vérifier si l'utilisateur existe
    $req = "SELECT COUNT(*) AS nombre FROM utilisateur WHERE email = ?";
    $stmt = $link->prepare($req);

    if ($stmt->execute([$email])) {
        $value = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier si un utilisateur avec cet email existe
        if ($value['nombre'] > 0) {
            return true;
        } else {
            return false;
        }
    } else {
        // Gestion des erreurs SQL
        var_dump($stmt->errorInfo());
        return false;
    }
}
