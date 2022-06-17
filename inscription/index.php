<?php
session_start();

include("../ressources/php/fichiers_communs.php");
include("texte.php");

global $render;
$tab = array('inscription' => 'true','form' => ['form_action'=> 'post-ok.php', 
        'form_method' => 'post', 
        'input_nom' => 'nom', 
        'input_prenom' => 'prenom',
        'input_user' => 'username', 
        'input_password' => 'password']);


$render->actionRendu($tab);