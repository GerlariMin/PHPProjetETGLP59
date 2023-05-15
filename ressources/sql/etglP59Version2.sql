-- MySQL dump 10.13  Distrib 8.0.33, for Win64 (x86_64)
--
-- Host: 192.168.30.34    Database: projetetglp59
-- ------------------------------------------------------
-- Server version	8.0.32

--
-- Table structure for table `Facturations`
--

DROP TABLE IF EXISTS `Facturations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Facturations` (
  `identifiantFacturation` int NOT NULL AUTO_INCREMENT,
  `montant` float NOT NULL,
  `datePaiement` datetime NOT NULL,
  `dateDeFinAchat` datetime NOT NULL,
  `utilisateurLie` varchar(45) NOT NULL,
  PRIMARY KEY (`identifiantFacturation`),
  UNIQUE KEY `idFacturations_UNIQUE` (`identifiantFacturation`),
  KEY `liaisonFacturationUtilisateur_idx` (`utilisateurLie`),
  CONSTRAINT `liaisonFacturationUtilisateur` FOREIGN KEY (`utilisateurLie`) REFERENCES `utilisateurs` (`identifiantUtilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `abonnements`
--

DROP TABLE IF EXISTS `abonnements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `abonnements` (
  `identifiantAbonnement` int NOT NULL AUTO_INCREMENT,
  `typeAbonnement` enum('gratuit','payant') NOT NULL,
  `limiteDocuments` int NOT NULL,
  `limiteStockage` float NOT NULL,
  `disponible` tinyint NOT NULL,
  `promotion` tinyint NOT NULL,
  `pourcentagePromotion` int DEFAULT NULL,
  `prixAbonnement` float DEFAULT NULL,
  `limiteTraitements` int NOT NULL,
  PRIMARY KEY (`identifiantAbonnement`),
  UNIQUE KEY `idabonnements_UNIQUE` (`identifiantAbonnement`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `carteIdentite`
--

DROP TABLE IF EXISTS `carteIdentite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carteIdentite` (
  `idCarteIdentite` int NOT NULL AUTO_INCREMENT,
  `utilisateurLie` varchar(10) NOT NULL,
  `nom` text NOT NULL,
  `prenom` text NOT NULL,
  `sexe` tinyint(1) NOT NULL,
  `naissanceDate` date NOT NULL,
  `naissanceLieu` text NOT NULL,
  `taille` int NOT NULL,
  `adresse` text NOT NULL,
  `dateDelivrance` date NOT NULL,
  `dateValabilite` date NOT NULL,
  `entiteDelivrante` text NOT NULL,
  PRIMARY KEY (`idCarteIdentite`),
  KEY `liaisonloginsUtilisateur` (`utilisateurLie`),
  CONSTRAINT `liaisonloginsUtilisateur` FOREIGN KEY (`utilisateurLie`) REFERENCES `utilisateurs` (`identifiantUtilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documents` (
  `identifiantDocument` int NOT NULL AUTO_INCREMENT,
  `nomDocument` varchar(256) NOT NULL,
  `utilisateurLie` varchar(10) NOT NULL,
  PRIMARY KEY (`identifiantDocument`),
  UNIQUE KEY `iddocuments_UNIQUE` (`identifiantDocument`),
  KEY `liaisonDocumentUtilisateur_idx` (`utilisateurLie`),
  CONSTRAINT `liaisonDocumentUtilisateur` FOREIGN KEY (`utilisateurLie`) REFERENCES `utilisateurs` (`identifiantUtilisateur`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `modifications`
--

DROP TABLE IF EXISTS `modifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `modifications` (
  `identifiantLogin` int NOT NULL AUTO_INCREMENT,
  `typeModification` varchar(128) DEFAULT NULL,
  `modification` varchar(128) DEFAULT NULL,
  `token` varchar(128) DEFAULT NULL,
  `utilisateurLie` varchar(10) NOT NULL,
  PRIMARY KEY (`identifiantLogin`),
  KEY `liaisonModificationsUtilisateur` (`utilisateurLie`),
  CONSTRAINT `liaisonModificationsUtilisateur` FOREIGN KEY (`utilisateurLie`) REFERENCES `utilisateurs` (`identifiantUtilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `motDePasse`
--

DROP TABLE IF EXISTS `motDePasse`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `motDePasse` (
  `identifiantMotDePasse` int NOT NULL AUTO_INCREMENT,
  `motDePasseChiffre` varchar(128) DEFAULT NULL,
  `token` varchar(128) DEFAULT NULL,
  `utilisateurLie` varchar(10) NOT NULL,
  `motDePasseModifie` int DEFAULT NULL,
  PRIMARY KEY (`identifiantMotDePasse`),
  UNIQUE KEY `identifiant_UNIQUE` (`identifiantMotDePasse`),
  KEY `liaisonMotDePasseUtilisateur` (`utilisateurLie`),
  CONSTRAINT `liaisonMotDePasseUtilisateur` FOREIGN KEY (`utilisateurLie`) REFERENCES `utilisateurs` (`identifiantUtilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `traitements`
--

DROP TABLE IF EXISTS `traitements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `traitements` (
  `identifiantTraitement` int NOT NULL AUTO_INCREMENT,
  `utilisateurLie` varchar(10) NOT NULL,
  `date` date NOT NULL,
  `traitementAbouti` tinyint(1) NOT NULL,
  PRIMARY KEY (`identifiantTraitement`),
  UNIQUE KEY `identifiantTraitement_UNIQUE` (`identifiantTraitement`),
  KEY `liaisonTratementUtilisateur_idx_idx` (`utilisateurLie`),
  CONSTRAINT `liaisonTratementUtilisateur_idx` FOREIGN KEY (`utilisateurLie`) REFERENCES `utilisateurs` (`identifiantUtilisateur`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `utilisateurs` (
  `identifiantUtilisateur` varchar(10) NOT NULL,
  `nomUtilisateur` varchar(128) NOT NULL,
  `prenomUtilisateur` varchar(128) NOT NULL,
  `loginUtilisateur` varchar(256) NOT NULL,
  `emailUtilisateur` varchar(128) NOT NULL,
  `abonnementUtilisateur` int NOT NULL,
  `motDePasseChiffreUtilisateur` varchar(60) NOT NULL,
  `motDePasseOublie` int DEFAULT NULL,
  `motDePasseOublieToken` varchar(30) DEFAULT NULL,
  `expirationToken` datetime DEFAULT NULL,
  `motDePasseModifie` int DEFAULT NULL,
  `loginModifie` int DEFAULT NULL,
  `emailModifie` int DEFAULT NULL,
  `derniereConnexion` datetime(6) DEFAULT NULL,
  `deadlineCompte` datetime(6) DEFAULT NULL,
  `demandeResiliationAbonnement` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`identifiantUtilisateur`),
  UNIQUE KEY `identifiant_UNIQUE` (`identifiantUtilisateur`),
  KEY `liaisonUtilisateurAbonnement_idx` (`abonnementUtilisateur`),
  CONSTRAINT `liaisonUtilisateurAbonnement` FOREIGN KEY (`abonnementUtilisateur`) REFERENCES `abonnements` (`identifiantAbonnement`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `utilisateurs_BEFORE_UPDATE` BEFORE UPDATE ON `utilisateurs` FOR EACH ROW BEGIN
IF NEW.motDePasseOublie = TRUE
AND NEW.motDePasseOublieToken IS NOT NULL
THEN
SET NEW.expirationToken = CURRENT_TIMESTAMP()+300;
END IF;
END */;;
DELIMITER ;

-- Dump completed on 2023-05-15  9:43:15
