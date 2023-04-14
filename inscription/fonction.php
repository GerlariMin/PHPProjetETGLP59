<?php
session_start();
require_once('../ressources/config/config.inc.php');
require_once('../ressources/php/Logs.php');
require_once('../ressources/php/Model.php');
// Initialisation de la classe dédiée aux Logs
$logs = new Logs($config);
// Valeur de retour
$reponse = false;
// Si il existe un GET email
if ($_GET['email']) {
    $logs->messageLog('GET email présent.', $logs->typeDebug);
    // Je recupère la valeur envoyé depuis ma fonction js
    $email = $_GET['email'];
    $logs->messageLog('GET[email] = "' . $_GET['email'] . '".', $logs->typeDebug);
    // Initialisation de la classe ddédiée à la BDD
    $modele = Model::getModel($config, $logs);
    $reponse = $modele->verifierEmail($email)['EMAIL'];
    $logs->messageLog('Réponse requete: "' . $reponse . '".', $logs->typeDebug);
} else {
    $logs->messageLog('Utilisateur "' . $_SESSION['identifiant'] . '" - GET email non conforme: "' . $_GET['email'] .'"', $logs->typeWarning);
}
// On retourne la valeur
echo $reponse;
