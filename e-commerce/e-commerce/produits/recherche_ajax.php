<?php
/**
 * Endpoint AJAX pour la recherche en temps réel de produits
 * 
 * Fonctionnalités :
 * - Recherche multi-critères (nom, marque, description, catégorie)
 * - Retourne les résultats en format JSON
 * - Limitation à 8 résultats pour ne pas surcharger l'interface
 * - Compatible avec fetch() JavaScript
 * 
 * Paramètres GET :
 * - q: string (requête de recherche, minimum 2 caractères recommandé)
 * 
 * Format de réponse JSON :
 * [
 *   {
 *     "id": int,
 *     "nom": string,
 *     "marque": string,
 *     "prix": float,
 *     "image_url": string,
 *     "categorie": string
 *   },
 *   ...
 * ]
 * 
 * Utilisation :
 * fetch('/produits/recherche_ajax.php?q=nike')
 *   .then(response => response.json())
 *   .then(data => console.log(data));
 * 
 * @package E-Commerce
 * @version 1.0.0
 */

require_once(__DIR__ . '/../config/dbconnect.php');

header('Content-Type: application/json');

if (!isset($_GET['q']) || empty(trim($_GET['q']))) {
    echo json_encode([]);
    exit;
}

$query = trim($_GET['q']);
$conn = connectDB();

// Recherche dans les produits (nom, marque, description)
$sql = "SELECT id, nom, marque, prix, image_url, categorie 
        FROM produits 
        WHERE nom LIKE ? 
        OR marque LIKE ? 
        OR description LIKE ?
        OR categorie LIKE ?
        LIMIT 8";

$searchTerm = '%' . $query . '%';
$stmt = $conn->prepare($sql);
$stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

closeDB($conn);

echo json_encode($produits);
?>
