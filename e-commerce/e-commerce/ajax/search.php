<?php
require_once(__DIR__ . '/../config/dbconnect.php');

header('Content-Type: application/json; charset=utf-8');

$q = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($q === '') {
    echo json_encode([]);
    exit;
}

try {
    $conn = connectDB();
    // Recherche sécurisée avec LIKE (wildcards) et limit
    $sql = "SELECT id, nom, prix FROM produits WHERE nom LIKE :term OR description LIKE :term LIMIT 8";
    $stmt = $conn->prepare($sql);
    $term = "%" . $q . "%";
    $stmt->bindParam(':term', $term, PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur serveur']);
}

?>
