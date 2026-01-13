<?php
session_start();
require_once __DIR__ . '/../config/dbconnect.php';
require_once __DIR__ . '/recommandations.php';

// Initialiser les tables nécessaires
initTableProduitsVus();

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

// Enregistrer que l'utilisateur a vu ce produit
$userId = isset($_SESSION['connectedUser']['id']) ? $_SESSION['connectedUser']['id'] : null;
enregistrerProduitVu($id, $userId);

// Récupérer les produits recommandés
$produitsRecommandes = getProduitsRecommandes($id, $userId, 4);

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($produit['nom']) ?> - Nike Basketball</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }

        .back-button {
            position: fixed;
            top: 20px;
            left: 20px;
            background-color: #000;
            color: #fff;
            border: none;
            padding: 12px 24px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
            z-index: 1000;
        }

        .back-button:hover {
            background-color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 80px auto 40px;
            padding: 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .product-image {
            text-align: center;
            padding: 20px;
        }

        .product-image img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .product-details {
            padding: 20px;
        }

        .product-details h1 {
            font-size: 32px;
            margin-bottom: 20px;
            color: #000;
        }

        .price {
            font-size: 28px;
            font-weight: bold;
            color: #e74c3c;
            margin-bottom: 20px;
        }

        .description {
            font-size: 16px;
            line-height: 1.8;
            margin-bottom: 30px;
            color: #666;
        }

        .add-to-cart {
            background-color: #000;
            color: #fff;
            border: none;
            padding: 15px 40px;
            font-size: 18px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .add-to-cart:hover {
            background-color: #333;
        }

        .message {
            background-color: #d4edda;
            color: #155724;
            padding: 12px;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .form-avis {
            margin-top: 40px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }

        .form-avis h2 {
            font-size: 24px;
            margin-bottom: 15px;
        }

        .form-avis label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-avis select,
        .form-avis textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-avis textarea {
            min-height: 100px;
            resize: vertical;
        }

        .form-avis button {
            background-color: #000;
            color: #fff;
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .form-avis button:hover {
            background-color: #333;
        }

        .avis-moyenne {
            margin: 20px 0;
            padding: 15px;
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 5px;
            font-size: 18px;
        }

        .avis-section {
            margin-top: 40px;
        }

        .avis-section h2 {
            font-size: 24px;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .avis {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            border-left: 4px solid #000;
        }

        .avis p {
            margin-bottom: 10px;
        }

        .avis small {
            color: #666;
            font-size: 12px;
        }

        .recommandations-section {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .recommandations-section h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
                margin: 100px 20px 40px;
            }

            .back-button {
                top: 10px;
                left: 10px;
                padding: 10px 20px;
                font-size: 14px;
            }

            .product-details h1 {
                font-size: 24px;
            }

            .price {
                font-size: 22px;
            }
        }
    </style>
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

    <?php if (!empty($produitsRecommandes)): ?>
    <div class="recommandations-section" style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">
        <h2 style="text-align: center; margin-bottom: 30px; font-size: 28px;">Vous pourriez aussi aimer</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <?php foreach ($produitsRecommandes as $prodRec): ?>
                <div style="border: 1px solid #ddd; border-radius: 8px; padding: 15px; text-align: center; transition: transform 0.3s;">
                    <a href="produit_details.php?id=<?= $prodRec['id'] ?>" style="text-decoration: none; color: inherit;">
                        <img src="../<?= htmlspecialchars($prodRec['image_url']) ?>" 
                             alt="<?= htmlspecialchars($prodRec['nom']) ?>" 
                             style="width: 100%; height: 200px; object-fit: cover; border-radius: 5px;">
                        <h3 style="margin: 15px 0 10px; font-size: 18px;"><?= htmlspecialchars($prodRec['nom']) ?></h3>
                        <p style="font-size: 20px; font-weight: bold; color: #000;"><?= number_format($prodRec['prix'], 2) ?> €</p>
                        <button style="background-color: #000; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; margin-top: 10px;">
                            Voir le produit
                        </button>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

</body>

</html>
