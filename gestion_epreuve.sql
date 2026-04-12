-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 11 avr. 2026 à 23:44
-- Version du serveur : 8.4.7
-- Version de PHP : 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion_epreuve`
--

-- --------------------------------------------------------

--
-- Structure de la table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(2, 'Princesse', '$2y$10$J.qZkcAjVWBHyxgny75MdOCuBFuqAzI9tbLCiIzSJJ2LwKantGA4.');

-- --------------------------------------------------------

--
-- Structure de la table `communes`
--

DROP TABLE IF EXISTS `communes`;
CREATE TABLE IF NOT EXISTS `communes` (
  `id_commune` int NOT NULL AUTO_INCREMENT,
  `nom_commune` varchar(50) NOT NULL,
  `maire` varchar(100) DEFAULT NULL,
  `population` int DEFAULT NULL,
  `type_commune` varchar(100) DEFAULT NULL,
  `image_commune` varchar(100) DEFAULT NULL,
  `id_departement` int NOT NULL,
  PRIMARY KEY (`id_commune`),
  KEY `id_departement` (`id_departement`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `communes`
--

INSERT INTO `communes` (`id_commune`, `nom_commune`, `maire`, `population`, `type_commune`, `image_commune`, `id_departement`) VALUES
(3, 'Sèmè-Kpodji ', 'SINGBO Thomas', 500000, 'Particulier', '1_5cgeV2Zpi0m-JEHaWXN1Dw.jpg', 1),
(4, 'Zogbodomey', 'Houesse Daniel', 67900, 'Particulier', 'images.jpg', 2),
(10, 'Ségbana', 'Abdoul Razack Kora', 70000000, 'Particulier', 'VUE_DE_PROFIL_ENTREE_DE_LA_PRISON_DE_SEGBANA.jpg', 3),
(19, 'FOLY', 'Abdoul Razack Kora', 2001, 'Ordinaire', '69bda6193f577.jpg', 3);

-- --------------------------------------------------------

--
-- Structure de la table `departement`
--

DROP TABLE IF EXISTS `departement`;
CREATE TABLE IF NOT EXISTS `departement` (
  `id_departement` int NOT NULL AUTO_INCREMENT,
  `nom_departement` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_departement`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `departement`
--

INSERT INTO `departement` (`id_departement`, `nom_departement`) VALUES
(1, 'Allibori\r\n'),
(2, 'Littoral'),
(3, 'Borgou'),
(4, 'Atlantique'),
(5, 'Mono'),
(6, 'Donga'),
(7, 'Ouémé'),
(8, 'Plateau'),
(9, 'Zou'),
(10, 'Collines'),
(11, 'Atacora'),
(12, 'Couffo');

-- --------------------------------------------------------

--
-- Structure de la table `epreuve`
--

DROP TABLE IF EXISTS `epreuve`;
CREATE TABLE IF NOT EXISTS `epreuve` (
  `id_epreuve` int NOT NULL AUTO_INCREMENT,
  `annee` int NOT NULL,
  `fichier_pdf` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_matiere` int DEFAULT NULL,
  `session_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'Examen',
  PRIMARY KEY (`id_epreuve`),
  KEY `id_matiere` (`id_matiere`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `epreuve`
--

INSERT INTO `epreuve` (`id_epreuve`, `annee`, `fichier_pdf`, `id_matiere`, `session_type`) VALUES
(1, 2026, '1775438859_86450f36.pdf', 16, 'Session Normale');

-- --------------------------------------------------------

--
-- Structure de la table `filiere`
--

DROP TABLE IF EXISTS `filiere`;
CREATE TABLE IF NOT EXISTS `filiere` (
  `id_filiere` int NOT NULL AUTO_INCREMENT,
  `nom_filiere` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_formation` int DEFAULT NULL,
  PRIMARY KEY (`id_filiere`),
  KEY `id_formation` (`id_formation`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `filiere`
--

INSERT INTO `filiere` (`id_filiere`, `nom_filiere`, `id_formation`) VALUES
(4, 'Informatique de gestion', 1),
(5, 'Informatique de gestion', 3),
(6, 'Informatique de gestion', 4),
(7, 'Audit et controle de gestion', 3),
(8, 'Transport et Logistique', 3),
(9, 'Audit et controle de gestion', 1),
(10, 'Transport et logistique', 1),
(11, 'Audit et controle de gestion', 4),
(12, 'Informatique de gestion', 4),
(13, 'Transport et Logistique', 4);

-- --------------------------------------------------------

--
-- Structure de la table `formation`
--

DROP TABLE IF EXISTS `formation`;
CREATE TABLE IF NOT EXISTS `formation` (
  `id_formation` int NOT NULL AUTO_INCREMENT,
  `nom_formation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_formation`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `formation`
--

INSERT INTO `formation` (`id_formation`, `nom_formation`) VALUES
(1, 'Licence 1'),
(3, 'Licence 2'),
(4, 'Licence 3'),
(7, 'Master 1'),
(17, 'Master 2');

-- --------------------------------------------------------

--
-- Structure de la table `matiere`
--

DROP TABLE IF EXISTS `matiere`;
CREATE TABLE IF NOT EXISTS `matiere` (
  `id_matiere` int NOT NULL AUTO_INCREMENT,
  `nom_matiere` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_filiere` int DEFAULT NULL,
  PRIMARY KEY (`id_matiere`),
  KEY `id_filiere` (`id_filiere`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `matiere`
--

INSERT INTO `matiere` (`id_matiere`, `nom_matiere`, `id_filiere`) VALUES
(9, 'Langage C/C++', 5),
(10, 'Excel Avancé VBA', 5),
(14, 'Systeme Exploitation', 4),
(16, 'Maintenance Informatique', 5),
(18, 'Psychologie du Comportement', 9),
(19, 'Technologie des Equipements Mobiles', 5),
(21, 'MERISE 2', 5);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `communes`
--
ALTER TABLE `communes`
  ADD CONSTRAINT `communes_ibfk_1` FOREIGN KEY (`id_departement`) REFERENCES `departement` (`id_departement`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `epreuve`
--
ALTER TABLE `epreuve`
  ADD CONSTRAINT `epreuve_ibfk_1` FOREIGN KEY (`id_matiere`) REFERENCES `matiere` (`id_matiere`) ON DELETE CASCADE;

--
-- Contraintes pour la table `filiere`
--
ALTER TABLE `filiere`
  ADD CONSTRAINT `filiere_ibfk_1` FOREIGN KEY (`id_formation`) REFERENCES `formation` (`id_formation`) ON DELETE CASCADE;

--
-- Contraintes pour la table `matiere`
--
ALTER TABLE `matiere`
  ADD CONSTRAINT `matiere_ibfk_1` FOREIGN KEY (`id_filiere`) REFERENCES `filiere` (`id_filiere`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
