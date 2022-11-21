<?php
    session_start();
    // LIEN PHP SSH2 WINDOWS: https://windows.php.net/downloads/pecl/releases/ssh2/1.3.1/
    // INSTALLER SSH2 WINDOWS: https://stackoverflow.com/questions/15134421/php-install-ssh2-on-windows-machine
    include("../ressources/php/fichiers_communs.php");
    // Cette partie n'est accessible que si l'utilisateur est connecté
    if(isset($_SESSION['login'])) {
        $logs->messageLog('Utilisateur "' . $_SESSION['identifiant'] .'" est connecté et veut faire un traitement OCR.', $logs->typeInfo);
        if(isset($_POST['fichierOCR']) && is_array($_POST['fichierOCR'])) {
            $logs->messageLog('Utilisateur "' . $_SESSION['identifiant'] .'" - Tentative de création du répertoire "résultats" échouée.', $logs->typeCritical);
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
            // Initialisation connexion SSH2
            $connexionSsh2 = ssh2_connect($config['ssh']['tesseract']['ip'], $config['variables']['ssh']['port']);
            // Authentification SSH avec mot de passe
            ssh2_auth_password($connexionSsh2,$config['ssh']['tesseract']['login'],$config['ssh']['tesseract']['password']);
            // Pour chaque fichier récupéré, si il existe bien dans le répertoire souhaité
            foreach ($nomsFichiers as $nomFichier) {
                // Chemin complet du fichier courant
                $fichier = $repertoireUtilisateur . $nomFichier['DOCUMENT'];
                $logs->messageLog('Utilisateur "' . $_SESSION['identifiant'] .'" - Traitement du fichier "' . $nomFichier['DOCUMENT'] . '".', $logs->typeInfo);
                // S'il s'agit bien d'un fichier
                if (file_exists($fichier)) {
                    // TODO - Utiliser les répertoires partagés sur la machine Tesseract pour ne pas à avoir à transférer les fichier avec scp_send/ scp_recv
                    // On envoie le fichier sur la machine tesseract
                    ssh2_scp_send($connexionSsh2, $fichier, '/home/tesseract/Documents/fichiersATraiter/' . $nomFichier['DOCUMENT'], 0777);
                    // On exécute une commande sur la machine dédiée à Tesseract
                    $stream = ssh2_exec($connexionSsh2, 'tesseract ~/Documents/fichiersATraiter/' . $nomFichier['DOCUMENT'] . ' ~/Documents/fichiersATraiter/OK');// . $nomFichier['DOCUMENT']);
                    // Répertoire dédié aux résultats Tesseract de l'utilisateur connecté
                    $repertoireResultatsTesseract = $repertoireUtilisateur . 'resultats/';
                    // Si le répertoire n'exste pas
                    if (!file_exists($repertoireResultatsTesseract)) {
                        $logs->messageLog('Utilisateur "' . $_SESSION['identifiant'] .'" - Le répertoire "résultats" n\'existe pas pour cet utilisateur..', $logs->typeAlert);
                        // On tente de créer le répertoire
                        if (!mkdir($repertoireResultatsTesseract, 0777, TRUE) && !is_dir($repertoireResultatsTesseract))
                        {
                            $logs->messageLog('Utilisateur "' . $_SESSION['identifiant'] .'" - Tentative de création du répertoire "résultats" échouée.', $logs->typeCritical);
                        }
                    }
                    // On récupère le fichier résultat du traitement du fichier courant
                    ssh2_scp_recv($connexionSsh2, '/home/tesseract/Documents/fichiersATraiter/OK.txt', $repertoireResultatsTesseract . 'resultat-' . $nomFichier['DOCUMENT']);
                    $logs->messageLog('Utilisateur "' . $_SESSION['identifiant'] .'" - Récupération du fichier soumi au traitement OCR Tesseract.', $logs->typeNotice);
                }
            }
            // close remote connection
            ssh2_exec($connexionSsh2, 'exit');
            $logs->messageLog('Utilisateur "' . $_SESSION['identifiant'] .'" - Connexion SSH2 fermée.', $logs->typeNotice);
            header('Location: ./?succes=tok');
            exit();
        } else {
            $logs->messageLog('Utilisateur "' . $_SESSION['identifiant'] .'" - Variable POST non présente ou non conforme.', $logs->typeError);
            header('Location: ./?erreur=1');
            exit();
        }
    } else {
        $logs->messageLog('Utilisateur non connecté, redurection vers la page de connexion.', $logs->typeAlert);
        header('Location: ../connexion/?erreur=5');
        exit();
    }
