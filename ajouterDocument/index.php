<?php
    session_start();
    // Cette partie n'est accessible que si l'utilisateur est connecté
    if(isset($_SESSION['login'])) {
        // Chargement des ressources utiles
        include('../ressources/php/fichiers_communs.php');
        // Initialisation de la classe de traitement dédiée au module de connexion
        $traitement = new TraitementAjouterDocument($logs, $render);
        // Appel de la méthode générant l'affichage du module de connexion
        $traitement->traitementRendu($erreur, $succes);
    } else {
        header('Location: ../connexion/?erreur=5');
        exit();
    }