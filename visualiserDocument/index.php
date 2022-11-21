<?php
    session_start();
    // On récupère le fichier de configuration
    include_once('../ressources/config/config.inc.php');
    // On récupère la classe de Logs
    include_once($config['variables']['chemin'] . 'ressources/php/Logs.php');
    // Initialisation de la classe Logs
    $logs = new Logs($config);
    // On regarde si il y a les clés GET essentielles pour l'affichage du document
    if (isset($_GET['document'], $_GET['type'])) {
        $logs->messageLog('L\'utilisateur "' . $_SESSION['identifiant'] . '" veut visualiser un document et l\'ensemble des clés essentielles sont présentes.', $logs->typeInfo);
        // On récupère le répertoire et sous répertoire dans lesquels les fichiers de l'utilisateur connecté sont stockés
        $repertoires = str_split($_SESSION['identifiant'], 5);
        // On récupère le chemin complet de l'endroit où sont stockés les fichiers de l'utilisateur connecté
        $repertoireUtilisateur = $config['variables']['repertoires']['utilisateurs'] . $repertoires[0] . '/' . $repertoires[1] . '/';
        // On récupère les valeurs utiles du GET
        $extensionDocument = (int) $_GET['type'];
        $nomDocument = $_GET['document'];
        // Tableau utile pour le Content-Type
        $tableauExtensionHeader =
            [
                0 => 'application/pdf',
                1 => 'image/jpeg',
                2 => 'image/png',
            ];
        // Si on a un document résultat, on adapte le chemin source du document
        if (isset($_GET['resultat'])) {
            $cheminDocument = $repertoireUtilisateur . 'resultats/' . $nomDocument;
            $logs->messageLog('L\'utilisateur "' . $_SESSION['identifiant'] . '" veut visualiser un document résultat OCR.', $logs->typeInfo);
        } else {
            $cheminDocument = $repertoireUtilisateur . $nomDocument;
            $logs->messageLog('L\'utilisateur "' . $_SESSION['identifiant'] . '" veut visualiser un document qu\'il a déposé.', $logs->typeInfo);
        }
        $logs->messageLog('Utilisateur "' . $_SESSION['identifiant'] . '" - URL "' . $cheminDocument . '".', $logs->typeInfo);
        $logs->messageLog('Utilisateur "' . $_SESSION['identifiant'] . '" - Préparation de l\'affichage du document.', $logs->typeInfo);
        $logs->messageLog('CONTENT TYPE: ' . $tableauExtensionHeader[$extensionDocument], $logs->typeDebug);
        // Préparation du header pour l'affichage du document
        header('cache-control: no-cache, must-revalidate, max-age=0');
        header('Content-Type: ' . $tableauExtensionHeader[$extensionDocument]);
        // Affichage du document
        readfile($cheminDocument);
    } else {
        $logs->messageLog('L\'utilisateur "' . $_SESSION['identifiant'] . '" veut visualiser un document mais au moins l\'une des clés essentielles n\'est pas présente.', $logs->typeWarning);
        header('Location: ../tableauDeBord/?erreur=nf');
        exit();
    }