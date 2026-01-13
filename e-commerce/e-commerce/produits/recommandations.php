<?php
/**
 * Système de recommandations de produits basé sur l'historique de navigation
 * 
 * Fonctionnalités :
 * - Enregistre les produits consultés par l'utilisateur
 * - Analyse les catégories des produits vus
 * - Recommande des produits similaires (même catégorie)
 * - Affichage de type "Vous pourriez aussi aimer"
 * 
 * Architecture de la table produits_vus :
 * - id: INT PRIMARY KEY AUTO_INCREMENT
 * - utilisateur_id: INT (NULL si utilisateur non connecté)
 * - session_id: VARCHAR(255) (pour utilisateurs non connectés)
 * - produit_id: INT FOREIGN KEY vers produits
 * - date_vue: DATETIME
 * 
 * Algorithme de recommandation :
 * 1. Récupérer les catégories des produits vus par l'utilisateur
 * 2. Trouver d'autres produits dans ces catégories
 * 3. Exclure les produits déjà vus
 * 4. Trier aléatoirement pour varier les suggestions
 * 5. Limiter à 4 produits recommandés
 * 
 * @package E-Commerce
 * @version 1.0.0
 */

require_once(__DIR__ . '/../config/dbconnect.php');

/**
 * Initialise la table des produits vus
 */
function initTableProduitsVus() {
    $conn = connectDB();
    
    // Vérifier si la colonne categorie existe dans la table produits
    $sql = "SHOW COLUMNS FROM produits LIKE 'categorie'";
    $result = $conn->query($sql);
    
    if ($result->rowCount() == 0) {
        // Ajouter la colonne categorie si elle n'existe pas
        $sql = "ALTER TABLE produits ADD COLUMN categorie VARCHAR(100) DEFAULT 'Basket'";
        try {
            $conn->exec($sql);
        } catch (PDOException $e) {
            // La colonne existe déjà ou erreur - continuer
        }
    }
    
    // Créer la table des produits vus
    $sql = "CREATE TABLE IF NOT EXISTS produits_vus (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        produit_id INT NOT NULL,
        date_vue DATETIME DEFAULT CURRENT_TIMESTAMP,
        INDEX(user_id),
        INDEX(produit_id)
    )";
    $conn->exec($sql);
    
    closeDB($conn);
}

/**
 * Enregistre qu'un utilisateur a vu un produit
 */
function enregistrerProduitVu($produitId, $userId = null) {
    $conn = connectDB();
    
    // Si l'utilisateur n'est pas connecté, on utilise NULL pour user_id
    $sql = "INSERT INTO produits_vus (user_id, produit_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$userId, $produitId]);
    
    closeDB($conn);
}

/**
 * Récupère les catégories des produits vus par l'utilisateur
 */
function getCategoriesProduitsVus($userId = null) {
    $conn = connectDB();
    
    if ($userId) {
        $sql = "SELECT p.categorie, MAX(pv.date_vue) as derniere_vue
                FROM produits_vus pv 
                JOIN produits p ON pv.produit_id = p.id 
                WHERE pv.user_id = ? 
                GROUP BY p.categorie
                ORDER BY derniere_vue DESC 
                LIMIT 5";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$userId]);
    } else {
        // Pour les utilisateurs non connectés, on prend les derniers produits vus (via session ou cookie)
        $sql = "SELECT p.categorie, MAX(pv.date_vue) as derniere_vue
                FROM produits_vus pv 
                JOIN produits p ON pv.produit_id = p.id 
                WHERE pv.user_id IS NULL 
                GROUP BY p.categorie
                ORDER BY derniere_vue DESC 
                LIMIT 5";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }
    
    $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
    closeDB($conn);
    
    return $categories;
}

/**
 * Récupère les produits recommandés basés sur les catégories vues
 */
function getProduitsRecommandes($produitActuelId, $userId = null, $limit = 4) {
    $conn = connectDB();
    
    // Récupérer la catégorie du produit actuel
    $sqlCategorie = "SELECT categorie FROM produits WHERE id = ?";
    $stmtCategorie = $conn->prepare($sqlCategorie);
    $stmtCategorie->execute([$produitActuelId]);
    $categorieActuelle = $stmtCategorie->fetchColumn();
    
    // Récupérer les catégories des produits vus
    $categories = getCategoriesProduitsVus($userId);
    
    // Ajouter la catégorie actuelle si elle n'est pas déjà présente
    if ($categorieActuelle && !in_array($categorieActuelle, $categories)) {
        array_unshift($categories, $categorieActuelle);
    }
    
    // S'assurer que $limit est un entier
    $limit = (int) $limit;
    
    if (empty($categories)) {
        // Si aucune catégorie trouvée, recommander des produits aléatoires
        $sql = "SELECT * FROM produits WHERE id != ? ORDER BY RAND() LIMIT " . $limit;
        $stmt = $conn->prepare($sql);
        $stmt->execute([$produitActuelId]);
    } else {
        // Recommander des produits des mêmes catégories
        $placeholders = str_repeat('?,', count($categories) - 1) . '?';
        $sql = "SELECT * FROM produits 
                WHERE id != ? 
                AND categorie IN ($placeholders) 
                ORDER BY RAND() 
                LIMIT " . $limit;
        $stmt = $conn->prepare($sql);
        $params = array_merge([$produitActuelId], $categories);
        $stmt->execute($params);
    }
    
    $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
    closeDB($conn);
    
    return $produits;
}
?>
