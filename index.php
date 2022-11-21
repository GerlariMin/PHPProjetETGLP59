<?php
session_start();

    if (isset($_SESSION['login'])) {
        if (array_key_first($_GET)) {
            $url = match (strtolower(array_key_first($_GET))) {
                'accueil' => './accueil/',
                'connexion' => './connexion/',
                'deconnexion' => './deconnexion/',
                'souscription' => './souscription/',
                'tableauDeBord' => './tableauDeBord/',
                default => './connexion/?erreur=indexRacine'
            };
        } else {
            $url = './tableauDeBord/';
        }
        header("Location: " . $url);
        exit();
    } else {
        header("Location: ./connexion/");
        exit();
    }
