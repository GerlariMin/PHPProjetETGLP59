<?php

session_start();

session_regenerate_id();
include_once("../ressources/config/config.inc.php");
global $config;
include_once($config['variables']['chemin'] . "ressources/php/Logs.php");
include_once('requetes.php');
include_once('texte.php');

$logs = new Logs($config);
$requetes = new RequetesProfil($config, $logs);
$texte = new TexteProfil($config);

$returnLink = '<a href="../connexion"><p>Retour à la page de connexion</p></a>';
// si le token correspond bien a une demande de modif (à définir)
if (isset($_GET['identifiant'], $_GET['token'])) {
  $motif = $requetes->confirmerModification($_GET['identifiant'], $_GET['token']);
  $messageConfirmation = $texte->messageConfirmation($motif);
  $texte->templateMessageSucces('', $messageConfirmation, $returnLink);
} else {
  header("Location: ./?token");
  //$logs->messageLog("Token ou identifiant non renseigné dans l'URL");
}