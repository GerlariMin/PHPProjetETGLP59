<?php
    session_start();

    include("../ressources/php/fichiers_communs.php");
    include("texte.php");

    $erreur = '';
    global $render;

    if(isset($_GET['erreur'])) {
        $erreur = $_GET['erreur'];
    }

    $traitement = new TraitementReinitialisationMotDePasse($render);
    $traitement->traitementRendu($erreur);