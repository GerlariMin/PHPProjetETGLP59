<?php
    session_start();

    require_once('../ressources/config/config.inc.php');
    require_once('../ressources/php/Logs.php');
    require_once('../ressources/php/Model.php');
    require_once('../ressources/mustache/Render.php');
    require_once('traitement.php');
    require_once('requetes.php');
    global $config;
    $logs = new Logs($config);

    if($_POST) {
        // Initialisation de la classe des requêtes dédiées au module d'inscription
        $requetes = new RequetesInscription($config, $logs);
        $render = new Render($logs, $config['variables']['chemin']);
        $traitement = new TraitementInscription($render);
        // Récuperation des champs du formulaire d'inscription
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $user = $_POST['username'];
        $email = $_POST['email'];
        $pass = $_POST['password'];
        $password = password_hash($pass, PASSWORD_DEFAULT);
        // On supprime les variables contenant le mot de passe en clair et les données POST
        unset($pass, $_POST);
        // Génération d'un identifiant pour le nouvel utilisateur a créer
        $uuid = $traitement->traitementGetUuid($requetes);
        // Insertion de l'utilisateur en base
        $insertionUtilisateur = $requetes->insererUtilisateur($uuid,$nom,$prenom,$user,$email,$password);
        try {
            //Si la requête est bonne alors on envoie en message de confirmation de l'inscription
            if ($insertionUtilisateur) {
                // TODO - DEBUT SECTION CODE A REVOIR
                // subject
                $subject = '[OCRSQUARE] Confirmation inscription';
                $email="davidtaha18@gmail.com";

                // message
                $message = '
                 <html>
                 <head>
                 <title>Bonjour</title>
                 </head>
                 <body>
                 <p>Bonjour ' . $nom . ',</p>
                 <p> Félicitations ! Votre compte a bien été crée, pour confirmer votre inscription veuillez cliquer <a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ">ici</a>.</p>
                 <p>Merci,</p>
                 <p>L\'équipe OCRSQUARE<p>
                 <!--<img src="images/logo_principal.JPG" width="175" height="100">-->
                 </body>
                 </html>
                 ';

                // To send HTML mail, the Content-type header must be set
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
                $headers .= 'From: OCRSQUARE <etglsquare@gmail.com>' . "\r\n";

                // renvoi true ou false (à ajuster quand il y aura accès à la base de donnée)
                ob_start();

                $output = ob_get_clean();
                // TODO - FIN SECTION CODE A REVOIR

                //-------------------------------creation du répertoire----------------------------//
                $traitement->traitementCreationRepertoireUtilisateur($logs, $uuid);

                header("Location: ../connexion");
                exit();
            }
        } catch (PDOException $e) {
            $logs->messageLog('Exception lors de l\'envoi du mail. Erreur: "' . $e->getMessage() . '"');
            header('./index.php?err=mauvais-email');
            exit();
        }
    } else {
        $logs->messageLog('Aucun POST reçu!', $logs->typeError);
        header('./index.php?err=post');
        exit();
    }

?>

