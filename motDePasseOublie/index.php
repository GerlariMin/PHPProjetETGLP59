<?php
    session_start();

    include("../ressources/php/fichiers_communs.php");

    $erreur = '';
    global $render;

    if(isset($_GET['erreur'])) {
        $erreur = $_GET['erreur'];
    }

    $traitement = new TraitementMotDePasseOublie($render);
    $traitement->traitementRendu($erreur);