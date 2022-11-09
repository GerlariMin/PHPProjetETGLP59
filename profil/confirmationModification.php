<?php

session_start();

session_regenerate_id();
include("../ressources/config/config.inc.php");
global $config;
include($config['variables']['chemin'] . "ressources/php/Model.php");

$model = Model::get_model($config);

$returnLink = '<a href="../connexion"><p>Retour à la page de connexion</p></a>';
// si le token correspond bien a une demande de modif (à définir)
if(isset($_GET['identifiant'], $_GET['token'])){
  $motif = $model->confirmerModification($_GET['identifiant'], $_GET['token']);
  $text = $model->messageConfirmation($motif);
  $model->templateMessageSucces('', $text, $returnLink);
}else{
  header("Location: ./?token");
  //$logs->messageLog("Token ou identifiant non renseigné dans l'URL");
}