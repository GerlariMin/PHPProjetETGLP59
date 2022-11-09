<?php

session_start();

session_regenerate_id();
include('../ressources/php/Logs.php');
include("../ressources/config/config.inc.php");
global $config;
include($config['variables']['chemin'] . "ressources/php/Model.php");

$logs = new Logs($config);

$infosUtilisateurs = Model::get_model($config);
//récupération donnée de l'utilisateur sur la session courante
$donneesUtilisateur = $infosUtilisateurs->donneesUtilisateur($_SESSION['identifiant']);

$email = $donneesUtilisateur['emailUtilisateur'];

$model = Model::get_model($config);
// token unique, d'une modification
$token = bin2hex(random_bytes(15));
$lien = 'http://'. $config['variables']['redirection']['mail']['ip'] .'/PHPProjetETGLP59-sandbox/profil/confirmationModification.php?token='. $token .'&identifiant='.$donneesUtilisateur['identifiantUtilisateur'];
$returnLink = '<a href="../tableauDeBord"><p>Retour au tableau de bord</p></a>';

if(isset($_SESSION['mdpModif'])){
  // verification si mail dans la bdd (cas de mot de passe oublié)
  if($model-> verifierEmail($email)){
      $motif = $model->messageModification("mot de passe");
      // insertion du nouveau mdp dans la table 'modifications' avant de le modifier sur la table 'utilisateurs'
      $model->modification($_SESSION['identifiant'], 'motDePasse', $_SESSION['mdpModif'], $token);
      $model->templateEmailModification($email, "mot de passe", $lien);
      $model->templateMessageSucces($email, $motif, $returnLink);
      $logs->messageLog('l\'email : '.$email.' existe en base et mail a été envoyé');
  }else{
      // email inexistant
      header("Location: ./?erreur=mauvais-email");
      $logs->messageLog('l\'email : '.$email.' n\'existe pas en base', $logs->typeError);
  };
  unset($_SESSION["mdpModif"]);
}

if(isset($_SESSION['loginModif'])){
  $motif = $model->messageModification("login");
  $model->modification($_SESSION['identifiant'], 'login', $_SESSION['loginModif'], $token);
  $model->templateEmailModification($email, "login", $lien);
  $model->templateMessageSucces($email, $motif, $returnLink);
  $logs->messageLog('Un mail de modification de login a été envoyé');
  unset($_SESSION["loginModif"]);
}

if(isset($_SESSION['emailModif'])){
  $motif = $model->messageModification("email");
  $model->modification($_SESSION['identifiant'], 'email', $_SESSION['emailModif'], $token);
  $model->templateEmailModification($email, "email", $lien);
  $model->templateMessageSucces($email, $motif, $returnLink);
  $logs->messageLog('Un mail de modification d\'email a été envoyé');
  unset($_SESSION["emailModif"]);
}