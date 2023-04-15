<?php
    include("../ressources/php/fichiers_communs.php");
    // On vérifie qu'on a bien reçu des données via POST
    if ($_POST) {
        require_once('./requetes.php');
        require_once('./mail.php');
        $mail = new MailInscription($config, $logs);
        $requetes = new RequetesInscription($config, $logs);
        $traitement = new TraitementInscription($render);
        // Vérification de la présence des champs requis
        if (!$_POST['nom']) {
            $logs->messageLog('Champ nom manquant.', $logs->typeWarning);
            header('Location: ./?erreur=nnom');
            exit();
        }
        if (!$_POST['prenom']) {
            $logs->messageLog('Champ prenom manquant.', $logs->typeWarning);
            header('Location: ./?erreur=nprenom');
            exit();
        }
        if (!$_POST['username']) {
            $logs->messageLog('Champ nom utilisateur manquant.', $logs->typeWarning);
            header('Location: ./?erreur=npseudo');
            exit();
        }
        if (!$_POST['email']) {
            $logs->messageLog('Champ email manquant.', $logs->typeWarning);
            header('Location: ./?erreur=nemail');
            exit();
        }
        if (!$_POST['password']) {
            $logs->messageLog('Champ mot de passe manquant.', $logs->typeWarning);
            header('Location: ./?erreur=nmdp');
            exit();
        }
        if (!$_POST['confirm']) {
            $logs->messageLog('Champ confirmation de mot de passe manquant.', $logs->typeWarning);
            header('Location: ./?erreur=ncmdp');
            exit();
        }
        // Si mdp différent confirmation mdp
        if ($_POST['password'] !== $_POST['confirm']) {
            $logs->messageLog('Champ mot de passe et confimation mot de passe ne sont pas identiques.', $logs->typeWarning);
            header('Location: ./?erreur=mdp');
            exit();
        }
        $logs->messageLog('Tous les champs sont remplis et conformes.', $logs->typeNotice);
        // Récuperation des champs du formulaire d'inscription
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $user = $_POST['username'];
        $email = $_POST['email'];
        // Chiffrement du mot de passe clair
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        // Suppression des variables contenant les valeurs claires des mdp
        unset($_POST['password'], $_POST['confirm']);
        // Génération d'un identifiant pour le nouvel utilisateur a créer
        $uuid = $traitement->traitementGetUuid($requetes);
        // Insertion de l'utilisateur en base
        if ($requetes->insererUtilisateur($uuid, $nom, $prenom, $user, $email, $password)) {
            $logs->messageLog('Utilisateur créé avec l\'identifiant "' . $uuid . '".', $logs->typeNotice);
            $traitement->traitementCreationRepertoireUtilisateur($logs, $uuid);
            $lien = $config['variables']['redirection']['mail']['ip'] . $config['variables']['redirection']['mail']['dossier'] . 'connexion/';
            if ($mail->templateEmailModification($email, $prenom . ' ' . $nom, $lien)) {
                header('Location: ../connexion/?succes=inscription');
                exit();
            }
            header('Location: ./?erreur=mail');
            exit();
        }
        $logs->messageLog('Problème lors de la création du compte.', $logs->typeError);
        header('Location: ./?erreur=mail');
        exit();
    } else {
        $logs->messageLog('Aucun POST reçu!', $logs->typeError);
        header('Location: ./?erreur=post');
        exit();
    }
