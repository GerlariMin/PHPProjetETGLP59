<?php
    session_start();
    // Chargement des ressources utiles
    include('../ressources/php/fichiers_communs.php');
    // Initialisation de la classe de traitement dédiée au module de connexion
    $traitement = new TraitementConnexion($logs, $render);
    // Appel de la méthode générant l'affichage du module de connexion
    $traitement->traitementRendu($erreur);