-- Active: 1757426699648@@127.0.0.1@3306@e_commerce
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 16 mai 2025 à 08:18
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `e_commerce`
--

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

DROP TABLE IF EXISTS `commande`;
CREATE TABLE IF NOT EXISTS `commande` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `montant_total` int NOT NULL,
  `statut` varchar(50) NOT NULL,
  `date_commande` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id`, `user_id`, `montant_total`, `statut`, `date_commande`) VALUES
(1, 12, 110, 'En cours de traitement', '2025-04-11 16:33:28'),
(23, 22, 240, 'confirmée', '2025-05-15 23:34:32');

-- --------------------------------------------------------

--
-- Structure de la table `commande_produits`
--

DROP TABLE IF EXISTS `commande_produits`;
CREATE TABLE IF NOT EXISTS `commande_produits` (
  `id` int NOT NULL AUTO_INCREMENT,
  `commande_id` int NOT NULL,
  `produit_id` int NOT NULL,
  `quantite` int NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `commande_id` (`commande_id`),
  KEY `produit_id` (`produit_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `details_commande`
--

DROP TABLE IF EXISTS `details_commande`;
CREATE TABLE IF NOT EXISTS `details_commande` (
  `id` int NOT NULL AUTO_INCREMENT,
  `commande_id` int NOT NULL,
  `produit_id` int NOT NULL,
  `quantite` int NOT NULL,
  `prix_a_achat` int NOT NULL,
  `nom_produit` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `details_commande`
--

INSERT INTO `details_commande` (`id`, `commande_id`, `produit_id`, `quantite`, `prix_a_achat`, `nom_produit`) VALUES
(1, 1, 2, 3, 330, '');

-- --------------------------------------------------------

--
-- Structure de la table `panier`
--

DROP TABLE IF EXISTS `panier`;
CREATE TABLE IF NOT EXISTS `panier` (
  `id` int NOT NULL AUTO_INCREMENT,
  `prix` decimal(10,2) NOT NULL,
  `produit_id` int NOT NULL,
  `quantite` int NOT NULL,
  `user_id` int NOT NULL,
  `dateset` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `produit_id` (`produit_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `panier`
--

INSERT INTO `panier` (`id`, `prix`, `produit_id`, `quantite`, `user_id`, `dateset`) VALUES
(26, 130.00, 3, 2, 22, '2025-05-16 07:14:08'),
(25, 110.00, 2, 1, 22, '2025-05-16 07:14:00');

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

DROP TABLE IF EXISTS `produits`;
CREATE TABLE IF NOT EXISTS `produits` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `marque` varchar(100) NOT NULL,
  `description` text,
  `prix` decimal(10,2) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `date_sortie` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `image_hover_url` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `produits`
--

INSERT INTO `produits` (`id`, `nom`, `marque`, `description`, `prix`, `image_url`, `date_sortie`, `image_hover_url`) VALUES
(2, 'Nike Air Force 1', '', 'Couleur Noire', 110.00, 'images/basket2.jpg', '2025-01-01 00:00:00', ''),
(3, 'New Balance 2002r', '', 'Couleur grise', 130.00, 'images/basket3.jpg', '2025-01-01 00:00:00', ''),
(19, 'fafzafz', '', 'uytre', 123.00, 'http://localhost/e-commerce/images/Bienvenue.jpg', '2025-02-12 14:07:49', '');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `mot_de_passe` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `secret` varchar(1000) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `role` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `nom`, `email`, `mot_de_passe`, `secret`, `created_at`, `role`) VALUES
(22, 'Johann', 'johannphan@outlook.fr', '$2y$10$NwH6hIGz.uu8fTdVbLZLVemrwtAwMNzFLExeMqlJVjcEHK7bocbya', 'EIJWRWDO4KA5TPCZ', '2025-04-14 19:30:02', 'organisateur'),
(23, 'jifjzaepof', 'test@gmail.com', '$2y$10$ID6Egz9IHIEFUS69BEMFV.IpEgKUqaP2fed5XB1u2NTRpXSI8//by', 'KDIRUFHAROPF6JOX', '2025-04-15 06:58:05', '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

