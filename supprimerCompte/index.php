<?php
    require_once('mail.php');
    require_once('requetes.php');
    session_start();
    global $config;
    if(isset($_SESSION['login'])) { // Décommenter si le module est accessible sans que l'utilisateur soit connecté
        // Chargement des ressources utiles
        include_once('../ressources/php/fichiers_communs.php');
        include_once('../ressources/config/config.inc.php');
        $logs = new Logs($config);
        $mails = new MailSupprimerCompte($config, $logs);
        $requetes = new RequetesSupprimerCompte($config, $logs);
        $donnesUtilisateur = $requetes->donneesUtilisateur($_SESSION['login']);
        $returnLink = '<a href="../tableauDeBord"><p>Retour au tableau de bord</p></a>';
        // ENVOI DE MAIL, A DECOMMENTER pour la mise en prod !
        //$mails->envoyerMailSupprimerCompte($donnesUtilisateur['emailUtilisateur']);
        if($requetes->setDeadlineCompte($_SESSION['login'])){
            $texte = "Demande de suppression de compte enregistrée, un mail a été envoyé à l'adresse suivante :\n";
            RequetesSupprimerCompte::templateMessageSucces($donnesUtilisateur['emailUtilisateur'], $texte, $returnLink);
        }
    }else {
        header('Location: ../connexion/?erreur=5');
        exit();
    }
