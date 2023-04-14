<?php
    session_start();
    // Chargement des ressources utiles
    include("../ressources/php/fichiers_communs.php");
    // Initialisation de la classe de traitement dédiée au module d'inscription
    $traitement = new TraitementInscription($render);
    // Appel de la méthode générant l'affichage du module d'inscription
    $traitement->traitementRendu($erreur);