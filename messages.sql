-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 19 fév. 2025 à 12:10
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
-- Base de données : `livre`
--

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `date_post` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `nom`, `prenom`, `message`, `date_post`) VALUES
(12, 'Helicopter', 'Eglantine', 'Joyeux anniversaire ma petite Bernie !', '2025-02-19 12:52:59'),
(9, 'Trian', 'Monique', 'Qu\'importe le temps qui passe, ton cœur est toujours aussi jeune et vibrant que dans les années 80. Joyeux anniversaire, Bernadette !', '2025-02-18 14:39:26'),
(10, 'Cuzco', 'Bernard', 'Une décennie iconique pour une femme tout aussi iconique. Que cette année soit remplie de bonheur. Joyeux anniversaire, Bernadette !', '2025-02-18 14:49:21'),
(11, 'Stitch', 'Lucette', 'Bernadette, tu es la reine des années 80 ! Joyeux anniversaire et que la fête commence avec du bon vieux Michael Jackson et Madonna !', '2025-02-18 14:52:36'),
(13, 'Bad', 'Girl', 'Ma Bernadette ! 80 ans tu te rends compte comme le temps passe. Je me rappelle encore de nos soirées déguisées, toi toute vêtue de cuir au vahiné.. Aujourd\'hui on est de vieux croutons mais toujours là, l\'une pour l\'autre. Love :)', '2025-02-19 12:57:54');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
