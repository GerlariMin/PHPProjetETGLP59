<?php
    require_once('requetes.php');
    require_once('mail.php');
    session_start();
    global $config;
    if(isset($_SESSION['login'])) {
        include_once('../ressources/php/fichiers_communs.php');
        include_once('../ressources/config/config.inc.php');
        $logs = new Logs($config);
        $mail = new MailResiliationAbonnement($config, $logs);
        // Chargement des ressources utiles
        include_once('../ressources/php/fichiers_communs.php');
        include_once('../ressources/config/config.inc.php');
        $requetes = new RequetesResilierAbonnement($config, $logs);
        $donnesUtilisateur = $requetes->donneesUtilisateur($_SESSION['login']);
        if($requetes->demandeResiliationAbonnement($donnesUtilisateur['identifiantUtilisateur'])){
            $mail->envoyerMailResiliationAbonnement($donnesUtilisateur['emailUtilisateur']);
            $returnLink = '<a href="../tableauDeBord"><p>Retour au tableau de bord</p></a>';
            $texte = "Demande de Résiliation d'abonnement enregistrée, un mail a été envoyé à l'adresse suivante :\n";
            RequetesResilierAbonnement::templateMessageSucces($donnesUtilisateur['emailUtilisateur'], $texte, $returnLink);
        }
    }else {
        header('Location: ../connexion/?erreur=5');
        exit();
    }