<?php

session_start();
include_once('../ressources/php/Logs.php');
include_once("../ressources/config/config.inc.php");
global $config;
require_once('requetes.php');
require_once('texte.php');

$logs = new Logs($config);
// Initialisation de la classe des requêtes dédiées au module d'inscription
$requetes = new RequetesProfil($config, $logs); // TODO - utiliser requetes et creer classe pour mail
$texte = new TexteProfil($config);

$returnLink = '<a href="../connexion"><p>Retour à la page de connexion</p></a>';

if(isset($_POST['nom']) && $_POST['nom'] !== ''){
    $requetes->modificationNom($_SESSION['identifiant'], $_POST['nom']);
    $texte->templateMessageSucces('', "Votre nom a bien été modifié", $returnLink);
}
if(isset($_POST['prenom']) && $_POST['prenom'] !== ''){
    $requetes->modificationPrenom($_SESSION['identifiant'], $_POST['prenom']);
    $texte->templateMessageSucces('', "Votre prénom a bien été modifié", $returnLink);
}
if(isset($_POST['login'])){
    $_SESSION['loginModif'] = $_POST['login'];
    header("Location: ../profil/envoiMailModification.php");
}
if(isset($_POST['email'])){
    $_SESSION['emailModif'] = $_POST['email'];
    header("Location: ../profil/envoiMailModification.php");
}
if(isset($_POST['mdp'])){
    // jsp si bonne idée de mettre ça en session, à voir...
    $_SESSION['mdpModif'] = password_hash($_POST['mdp'], PASSWORD_DEFAULT);
    header("Location: ../profil/envoiMailModification.php");
}
if(isset($_POST['abonnement'])){
    header("Location: ../souscription/souscription.mustache");
}
