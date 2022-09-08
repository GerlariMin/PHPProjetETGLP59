<?php

session_start();
include('../ressources/php/Logs.php');
include("../ressources/config/config.inc.php");
global $config;
include($config['variables']['chemin'] . "ressources/php/Model.php");

$logs = new Logs($config);
$model = Model::get_model($config);

if(isset($_POST['nom']) && $_POST['nom'] !== ''){
    $model->modificationNom($_SESSION['identifiant'], $_POST['nom']);
}
if(isset($_POST['prenom']) && $_POST['prenom'] !== ''){
    $model->modificationPrenom($_SESSION['identifiant'], $_POST['prenom']);
}
if(isset($_POST['login']) && $_POST['login'] !== ''){
    $model->modificationLogin($_SESSION['identifiant'], $_POST['login']);
}
if(isset($_POST['email'])){
    //TODO: envoyer un mail
    $model->modificationEmail($_SESSION['identifiant'], $_POST['email']);
}
if(isset($_POST['mdp'])){
    // jsp si bonne idée de mettre ça en session, à voir...
    $_SESSION['mdp'] = $_POST['mdp'];
    header("Location: ../profil/envoiMailModifMdp.php");
}
if(isset($_POST['abonnement'])){
    header("Location: ../souscription/souscription.mustache");
}
