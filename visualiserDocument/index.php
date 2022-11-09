<?php
    session_start();
    // On récupère le fichier de configuration
    include_once('../ressources/config/config.inc.php');
    if (isset($_GET['document'], $_GET['TYPE'])) {
        // On récupère le répertoire et sous répertoire dans lesquels les fichiers de l'utilisateur connecté sont stockés
        $repertoires = str_split($_SESSION['identifiant'], 5);
        // On récupère le chemin complet de l'endroit où sont stockés les fichiers de l'utilisateur connecté
        $repertoireUtilisateur = $config['variables']['repertoires']['utilisateurs'] . $repertoires[0] . '/' . $repertoires[1] . '/';

        $nomDocument = $_GET['document'];
        $extensionDocument = (int) $_GET['type'];

        if (isset($_GET['resultat'])) {
            $cheminDocument = $repertoireUtilisateur . 'resultats/' . $nomDocument;
        } else {
            $cheminDocument = $repertoireUtilisateur . $nomDocument;
        }

        $tableauExtensionHeader =
            [
                0 => 'application/pdf',
                1 => 'image/jpeg',
                2 => 'image/png',
            ];

        header('cache-control: no-cache, must-revalidate, max-age=0');
        header('Content-Type: ' . $tableauExtensionHeader[$extensionDocument]);
        readfile($cheminDocument);
    } else {
        header('Location: ../tableauDeBord/?erreur=nf');
        exit();
    }