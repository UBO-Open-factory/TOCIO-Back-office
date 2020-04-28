# Installation BDD
La structure de la BDD doit être initialisée de la façon suivante :
```mysql
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

DROP TABLE IF EXISTS `capteur`;
CREATE TABLE `capteur` (
  `id` int(4) NOT NULL,
  `nom` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `grandeur`;
CREATE TABLE `grandeur` (
  `id` int(3) NOT NULL,
  `nature` varchar(50) NOT NULL COMMENT 'nature en toutes lettres (Unité)',
  `formatCapteur` varchar(10) NOT NULL COMMENT 'signe - nombreDeCaracteresAvantVirgule, nombreDeCaracteresApresVirgule',
  `tablename` varchar(50) DEFAULT NULL COMMENT 'Nom interne de la table',
  `type` varchar(10) NOT NULL COMMENT 'Type des valeurs dans la table des mesures (Float, int, text, etc...)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `localisationmodule`;
CREATE TABLE `localisationmodule` (
  `id` int(3) UNSIGNED NOT NULL,
  `description` text NOT NULL COMMENT 'Description',
  `coordX` int(3) NOT NULL DEFAULT 0 COMMENT 'Coordonnées x dans le système de repérage',
  `coordY` int(3) NOT NULL DEFAULT 0 COMMENT 'Coordonnées y dans le système de repérage',
  `coordZ` int(3) NOT NULL DEFAULT 0 COMMENT 'Coordonnées z dans le système de repérage'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `log`;
CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `level` varchar(15) NOT NULL,
  `category` varchar(20) NOT NULL,
  `log_time` int(11) NOT NULL,
  `prefix` varchar(50) NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Logs de l''application Yii';


DROP TABLE IF EXISTS `module`;
CREATE TABLE `module` (
  `identifiantReseau` varchar(50) NOT NULL,
  `nom` varchar(50) NOT NULL COMMENT 'Le nom du module',
  `description` text NOT NULL,
  `idLocalisationModule` int(3) UNSIGNED NOT NULL,
  `actif` tinyint(1) NOT NULL COMMENT '1 = Actif, 0 = Innactif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `rel_capteurgrandeur`;
CREATE TABLE `rel_capteurgrandeur` (
  `idCapteur` int(4) NOT NULL,
  `idGrandeur` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `rel_modulecapteur`;
CREATE TABLE `rel_modulecapteur` (
  `idModule` varchar(50) NOT NULL,
  `idCapteur` int(4) NOT NULL,
  `nomcapteur` text NOT NULL,
  `ordre` int(2) NOT NULL DEFAULT 0,
  `x` int(3) NOT NULL DEFAULT 0 COMMENT 'Coordonnées X',
  `y` int(3) NOT NULL DEFAULT 0 COMMENT 'Coordonnées Y',
  `z` int(3) NOT NULL DEFAULT 0 COMMENT 'Coordonnées Z'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` text NOT NULL,
  `authKey` varchar(50) DEFAULT NULL,
  `accessToken` text DEFAULT NULL,
  `lastAccess` date NOT NULL DEFAULT current_timestamp(),
  `idGroupe` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `utilisateurs_group`;
CREATE TABLE `utilisateurs_group` (
  `id` int(2) NOT NULL,
  `groupName` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `capteur`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

ALTER TABLE `grandeur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Nature_Unique` (`nature`),
  ADD KEY `id` (`id`) USING BTREE;

ALTER TABLE `localisationmodule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

ALTER TABLE `log`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `module`
  ADD PRIMARY KEY (`identifiantReseau`),
  ADD UNIQUE KEY `identifiantReseau` (`identifiantReseau`),
  ADD KEY `module_localisation` (`idLocalisationModule`);

ALTER TABLE `rel_capteurgrandeur`
  ADD UNIQUE KEY `cle` (`idCapteur`,`idGrandeur`),
  ADD KEY `contgrandeur` (`idGrandeur`);

ALTER TABLE `rel_modulecapteur`
  ADD PRIMARY KEY (`idModule`,`idCapteur`,`nomcapteur`(50),`ordre`) USING BTREE,
  ADD KEY `contcapteur` (`idCapteur`);

ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `idGroupe` (`idGroupe`);

ALTER TABLE `utilisateurs_group`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `id` (`id`);


ALTER TABLE `capteur`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

ALTER TABLE `grandeur`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

ALTER TABLE `localisationmodule`
  MODIFY `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=456;

ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `utilisateurs_group`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;


ALTER TABLE `module`
  ADD CONSTRAINT `module_localisation` FOREIGN KEY (`idLocalisationModule`) REFERENCES `localisationmodule` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `rel_capteurgrandeur`
  ADD CONSTRAINT `contgrandeur` FOREIGN KEY (`idGrandeur`) REFERENCES `grandeur` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `rel_modulecapteur`
  ADD CONSTRAINT `contcapteur` FOREIGN KEY (`idCapteur`) REFERENCES `capteur` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contmodule` FOREIGN KEY (`idModule`) REFERENCES `module` (`identifiantReseau`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `utilisateurs`
  ADD CONSTRAINT `utilisateurs_ibfk_1` FOREIGN KEY (`idGroupe`) REFERENCES `utilisateurs_group` (`id`);
COMMIT;

```