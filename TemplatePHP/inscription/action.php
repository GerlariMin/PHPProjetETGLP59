<?php
session_start();

$formulaire = $_POST;
unset($_POST);
$cgu = $formulaire['cgu'];
$login = $formulaire['login'];
$nom = $formulaire['nom'];
$prenom = $formulaire['prenom'];
$email = $formulaire['email'];
$password = $formulaire['password'];
$passwordConfirm = $formulaire['passwordConfirm'];

// On s'assure d'abord que les conditions générales d'utilisations sont acceptées
if(isset($cgu)){
    session_regenerate_id();
    include("../ressources/config/config.inc.php");
    global $config;
    include($config['variables']['chemin'] . "ressources/php/Model.php");
    // Instanciation de la classe qui gère la connexion et les requêtes avec la base de données
    $model = Model::get_model($config);
    // On s'assure que les mots de passe saisis soient identiques
    if(isset($password, $passwordConfirm) && $password === $passwordConfirm) {
        $motDePasseClair = $password;
        unset($password, $passwordConfirm);
        $motDePasseChiffre = password_hash(password: $motDePasseClair, algo: PASSWORD_DEFAULT);
        // On s'assure qu'un email ait été saisi et qu'il n'existe pas déjà en base
        if(isset($email) && !$model->verifierEmail(email: $email)) {
            // On s'assure que les données essentielles du profil soient saisies
            if(isset($login, $nom, $prenom)) {
                try {
                    $identifiant = bin2hex(random_bytes(length: 5));
                } catch (Exception $e) {
                    header('Location: index.php?erreur=5');
                    exit();
                }
                // On insère le nouvel utilisateur en base
                if($model->insererUtilisateur(identifiant: $identifiant, nom: $nom, prenom: $prenom, login: $login, email: $email)) {
                    // On enregistre son mot de passe
                    if($model->insererMotDePasse(motDePasseChiffre: $motDePasseChiffre, identifiantUtilisateur: $identifiant)) {
                        // Suppression des variables de mots de passe
                        unset($motDePasseClair, $motDePasseChiffre);
                        // Création de l'espace utilisateur (répertoires de fichiers)
                        $repertoires = str_split(string: $identifiant, length: 5);
                        if(!is_dir($config['variables']['repertoires']['utilisateurs'] . $repertoires[0])) {
                            mkdir($config['variables']['repertoires']['utilisateurs'] . $repertoires[0]);
                            if(!is_dir($config['variables']['repertoires']['utilisateurs'] . $repertoires[0] . '/' . $repertoires[1])) {
                                mkdir($config['variables']['repertoires']['utilisateurs'] . $repertoires[0] . '/' . $repertoires[1]);
                            }
                        }
                        // Si tout s'est bien passé, on redirige l'utilisateur sur son espace.
                        $_SESSION['identifiant'] = $identifiant;
                        $_SESSION['login'] = $login;
                        header('Location: ../tableau-de-bord/');
                    } else {
                        header('Location: index.php?erreur=5');
                    }
                } else {
                    header('Location: index.php?erreur=5');
                }
            } else {
                header('Location: index.php?erreur=4');
            }
        } else {
            header('Location: index.php?erreur=3');
        }
    } else {
        header('Location: index.php?erreur=2');
    }
} else {
    header('Location: index.php?erreur=1');
}
exit();