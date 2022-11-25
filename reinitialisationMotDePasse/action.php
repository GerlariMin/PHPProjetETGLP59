<?php
    session_start();

    $formulaire = $_POST;
    $nouveauMotDePasse = $formulaire['nouveauMotDePasse'];
    $confirmationMotDePasse = $formulaire['confirmationMotDePasse'];
    if(isset($_GET['token'])){
        $_SESSION['token'] = $_GET['token'];
    }
    
    session_regenerate_id();
    include('../ressources/php/Logs.php');
    include("../ressources/config/config.inc.php");
    global $config;
    include($config['variables']['chemin'] . "ressources/php/Model.php");

    $logs = new Logs($config);

    // ajout de condition pour vérifier que le mot de passe  n'est pas le même qu'avant
    // => maj de la bdd
    if($nouveauMotDePasse === $confirmationMotDePasse){

        $model = Model::getModel($config, $logs);

        // récupération du token passé en clair (pas de pb je pense car token unique)
        if(isset($_SESSION['token']) && $model->verifierToken($_SESSION['token'])) {
            // vérifier que la requête renvoi qq chose...
            $model->modificationMotDePasse($nouveauMotDePasse, $_SESSION['token']);
            header("Location: ../reinitialisationMotDePasse/confirmationMotDePasseOublie.php");
            $logs->messageLog('token valide et date d\'expiration associé valide');
        }else{
            header("Location: ./?erreur=token");
            $logs->messageLog('token invalide ou expiré', $logs->typeError);
        }
    } else {
        header("Location: ./?erreur=mdp-differents");
        $logs->messageLog('Les mots de passes saisis sont différents', $logs->typeError);
    }
    exit();