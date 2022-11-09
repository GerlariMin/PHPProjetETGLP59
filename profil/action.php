<?php

session_start();
include('../ressources/php/Logs.php');
include("../ressources/config/config.inc.php");
global $config;
include($config['variables']['chemin'] . "ressources/php/Model.php");

$logs = new Logs($config);
$model = Model::get_model($config);
$returnLink = '<a href="../connexion"><p>Retour à la page de connexion</p></a>';

if(isset($_POST['nom']) && $_POST['nom'] !== ''){
    $model->modificationNom($_SESSION['identifiant'], $_POST['nom']);
    $model->templateMessageSucces('', "Votre nom a bien été modifié", $returnLink);
}
if(isset($_POST['prenom']) && $_POST['prenom'] !== ''){
    $model->modificationPrenom($_SESSION['identifiant'], $_POST['prenom']);
    $model->templateMessageSucces('', "Votre prénom a bien été modifié", $returnLink);
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
