-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 30 oct. 2025 à 11:28
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
-- Structure de la table `commandes`
--

DROP TABLE IF EXISTS `commandes`;
CREATE TABLE IF NOT EXISTS `commandes` (
  `id_commande` int NOT NULL AUTO_INCREMENT,
  `adresse_facturation` text COLLATE latin1_bin,
  `ref_fournisseur` int DEFAULT NULL,
  `ref_produit` int DEFAULT NULL,
  `ref_magasin` int DEFAULT NULL,
  `ref_utilisateur` int DEFAULT NULL,
  `date_commande` date NOT NULL,
  `date_arrivee` date DEFAULT NULL,
  `quantite` int DEFAULT NULL,
  `total_ht` decimal(10,2) DEFAULT NULL,
  `tva` decimal(10,2) DEFAULT NULL,
  `total_ttc` decimal(10,2) DEFAULT NULL,
  `remise` enum('rabais','comptant','avoir') COLLATE latin1_bin DEFAULT NULL,
  `date_reglement` date DEFAULT NULL,
  `quantite_totale` int DEFAULT NULL,
  `mode_reglement` varchar(50) COLLATE latin1_bin DEFAULT NULL,
  PRIMARY KEY (`id_commande`),
  KEY `ref_fournisseur` (`ref_fournisseur`),
  KEY `ref_produit` (`ref_produit`),
  KEY `ref_magasin` (`ref_magasin`),
  KEY `ref_utilisateur` (`ref_utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

-- --------------------------------------------------------

--
-- Structure de la table `facture`
--

DROP TABLE IF EXISTS `facture`;
CREATE TABLE IF NOT EXISTS `facture` (
  `id_facture` int NOT NULL AUTO_INCREMENT,
  `ref_user` int DEFAULT NULL,
  `ref_commande` int DEFAULT NULL,
  `date_paiement` date DEFAULT NULL,
  `paye` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id_facture`),
  KEY `ref_user` (`ref_user`),
  KEY `ref_commande` (`ref_commande`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

-- --------------------------------------------------------

--
-- Structure de la table `ficheconfirmationcommande`
--

DROP TABLE IF EXISTS `ficheconfirmationcommande`;
CREATE TABLE IF NOT EXISTS `ficheconfirmationcommande` (
  `id_fiche` int NOT NULL AUTO_INCREMENT,
  `ref_commande` int NOT NULL,
  `date_confirmation` datetime DEFAULT CURRENT_TIMESTAMP,
  `commentaire` text COLLATE latin1_bin,
  `confirme_par` int DEFAULT NULL,
  PRIMARY KEY (`id_fiche`),
  KEY `ref_commande` (`ref_commande`),
  KEY `confirme_par` (`confirme_par`)
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
-- Contraintes pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `commandes_ibfk_1` FOREIGN KEY (`ref_fournisseur`) REFERENCES `fournisseur` (`id_fournisseur`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `commandes_ibfk_2` FOREIGN KEY (`ref_produit`) REFERENCES `produits` (`id_produit`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `commandes_ibfk_3` FOREIGN KEY (`ref_magasin`) REFERENCES `magasins` (`id_magasin`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `commandes_ibfk_4` FOREIGN KEY (`ref_utilisateur`) REFERENCES `utilisateur` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `facture`
--
ALTER TABLE `facture`
  ADD CONSTRAINT `facture_ibfk_1` FOREIGN KEY (`ref_user`) REFERENCES `utilisateur` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `facture_ibfk_2` FOREIGN KEY (`ref_commande`) REFERENCES `commandes` (`id_commande`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `ficheconfirmationcommande`
--
ALTER TABLE `ficheconfirmationcommande`
  ADD CONSTRAINT `ficheconfirmationcommande_ibfk_1` FOREIGN KEY (`ref_commande`) REFERENCES `commandes` (`id_commande`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ficheconfirmationcommande_ibfk_2` FOREIGN KEY (`confirme_par`) REFERENCES `utilisateur` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE;

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
