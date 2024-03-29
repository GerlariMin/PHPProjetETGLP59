<?php
    session_start();

    $formulaire = $_POST;
    $email = $formulaire['email'];
    $_SESSION['email'] = $email;

    $sujet = '[OCRSQUARE] Réinitialisation de mot de passe';

    $token = bin2hex(random_bytes(15));

    // header pour l'intégration d'html dans le mail
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
    $headers .= 'From: OCRSQUARE <etglsquare@gmail.com>' . "\r\n";
    
    session_regenerate_id();
    include('../ressources/php/Logs.php');
    include("../ressources/config/config.inc.php");
    include_once('mail.php');
    global $config;
    include($config['variables']['chemin'] . "ressources/php/Model.php");

    $logs = new Logs($config);
    $mailMDPO = new MailMotDePasseOublie($config, $logs);
    
    if(isset($email)){

        $model = Model::getModel($config, $logs);
        $_SESSION['email'] = $email;

        $lienMail = 'http://'. $config['variables']['redirection']['mail']['ip'] .'/'. $config['variables']['redirection']['mail']['dossier'] .'reinitialisationMotDePasse?token='. $token;

        // verification si mail dans la bdd
        if($model-> verifierEmail($email)){
            $model->motDePasseOublie($email, $token);
            $mailMDPO->templateEmailMotDePasseOublie($email, $lienMail);
            $logs->messageLog('l\'email : '.$email.' existe en base');
            header("Location: ../motDePasseOublie/confirmationMotDePasseOublie.php");
        }else{
            // affichage d'un message d'erreur (proposer de s'inscrire ?)
            header("Location: ./?erreur=mauvais-email");
            $logs->messageLog('l\'email : '.$email.' n\'existe pas en base', $logs->typeError);
        };

    } else {
        header("Location: ./?erreur=mauvais-email");
        $logs->messageLog('formatage de l\'email erroné', $logs->typeError);
    }
    exit();