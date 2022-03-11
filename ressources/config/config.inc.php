<?php

    global $config;

    /**                **
     * Variables utiles *
     **                **/

    $config['variables']['chemin'] = "../";
    $config['variables']['repertoires']['utilisateurs'] = 'c:/wamp64/www/utilisateurs/';

    // BDD
    $config['bdd']['dbname'] = 'projetetglp59';
    $config['bdd']['host'] = 'localhost';
    $config['bdd']['username'] = 'root';
    $config['bdd']['password'] = '';
    $config['bdd']['dsn'] = 'mysql:host=' . $config['bdd']['host'] . ';port=3306;dbname=' . $config['bdd']['dbname'];

    /**                  **
     * Messages d'erreurs *
     **                  **/

    // erreur 401
    $config['erreur']['401'] = "Acces interdit";
    // erreur 402
    $config['erreur']['403'] = "Dossier non accessible";
    // erreur 403
    $config['erreur']['404'] = "Page introuvable";
    // erreur 404
    $config['erreur']['405'] = "Erreur serveur interne";
    // erreur 500
    $config['erreur']['500'] = "Erreur serveur interne";

    /**
     * Actions spécifiques d'installation
     */

    // création du répertoire qui contiendra les données utilisateurs
    if(!is_dir($config['variables']['repertoires']['utilisateurs'])) {
        mkdir(directory: $config['variables']['repertoires']['utilisateurs'], recursive: true);
    }
