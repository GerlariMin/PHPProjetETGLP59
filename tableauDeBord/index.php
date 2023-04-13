<?php
    session_start();
    // Cette partie n'est accessible que si l'utilisateur est connecté
    if (isset($_SESSION['login'])) {
        include("../ressources/php/fichiers_communs.php");
        require_once('requetes.php');
        $requetes = new RequetesTableauDeBord($config, $logs);
        $traitement = new TraitementTableauDeBord($render);
        $traitement->traitementRendu($requetes, $erreur, $succes);
    } else {
        header('Location: ../connexion/?erreur=5');
        exit();
    }