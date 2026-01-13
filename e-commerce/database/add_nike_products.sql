-- ============================================
-- Script pour ajouter 3 nouveaux produits Nike
-- Date: 13 janvier 2026
-- ============================================

-- Ce script ajoute 3 nouvelles chaussures Nike à la base de données
-- sans avoir à réimporter tout le fichier e_commerce.sql

USE e_commerce;

-- Vérifier si les produits existent déjà (pour éviter les doublons)
-- Si ils existent, les supprimer d'abord
DELETE FROM produits WHERE id IN (4, 5, 6);

-- Ajouter les 3 nouveaux produits Nike
INSERT INTO `produits` (`id`, `nom`, `marque`, `description`, `prix`, `image_url`, `date_sortie`, `image_hover_url`, `categorie`) VALUES
(4, 'Nike Air Jordan 1 Retro', 'Nike', 'Modèle iconique en cuir premium. Confort optimal pour le basketball. Disponible en plusieurs coloris. Semelle en caoutchouc pour une adhérence maximale.', 150.00, 'images/nike1.jpg', '2026-01-13 00:00:00', 'images/nike12.jpg', 'Basketball'),
(5, 'Nike LeBron 20', 'Nike', 'Chaussure de basketball haute performance signée LeBron James. Technologie Air Max pour un amorti exceptionnel. Design moderne et agressif. Idéale pour les joueurs explosifs.', 180.00, 'images/nike2.jpg', '2026-01-13 00:00:00', 'images/nike21.jpg', 'Basketball'),
(6, 'Nike Kyrie 8', 'Nike', 'Basket signature de Kyrie Irving. Traction multidirectionnelle pour les changements de direction rapides. Upper respirant en mesh. Parfaite pour les guards rapides et agiles.', 140.00, 'images/nike3.jpg', '2026-01-13 00:00:00', 'images/nike32.jpg', 'Sneaker');

-- Vérifier l'ajout
SELECT id, nom, marque, prix, categorie FROM produits WHERE marque = 'Nike' ORDER BY id;

-- Message de confirmation
SELECT CONCAT('✅ ', COUNT(*), ' produits Nike ajoutés avec succès!') AS Status FROM produits WHERE id IN (4, 5, 6);
