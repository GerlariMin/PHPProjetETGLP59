<?php
    session_start();
    // Cette partie n'est accessible que si l'utilisateur est connecté
    if (isset($_SESSION['login'])) {
        include_once("../ressources/php/fichiers_communs.php");
        include_once("requetes.php");
        $requetes = new RequetesProfil($config, $logs);
        $traitement = new TraitementProfil($render);
        $traitement->traitementRendu($requetes, $erreur, $succes);
    } else {
        header('Location: ../connexion/?erreur=5');
        exit();
    }
    