<?php
session_start();

include("../ressources/php/fichiers_communs.php");
include("texte.php");

global $render;

$traitement = new TraitementInscription($render);
$codeErreur = '';
if(isset($_GET['erreur'])) {
    $codeErreur = $_GET['erreur'];
}
$traitement->traitementRendu($codeErreur);