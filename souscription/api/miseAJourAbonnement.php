<?php
    session_start();

    require_once('../../ressources/config/config.inc.php');
    require_once('../../ressources/php/Logs.php');
    require_once('../../ressources/php/Model.php');
    require_once('../requetes.php');

    global $config;
    $logs = new Logs($config);
    $requetes = new RequetesSouscription($config, $logs);
    $tableauRetour = array('actualisation' => false);
    if ($_SESSION['identifiant']) {
        // Actualisation de l'abonnement de l'utilisateur
        if ($_POST['abonnement'] && $requetes->miseAJourAbonnementutilisateur($_SESSION['identifiant'], $_POST['abonnement'])) {
            $tableauRetour['actualisation'] = true;
            $logs->messageLog('Actualisation faite pour l\'utilisateur "' . $_SESSION['identifiant'] . '", qui a souscri à l\'abonnement "' . $_POST['abonnement'] . '".', $logs->typeInfo);
        }
        // Date d'aujourd'hui
        $date = date('Y-m-d h:i:s');
        // Un mois à partir d'aujourd'hui
        $dateFin = date('Y-m-d h:i:s', strtotime('+1 month'));
        // Génération de la facturation en base
        if ($_POST['prix'] && $requetes->ajouterFacturation($_POST['prix'], $date, $dateFin, $_SESSION['identifiant'])) {
            $tableauRetour['actualisation'] = true;
            $logs->messageLog('Facturation enregistrée pour l\'utilisateur "' . $_SESSION['identifiant'] . '", qui a payé l\'abonnement "' . $_POST['prix'] . '" euros.', $logs->typeInfo);
        }
    }
    try {
        echo json_encode($tableauRetour, JSON_THROW_ON_ERROR);
    } catch (JsonException $e) {
        $logs->messageLog('Problème lors du retour du JSON. Erreur: ' . $e->getMessage(), $logs->typeError);
    }