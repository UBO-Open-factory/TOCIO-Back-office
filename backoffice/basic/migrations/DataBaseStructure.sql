-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : ven. 08 jan. 2021 à 13:56
-- Version du serveur :  10.3.27-MariaDB
-- Version de PHP : 7.2.24
 
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
 
 
 
--
-- Base de données : `data`
--
 
-- --------------------------------------------------------
 
--
-- Structure de la table `auth_assignment`
--
 
CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
 
-- --------------------------------------------------------
 
--
-- Structure de la table `auth_item`
--
 
CREATE TABLE `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` blob DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
 
-- --------------------------------------------------------
 
--
-- Structure de la table `auth_item_child`
--
 
CREATE TABLE `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
 
-- --------------------------------------------------------
 
--
-- Structure de la table `auth_rule`
--
 
CREATE TABLE `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` blob DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
 
-- --------------------------------------------------------
 
--
-- Structure de la table `capteur`
--
 
CREATE TABLE `capteur` (
  `id` int(4) NOT NULL,
  `nom` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 
-- --------------------------------------------------------
 
--
-- Structure de la table `grandeur`
--
 
CREATE TABLE `grandeur` (
  `id` int(3) NOT NULL,
  `nature` varchar(50) NOT NULL COMMENT 'nature en toutes lettres (Unité)',
  `formatCapteur` varchar(10) NOT NULL COMMENT 'signe - nombreDeCaracteresAvantVirgule, nombreDeCaracteresApresVirgule',
  `tablename` varchar(50) DEFAULT NULL COMMENT 'Nom interne de la table',
  `type` varchar(15) NOT NULL COMMENT 'Type des valeurs dans la table des mesures (Float, int, text, etc...)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 
-- --------------------------------------------------------
 
--
-- Structure de la table `localisationmodule`
--
 
CREATE TABLE `localisationmodule` (
  `id` int(3) UNSIGNED NOT NULL,
  `description` text NOT NULL COMMENT 'Description',
  `coordX` int(3) NOT NULL DEFAULT 0 COMMENT 'Coordonnées x dans le système de repérage',
  `coordY` int(3) NOT NULL DEFAULT 0 COMMENT 'Coordonnées y dans le système de repérage',
  `coordZ` int(3) NOT NULL DEFAULT 0 COMMENT 'Coordonnées z dans le système de repérage'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 
-- --------------------------------------------------------
 
--
-- Structure de la table `log`
--
 
CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `level` varchar(15) NOT NULL,
  `category` varchar(20) NOT NULL,
  `log_time` int(11) NOT NULL,
  `prefix` varchar(50) NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Logs de l''application Yii';
 
-- --------------------------------------------------------
 
--
-- Structure de la table `migration`
--
 
CREATE TABLE `migration` (
  `version` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
 
-- --------------------------------------------------------
 
--
-- Structure de la table `module`
--
 
CREATE TABLE `module` (
  `identifiantReseau` varchar(50) NOT NULL,
  `nom` varchar(50) NOT NULL COMMENT 'Le nom du module',
  `description` text NOT NULL,
  `idLocalisationModule` int(3) UNSIGNED NOT NULL,
  `actif` tinyint(1) NOT NULL COMMENT '1 = Actif, 0 = Innactif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 
-- --------------------------------------------------------
 
--
-- Structure de la table `rel_capteurgrandeur`
--
 
CREATE TABLE `rel_capteurgrandeur` (
  `idCapteur` int(4) NOT NULL,
  `idGrandeur` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 
-- --------------------------------------------------------
 
--
-- Structure de la table `rel_modulecapteur`
--
 
CREATE TABLE `rel_modulecapteur` (
  `idModule` varchar(50) NOT NULL,
  `idCapteur` int(4) NOT NULL,
  `nomcapteur` text NOT NULL,
  `ordre` int(2) NOT NULL DEFAULT 0,
  `x` int(3) NOT NULL DEFAULT 0 COMMENT 'Coordonnées X',
  `y` int(3) NOT NULL DEFAULT 0 COMMENT 'Coordonnées Y',
  `z` int(3) NOT NULL DEFAULT 0 COMMENT 'Coordonnées Z'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 
-- --------------------------------------------------------
 
--
-- Structure de la table `utilisateur`
--
 
CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `authKey` varchar(50) DEFAULT NULL,
  `accessToken` text DEFAULT NULL,
  `lastAccess` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `utilisateur` (`id`, `username`, `email`, `password`, `authKey`, `accessToken`, `lastAccess`) VALUES
(1, 'admin', 'admin@server.fr', '$2y$13$E5nJTOVmgqXBxXs/PjlOluX0OrtnSzbX4XInE0KLGVLG.ivXC2bri', NULL, NULL, ''),

--
-- Index pour les tables déchargées
--
 
--
-- Index pour la table `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD PRIMARY KEY (`item_name`,`user_id`),
  ADD KEY `idx-auth_assignment-user_id` (`user_id`);
 
--
-- Index pour la table `auth_item`
--
ALTER TABLE `auth_item`
  ADD PRIMARY KEY (`name`),
  ADD KEY `rule_name` (`rule_name`),
  ADD KEY `idx-auth_item-type` (`type`);
 
--
-- Index pour la table `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD PRIMARY KEY (`parent`,`child`),
  ADD KEY `child` (`child`);
 
--
-- Index pour la table `auth_rule`
--
ALTER TABLE `auth_rule`
  ADD PRIMARY KEY (`name`);
 
--
-- Index pour la table `capteur`
--
ALTER TABLE `capteur`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);
 
--
-- Index pour la table `grandeur`
--
ALTER TABLE `grandeur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Nature_Unique` (`nature`),
  ADD KEY `id` (`id`) USING BTREE;
 
--
-- Index pour la table `localisationmodule`
--
ALTER TABLE `localisationmodule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);
 
--
-- Index pour la table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`);
 
--
-- Index pour la table `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);
 
--
-- Index pour la table `module`
--
ALTER TABLE `module`
  ADD PRIMARY KEY (`identifiantReseau`),
  ADD UNIQUE KEY `identifiantReseau` (`identifiantReseau`),
  ADD KEY `module_localisation` (`idLocalisationModule`);
 
--
-- Index pour la table `rel_capteurgrandeur`
--
ALTER TABLE `rel_capteurgrandeur`
  ADD UNIQUE KEY `cle` (`idCapteur`,`idGrandeur`),
  ADD KEY `contgrandeur` (`idGrandeur`);
 
--
-- Index pour la table `rel_modulecapteur`
--
ALTER TABLE `rel_modulecapteur`
  ADD PRIMARY KEY (`idModule`,`idCapteur`,`nomcapteur`(50),`ordre`) USING BTREE,
  ADD KEY `contcapteur` (`idCapteur`);
 
--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `username` (`username`);
 
--
-- AUTO_INCREMENT pour les tables déchargées
--
 
--
-- AUTO_INCREMENT pour la table `capteur`
--
ALTER TABLE `capteur`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
 
--
-- AUTO_INCREMENT pour la table `grandeur`
--
ALTER TABLE `grandeur`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT;
 
--
-- AUTO_INCREMENT pour la table `localisationmodule`
--
ALTER TABLE `localisationmodule`
  MODIFY `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT;
 
--
-- AUTO_INCREMENT pour la table `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
 
--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
 
--
-- Contraintes pour les tables déchargées
--
 
--
-- Contraintes pour la table `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;
 
--
-- Contraintes pour la table `auth_item`
--
ALTER TABLE `auth_item`
  ADD CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;
 
--
-- Contraintes pour la table `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;
 
--
-- Contraintes pour la table `module`
--
ALTER TABLE `module`
  ADD CONSTRAINT `module_localisation` FOREIGN KEY (`idLocalisationModule`) REFERENCES `localisationmodule` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
 
--
-- Contraintes pour la table `rel_capteurgrandeur`
--
ALTER TABLE `rel_capteurgrandeur`
  ADD CONSTRAINT `contgrandeur` FOREIGN KEY (`idGrandeur`) REFERENCES `grandeur` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
 
--
-- Contraintes pour la table `rel_modulecapteur`
--
ALTER TABLE `rel_modulecapteur`
  ADD CONSTRAINT `contcapteur` FOREIGN KEY (`idCapteur`) REFERENCES `capteur` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contmodule` FOREIGN KEY (`idModule`) REFERENCES `module` (`identifiantReseau`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;