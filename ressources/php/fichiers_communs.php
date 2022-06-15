<?php
    // On récupère le fichier de configuration
    include("../ressources/config/config.inc.php");
    global $config;
    // On récupère les différentes ressources communes utiles pour le site
    include($config['variables']['chemin'] . "ressources/vendor/autoload.php");
    include($config['variables']['chemin'] . "ressources/mustache/Render.php");
    include($config['variables']['chemin'] . "ressources/php/Logs.php");
    // On récupère les fichiers utiles pour afficher une page
    include("traitement.php");
    include("texte.php");
    // Initialisation de la classe Logs
    $logs = new Logs($config);
    // Initialisation de la classe Render
    $render = new Render($logs, $config['variables']['chemin']);