<?php

use yii\db\Migration;


class m210420_095147_data extends Migration
{
    public function safeUp()
    {
        /*
        this document is a migration script for TOCIO application
        use it by enter "php yii migrate" to create associate 
        database for the application. 
        use "php yii migrate/down" to undo the script.

        WARNING : this script can only be use if you have already
        create and configure a database named "data"

        WARNING : your database "data" need to be empty or at least
        doesn't contains this table name :
            auth_assignment
            auth_item
            auth_rule
            utilisateur
            rel_capteurgrandeur
            rel_modulecapteur
            module
            localisationmodule
            capteur
            grandeur
            log
            migration
        any table with the same name would be drop and data will be
        lose
        */

        /*=========================

            auth_assignment

        =========================*/
        $this->execute
        ("
            CREATE TABLE IF NOT EXISTS `auth_assignment` 
            (
                `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
                `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
                `created_at` int(11) DEFAULT NULL,
                PRIMARY KEY (`item_name`,`user_id`),
                KEY `idx-auth_assignment-user_id` (`user_id`)
            ) 
            ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");

        /*=========================

            auth_item

        =========================*/
        $this->execute
        ("
            CREATE TABLE IF NOT EXISTS `auth_item` 
            (
              `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
              `type` smallint(6) NOT NULL,
              `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
              `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
              `data` blob DEFAULT NULL,
              `created_at` int(11) DEFAULT NULL,
              `updated_at` int(11) DEFAULT NULL,
              PRIMARY KEY (`name`),
              KEY `rule_name` (`rule_name`),
              KEY `idx-auth_item-type` (`type`)
            ) 
            ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");

        /*=========================

            auth_item_child

        =========================*/
        $this->execute
        ("
            CREATE TABLE IF NOT EXISTS `auth_item_child` 
            (
              `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
              `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
              PRIMARY KEY (`parent`,`child`),
              KEY `child` (`child`)
            ) 
            ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");

        /*=========================

            auth_rule

        =========================*/
        $this->execute
        ("
            CREATE TABLE IF NOT EXISTS `auth_rule` 
            (
              `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
              `data` blob DEFAULT NULL,
              `created_at` int(11) DEFAULT NULL,
              `updated_at` int(11) DEFAULT NULL,
              PRIMARY KEY (`name`)
            ) 
            ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

        ");

        /*=========================

            capteur

        =========================*/
        $this->execute
        ("
            CREATE TABLE IF NOT EXISTS `capteur` 
            (
              `id` int(4) NOT NULL AUTO_INCREMENT,
              `nom` text NOT NULL,
              PRIMARY KEY (`id`),
              KEY `id` (`id`)
            ) 
            ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
        ");

        /*=========================

            grandeur

        =========================*/
        $this->execute
        ("
            CREATE TABLE IF NOT EXISTS `grandeur` 
            (
              `id` int(3) NOT NULL AUTO_INCREMENT,
              `nature` varchar(50) NOT NULL COMMENT 'nature en toutes lettres (Unité)',
              `formatCapteur` varchar(10) NOT NULL COMMENT 'signe - nombreDeCaracteresAvantVirgule, nombreDeCaracteresApresVirgule',
              `tablename` varchar(50) DEFAULT NULL COMMENT 'Nom interne de la table',
              `type` varchar(15) NOT NULL COMMENT 'Type des valeurs dans la table des mesures (Float, int, text, etc...)',
              PRIMARY KEY (`id`),
              UNIQUE KEY `Nature_Unique` (`nature`),
              KEY `id` (`id`) USING BTREE
            ) 
            ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
        ");

        /*=========================

            localisationmodule

        =========================*/
        $this->execute
        ("
            CREATE TABLE IF NOT EXISTS `localisationmodule` 
            (
              `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT,
              `description` text NOT NULL COMMENT 'Description',
              `coordX` int(3) NOT NULL DEFAULT 0 COMMENT 'Coordonnées x dans le système de repérage',
              `coordY` int(3) NOT NULL DEFAULT 0 COMMENT 'Coordonnées y dans le système de repérage',
              `coordZ` int(3) NOT NULL DEFAULT 0 COMMENT 'Coordonnées z dans le système de repérage',
              PRIMARY KEY (`id`),
              KEY `id` (`id`)
            ) 
            ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
        ");

        /*=========================

            log

        =========================*/
        $this->execute
        ("
            CREATE TABLE IF NOT EXISTS `log` 
            (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `level` varchar(15) NOT NULL,
              `category` varchar(20) NOT NULL,
              `log_time` int(11) NOT NULL,
              `prefix` varchar(50) NOT NULL,
              `message` text NOT NULL,
              PRIMARY KEY (`id`)
            ) 
            ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Logs de l''application Yii';
        ");

        /*=========================

            module

        =========================*/
        $this->execute
        ("
            CREATE TABLE IF NOT EXISTS `module` 
            (
              `identifiantReseau` varchar(50) NOT NULL,
              `nom` varchar(50) NOT NULL COMMENT 'Le nom du module',
              `description` text NOT NULL,
              `idLocalisationModule` int(3) UNSIGNED NOT NULL,
              `actif` tinyint(1) NOT NULL COMMENT '1 = Actif, 0 = Innactif',
              PRIMARY KEY (`identifiantReseau`),
              UNIQUE KEY `identifiantReseau` (`identifiantReseau`),
              KEY `module_localisation` (`idLocalisationModule`)
            ) 
            ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        /*=========================

            rel_capteurgrandeur

        =========================*/
        $this->execute
        ("
            CREATE TABLE IF NOT EXISTS `rel_capteurgrandeur` 
            (
              `idCapteur` int(4) NOT NULL,
              `idGrandeur` int(3) NOT NULL,
              UNIQUE KEY `cle` (`idCapteur`,`idGrandeur`),
              KEY `contgrandeur` (`idGrandeur`)
            ) 
            ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        /*=========================

            rel_modulecapteur

        =========================*/
        $this->execute
        ("
            CREATE TABLE IF NOT EXISTS `rel_modulecapteur` 
            (
              `idModule` varchar(50) NOT NULL,
              `idCapteur` int(4) NOT NULL,
              `nomcapteur` text NOT NULL,
              `ordre` int(2) NOT NULL DEFAULT 0,
              `x` int(3) NOT NULL DEFAULT 0 COMMENT 'Coordonnées X',
              `y` int(3) NOT NULL DEFAULT 0 COMMENT 'Coordonnées Y',
              `z` int(3) NOT NULL DEFAULT 0 COMMENT 'Coordonnées Z',
              PRIMARY KEY (`idModule`,`idCapteur`,`nomcapteur`(50),`ordre`) USING BTREE,
              KEY `contcapteur` (`idCapteur`)
            ) 
            ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        /*=========================

            utilisateur

        =========================*/
        $this->execute
        ("
            CREATE TABLE IF NOT EXISTS `utilisateur` 
            (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `username` varchar(20) NOT NULL,
              `email` varchar(50) NOT NULL,
              `password` varchar(255) NOT NULL,
              `authKey` varchar(50) DEFAULT NULL,
              `accessToken` text DEFAULT NULL,
              `lastAccess` date NOT NULL DEFAULT current_timestamp(),
              PRIMARY KEY (`id`),
              UNIQUE KEY `id` (`id`),
              UNIQUE KEY `username` (`username`)
            ) 
            ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
        ");

        /*=========================
        This part produce primary 
        and foreign key for each
        table in the right order
        =========================*/
        $this->execute
        ("
            ALTER TABLE `auth_assignment` ADD 
            CONSTRAINT `auth_assignment_ibfk_1` 
            FOREIGN KEY (`item_name`) 
            REFERENCES `auth_item` (`name`) 
            ON DELETE CASCADE ON UPDATE CASCADE;
        ");

        $this->execute
        ("
            ALTER TABLE `auth_item` ADD 
            CONSTRAINT `auth_item_ibfk_1` 
            FOREIGN KEY (`rule_name`) 
            REFERENCES `auth_rule` (`name`) 
            ON DELETE SET NULL ON UPDATE CASCADE;
        ");

        $this->execute
        ("
            ALTER TABLE `auth_item_child` ADD 
            CONSTRAINT `auth_item_child_ibfk_1` 
            FOREIGN KEY (`parent`) 
            REFERENCES `auth_item` (`name`) 
            ON DELETE CASCADE ON UPDATE CASCADE, ADD 
            CONSTRAINT `auth_item_child_ibfk_2` 
            FOREIGN KEY (`child`) 
            REFERENCES `auth_item` (`name`) 
            ON DELETE CASCADE ON UPDATE CASCADE;
        ");

        $this->execute
        ("
            ALTER TABLE `module` ADD 
            CONSTRAINT `module_localisation` 
            FOREIGN KEY (`idLocalisationModule`) 
            REFERENCES `localisationmodule` (`id`) 
            ON DELETE NO ACTION 
            ON UPDATE NO ACTION;
        ");

        $this->execute
        ("
            ALTER TABLE `rel_capteurgrandeur` ADD 
            CONSTRAINT `contgrandeur` 
            FOREIGN KEY (`idGrandeur`) 
            REFERENCES `grandeur` (`id`) 
            ON DELETE CASCADE ON UPDATE CASCADE;
        ");

        $this->execute
        ("
            ALTER TABLE `rel_modulecapteur` ADD 
            CONSTRAINT `contcapteur` 
            FOREIGN KEY (`idCapteur`) 
            REFERENCES `capteur` (`id`) 
            ON DELETE CASCADE ON UPDATE CASCADE, ADD 
            CONSTRAINT `contmodule` 
            FOREIGN KEY (`idModule`) 
            REFERENCES `module` (`identifiantReseau`) 
            ON DELETE CASCADE ON UPDATE CASCADE;
        ");

        /*=========================
        this part insert required
        data for the web application
        in the right order
        =========================*/
        $this->execute
        ("
            INSERT INTO `utilisateur` (`id`, `username`, `email`, `password`, `authKey`, `accessToken`, `lastAccess`) VALUES (1, 'admin', 'admin@admin', '\$2y\$13\$E5nJTOVmgqXBxXs/PjlOluX0OrtnSzbX4XInE0KLGVLG.ivXC2bri', NULL, NULL, '2021-04-17');
        ");

        $this->execute
        ("
            INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
                ('Utilisateur', 1, 'Simple utilisateur', NULL, NULL, 1589553357, 1589553357),
                ('Admin', 1, 'Administrateur du Back Office', NULL, NULL, 1589553357, 1589553357),
                ('createCapteur', 2, 'Créer un Capteur', NULL, NULL, 1589553357, 1589553357),
                ('createGrandeur', 2, 'Créer une Grandeur', NULL, NULL, 1589553357, 1589553357),
                ('createLocalisation', 2, 'Créer une Localisation', NULL, NULL, 1589553357, 1589553357),
                ('createModule', 2, 'Créer un Module', NULL, NULL, 1589553357, 1589553357),
                ('createUser', 2, 'Créer un Utilisateur', NULL, NULL, 1589553357, 1589553357);
        ");

        $this->execute
        ("
            INSERT INTO `auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES 
                ('Admin', '1', NULL);
        ");

        $this->execute
        ("
            INSERT INTO `auth_item_child` (`parent`, `child`) VALUES
                ('Admin', 'createCapteur'),
                ('Admin', 'createGrandeur'),
                ('Admin', 'createLocalisation'),
                ('Admin', 'createModule'),
                ('Admin', 'createUser'),
                ('Utilisateur', 'createCapteur'),
                ('Utilisateur', 'createGrandeur'),
                ('Utilisateur', 'createLocalisation'),
                ('Utilisateur', 'createModule');
        ");  
    }

    public function safeDown()
    {
        $this->execute("DROP TABLE IF EXISTS `auth_assignment`");
        $this->execute("DROP TABLE IF EXISTS `auth_item_child`");
        $this->execute("DROP TABLE IF EXISTS `auth_item`");
        $this->execute("DROP TABLE IF EXISTS `auth_rule`");
        $this->execute("DROP TABLE IF EXISTS `utilisateur`");

        $this->execute("DROP TABLE IF EXISTS `rel_capteurgrandeur`");
        $this->execute("DROP TABLE IF EXISTS `rel_modulecapteur`");

        $this->execute("DROP TABLE IF EXISTS `module`");
        $this->execute("DROP TABLE IF EXISTS `localisationmodule`");
        $this->execute("DROP TABLE IF EXISTS `capteur`");
        $this->execute("DROP TABLE IF EXISTS `grandeur`");

        $this->execute("DROP TABLE IF EXISTS `log`");
        //$this->execute("DROP TABLE IF EXISTS `migration`");
    }
}
