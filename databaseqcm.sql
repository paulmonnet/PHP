-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 27 avr. 2022 à 00:45
-- Version du serveur : 5.7.36
-- Version de PHP : 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `databaseqcm`
--

-- --------------------------------------------------------

--
-- Structure de la table `difficulte`
--

DROP TABLE IF EXISTS `difficulte`;
CREATE TABLE IF NOT EXISTS `difficulte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `difficulte`
--

INSERT INTO `difficulte` (`id`, `level`) VALUES
(1, 'très facile'),
(2, 'facile'),
(4, 'normal'),
(5, 'difficile'),
(6, 'insurmontable');

-- --------------------------------------------------------

--
-- Structure de la table `question`
--

DROP TABLE IF EXISTS `question`;
CREATE TABLE IF NOT EXISTS `question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `possibilites` text NOT NULL,
  `reponses` text NOT NULL,
  `difficulte` int(11) NOT NULL,
  `tempsEstimatif` int(11) NOT NULL,
  `pointMaxi` float NOT NULL,
  `typeNotation` int(11) NOT NULL,
  `TypeRep` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `difficulte` (`difficulte`),
  KEY `notation` (`typeNotation`),
  KEY `type_rep` (`TypeRep`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `question`
--

INSERT INTO `question` (`id`, `question`, `possibilites`, `reponses`, `difficulte`, `tempsEstimatif`, `pointMaxi`, `typeNotation`, `TypeRep`) VALUES
(1, 'Ma première question vaut t\'elle le coup ?', 'Vrai;Faux', '', 1, 120, 1, -1, 1),
(4, 'Question Test Vrai Faux (rep F) ?', 'Vrai;Faux', '1', 1, 120, 1, 1, 1),
(10, 'Test Question libre ?', '', 'aucune r&eacute;ponse', 4, 360, 2, 99, 4),
(14, 'Test Question Multiple r&eacute;ponse V2 ?', 'rep1;rep2 checked;rep3;rep4;rep5 checked', '1;4', 2, 120, 1, 1, 3),
(15, 'Test Question Multiple choix &agrave; r&eacute;ponse unique ?', 'rep index 0;rep index 1 radio coch&eacute;;rep index 2', '1', 1, 120, 1, 1, 2);

-- --------------------------------------------------------

--
-- Structure de la table `typenotation`
--

DROP TABLE IF EXISTS `typenotation`;
CREATE TABLE IF NOT EXISTS `typenotation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `typenotation`
--

INSERT INTO `typenotation` (`id`, `name`, `description`) VALUES
(-1, 'NOTATION_NEGATIVE_STRICTE', 'un éléments de réponse incorecte implique immédiatement la note négative'),
(1, 'NOTATION_POSITIVE_FRACTION', 'les éléments correctes sont comptabilisé et se soustrait en cas d\'élément incorrect mais ne descend pas en dessous de 0)'),
(2, 'NOTATION_POSITIVE_STRICTE', 'Seul l\'ensemble des bonne réponse confère les points, 0 sinon'),
(3, 'NOTATION_POSITIVE_NEGATIVE', 'les éléments s\'ajoute en cas de bonne réponse et se soustrait en cas d\'éléments erroné avec résultat négatif possible'),
(99, 'NOTATION_AUTO_IMPOSSIBLE', 'notation spécifique pour la notation de question libre nécessitant une notation manuel');

-- --------------------------------------------------------

--
-- Structure de la table `typereponse`
--

DROP TABLE IF EXISTS `typereponse`;
CREATE TABLE IF NOT EXISTS `typereponse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `typereponse`
--

INSERT INTO `typereponse` (`id`, `name`, `description`) VALUES
(1, 'simple', 'réponse de type vrai ou faux'),
(2, 'unique', 'une réponse possible parmi plusieurs réponse'),
(3, 'multiple', 'plusieurs réponse possible'),
(4, 'libre', 'réponse libre : du texte'),
(5, 'code', 'un espace dédié à du code source');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `difficult` FOREIGN KEY (`difficulte`) REFERENCES `difficulte` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `notation` FOREIGN KEY (`typeNotation`) REFERENCES `typenotation` (`id`),
  ADD CONSTRAINT `type_rep` FOREIGN KEY (`TypeRep`) REFERENCES `typereponse` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
