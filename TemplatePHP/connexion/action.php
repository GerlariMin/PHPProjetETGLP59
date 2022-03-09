<?php
    session_start();

    $formulaire = $_POST;
    $email = $formulaire['email'];
    $password = $formulaire['password'];

    if(isset($email, $password)){
        session_regenerate_id();
        include("../ressources/config/config.inc.php");
        global $config;
        include($config['variables']['chemin'] . "ressources/php/Model.php");

        $model = Model::get_model($config);
        $identifiant = $model->recupererIdentifiant($email);
        // On s'assure de pouvoir récupérer un profil à partir du mail (qui est unique en base)
        if($identifiant) {
            // On récupère le mot de passe courant associé à cet identifiant
            $motDePasseCourant = $model->recupererMotDePassCourant(identifiant: $identifiant);
            // Si on obtient bien un mot de passe chiffré et que le mot de passe saisi correspond
            if($motDePasseCourant && password_verify($password, $motDePasseCourant)) {
                // On supprime les variables liées au mot de passe
                unset($password, $motDePasseCourant);
                // On tente de récupérer l'utilisateur lié à l'adresse e-mail saisi et à l'identifiant récupéré
                $utilisateur = $model->recupererUtilisateur(email: $email, identifiant: $identifiant);
                // Si on récupère bien des valeurs
                if($utilisateur){
                    // Si tout s'est bien passé, on redirige l'utilisateur sur son espace.
                    $_SESSION['identifiant'] = $identifiant;
                    $_SESSION['login'] = $utilisateur['LOGIN'];
                    header("Location: ../tableau-de-bord/");
                } else {
                    header("Location: ../erreur/?erreur=u404");
                }
            }
        }
    } else {
        header("Location: ../accueil/");
    }
    exit();