-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: mysql-vegas-security.alwaysdata.net
-- Generation Time: Mar 22, 2019 at 09:20 AM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vegas-security_bd`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_activites`
--

CREATE TABLE `tb_activites` (
  `col_id` int(11) NOT NULL,
  `col_code` varchar(45) NOT NULL,
  `col_libele` varchar(45) NOT NULL,
  `col_photo` varchar(200) NOT NULL,
  `col_type` varchar(45) NOT NULL,
  `col_description` varchar(145) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_activites`
--

INSERT INTO `tb_activites` (`col_id`, `col_code`, `col_libele`, `col_photo`, `col_type`, `col_description`) VALUES
(1, 'BAR', 'Gestion Bar', '9c3ee-bar.jpg', 'alimentation', '<p>\r\n	vente de boisson gazeuses</p>\r\n'),
(2, 'MOBILE', 'MOBILE MONEY', '339f6-mobile.jpg', 'finance', '<p>\r\n	mobile money</p>\r\n'),
(3, 'CALLBOX', 'RECHARGE AIRTIME', '9ac7e-images.jpg', 'call', '<p>\r\n	call center</p>\r\n'),
(4, 'IMMOBILIER', 'HEBERGEMENT', '7e176-pret-immobilier-en-cours-societe-generale.jpg', 'loisir', '<p>\r\n	loisir</p>\r\n'),
(5, 'LAVERIE', 'LAVERIE AUTO', 'edf55-index.jpg', 'wash', '<p>\r\n	car</p>\r\n'),
(6, 'CANAL', 'CANAL PLUS', '48f6a-canal-600x440.png', 'tv', '<p>\r\n	tv</p>\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `tb_activite_espace`
--

CREATE TABLE `tb_activite_espace` (
  `col_espace` int(11) NOT NULL,
  `col_activite` int(11) NOT NULL,
  `col_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_activite_espace`
--

INSERT INTO `tb_activite_espace` (`col_espace`, `col_activite`, `col_date`) VALUES
(1, 1, '0000-00-00 00:00:00'),
(2, 1, '0000-00-00 00:00:00'),
(3, 1, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tb_commandes`
--

CREATE TABLE `tb_commandes` (
  `col_id` int(11) NOT NULL,
  `col_date` datetime NOT NULL,
  `col_statut` enum('P','F','O','R') NOT NULL,
  `col_servant` int(11) NOT NULL,
  `col_client` int(11) DEFAULT NULL,
  `col_espace` int(11) NOT NULL,
  `col_activite` int(11) NOT NULL,
  `col_description` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_commandes`
--

INSERT INTO `tb_commandes` (`col_id`, `col_date`, `col_statut`, `col_servant`, `col_client`, `col_espace`, `col_activite`, `col_description`) VALUES
(1, '2018-07-27 11:07:47', 'F', 1, 1, 3, 1, ''),
(2, '2018-07-27 12:07:44', 'F', 1, 2, 1, 1, ''),
(3, '2018-07-28 18:07:16', 'P', 1, 2, 3, 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `tb_commandes_stocks`
--

CREATE TABLE `tb_commandes_stocks` (
  `col_id` int(11) NOT NULL,
  `col_stock` int(11) NOT NULL,
  `col_commande` int(11) NOT NULL,
  `col_qte` int(11) DEFAULT NULL,
  `col_puv_reel` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_commandes_stocks`
--

INSERT INTO `tb_commandes_stocks` (`col_id`, `col_stock`, `col_commande`, `col_qte`, `col_puv_reel`) VALUES
(1, 1, 1, 20, 500),
(2, 1, 2, 10, 700),
(3, 1, 3, 2, 500);

-- --------------------------------------------------------

--
-- Table structure for table `tb_espace`
--

CREATE TABLE `tb_espace` (
  `col_id` int(11) NOT NULL,
  `col_libele` varchar(45) NOT NULL,
  `col_position` varchar(45) DEFAULT NULL,
  `col_photo` varchar(200) NOT NULL,
  `col_statut` enum('LIBRE','OCCUPE') NOT NULL,
  `col_agis_sur_stock` tinyint(1) NOT NULL DEFAULT '1',
  `col_nbre_place` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_espace`
--

INSERT INTO `tb_espace` (`col_id`, `col_libele`, `col_position`, `col_photo`, `col_statut`, `col_agis_sur_stock`, `col_nbre_place`) VALUES
(1, 'TABLE 1', '1', '44782-japanese-restaurant-table.jpg77c7d3de-0d6c-4279-926d-e9158996d476larger.jpg', 'LIBRE', 1, 5),
(2, 'TABLE 2', '2', '44957-japanese-restaurant-table.jpg77c7d3de-0d6c-4279-926d-e9158996d476larger.jpg', 'LIBRE', 1, 5),
(3, 'TABLE 3', '3', '74739-japanese-restaurant-table.jpg77c7d3de-0d6c-4279-926d-e9158996d476larger.jpg', 'LIBRE', 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `tb_factures`
--

CREATE TABLE `tb_factures` (
  `col_id` int(11) NOT NULL,
  `col_date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  `col_date_reglement` datetime DEFAULT CURRENT_TIMESTAMP,
  `col_caissier` int(11) DEFAULT NULL,
  `col_statut` enum('P','O','D') NOT NULL,
  `col_description` varchar(150) NOT NULL,
  `col_generer_sms` tinyint(1) NOT NULL DEFAULT '0',
  `col_generer_mail` tinyint(1) NOT NULL DEFAULT '0',
  `col_generer_print` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_factures`
--

INSERT INTO `tb_factures` (`col_id`, `col_date_creation`, `col_date_reglement`, `col_caissier`, `col_statut`, `col_description`, `col_generer_sms`, `col_generer_mail`, `col_generer_print`) VALUES
(1, '2018-07-27 11:07:12', '2018-07-27 11:07:12', 1, 'O', 'nouvelle facture', 0, 0, 1),
(2, '2018-07-28 13:07:04', '2018-07-28 13:07:04', 1, 'O', 'nouvelle facture', 0, 0, 1),
(3, '2018-07-28 18:07:30', '2018-07-28 18:07:38', 1, 'P', 'nouvelle facture', 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tb_factures_commandes`
--

CREATE TABLE `tb_factures_commandes` (
  `col_commande` int(11) NOT NULL,
  `col_facture` int(11) NOT NULL,
  `col_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_factures_commandes`
--

INSERT INTO `tb_factures_commandes` (`col_commande`, `col_facture`, `col_date`) VALUES
(1, 1, '2018-07-27 11:07:12'),
(2, 2, '2018-07-28 13:07:04'),
(3, 3, '2018-07-28 18:07:30');

-- --------------------------------------------------------

--
-- Table structure for table `tb_privileges`
--

CREATE TABLE `tb_privileges` (
  `col_id` int(11) NOT NULL,
  `col_privilege` varchar(45) DEFAULT NULL,
  `col_code` varchar(45) NOT NULL,
  `col_description` varchar(145) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_privileges`
--

INSERT INTO `tb_privileges` (`col_id`, `col_privilege`, `col_code`, `col_description`) VALUES
(1, 'ajouter les users', 'VEGAS-01', 'ajouter les users'),
(2, 'modifier les users', 'VEGAS-02', 'modifier les users'),
(3, 'supprimer les users', 'VEGAS-03', 'supprimer les users'),
(4, 'lister les users', 'VEGAS-04', 'lister les users'),
(5, 'voir les logs', 'VEGAS-05', 'voir les logs'),
(6, 'supprimer les logs', 'VEGAS-06', 'supprimer les logs'),
(7, 'voir gestion web', 'VEGAS-07', 'voir gestion web'),
(8, 'voir gestion mobile', 'VEGAS-08', 'voir gestion mobile'),
(9, 'gerer les roles', 'VEGAS-09', 'gerer les roles'),
(10, 'gerer les privileges', 'VEGAS-10', 'gerer les privileges'),
(11, 'ajouter un template', 'VEGAS-11', 'ajouter un template'),
(12, 'modifier un template', 'VEGAS-12', 'modifier un template'),
(13, 'supprimer un template', 'VEGAS-13', 'supprimer un template'),
(14, 'lister les templates', 'VEGAS-14', 'lister les templates'),
(15, 'gestion des activites', 'VEGAS-15', 'gestion des activites'),
(16, 'gestion des espaces', 'VEGAS-16', 'gestion des espaces'),
(17, 'gestion des stocks', 'VEGAS-17', 'gestion des stocks'),
(18, 'gestion des factures', 'VEGAS-18', 'gestion des factures'),
(19, 'gestion des commandes', 'VEGAS-19', 'gestion des commandes'),
(20, 'gestion des ventes', 'VEGAS-20', 'gestion des ventes');

-- --------------------------------------------------------

--
-- Table structure for table `tb_roles`
--

CREATE TABLE `tb_roles` (
  `col_id` int(11) NOT NULL,
  `col_nom` varchar(45) NOT NULL,
  `col_description` varchar(145) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_roles`
--

INSERT INTO `tb_roles` (`col_id`, `col_nom`, `col_description`) VALUES
(1, 'SUPER_ADMIN', '<p>\r\n	super admin</p>\r\n'),
(2, 'CLIENT', 'clients'),
(3, 'FOURNISSEUR', 'fournisseur'),
(4, 'VENDEUR', '<p>\r\n	vendeur</p>\r\n'),
(5, 'CAISSIER', '<p>\r\n	caissier</p>\r\n'),
(6, 'GESTIONNAIRE DE STOCK', '<p>\r\n	gestionnaire de stock</p>\r\n'),
(7, 'MANAGER', '<p>\r\n	manager</p>\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `tb_role_privilege`
--

CREATE TABLE `tb_role_privilege` (
  `col_roles` int(11) NOT NULL,
  `col_privileges` int(11) NOT NULL,
  `col_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_role_privilege`
--

INSERT INTO `tb_role_privilege` (`col_roles`, `col_privileges`, `col_date`) VALUES
(1, 1, '0000-00-00 00:00:00'),
(1, 2, '0000-00-00 00:00:00'),
(1, 3, '0000-00-00 00:00:00'),
(1, 4, '0000-00-00 00:00:00'),
(1, 5, '0000-00-00 00:00:00'),
(1, 6, '0000-00-00 00:00:00'),
(1, 7, '0000-00-00 00:00:00'),
(1, 8, '0000-00-00 00:00:00'),
(1, 9, '0000-00-00 00:00:00'),
(1, 10, '0000-00-00 00:00:00'),
(1, 11, '0000-00-00 00:00:00'),
(1, 12, '0000-00-00 00:00:00'),
(1, 13, '0000-00-00 00:00:00'),
(1, 14, '0000-00-00 00:00:00'),
(1, 15, '0000-00-00 00:00:00'),
(1, 16, '0000-00-00 00:00:00'),
(1, 17, '0000-00-00 00:00:00'),
(1, 18, '0000-00-00 00:00:00'),
(1, 19, '0000-00-00 00:00:00'),
(1, 20, '0000-00-00 00:00:00'),
(4, 8, '0000-00-00 00:00:00'),
(4, 15, '0000-00-00 00:00:00'),
(4, 16, '0000-00-00 00:00:00'),
(4, 19, '0000-00-00 00:00:00'),
(4, 20, '0000-00-00 00:00:00'),
(5, 8, '0000-00-00 00:00:00'),
(5, 15, '0000-00-00 00:00:00'),
(5, 16, '0000-00-00 00:00:00'),
(5, 18, '0000-00-00 00:00:00'),
(6, 8, '0000-00-00 00:00:00'),
(6, 15, '0000-00-00 00:00:00'),
(6, 16, '0000-00-00 00:00:00'),
(6, 17, '0000-00-00 00:00:00'),
(7, 1, '0000-00-00 00:00:00'),
(7, 2, '0000-00-00 00:00:00'),
(7, 3, '0000-00-00 00:00:00'),
(7, 4, '0000-00-00 00:00:00'),
(7, 7, '0000-00-00 00:00:00'),
(7, 8, '0000-00-00 00:00:00'),
(7, 9, '0000-00-00 00:00:00'),
(7, 15, '0000-00-00 00:00:00'),
(7, 16, '0000-00-00 00:00:00'),
(7, 17, '0000-00-00 00:00:00'),
(7, 18, '0000-00-00 00:00:00'),
(7, 19, '0000-00-00 00:00:00'),
(7, 20, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tb_stocks`
--

CREATE TABLE `tb_stocks` (
  `col_id` int(11) NOT NULL,
  `col_libele` varchar(45) NOT NULL,
  `col_qte` int(11) NOT NULL,
  `col_est_virtuel` tinyint(1) NOT NULL DEFAULT '0',
  `col_pua` int(11) NOT NULL,
  `col_puv` int(11) NOT NULL,
  `col_date_deniere_mod` datetime NOT NULL,
  `col_description` text,
  `col_historique` text,
  `col_fornisseur` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_stocks`
--

INSERT INTO `tb_stocks` (`col_id`, `col_libele`, `col_qte`, `col_est_virtuel`, `col_pua`, `col_puv`, `col_date_deniere_mod`, `col_description`, `col_historique`, `col_fornisseur`) VALUES
(1, 'CASTEL', 18, 0, 450, 500, '2018-07-27 10:07:14', 'TEST', '[2018-07-27 10:07:14]: Mise ajour du stock par Mr/Mme FK Christian : nouvelle qte = 50', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tb_stock_activite`
--

CREATE TABLE `tb_stock_activite` (
  `col_stock` int(11) NOT NULL,
  `col_activite` int(11) NOT NULL,
  `col_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_stock_activite`
--

INSERT INTO `tb_stock_activite` (`col_stock`, `col_activite`, `col_date`) VALUES
(1, 1, '2018-07-27 10:07:14');

-- --------------------------------------------------------

--
-- Table structure for table `tb_templates`
--

CREATE TABLE `tb_templates` (
  `col_id` int(11) NOT NULL,
  `col_code` varchar(30) NOT NULL,
  `col_name` varchar(100) NOT NULL,
  `col_html` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_templates`
--

INSERT INTO `tb_templates` (`col_id`, `col_code`, `col_name`, `col_html`) VALUES
(1, 'FORGOT', 'mot de passe oublié', 'bonjour');

-- --------------------------------------------------------

--
-- Table structure for table `tb_users`
--

CREATE TABLE `tb_users` (
  `col_id` int(11) NOT NULL,
  `col_key` varchar(255) DEFAULT NULL,
  `col_status` varchar(30) DEFAULT NULL,
  `col_is_delete` tinyint(1) DEFAULT '0',
  `col_profil_pic` varchar(255) DEFAULT NULL,
  `col_nom_prenom` varchar(150) DEFAULT NULL,
  `col_cni_or_passport` varchar(20) NOT NULL,
  `col_carte_canal` varchar(15) DEFAULT NULL,
  `col_telephone` varchar(45) NOT NULL,
  `col_email` varchar(45) DEFAULT NULL,
  `col_password` varchar(255) NOT NULL,
  `col_ville` varchar(45) DEFAULT NULL,
  `col_quartier` varchar(45) DEFAULT NULL,
  `col_date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  `col_description` text,
  `col_role` int(11) NOT NULL,
  `col_creation` varchar(200) NOT NULL DEFAULT '{"ORANGE":{"statut":1,"data":"+237699876016"},"MTN":{"statut":1,"data":"+237678132186"},"EU":{"statut":1,"data":"+237696121323"},"CANAL":{"statut":1,"data":"EVASION"}}' COMMENT '{"ORANGE":{"statut":1,"data":"+237699876016"},"MTN":{"statut":1,"data":"+237678132186"},"EU":{"statut":1,"data":"+237696121323"},"CANAL":{"statut":1,"data":"EVASION"}}'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_users`
--

INSERT INTO `tb_users` (`col_id`, `col_key`, `col_status`, `col_is_delete`, `col_profil_pic`, `col_nom_prenom`, `col_cni_or_passport`, `col_carte_canal`, `col_telephone`, `col_email`, `col_password`, `col_ville`, `col_quartier`, `col_date_creation`, `col_description`, `col_role`, `col_creation`) VALUES
(1, NULL, 'active', 0, 'IMG_20180203_064049_1521215155.jpg', 'FK Christian', '123456789', '0', '+237678132186', 'fodoup@gmail.com', '$2y$10$8/iJkKxtvjZjIRW5QdtiQuisAMCahHLkWzfWH3OPw3TpFMGe66I6W', 'Yaounde', 'Emana', '2018-06-18 07:24:31', 'ras', 1, '{\"ORANGE\":{\"data\":\"+237699876016\",\"statut\":1},\"MTN\":{\"data\":\"+237678132186\",\"statut\":0},\"EU\":{\"data\":\"+237696121323\",\"statut\":0},\"CANAL\":{\"data\":\"EVASION\",\"statut\":0}}'),
(2, NULL, NULL, 0, NULL, 'INCONNU', '123456789', NULL, '237000000000', 'info@vegasafrica.net', 'client', NULL, NULL, '2018-07-27 12:01:44', NULL, 2, '{\"ORANGE\":{\"statut\":1,\"data\":\"+237699876016\"},\"MTN\":{\"statut\":1,\"data\":\"+237678132186\"},\"EU\":{\"statut\":1,\"data\":\"+237696121323\"},\"CANAL\":{\"statut\":1,\"data\":\"EVASION\"}}');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_activites`
--
ALTER TABLE `tb_activites`
  ADD PRIMARY KEY (`col_id`);

--
-- Indexes for table `tb_activite_espace`
--
ALTER TABLE `tb_activite_espace`
  ADD PRIMARY KEY (`col_espace`,`col_activite`),
  ADD KEY `fk_tb_espace_has_tb_activites_tb_activites1_idx` (`col_activite`),
  ADD KEY `fk_tb_espace_has_tb_activites_tb_espace1_idx` (`col_espace`);

--
-- Indexes for table `tb_commandes`
--
ALTER TABLE `tb_commandes`
  ADD PRIMARY KEY (`col_id`),
  ADD KEY `fk_tb_commandes_tb_users1_idx` (`col_servant`),
  ADD KEY `fk_tb_commandes_tb_users2_idx` (`col_client`),
  ADD KEY `fk_tb_commandes_tb_espace1_idx` (`col_espace`),
  ADD KEY `fk_tb_commandes_tb_activites1_idx` (`col_activite`);

--
-- Indexes for table `tb_commandes_stocks`
--
ALTER TABLE `tb_commandes_stocks`
  ADD PRIMARY KEY (`col_id`),
  ADD KEY `fk_tb_stocks_has_tb_commandes_tb_commandes1_idx` (`col_commande`),
  ADD KEY `fk_tb_stocks_has_tb_commandes_tb_stocks1_idx` (`col_stock`);

--
-- Indexes for table `tb_espace`
--
ALTER TABLE `tb_espace`
  ADD PRIMARY KEY (`col_id`);

--
-- Indexes for table `tb_factures`
--
ALTER TABLE `tb_factures`
  ADD PRIMARY KEY (`col_id`),
  ADD KEY `fk_tb_factures_tb_users1_idx` (`col_caissier`);

--
-- Indexes for table `tb_factures_commandes`
--
ALTER TABLE `tb_factures_commandes`
  ADD PRIMARY KEY (`col_commande`,`col_facture`),
  ADD KEY `fk_tb_commandes_has_tb_factures_tb_factures1_idx` (`col_facture`),
  ADD KEY `fk_tb_commandes_has_tb_factures_tb_commandes1_idx` (`col_commande`);

--
-- Indexes for table `tb_privileges`
--
ALTER TABLE `tb_privileges`
  ADD PRIMARY KEY (`col_id`),
  ADD UNIQUE KEY `col_code_UNIQUE` (`col_code`);

--
-- Indexes for table `tb_roles`
--
ALTER TABLE `tb_roles`
  ADD PRIMARY KEY (`col_id`);

--
-- Indexes for table `tb_role_privilege`
--
ALTER TABLE `tb_role_privilege`
  ADD PRIMARY KEY (`col_roles`,`col_privileges`),
  ADD KEY `fk_tb_roles_has_tb_privileges_tb_privileges1_idx` (`col_privileges`),
  ADD KEY `fk_tb_roles_has_tb_privileges_tb_roles_idx` (`col_roles`);

--
-- Indexes for table `tb_stocks`
--
ALTER TABLE `tb_stocks`
  ADD PRIMARY KEY (`col_id`),
  ADD KEY `fk_tb_stocks_tb_users1_idx` (`col_fornisseur`);

--
-- Indexes for table `tb_stock_activite`
--
ALTER TABLE `tb_stock_activite`
  ADD PRIMARY KEY (`col_stock`,`col_activite`),
  ADD KEY `fk_tb_stocks_has_tb_activites_tb_activites1_idx` (`col_activite`),
  ADD KEY `fk_tb_stocks_has_tb_activites_tb_stocks1_idx` (`col_stock`);

--
-- Indexes for table `tb_templates`
--
ALTER TABLE `tb_templates`
  ADD PRIMARY KEY (`col_id`);

--
-- Indexes for table `tb_users`
--
ALTER TABLE `tb_users`
  ADD PRIMARY KEY (`col_id`),
  ADD KEY `fk_tb_users_tb_roles1_idx` (`col_role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_activites`
--
ALTER TABLE `tb_activites`
  MODIFY `col_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tb_commandes`
--
ALTER TABLE `tb_commandes`
  MODIFY `col_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tb_commandes_stocks`
--
ALTER TABLE `tb_commandes_stocks`
  MODIFY `col_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_espace`
--
ALTER TABLE `tb_espace`
  MODIFY `col_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_factures`
--
ALTER TABLE `tb_factures`
  MODIFY `col_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_privileges`
--
ALTER TABLE `tb_privileges`
  MODIFY `col_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tb_roles`
--
ALTER TABLE `tb_roles`
  MODIFY `col_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tb_stocks`
--
ALTER TABLE `tb_stocks`
  MODIFY `col_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_templates`
--
ALTER TABLE `tb_templates`
  MODIFY `col_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_users`
--
ALTER TABLE `tb_users`
  MODIFY `col_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_activite_espace`
--
ALTER TABLE `tb_activite_espace`
  ADD CONSTRAINT `fk_tb_espace_has_tb_activites_tb_activites1` FOREIGN KEY (`col_activite`) REFERENCES `tb_activites` (`col_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tb_espace_has_tb_activites_tb_espace1` FOREIGN KEY (`col_espace`) REFERENCES `tb_espace` (`col_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_commandes`
--
ALTER TABLE `tb_commandes`
  ADD CONSTRAINT `fk_tb_commandes_tb_activites1` FOREIGN KEY (`col_activite`) REFERENCES `tb_activites` (`col_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tb_commandes_tb_espace1` FOREIGN KEY (`col_espace`) REFERENCES `tb_espace` (`col_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tb_commandes_tb_users1` FOREIGN KEY (`col_servant`) REFERENCES `tb_users` (`col_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tb_commandes_tb_users2` FOREIGN KEY (`col_client`) REFERENCES `tb_users` (`col_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_commandes_stocks`
--
ALTER TABLE `tb_commandes_stocks`
  ADD CONSTRAINT `fk_tb_stocks_has_tb_commandes_tb_commandes1` FOREIGN KEY (`col_commande`) REFERENCES `tb_commandes` (`col_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tb_stocks_has_tb_commandes_tb_stocks1` FOREIGN KEY (`col_stock`) REFERENCES `tb_stocks` (`col_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_factures`
--
ALTER TABLE `tb_factures`
  ADD CONSTRAINT `fk_tb_factures_tb_users1` FOREIGN KEY (`col_caissier`) REFERENCES `tb_users` (`col_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_factures_commandes`
--
ALTER TABLE `tb_factures_commandes`
  ADD CONSTRAINT `fk_tb_commandes_has_tb_factures_tb_commandes1` FOREIGN KEY (`col_commande`) REFERENCES `tb_commandes` (`col_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tb_commandes_has_tb_factures_tb_factures1` FOREIGN KEY (`col_facture`) REFERENCES `tb_factures` (`col_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_role_privilege`
--
ALTER TABLE `tb_role_privilege`
  ADD CONSTRAINT `fk_tb_roles_has_tb_privileges_tb_privileges1` FOREIGN KEY (`col_privileges`) REFERENCES `tb_privileges` (`col_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tb_roles_has_tb_privileges_tb_roles` FOREIGN KEY (`col_roles`) REFERENCES `tb_roles` (`col_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_stocks`
--
ALTER TABLE `tb_stocks`
  ADD CONSTRAINT `fk_tb_stocks_tb_users1` FOREIGN KEY (`col_fornisseur`) REFERENCES `tb_users` (`col_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_stock_activite`
--
ALTER TABLE `tb_stock_activite`
  ADD CONSTRAINT `fk_tb_stocks_has_tb_activites_tb_activites1` FOREIGN KEY (`col_activite`) REFERENCES `tb_activites` (`col_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tb_stocks_has_tb_activites_tb_stocks1` FOREIGN KEY (`col_stock`) REFERENCES `tb_stocks` (`col_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_users`
--
ALTER TABLE `tb_users`
  ADD CONSTRAINT `fk_tb_users_tb_roles1` FOREIGN KEY (`col_role`) REFERENCES `tb_roles` (`col_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
