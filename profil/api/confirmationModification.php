<?php

session_start();

session_regenerate_id();
include_once("../../ressources/config/config.inc.php");
global $config;
include_once("../../ressources/php/Logs.php");
include_once("../../ressources/php/Model.php");
include_once('../requetes.php');
// Instanciations des classes utiles
$logs = new Logs($config);
$requetes = new RequetesProfil($config, $logs);
// si le token correspond bien a une demande de modif (à définir)
if (isset($_GET['identifiant'], $_GET['token'])) {
    // On tente d'appliquer la modification définitivement
  if ($requetes->confirmerModification($_GET['identifiant'], $_GET['token'])) {
      $logs->messageLog("Confirmation de la modification réussie.", $logs->typeNotice);
      header('Location: ../?succes=modification');
  } else {
      $logs->messageLog("Confirmation de la modification échouée.", $logs->typeError);
      header('Location: ../?erreur=modification');
  }
} else {
    $logs->messageLog("Token ou identifiant non renseigné dans l'URL", $logs->typeAlert);
    header("Location: ./?erreur=token");
}
exit();