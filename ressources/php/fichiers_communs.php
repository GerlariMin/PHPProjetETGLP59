<?php
    // On récupère le fichier de configuration
    include_once('../ressources/config/config.inc.php');
    global $config;
    // On récupère les différentes ressources communes utiles pour le site
    include_once($config['variables']['chemin'] . 'ressources/vendor/autoload.php');
    include_once($config['variables']['chemin'] . 'ressources/mustache/Render.php');
    include_once($config['variables']['chemin'] . 'ressources/php/Logs.php');
    // On récupère les fichiers utiles pour afficher une page
    if(file_exists('traitement.php')) {
        include_once('traitement.php');
    }
    if(file_exists('texte.php')){
        include_once('texte.php');
    }
    // Initialisation de la classe Logs
    $logs = new Logs($config);
    // Initialisation de la classe Render
    $render = new Render($logs, $config['variables']['chemin']);
    // variable dédiée au code d'erreur
    $erreur = '';
    // variable dédiée au code de succès
    $succes = '';
    // Si il existe une clé erreur dans le lien
    if(isset($_GET['erreur'])) {
        // On affecte la valeur associée à la clé à la variable dédiée
        $erreur = $_GET['erreur'];
    // Sinon, si il existe une clé succes dans le lien
    } else if(isset($_GET['succes'])) {
        // On affecte la valeur associée à la clé à la variable dédiée
        $succes = $_GET['succes'];
    }