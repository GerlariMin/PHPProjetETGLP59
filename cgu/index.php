<?php
    session_start();
    // On récupère le fichier de configuration
    include_once('../ressources/config/config.inc.php');
    // On récupère la classe de Logs
    include_once('../ressources/php/Logs.php');
    // Initialisation de la classe Logs
    $logs = new Logs($config);
    // Emplacement du PDF des CGU
    $fichierCGU = './pdf/CGU.pdf';
    // Si le fichier existe bien
    if (file_exists($fichierCGU)) {
        $logs->messageLog('CGU pretes à afficher.', $logs->typeWarning);
        // Préparation du header pour l'affichage du document
        header('cache-control: no-cache, must-revalidate, max-age=0');
        header('Content-Type: application/pdf');
        // Affichage du document
        readfile($fichierCGU);
    } else {
        $logs->messageLog('Pas de CGU a afficher.', $logs->typeWarning);
        if (isset($_SERVER['HTTP_REFERER'])) {
            $cassureLienPrecedent = explode('/', $_SERVER['HTTP_REFERER']);
            $indiceCassureAUtiliser = count($cassureLienPrecedent) - 2;
            $moduleRedirection = $cassureLienPrecedent[$indiceCassureAUtiliser];
            $redirection = '../' . $moduleRedirection . '/?erreur=cgu';
            $logs->messageLog('Redirection vers le module précédent: ' . $moduleRedirection . '.');
        } else {
            $logs->messageLog('Pas de trace du module précédent, redirection vers l\'index général.');
            $redirection = '../?erreur=cgu';
        }
        header('Location: ' . $redirection);
        exit();
    }