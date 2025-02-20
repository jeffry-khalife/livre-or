-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 20 fév. 2025 à 16:12
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
-- Base de données : `livreor`
--

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(191) NOT NULL,
  `prenom` varchar(191) NOT NULL,
  `comment` text NOT NULL,
  `id_user` int NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `comment`
--

INSERT INTO `comment` (`id`, `nom`, `prenom`, `comment`, `id_user`, `date`) VALUES
(13, 'Lus', 'Claude', 'Une décennie iconique pour une femme tout aussi iconique. Que cette année soit remplie de bonheur. Joyeux anniversaire, Bernadette !', 0, '2025-02-20 10:00:28'),
(11, 'Simple', 'Donald', 'Bernadette, tu es la reine des années 80 ! Joyeux anniversaire et que la fête commence avec du bon vieux Michael Jackson et Madonna !', 0, '2025-02-20 09:58:10'),
(12, 'Basique', 'Monique', 'Qu\'importe le temps qui passe, ton cœur est toujours aussi jeune et vibrant que dans les années 80. Joyeux anniversaire, Bernadette !', 0, '2025-02-20 09:58:44'),
(14, 'Dominia', 'Marie-Jo', 'Ma chère Bernadette ! Tellement contente de célébré ton anniversaire avec tout ton entourage ! Encore de belles années nous attendent ! Bien à toi ma Bérnie', 0, '2025-02-20 10:03:39'),
(15, 'Caduc', 'Valentin', 'Ma bernie, voilà que tu as maintenant 80 ans et toutes tes dents lol ! Ouais ma gaté je te souhaite un très bon anniversaire kiss !', 0, '2025-02-20 10:09:10'),
(16, 'Macaron', 'Bernard', 'Coucou ma petite bernie, tu as toujours été mon âme sœur platonique et rien ne pourra briser notre lien ! Bon anniversaire ! ', 0, '2025-02-20 10:11:12'),
(17, 'Marvel', 'Jinx', 'Longue vie et prospérité ! Très bon gâteau au fait à refaire pour mes 95 ans l\'année prochaine lol ', 0, '2025-02-20 10:14:21'),
(18, 'Rachel', 'Green', 'Quel plaisir de revoir après toutes ces années et de partager cette superbe fête à tes cotés. Encore joyeux anniversaire ma belle Bernadette', 0, '2025-02-20 10:16:27'),
(19, 'Edith', 'Piaf', 'Emportez par la foule qui nous entraine et se déchaine.. ', 0, '2025-02-20 10:17:40'),
(20, 'Cabrel', 'Francis', 'Toi, ma Bernadette, je t\'aimais, je t\'aime et je t\'aimerai même si nos chemins se sont malgré nous séparés', 0, '2025-02-20 10:19:04'),
(21, 'Céline', 'Dion', 'Et si tu crois que c\'est fini, jamais.. Encore de belles années à vivre avant la tombe bb', 0, '2025-02-20 10:21:07'),
(22, 'Lo', 'Rie', 'Je serais là, toujours pour toi, n\'importe où quand tu voudras.. Car je resterais, ta meilleure amie', 0, '2025-02-20 10:22:36'),
(23, 'Sardou', 'Michel', 'Terre brûlée au vent\r\nDes landes de pierre\r\nAutour des lacs\r\nC\'est pour les vivants\r\nBientôt la tombe pour nous ma Bernadette :p', 0, '2025-02-20 10:24:39'),
(24, 'Brassens', 'George', 'Son capitaine et ses matelots n\'étaient pas des enfants d\'salauds mais des amis franco de port, des copain d\'abord ! Bon anniversaire Bernadette', 0, '2025-02-20 10:26:52'),
(25, 'image', 'Emile', 'Tu m\'entraine au bout de la nuit ! Les démons d\'Bernadette !! tu m\'entraine jusqu\'a l\'insomnie !! ', 0, '2025-02-20 10:30:27'),
(26, 'Fabian', 'Lara', 'Je t\'aime comme un fou, comme un soldat, comme une star de cinéma ! Bon anniversaire !!!', 0, '2025-02-20 10:31:21'),
(27, 'Dassin', 'Joe', 'Et si tu n\'existais pas, dis-moi pourquoi j\'existerai ma Bernie ? Je plaisante, bon anniversaire à toi ma poule !', 0, '2025-02-20 10:34:15'),
(28, 'Balavoine', 'Daniel', 'Et comment retrouver le goût de la vie sans toi Bernie ? Et qui pourra remplacer le besoin par l\'envie ? Joyeux anniversaire ! ', 0, '2025-02-20 10:36:45'),
(29, 'Dada', 'Dalida', 'C\'est étrange, je ne sais pas ce qui m\'arrive ce soir mais je te regarde comme pour la première fois. Joyeux anniversaire ! ', 0, '2025-02-20 10:46:35'),
(30, 'Sebastien', 'Patrick', 'Ce soir ce sont toujours les mêmes, des vieux habitués, un qui fait des poèmes, l’autre des mots croisés et c\'est comme ça qu\'on aime tes anniversaires !', 0, '2025-02-20 10:48:32'),
(31, 'magali', 'Monique', 'bon anniversaire grand-mère !!', 0, '2025-02-20 15:31:44'),
(32, 'jinx', 'Monique', 'w', 0, '2025-02-20 15:47:03');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `login` varchar(191) NOT NULL,
  `password` varchar(191) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `login`, `password`, `role`) VALUES
(2, 'test', '$2y$10$sq18odxo8nTsheQttnuf3OS56B4j.SsnEB6bCA.Ow/fW.MYDhm5iy', 'user'),
(1, 'jeffry', '$2y$10$bo7W9tpMz30j691NCqXMQuCjMWbaS2fEvdG.h4bJzT/TIiLEuLosi', 'admin');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


