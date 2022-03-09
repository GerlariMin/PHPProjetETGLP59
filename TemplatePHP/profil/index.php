<?php
session_start();

include("../ressources/php/fichiers_communs.php");
//include("texte.php");

global $render;

global $config;
$data['utilisateur'] = $_SESSION['login'];
$data['chemin'] = $config['variables']['chemin'];
$data['profil'] = true;
$render->actionRendu($data);