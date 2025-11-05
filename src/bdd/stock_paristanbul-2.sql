-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Nov 05, 2025 at 02:23 PM
-- Server version: 8.0.40
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stock_paristanbul`
--

-- --------------------------------------------------------

--
-- Table structure for table `categorie`
--

CREATE TABLE `categorie` (
  `id_categorie` int NOT NULL,
  `nom` varchar(100) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Dumping data for table `categorie`
--

INSERT INTO `categorie` (`id_categorie`, `nom`) VALUES
(1, 'Boissons'),
(2, 'Épicerie'),
(3, 'Produits frais'),
(4, 'Hygiène'),
(5, 'Viande'),
(6, 'Emballage'),
(7, 'Produit sec');

-- --------------------------------------------------------

--
-- Table structure for table `commande`
--

CREATE TABLE `commande` (
  `id_commande` int NOT NULL,
  `ref_magasin` int DEFAULT NULL,
  `ref_utilisateur` int DEFAULT NULL,
  `date_commande` datetime DEFAULT CURRENT_TIMESTAMP,
  `etat` enum('en attente','préparée','expédiée','livrée','annulée') CHARACTER SET latin1 COLLATE latin1_bin DEFAULT 'en attente',
  `commentaire` text CHARACTER SET latin1 COLLATE latin1_bin
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Dumping data for table `commande`
--

INSERT INTO `commande` (`id_commande`, `ref_magasin`, `ref_utilisateur`, `date_commande`, `etat`, `commentaire`) VALUES
(1, 1, 1, '2025-10-25 10:15:00', 'en attente', 'Commande centrale initiale'),
(2, 2, 2, '2025-10-28 14:30:00', 'préparée', 'Livraison prévue demain'),
(3, 3, 3, '2025-10-29 09:45:00', 'expédiée', 'Urgent, suivre la livraison'),
(4, 2, 2, '2025-10-30 16:20:00', 'livrée', 'Commande reçue avec succès'),
(5, 3, 3, '2025-10-31 11:00:00', 'annulée', 'Client a annulé');

-- --------------------------------------------------------

--
-- Table structure for table `commande_detail`
--

CREATE TABLE `commande_detail` (
  `id_detail` int NOT NULL,
  `ref_commande` int DEFAULT NULL,
  `ref_produit` int DEFAULT NULL,
  `quantite` int NOT NULL,
  `prix_unitaire` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Dumping data for table `commande_detail`
--

INSERT INTO `commande_detail` (`id_detail`, `ref_commande`, `ref_produit`, `quantite`, `prix_unitaire`) VALUES
(1, 1, 1, 50, 18.50),
(2, 1, 2, 30, 4.90),
(3, 2, 2, 20, 4.90),
(4, 2, 3, 40, 3.20),
(5, 3, 1, 10, 18.50),
(6, 4, 3, 15, 3.20);

-- --------------------------------------------------------

--
-- Table structure for table `facture`
--

CREATE TABLE `facture` (
  `id_facture` int NOT NULL,
  `ref_commande` int DEFAULT NULL,
  `montant` decimal(10,2) DEFAULT NULL,
  `date_emission` date DEFAULT (curdate()),
  `paye` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Dumping data for table `facture`
--

INSERT INTO `facture` (`id_facture`, `ref_commande`, `montant`, `date_emission`, `paye`) VALUES
(1, 1, 1185.00, '2025-10-26', 1),
(2, 2, 256.00, '2025-10-29', 0),
(3, 3, 185.00, '2025-10-30', 0),
(4, 4, 48.00, '2025-10-31', 1);

-- --------------------------------------------------------

--
-- Table structure for table `fournisseur`
--

CREATE TABLE `fournisseur` (
  `id_fournisseur` int NOT NULL,
  `nom` varchar(100) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `entreprise` varchar(100) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  `email` varchar(150) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  `telephone` varchar(20) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  `adresse` text CHARACTER SET latin1 COLLATE latin1_bin
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Dumping data for table `fournisseur`
--

INSERT INTO `fournisseur` (`id_fournisseur`, `nom`, `entreprise`, `email`, `telephone`, `adresse`) VALUES
(1, 'Dupont', 'Fournitures Paris', 'dupont@fournitures.com', '0102030405', '12 rue du Commerce, Paris');

-- --------------------------------------------------------

--
-- Table structure for table `magasin`
--

CREATE TABLE `magasin` (
  `id_magasin` int NOT NULL,
  `nom` varchar(100) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `ville` varchar(100) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  `adresse` text CHARACTER SET latin1 COLLATE latin1_bin,
  `telephone` varchar(20) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  `email` varchar(150) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  `type` enum('centrale','magasin') CHARACTER SET latin1 COLLATE latin1_bin DEFAULT 'magasin'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Dumping data for table `magasin`
--

INSERT INTO `magasin` (`id_magasin`, `nom`, `ville`, `adresse`, `telephone`, `email`, `type`) VALUES
(1, 'Centrale Paristanbul', 'Paris', NULL, NULL, NULL, 'centrale'),
(2, 'Paristanbul Villemomble', 'Lyon', NULL, NULL, NULL, 'magasin'),
(3, 'Paristanbul Bondy', 'Lille', NULL, NULL, NULL, 'magasin');

-- --------------------------------------------------------

--
-- Table structure for table `mouvement`
--

CREATE TABLE `mouvement` (
  `id_mouvement` int NOT NULL,
  `ref_produit` int DEFAULT NULL,
  `ref_magasin` int DEFAULT NULL,
  `type` enum('entrée','sortie') CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `quantite` int NOT NULL,
  `source` enum('fournisseur','centrale','autre') CHARACTER SET latin1 COLLATE latin1_bin DEFAULT 'centrale',
  `date_mouvement` datetime DEFAULT CURRENT_TIMESTAMP,
  `commentaire` text CHARACTER SET latin1 COLLATE latin1_bin
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Dumping data for table `mouvement`
--

INSERT INTO `mouvement` (`id_mouvement`, `ref_produit`, `ref_magasin`, `type`, `quantite`, `source`, `date_mouvement`, `commentaire`) VALUES
(1, 1, 1, 'entrée', 100, 'fournisseur', '2025-10-20 09:00:00', 'Livraison initiale riz'),
(2, 2, 2, 'entrée', 50, 'centrale', '2025-10-22 10:30:00', 'Stock transfert centrale-Lyon'),
(3, 3, 3, 'sortie', 20, '', '2025-10-25 15:45:00', 'Vente directe'),
(4, 1, 1, 'sortie', 30, '', '2025-10-28 12:00:00', 'Commande client');

-- --------------------------------------------------------

--
-- Table structure for table `produit`
--

CREATE TABLE `produit` (
  `id_produit` int NOT NULL,
  `libelle` varchar(150) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `marque` varchar(250) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  `quantite_centrale` int DEFAULT '0',
  `prix_unitaire` decimal(10,2) DEFAULT NULL,
  `seuil_alerte` int DEFAULT '10',
  `ref_categorie` int DEFAULT NULL,
  `date_ajout` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Dumping data for table `produit`
--

INSERT INTO `produit` (`id_produit`, `libelle`, `marque`, `quantite_centrale`, `prix_unitaire`, `seuil_alerte`, `ref_categorie`, `date_ajout`) VALUES
(1, 'Riz Basmati 5kg', 'Tilda', 250, 18.50, 20, 2, '2025-10-30 19:37:22'),
(2, 'Huile de tournesol 1L', 'Lesieur', 120, 4.90, 15, 2, '2025-10-30 19:37:22'),
(3, 'Jus d’orange 1L', 'Tropicana', 80, 3.20, 10, 1, '2025-10-30 19:37:22'),
(5, 'viande', 'délice halal', 70, 35.00, 20, 5, '2025-11-05 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_user` int NOT NULL,
  `nom` varchar(100) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `prenom` varchar(100) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  `email` varchar(150) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `mdp` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `role` enum('admin','gestionnaire','magasinier') CHARACTER SET latin1 COLLATE latin1_bin DEFAULT 'gestionnaire',
  `ref_magasin` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Dumping data for table `utilisateur`
--

INSERT INTO `utilisateur` (`id_user`, `nom`, `prenom`, `email`, `mdp`, `role`, `ref_magasin`) VALUES
(1, 'Admin', 'Centrale', 'admin@paristanbul.com', 'admin123', 'admin', 1),
(2, 'Julie', 'Martin', 'julie@lyon.com', 'test123', 'magasinier', 2),
(3, 'Karim', 'Ben', 'karim@lille.com', 'test123', 'magasinier', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id_categorie`);

--
-- Indexes for table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id_commande`),
  ADD KEY `ref_magasin` (`ref_magasin`),
  ADD KEY `ref_utilisateur` (`ref_utilisateur`);

--
-- Indexes for table `commande_detail`
--
ALTER TABLE `commande_detail`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `ref_commande` (`ref_commande`),
  ADD KEY `ref_produit` (`ref_produit`);

--
-- Indexes for table `facture`
--
ALTER TABLE `facture`
  ADD PRIMARY KEY (`id_facture`),
  ADD KEY `ref_commande` (`ref_commande`);

--
-- Indexes for table `fournisseur`
--
ALTER TABLE `fournisseur`
  ADD PRIMARY KEY (`id_fournisseur`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `magasin`
--
ALTER TABLE `magasin`
  ADD PRIMARY KEY (`id_magasin`);

--
-- Indexes for table `mouvement`
--
ALTER TABLE `mouvement`
  ADD PRIMARY KEY (`id_mouvement`),
  ADD KEY `ref_produit` (`ref_produit`),
  ADD KEY `ref_magasin` (`ref_magasin`);

--
-- Indexes for table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id_produit`),
  ADD KEY `ref_categorie` (`ref_categorie`);

--
-- Indexes for table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `ref_magasin` (`ref_magasin`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id_categorie` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `commande`
--
ALTER TABLE `commande`
  MODIFY `id_commande` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `commande_detail`
--
ALTER TABLE `commande_detail`
  MODIFY `id_detail` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `facture`
--
ALTER TABLE `facture`
  MODIFY `id_facture` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `fournisseur`
--
ALTER TABLE `fournisseur`
  MODIFY `id_fournisseur` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `magasin`
--
ALTER TABLE `magasin`
  MODIFY `id_magasin` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mouvement`
--
ALTER TABLE `mouvement`
  MODIFY `id_mouvement` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `produit`
--
ALTER TABLE `produit`
  MODIFY `id_produit` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`ref_magasin`) REFERENCES `magasin` (`id_magasin`) ON DELETE CASCADE,
  ADD CONSTRAINT `commande_ibfk_2` FOREIGN KEY (`ref_utilisateur`) REFERENCES `utilisateur` (`id_user`) ON DELETE SET NULL;

--
-- Constraints for table `commande_detail`
--
ALTER TABLE `commande_detail`
  ADD CONSTRAINT `commande_detail_ibfk_1` FOREIGN KEY (`ref_commande`) REFERENCES `commande` (`id_commande`) ON DELETE CASCADE,
  ADD CONSTRAINT `commande_detail_ibfk_2` FOREIGN KEY (`ref_produit`) REFERENCES `produit` (`id_produit`) ON DELETE CASCADE;

--
-- Constraints for table `facture`
--
ALTER TABLE `facture`
  ADD CONSTRAINT `facture_ibfk_1` FOREIGN KEY (`ref_commande`) REFERENCES `commande` (`id_commande`) ON DELETE CASCADE;

--
-- Constraints for table `mouvement`
--
ALTER TABLE `mouvement`
  ADD CONSTRAINT `mouvement_ibfk_1` FOREIGN KEY (`ref_produit`) REFERENCES `produit` (`id_produit`) ON DELETE CASCADE,
  ADD CONSTRAINT `mouvement_ibfk_2` FOREIGN KEY (`ref_magasin`) REFERENCES `magasin` (`id_magasin`) ON DELETE CASCADE;

--
-- Constraints for table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `produit_ibfk_1` FOREIGN KEY (`ref_categorie`) REFERENCES `categorie` (`id_categorie`) ON DELETE SET NULL;

--
-- Constraints for table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `utilisateur_ibfk_1` FOREIGN KEY (`ref_magasin`) REFERENCES `magasin` (`id_magasin`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
