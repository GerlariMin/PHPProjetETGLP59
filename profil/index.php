<?php
    session_start();

    include_once("../ressources/php/fichiers_communs.php");
    include_once("requetes.php");

    $erreur = '';
    global $render;

    if(isset($_GET['erreur'])) {
        $erreur = $_GET['erreur'];
    }

    $requetes = new RequetesProfil($config, $logs);

    $traitement = new TraitementProfil($render);
    $traitement->traitementRendu($requetes, $erreur);