<?php
// Chargement des ressources utiles
require_once('../../ressources/config/config.inc.php');
require_once('../../ressources/php/Logs.php');
require_once('../../ressources/php/Model.php');
require_once("../requetes.php");
$logs = new Logs($config);
$logs->messageLog('Appel du fichier verifierExistenceEmail.php.', $logs->typeInfo);
$requetes = new RequetesInscription($config, $logs);
$json = [];
if (!isset($_POST['email'])) {
    $logs->messageLog('Pas de paramètre reçu pour vérifier l\'adresse email.', $logs->typeWarning);
} else {
    if ($requetes->verifierEmail($_POST['email']) !== false) {
        $logs->messageLog('L\'email "' . $_POST['email'] .'" n\'est pas déjà en base.', $logs->typeInfo);
        $json = ['email' => true];
    } else {
        $logs->messageLog('L\'email "' . $_POST['email'] .'" est déjà en base.', $logs->typeWarning);
    }
}
try {
    echo json_encode($json, JSON_THROW_ON_ERROR);
} catch (JsonException $e) {
    $logs->messageLog('Problème lors du retour JSON. Message: ' . $e->getMessage(), $logs->typeError);
}