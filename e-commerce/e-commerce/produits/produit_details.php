<?php
session_start();
require_once __DIR__ . '/../config/dbconnect.php';

function utilisateurA_Achete($conn, $idUser, $idProduit) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM commande
        JOIN commande_produits ON commande.id = commande_produits.commande_id
        WHERE commande.user_id = ? AND commande_produits.produit_id = ?");
    $stmt->execute([$idUser, $idProduit]);
    return $stmt->fetchColumn() > 0;
}

if (!isset($_GET['id'])) {
    echo "Produit non spécifié.";
    exit;
}

$id = $_GET['id'];
$conn = connectDB();

$stmt = $conn->prepare("SELECT * FROM produits WHERE id = ?");
$stmt->execute([$id]);
$produit = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produit) {
    echo "Produit introuvable.";
    exit;
}

$avisStmt = $conn->prepare("SELECT * FROM avis WHERE id_produit = ? ORDER BY date_avis DESC");
$avisStmt->execute([$id]);
$avis = $avisStmt->fetchAll(PDO::FETCH_ASSOC);

$moyenneStmt = $conn->prepare("SELECT AVG(note) as moyenne FROM avis WHERE id_produit = ?");
$moyenneStmt->execute([$id]);
$moyenne = $moyenneStmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($produit['Nom']) ?> - Détails</title>
    <link rel="stylesheet" href="../css/styles.css">
    <!-- Styles inlined comme dans ton code d'origine -->
</head>

<body>

    <a href="../index.php"><button class="back-button">← Retour à l'accueil</button></a>

    <div class="container">
        <div class="product-image">
            <img src="../<?= htmlspecialchars($produit['image_url']) ?>" alt="Produit">
        </div>
        <div class="product-details">
            <?php if (isset($_GET['message'])): ?>
                <div class="message">
                    <?= htmlspecialchars($_GET['message']); ?>
                </div>
            <?php endif; ?>

            <h1><?= htmlspecialchars($produit['nom']) ?></h1>
            <div class="price"><?= number_format($produit['prix'], 2) ?> €</div>
            <div class="description"><?= nl2br(htmlspecialchars($produit['description'])) ?></div>

            <form method="post" action="../ajout_cart.php">
                <input type="hidden" name="prix" value="<?= htmlspecialchars($produit['prix']) ?>">
                <input type="hidden" name="quantite" value="1">
                <input type="hidden" name="Id_produits" value="<?= htmlspecialchars($produit['id']) ?>">
                <?php if (isset($_SESSION['connectedUser']['id'])): ?>
                    <input type="hidden" name="Id_Clients" value="<?= htmlspecialchars($_SESSION['connectedUser']['id']) ?>">
                    <button type="submit" class="add-to-cart">Ajouter au panier</button>
                <?php else: ?>
                    <a href="../auth/login.php" class="add-to-cart" style="display: block; text-align: center; text-decoration: none;">Connectez-vous pour acheter</a>
                <?php endif; ?>
            </form>

            <?php if (isset($_SESSION['connectedUser']['id']) && utilisateurA_Achete($conn, $_SESSION['connectedUser']['id'], $id)): ?>
                <div class="form-avis">
                    <h2>Laisser un avis</h2>
                    <form method="post" action="../produits/ajouter_avis.php">
                        <input type="hidden" name="id_produit" value="<?= $id ?>">
                        <label for="note">Note :</label>
                        <select name="note" required>
                            <option value="5">⭐⭐⭐⭐⭐</option>
                            <option value="4">⭐⭐⭐⭐</option>
                            <option value="3">⭐⭐⭐</option>
                            <option value="2">⭐⭐</option>
                            <option value="1">⭐</option>
                        </select>
                        <label for="commentaire">Commentaire :</label>
                        <textarea name="commentaire" required></textarea>
                        <button type="submit">Envoyer mon avis</button>
                    </form>
                </div>
            <?php endif; ?>

            <?php if ($moyenne): ?>
                <div class="avis-moyenne">
                    <strong>Note moyenne : <?= number_format($moyenne, 1) ?>/5</strong>
                </div>
            <?php endif; ?>

            <div class="avis-section">
                <h2>Avis des utilisateurs</h2>
                <?php if (count($avis) > 0): ?>
                    <?php foreach ($avis as $a): ?>
                        <div class="avis">
                            <p><strong>Note : <?= $a['note'] ?>/5</strong></p>
                            <p><?= nl2br(htmlspecialchars($a['commentaire'])) ?></p>
                            <small>Posté le <?= date('d/m/Y', strtotime($a['date_avis'])) ?></small>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucun avis pour ce produit pour le moment.</p>
                <?php endif; ?>
            </div>

        </div>
    </div>

</body>

</html>
