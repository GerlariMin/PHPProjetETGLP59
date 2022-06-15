<?php
    session_start();
    // Chargement des ressources utiles
    include("../ressources/php/fichiers_communs.php");
    // variable dédiée au code d'erreur
    $erreur = '';
    // Si il existe une clé erreur dans le lien
    if(isset($_GET['erreur'])) {
        // On affecte la valeur associée à la clé à la variable dédiée
        $erreur = $_GET['erreur'];
    }
    // Initialisation de la classe de traitement dédiée au module de connexion
    $traitement = new TraitementConnexion($logs, $render);
    // Appel de la méthode générant l'affichage du module de connexion
    $traitement->traitementRendu($erreur);