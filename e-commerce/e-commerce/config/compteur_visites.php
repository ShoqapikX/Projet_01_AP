<?php
/**
 * Système de compteur de visites pour le site e-commerce
 * 
 * Ce fichier contient les fonctions permettant de :
 * - Créer la table de visites si elle n'existe pas
 * - Incrémenter le compteur de visites
 * - Récupérer le nombre total de visites
 * - Détecter les nouvelles visites (basé sur les sessions)
 * 
 * Architecture de la table :
 * - id: INT PRIMARY KEY AUTO_INCREMENT
 * - nombre_visites: INT (compteur global)
 * - date_derniere_visite: DATETIME (horodatage)
 * 
 * Fonctionnement :
 * - Une visite est comptabilisée une seule fois par session
 * - Utilise $_SESSION pour éviter le comptage multiple lors de rafraîchissements
 * - Un seul enregistrement (id=1) stocke le compteur global
 * 
 * @package E-Commerce
 * @version 1.0.0
 */

require_once(__DIR__ . '/dbconnect.php');

/**
 * Initialise la table des visites si elle n'existe pas
 */
function initTableVisites() {
    $conn = connectDB();
    $sql = "CREATE TABLE IF NOT EXISTS visites (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre_visites INT NOT NULL DEFAULT 0,
        date_derniere_visite DATETIME DEFAULT CURRENT_TIMESTAMP
    )";
    $conn->exec($sql);
    
    // Vérifie si on a déjà un enregistrement
    $checkSql = "SELECT COUNT(*) FROM visites";
    $count = $conn->query($checkSql)->fetchColumn();
    
    if ($count == 0) {
        // Initialise le compteur à 0
        $insertSql = "INSERT INTO visites (nombre_visites) VALUES (0)";
        $conn->exec($insertSql);
    }
    
    closeDB($conn);
}

/**
 * Incrémente le compteur de visites
 */
function incrementerVisites() {
    $conn = connectDB();
    $sql = "UPDATE visites SET nombre_visites = nombre_visites + 1, date_derniere_visite = NOW() WHERE id = 1";
    $conn->exec($sql);
    closeDB($conn);
}

/**
 * Récupère le nombre total de visites
 */
function getNombreVisites() {
    $conn = connectDB();
    $sql = "SELECT nombre_visites FROM visites WHERE id = 1";
    $stmt = $conn->query($sql);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    closeDB($conn);
    
    return $result ? $result['nombre_visites'] : 0;
}

/**
 * Vérifie si c'est une nouvelle visite (utilise les sessions)
 */
function estNouvelleVisite() {
    if (!isset($_SESSION['visite_enregistree'])) {
        $_SESSION['visite_enregistree'] = true;
        return true;
    }
    return false;
}
?>
