<?php
    session_start();

    include("../ressources/php/fichiers_communs.php");
    include("texte.php");

    global $render;

    $traitement = new TraitementConnexion($render);
    $traitement->traitementRendu();