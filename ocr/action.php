<?php
    session_start();
    // LIEN PHP SSH2 WINDOWS: https://windows.php.net/downloads/pecl/releases/ssh2/1.3.1/
    // INSTALLER SSH2 WINDOWS: https://stackoverflow.com/questions/15134421/php-install-ssh2-on-windows-machine
    var_dump('POST: ', $_POST, '<br>');
    var_dump('SESSION: ', $_SESSION, '<br>');
    // Cette partie n'est accessible que si l'utilisateur est connecté
    if(isset($_SESSION['login'])) {
        include("../ressources/php/fichiers_communs.php");
        if(isset($_POST['fichierOCR']) && is_array($_POST['fichierOCR'])) {
            // Chargement et instanciation de la classe dédiées aux requête SQL pour le traitement OCR
            require_once("./requetes.php");
            $requetes = new RequetesOCR($config, $logs);
            // Liste des fichiers sélectionnés par l'utilisateur pour le traitement OCR
            $listeFichiers = '';
            // On parcourt le POST dédié
            foreach ($_POST['fichierOCR'] as $fichier) {
                $listeFichiers .= '\'' . $fichier . '\', ';
            }
            // On enlève le dernier '\' '
            $listeFichiers = substr($listeFichiers, 0, -2);
            // On récupère l'ensemble des noms de fichiers sélectionnés
            $nomsFichiers = $requetes->recupererNomsDocuments($_SESSION['identifiant'], $listeFichiers);
            // On récupère le répertoire et sous répertoire dans lesquels les fichiers de l'utilisateur connecté sont stockés
            $repertoires = str_split($_SESSION['identifiant'], 5);
            // On récupère le chemin complet de l'endroit où sont stockés les fichiers de l'utilisateur connecté
            $repertoireUtilisateur = $config['variables']['repertoires']['utilisateurs'] . $repertoires[0] . '/' . $repertoires[1] . '/';
            var_dump('Début SSH <br>');
            // Initialisation connexion SSH2
            $connexionSsh2 = ssh2_connect("192.168.30.35", 22);
            var_dump('SSH OK <br>');
            // Authentification SSH avec mot de passe
            ssh2_auth_password($connexionSsh2,'tesseract','@qALiF38xXjS0vZD');
            var_dump('AUTH SSH OK <br>');
            // Pour chaque fichier récupéré, si il existe bien dans le répertoire souhaité
            foreach ($nomsFichiers as $nomFichier) {
                // Chemin complet du fichier courant
                $fichier = $repertoireUtilisateur . $nomFichier['DOCUMENT'];
                var_dump('FICHIER: ', $fichier,' <br>');
                // S'il s'agit bien d'un fichier
                if(file_exists($fichier)) {
                    var_dump('FICHIER OK <br>');
                    // TODO - Utiliser les répertoires partagés sur la machine Tesseract pour ne pas à avoir à transférer les fichier avec scp_send/ scp_recv
                    // On envoie le fichier sur la machine tesseract
                    ssh2_scp_send($connexionSsh2, $fichier, '/home/tesseract/Documents/fichiersATraiter/' . $nomFichier['DOCUMENT'], 0777);
                    var_dump('FICHIER SEND <br>');
                    // On exécute une commande sur la machine dédiée à Tesseract
                    $stream = ssh2_exec($connexionSsh2, 'tesseract ~/Documents/fichiersATraiter/' . $nomFichier['DOCUMENT'] . ' ~/Documents/fichiersATraiter/OK');// . $nomFichier['DOCUMENT']);
                    var_dump('TESSERACT. "tesseract ~/Documents/fichiersATraiter/' . $nomFichier['DOCUMENT'] . ' ~/Documents/fichiersATraiter/OK" OK <br>');
                    // Répertoire dédié aux résultats Tesseract de l'utilisateur connecté
                    $repertoireResultatsTesseract = $repertoireUtilisateur . 'resultats/';
                    // Si le répertoire n'exste pas
                    if(!file_exists($repertoireResultatsTesseract)) {
                        // On tente de créer le répertoire
                        if (!mkdir($repertoireResultatsTesseract, 0777, TRUE) && !is_dir($repertoireResultatsTesseract)) {
                            var_dump('PBM MKDIR');
                        }
                    }
                    // On récupère le fichier résultat du traitement du fichier courant
                    ssh2_scp_recv($connexionSsh2, '', $repertoireResultatsTesseract . 'resultat-' . $nomFichier['DOCUMENT']);
                }
            }
            // close remote connection
            ssh2_exec($connexionSsh2, 'exit');
            var_dump('Fin SSH <br>');
            header('Location: ./?succes=tok');
            exit();
        } else {
            header('Location: ./?erreur=1');
            exit();
        }
    } else {
        header('Location: ../connexion/?erreur=5');
        exit();
    }
