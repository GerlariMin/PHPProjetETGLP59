<?php

session_start();

session_regenerate_id();
include_once('../ressources/php/Logs.php');
include_once("../ressources/config/config.inc.php");
global $config;
include_once('mail.php');
include_once('requetes.php');
include_once('texte.php');

$logs = new Logs($config);
$mails = new MailProfil($config, $logs);
$requetes = new RequetesProfil($config, $logs);
$texte = new TexteProfil($config);
//récupération donnée de l'utilisateur sur la session courante
$donneesUtilisateur = $requetes->donneesUtilisateur($_SESSION['identifiant']);

$email = $donneesUtilisateur['emailUtilisateur'];
// token unique, d'une modification
$token = bin2hex(random_bytes(15));
$lien = 'http://'. $config['variables']['redirection']['mail']['ip'] .'/'.$config['variables']['redirection']['mail']['dossier'].'profil/confirmationModification.php?token='. $token .'&identifiant='.$donneesUtilisateur['identifiantUtilisateur'];
$returnLink = '<a href="../tableauDeBord"><p>Retour au tableau de bord</p></a>';

if (isset($_SESSION['mdpModif'])) {
    // verification si mail dans la bdd (cas de mot de passe oublié)
    if ($requetes->verifierEmail($email)) {
        $motif = $texte->messageModification("mot de passe");
        // insertion du nouveau mdp dans la table 'modifications' avant de le modifier sur la table 'utilisateurs'
        $requetes->modification($_SESSION['identifiant'], 'motDePasse', $_SESSION['mdpModif'], $token);
        $mails->templateEmailModification($email, "mot de passe", $lien);
        $texte->templateMessageSucces($email, $motif, $returnLink);
        $logs->messageLog('l\'email : '.$email.' existe en base et mail a été envoyé');
    } else {
        // email inexistant
        header("Location: ./?erreur=mauvais-email");
        $logs->messageLog('l\'email : '.$email.' n\'existe pas en base', $logs->typeError);
    }
    unset($_SESSION["mdpModif"]);
}

if (isset($_SESSION['loginModif'])) {
    $motif = $texte->messageModification("login");
    $requetes->modification($_SESSION['identifiant'], 'login', $_SESSION['loginModif'], $token);
    $mails->templateEmailModification($email, "login", $lien);
    $texte->templateMessageSucces($email, $motif, $returnLink);
    $logs->messageLog('Un mail de modification de login a été envoyé');
    unset($_SESSION["loginModif"]);
}

if (isset($_SESSION['emailModif'])) {
    $motif = $texte->messageModification("email");
    $requetes->modification($_SESSION['identifiant'], 'email', $_SESSION['emailModif'], $token);
    $mails->templateEmailModification($email, "email", $lien);
    $texte->templateMessageSucces($email, $motif, $returnLink);
    $logs->messageLog('Un mail de modification d\'email a été envoyé');
    unset($_SESSION["emailModif"]);
}