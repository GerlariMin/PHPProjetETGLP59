<?php

    global $config;

    /**                **
     * Variables utiles *
     **                **/
    $config['variables']['application']['nom'] = 'OCR Square';
    $config['variables']['chemin'] = "../";
    $config['variables']['repertoires']['utilisateurs']['linux'] ='/home/user/utilisateurs/';
    $config['variables']['repertoires']['utilisateurs']['windows'] = 'c:/wamp64/www/utilisateurs/';
    $config['variables']['redirection']['mail']['ip'] = '192.168.30.34';
    $config['variables']['ssh']['port'] = 22;
    /**
     * Variables BDD
     */
    $config['bdd']['dbname'] = 'projetetglp59';
    $config['bdd']['host'] = 'localhost';
    $config['bdd']['username'] = 'root';//'etglp59';
    $config['bdd']['password'] = '';//paf@Du#!5iVK@a&n';
    $config['bdd']['dsn'] = 'mysql:host=' . $config['bdd']['host'] . ';port=3306;dbname=' . $config['bdd']['dbname'];
    /**                       **
     * Variables SSH Tesseract *
     **                       **/
    $config['ssh']['tesseract']['ip'] = '192.168.30.35';
    $config['ssh']['tesseract']['login'] = 'tesseract';
    $config['ssh']['tesseract']['password'] = '@qALiF38xXjS0vZD';
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
     * Logs
     */
     $config['logs']['emplacement']['linux'] = '/home/user/logs/';
     $config['logs']['emplacement']['windows'] = 'c:/wamp64/www/logs/';
     $config['logs']['fichier'] = 'ocr-square-logs';
    /**
     * Variables qui dépendent du système d'exploitation
     */
    if(PHP_OS_FAMILY === 'Windows') {
        $config['logs']['emplacement'] = $config['logs']['emplacement']['windows'];
        $config['variables']['repertoires']['utilisateurs'] = $config['variables']['repertoires']['utilisateurs']['windows'];
    } else {
        $config['logs']['emplacement'] = $config['logs']['emplacement']['linux'];
        $config['variables']['repertoires']['utilisateurs'] = $config['variables']['repertoires']['utilisateurs']['linux'];
    }
    /**
     * Actions spécifiques d'installation
     */
    // création du répertoire qui contiendra les données utilisateurs
    if(!is_dir($config['variables']['repertoires']['utilisateurs'])) {
        try {
            if (!mkdir($concurrentDirectory = $config['variables']['repertoires']['utilisateurs'], true) && !is_dir($concurrentDirectory)) {
                throw new RuntimeException(sprintf('Le répertoire "%s" n\'a pas pu être créé!', $concurrentDirectory));
            }
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }
    }