<?php

session_start();
include_once('../../ressources/php/Logs.php');
include_once("../../ressources/config/config.inc.php");
global $config;
require_once('../../ressources/php/Mail.php');
include_once('../mail.php');
require_once('../../ressources/php/Model.php');
require_once('../requetes.php');
require_once('../texte.php');

$logs = new Logs($config);
$mails = new MailProfil($config, $logs);
// Initialisation de la classe des requêtes dédiées au module d'inscription
$requetes = new RequetesProfil($config, $logs); // TODO - utiliser requetes et creer classe pour mail
$logs->messageLog('Début du script pour modifier des informations du profil de l\'utilisateur connecté', $logs->typeInfo);
// Valeur de retour du script
$retour = false;
// On vérifie qu'il y a bien un POST
if (isset($_POST)) {
    $logs->messageLog('POST reçu.', $logs->typeNotice);
    // Création du token
    $token = bin2hex(random_bytes(15));
    // Récupération de l'adresse e-mail de l'utilisateur connecté effectuant la demande de modification
    $destinataire = $requetes->recupererEmailUtilisateur($_SESSION['identifiant']);
    // On a post nom et la requête a bien été faite
    if (isset($_POST['nom']) && $_POST['nom'] !== '') {
        $logs->messageLog('Changement de nom demandé.', $logs->typeInfo);
        // Application de la modification en base
        $retour = $requetes->modificationNom($_SESSION['identifiant'], $_POST['nom']);
        if ($retour) {
            $logs->messageLog('Modification du nom effectuée.', $logs->typeInfo);
        } else {
            $logs->messageLog('Modification du nom non effectuée.', $logs->typeWarning);
        }
    }
    // On a post prenom et la requête a bien été faite
    if (isset($_POST['prenom']) && $_POST['prenom'] !== '') {
        $logs->messageLog('Changement de prénom demandé.', $logs->typeInfo);
        // Application de la modification en base
        $retour = $requetes->modificationPrenom($_SESSION['identifiant'], $_POST['prenom']);
        if ($retour) {
            $logs->messageLog('Modification du prénom effectuée.', $logs->typeInfo);
        } else {
            $logs->messageLog('Modification du prénom non effectuée.', $logs->typeWarning);
        }
    }
    // On a post nom et la requête a bien été faite
    if (isset($_POST['login'])) {
        $logs->messageLog('Changement de login demandé.', $logs->typeInfo);
        // On ajoute la modification dans la table dédiée
        if ($requetes->modification($_SESSION['identifiant'], 'login', $_POST['login'], $token)) {
            $logs->messageLog('Modification du login enregistrée en base, en attente de confirmation.', $logs->typeInfo);
            // Procéder à l'envoi du mail pour faire valider la modification
            $lien = 'http://'. $config['variables']['redirection']['mail']['ip'] . '/' . $config['variables']['redirection']['mail']['dossier'] . 'profil/api/confirmationModification.php?token=' . $token . '&identifiant=' . $_SESSION['identifiant'];
            // Récupération de l'adresse e-mail de l'utilisateur connecté effectuant la demande de modification
            $destinataire = $requetes->recupererEmailUtilisateur($_SESSION['identifiant']);
            // Envoi du mail de confirmation de modification
            if ($mails->templateEmailModification($destinataire, 'login', $lien)) {
                $retour = true;
            } else {
                $retour = false;
            }
        } else {
            $logs->messageLog('Modification du login non effectuée.', $logs->typeWarning);
            $retour = false;
        }
    }
    // On a post mdp et la requête a bien été faite
    if (isset($_POST['mail'])) {
        $logs->messageLog('Changement de mail demandé.', $logs->typeInfo);
        // On ajoute la modification dans la table dédiée
        if ($requetes->modification($_SESSION['identifiant'], 'email', $_POST['mail'], $token)) {
            $logs->messageLog('Modification du mail enregistrée en base, en attente de confirmation.', $logs->typeInfo);
            // Procéder à l'envoi du mail pour faire valider la modification
            $lien = 'http://'. $config['variables']['redirection']['mail']['ip'] . '/' . $config['variables']['redirection']['mail']['dossier'] . 'profil/api/confirmationModification.php?token=' . $token . '&identifiant=' . $_SESSION['identifiant'];
            // Envoi du mail de confirmation de modification
            if ($mails->templateEmailModification($destinataire, 'email', $lien)) {
                $retour = true;
            } else {
                $retour = false;
            }
        } else {
            $logs->messageLog('Modification du mail non effectuée.', $logs->typeWarning);
            $retour = false;
        }
    }
    // On a post mdp et la requête a bien été faite
    if (isset($_POST['mdp'])) {
        $logs->messageLog('Changement de mdp demandé.', $logs->typeInfo);
        $mdp = password_hash($_POST['mdp'], PASSWORD_DEFAULT);
        // On ajoute la modification dans la table dédiée
        if ($requetes->modification($_SESSION['identifiant'], 'motDePasse', $mdp, $token)) {
            $logs->messageLog('Modification du mdp enregistrée en base, en attente de confirmation.', $logs->typeInfo);
            // Procéder à l'envoi du mail pour faire valider la modification
            $lien = 'http://'. $config['variables']['redirection']['mail']['ip'] . '/' . $config['variables']['redirection']['mail']['dossier'] . 'profil/api/confirmationModification.php?token=' . $token . '&identifiant=' . $_SESSION['identifiant'];
            // Envoi du mail de confirmation de modification
            if ($mails->templateEmailModification($destinataire, 'mot de passe', $lien)) {
                $retour = true;
            } else {
                $retour = false;
            }
        } else {
            $logs->messageLog('Modification du mdp non effectuée.', $logs->typeWarning);
            $retour = false;
        }
    }
} else {
    $logs->messageLog('Aucun POST reçu.', $logs->typeNotice);
}
// Retour au script
try {
    $logs->messageLog('Fin du script, retour envoyé: "' . $retour . '".', $logs->typeInfo);
    echo json_encode(array('modification' => $retour), JSON_THROW_ON_ERROR);
} catch (JsonException $e) {
    $logs->messageLog('Problème lors du retour JSON. Message: ' . $e->getMessage(), $logs->typeError);
}