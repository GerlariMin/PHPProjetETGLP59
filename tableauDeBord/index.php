<?php
    session_start();
    // Cette partie n'est accessible que si l'utilisateur est connecté
    if(isset($_SESSION['login'])) {
        include("../ressources/php/fichiers_communs.php");
        $traitement = new TraitementTableauDeBord($render);
        $traitement->traitementRendu();
    } else {
        header('Location: ../connexion/?erreur=5');
        exit();
    }