-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 29 oct. 2025 à 15:35
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
-- Base de données : `stock_paristanbul`
--

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE IF NOT EXISTS `categorie` (
  `id_categorie` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE latin1_bin NOT NULL,
  PRIMARY KEY (`id_categorie`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

-- --------------------------------------------------------

--
-- Structure de la table `fournisseur`
--

DROP TABLE IF EXISTS `fournisseur`;
CREATE TABLE IF NOT EXISTS `fournisseur` (
  `id_fournisseur` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE latin1_bin NOT NULL,
  `prenom` varchar(100) COLLATE latin1_bin DEFAULT NULL,
  `cp` varchar(10) COLLATE latin1_bin DEFAULT NULL,
  `adresse` text COLLATE latin1_bin,
  `email` varchar(150) COLLATE latin1_bin DEFAULT NULL,
  `site_web` varchar(150) COLLATE latin1_bin DEFAULT NULL,
  `mdp` varchar(255) COLLATE latin1_bin DEFAULT NULL,
  `num_telephone` varchar(20) COLLATE latin1_bin DEFAULT NULL,
  `num_mobile` varchar(20) COLLATE latin1_bin DEFAULT NULL,
  `entreprise` varchar(100) COLLATE latin1_bin DEFAULT NULL,
  `devise` varchar(10) COLLATE latin1_bin DEFAULT NULL,
  PRIMARY KEY (`id_fournisseur`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

-- --------------------------------------------------------

--
-- Structure de la table `magasins`
--

DROP TABLE IF EXISTS `magasins`;
CREATE TABLE IF NOT EXISTS `magasins` (
  `id_magasin` int NOT NULL AUTO_INCREMENT,
  `ville` varchar(100) COLLATE latin1_bin NOT NULL,
  `adresse` text COLLATE latin1_bin,
  `telephone` varchar(20) COLLATE latin1_bin DEFAULT NULL,
  `email` varchar(150) COLLATE latin1_bin DEFAULT NULL,
  `nom` varchar(100) COLLATE latin1_bin DEFAULT 'Paristanbul',
  `ref_utilisateur` int DEFAULT NULL,
  PRIMARY KEY (`id_magasin`),
  KEY `ref_utilisateur` (`ref_utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

DROP TABLE IF EXISTS `produits`;
CREATE TABLE IF NOT EXISTS `produits` (
  `id_produit` int NOT NULL AUTO_INCREMENT,
  `libelle` varchar(150) COLLATE latin1_bin NOT NULL,
  `marque` varchar(100) COLLATE latin1_bin DEFAULT NULL,
  `origine` varchar(100) COLLATE latin1_bin DEFAULT NULL,
  `ref_sous_categorie` int DEFAULT NULL,
  `ref_categorie` int DEFAULT NULL,
  `reference_produit` varchar(100) COLLATE latin1_bin DEFAULT NULL,
  `code_barre` varchar(50) COLLATE latin1_bin DEFAULT NULL,
  `unite_mesure` enum('gramme','kilogramme','litre','millilitre','pièce') COLLATE latin1_bin DEFAULT NULL,
  `unite_ou_pack` enum('pack','unite') COLLATE latin1_bin DEFAULT NULL,
  `nb_unite_pack` int DEFAULT NULL,
  `bio` tinyint(1) DEFAULT '0',
  `halal` tinyint(1) DEFAULT '0',
  `vegan` tinyint(1) DEFAULT '0',
  `prix_unitaire` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_produit`),
  UNIQUE KEY `reference_produit` (`reference_produit`),
  KEY `ref_sous_categorie` (`ref_sous_categorie`),
  KEY `ref_categorie` (`ref_categorie`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

-- --------------------------------------------------------

--
-- Structure de la table `souscategorie`
--

DROP TABLE IF EXISTS `souscategorie`;
CREATE TABLE IF NOT EXISTS `souscategorie` (
  `id_sous_categorie` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE latin1_bin NOT NULL,
  `ref_categorie` int NOT NULL,
  PRIMARY KEY (`id_sous_categorie`),
  KEY `ref_categorie` (`ref_categorie`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE latin1_bin NOT NULL,
  `prenom` varchar(100) COLLATE latin1_bin DEFAULT NULL,
  `email` varchar(150) COLLATE latin1_bin NOT NULL,
  `mdp` varchar(255) COLLATE latin1_bin NOT NULL,
  `role` enum('admin','gestionnaire','operateur') COLLATE latin1_bin DEFAULT 'operateur',
  `genre` enum('H','F','Autre') COLLATE latin1_bin DEFAULT NULL,
  `poste` varchar(100) COLLATE latin1_bin DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `magasins`
--
ALTER TABLE `magasins`
  ADD CONSTRAINT `magasins_ibfk_1` FOREIGN KEY (`ref_utilisateur`) REFERENCES `utilisateur` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `produits`
--
ALTER TABLE `produits`
  ADD CONSTRAINT `produits_ibfk_1` FOREIGN KEY (`ref_sous_categorie`) REFERENCES `souscategorie` (`id_sous_categorie`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `produits_ibfk_2` FOREIGN KEY (`ref_categorie`) REFERENCES `categorie` (`id_categorie`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `souscategorie`
--
ALTER TABLE `souscategorie`
  ADD CONSTRAINT `souscategorie_ibfk_1` FOREIGN KEY (`ref_categorie`) REFERENCES `categorie` (`id_categorie`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
