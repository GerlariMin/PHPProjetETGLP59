<?php
    session_start();

    require_once('../ressources/config/config.inc.php');
    require_once('../ressources/php/Logs.php');
    require_once('requetes.php');
    require_once('../supprimerCompte/requetes.php');
    global $config;
    $logs = new Logs($config);
    $requetes = new RequetesConnexion($config, $logs);
    $requetesSuppression = new RequetesSupprimerCompte($config, $logs);
    // On récupère le formulaire
    $formulaire = $_POST;
    $login = $formulaire['login'];
    $password = $formulaire['password'];
    // Si les champs sont bien présent et non vides
    if(isset($login, $password)) {
        session_regenerate_id();
        // On récupère l'identifiant associé en base
        $identifiant = $requetes->recupererIdentifiant($login);
        // On s'assure de pouvoir récupérer un profil à partir du mail (qui est unique en base)
        if($identifiant) {
            // On récupère le mot de passe courant associé à cet identifiant
            $motDePasseCourant = $requetes->recupererMotDePasseCourant(identifiant: $identifiant);
            // Si on obtient bien un mot de passe chiffré et que le mot de passe saisi correspond
            if($motDePasseCourant && password_verify($password, $motDePasseCourant)) {
                // On supprime les variables liées au mot de passe
                unset($password, $motDePasseCourant);
                // On tente de récupérer l'utilisateur lié à l'adresse e-mail saisi et à l'identifiant récupéré
                $utilisateur = $requetes->recupererUtilisateur(login: $login, identifiant: $identifiant);
                // Si on récupère bien des valeurs
                if($utilisateur) {
                    $logs->messageLog('Utilisateur ' . $identifiant . ' trouvé.', $logs->typeNotice);
                    // Si tout s'est bien passé, on redirige l'utilisateur sur son espace.
                    $_SESSION['identifiant'] = $identifiant;
                    $_SESSION['login'] = $utilisateur['LOGIN'];
                    $requetesSuppression->horodatageConnexion($_SESSION['login']);
                    $requetesSuppression->verificationDeadlineUtilisateur($_SESSION['login']);
                    $logs->messageLog('Sessions initialisées.', $logs->typeNotice);
                    $logs->messageLog('Vérification abonnement.', $logs->typeInfo);
                    // Vérification de la validité de l'abonnement
                    if (!$requetes->dateAbonnementEncoreValide($identifiant)) {
                        $logs->messageLog('Actualisation abonnement, retour à l\'abonnement gratuit car fin de l\'abonnement payant.', $logs->typeInfo);
                        // Si abonnement expiré, on repasse à l'abonnement gratuit
                        $requetes->actualiserAbonnementutilisateur($identifiant);
                    }
                    // Décomposition de l'identifiant de l'utilisateur pour créer son répertoire.
                    $dir = substr($identifiant,0,5);
                    $subdir=substr($identifiant,5,10);
                    $repertoireUtilisateur =  $config['variables']['repertoires']['utilisateurs']."./{$dir}/{$subdir}/";
                    $repertoireResultatsUtilisateur =  $config['variables']['repertoires']['utilisateurs']."./{$dir}/{$subdir}/resultats/";
                    // Création du répertoire utilisateur
                    if (!mkdir($repertoireUtilisateur, 0777, true) && !is_dir($repertoireUtilisateur)){
                        $logs->messageLog('Le répertoire utilisateur "' . $repertoireUtilisateur .'" n\'a pas pu être créé.', $logs->typeError);
                    } else {
                        $logs->messageLog('Le répertoire utilisateur "' . $repertoireUtilisateur .'" a été créé.', $logs->typeNotice);
                    }
                    // Création du répertoire résultats du répertoire utilisateur
                    if (!mkdir($repertoireResultatsUtilisateur, 0777, true) && !is_dir($repertoireResultatsUtilisateur)){
                        $logs->messageLog('Le répertoire utilisateur "' . $repertoireResultatsUtilisateur .'" n\'a pas pu être créé.', $logs->typeError);
                    } else {
                        $logs->messageLog('Le répertoire utilisateur "' . $repertoireResultatsUtilisateur .'" a été créé.', $logs->typeNotice);
                    }
                    header('Location: ../tableauDeBord/');
                } else {
                    $logs->messageLog('Utilisateur introuvable.', $logs->typeError);
                    header('Location: ./?erreur=4');
                }
            } else {
                $logs->messageLog('Le mot de passe n\'a pas pu être récupéré ou n\'est pas conforme à ce qu\'a saisi l\'utilisateur.', $logs->typeError);
                header('Location: ./?erreur=3');
            }
        } else {
            $logs->messageLog('Aucun identifiant trouvé à partir des informations saisies par l\'utilisateur.', $logs->typeError);
            header('Location: ./?erreur=2');
        }
    } else {
        $logs->messageLog('Un des champs est vide ou non conforme.', $logs->typeError);
        header('Location: ./?erreur=1');
    }
    exit();