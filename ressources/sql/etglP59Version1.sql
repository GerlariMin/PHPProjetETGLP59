-- MySQL Script generated by MySQL Workbench
-- Mon Jun 13 12:19:45 2022
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema projetetglp59
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `projetetglp59` ;

-- -----------------------------------------------------
-- Schema projetetglp59
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `projetetglp59` DEFAULT CHARACTER SET utf8 ;
USE `projetetglp59` ;

-- -----------------------------------------------------
-- Table `projetetglp59`.`abonnements`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `projetetglp59`.`abonnements` ;

CREATE TABLE IF NOT EXISTS `projetetglp59`.`abonnements` (
  `identifiantAbonnement` INT NOT NULL AUTO_INCREMENT,
  `typeAbonnement` ENUM('gratuit', 'payant') NOT NULL,
  `limiteDocuments` INT NOT NULL,
  `limiteStockage` INT NOT NULL,
  `disponible` TINYINT NOT NULL,
  `promotion` TINYINT NOT NULL,
  `pourcentagePromotion` INT NULL,
  `prixAbonnement` FLOAT NULL,
  PRIMARY KEY (`identifiantAbonnement`),
  UNIQUE INDEX `idabonnements_UNIQUE` (`identifiantAbonnement` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `projetetglp59`.`utilisateurs`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `projetetglp59`.`utilisateurs` ;

CREATE TABLE IF NOT EXISTS `projetetglp59`.`utilisateurs` (
  `identifiantUtilisateur` VARCHAR(10) NOT NULL,
  `nomUtilisateur` VARCHAR(128) NOT NULL,
  `prenomUtilisateur` VARCHAR(128) NOT NULL,
  `loginUtilisateur` VARCHAR(256) NOT NULL,
  `emailUtilisateur` VARCHAR(128) NOT NULL,
  `abonnementUtilisateur` INT NOT NULL,
  `motDePasseChiffreUtilisateur` VARCHAR(45) NOT NULL,
  `motDePasseOublie` TINYINT NOT NULL DEFAULT 0,
  `motDePasseOublieToken` VARCHAR(30) NULL,
  `expirationToken` DATETIME NULL,
  `motDePasseModifie`INT NULL,
  PRIMARY KEY (`identifiantUtilisateur`),
  UNIQUE INDEX `identifiant_UNIQUE` (`identifiantUtilisateur` ASC) VISIBLE,
  INDEX `liaisonUtilisateurAbonnement_idx` (`abonnementUtilisateur` ASC) VISIBLE,
  CONSTRAINT `liaisonUtilisateurAbonnement`
    FOREIGN KEY (`abonnementUtilisateur`)
    REFERENCES `projetetglp59`.`abonnements` (`identifiantAbonnement`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `projetetglp59`.`documents`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `projetetglp59`.`documents` ;

CREATE TABLE IF NOT EXISTS `projetetglp59`.`documents` (
  `identifiantDocument` INT NOT NULL AUTO_INCREMENT,
  `nomDocument` VARCHAR(256) NOT NULL,
  `utilisateurLie` VARCHAR(10) NOT NULL,
  PRIMARY KEY (`identifiantDocument`),
  UNIQUE INDEX `iddocuments_UNIQUE` (`identifiantDocument` ASC) VISIBLE,
  INDEX `liaisonDocumentUtilisateur_idx` (`utilisateurLie` ASC) VISIBLE,
  CONSTRAINT `liaisonDocumentUtilisateur`
    FOREIGN KEY (`utilisateurLie`)
    REFERENCES `projetetglp59`.`utilisateurs` (`identifiantUtilisateur`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `projetetglp59`.`Facturations`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `projetetglp59`.`Facturations` ;

CREATE TABLE IF NOT EXISTS `projetetglp59`.`Facturations` (
  `identifiantFacturation` INT NOT NULL AUTO_INCREMENT,
  `montant` FLOAT NOT NULL,
  `datePaiement` DATETIME NOT NULL,
  `dateDeFinAchat` DATETIME NOT NULL,
  `utilisateurLie` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`identifiantFacturation`),
  UNIQUE INDEX `idFacturations_UNIQUE` (`identifiantFacturation` ASC) VISIBLE,
  INDEX `liaisonFacturationUtilisateur_idx` (`utilisateurLie` ASC) VISIBLE,
  CONSTRAINT `liaisonFacturationUtilisateur`
    FOREIGN KEY (`utilisateurLie`)
    REFERENCES `projetetglp59`.`utilisateurs` (`identifiantUtilisateur`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `projetetglp59`.`motDePasse`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `projetetglp59`.`motdepasse`;

CREATE TABLE IF NOT EXISTS `projetetglp59`.`motdepasse` (
  `identifiantMotDePasse` int(11) NOT NULL AUTO_INCREMENT,
  `motDePasseChiffre` varchar(128) NULL,
  `token` varchar(20) NULL,
  `utilisateurLie` varchar(10) NOT NULL,
  PRIMARY KEY (`identifiantMotDePasse`),
  KEY `utilisateur mdp` (`utilisateurLie`)
  CONSTRAINT `liaisonMotDePasseUtilisateur`
    FOREIGN KEY (`utilisateurLie`)
    REFERENCES `projetetglp59`.`utilisateurs` (`identifiantUtilisateur`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE=InnoDB;

-- -----------------------------------------------------
-- Table `projetetglp59`.`emails`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `projetetglp59`.`emails`;

CREATE TABLE `projetetglp59`.`emails` (
  `identifiantEmail` INT(11) NOT NULL AUTO_INCREMENT ,
  `email` VARCHAR(128) NULL DEFAULT NULL ,
  `token` VARCHAR(128) NULL DEFAULT NULL ,
  `utilisateurLie` VARCHAR(10) NOT NULL ,
  PRIMARY KEY (`identifiantEmail`),
  CONSTRAINT `liaisonEmailsUtilisateur`
    FOREIGN KEY (`utilisateurLie`)
    REFERENCES `projetetglp59`.`utilisateurs` (`identifiantUtilisateur`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE=InnoDB;

-- -----------------------------------------------------
-- Table `projetetglp59`.`logins`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `projetetglp59`.`logins`;

CREATE TABLE `projetetglp59`.`logins` (
  `identifiantLogin` INT(11) NOT NULL AUTO_INCREMENT ,
  `login` VARCHAR(128) NULL DEFAULT NULL ,
  `token` VARCHAR(128) NULL DEFAULT NULL ,
  `utilisateurLie` VARCHAR(10) NOT NULL ,
  PRIMARY KEY (`identifiantLogin`),
  CONSTRAINT `liaisonloginsUtilisateur`
    FOREIGN KEY (`utilisateurLie`)
    REFERENCES `projetetglp59`.`utilisateurs` (`identifiantUtilisateur`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE=InnoDB;

-- -----------------------------------------------------
-- Table `projetetglp59`.`modifications`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `projetetglp59`.`modifications`;

CREATE TABLE `projetetglp59`.`modifications` (
  `identifiantLogin` INT(11) NOT NULL AUTO_INCREMENT ,
  `typeModification` VARCHAR(128) NULL DEFAULT NULL ,
  `modification` VARCHAR(128) NULL DEFAULT NULL ,
  `token` VARCHAR(128) NULL DEFAULT NULL ,
  `utilisateurLie` VARCHAR(10) NOT NULL ,
  PRIMARY KEY (`identifiantLogin`),
  CONSTRAINT `liaisonloginsUtilisateur`
    FOREIGN KEY (`utilisateurLie`)
    REFERENCES `projetetglp59`.`utilisateurs` (`identifiantUtilisateur`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE=InnoDB;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
