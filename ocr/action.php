<?php
    session_start();

    var_dump($_POST);
    exit();
    // LIEN PHP SSH2 WINDOWS: https://windows.php.net/downloads/pecl/releases/ssh2/1.3.1/
    // INSTALLER SSH2 WINDOWS: https://stackoverflow.com/questions/15134421/php-install-ssh2-on-windows-machine
    include("../ressources/php/fichiers_communs.php");
    // Cette partie n'est accessible que si l'utilisateur est connecté
    if (isset($_SESSION['login'])) {
        $logs->messageLog('Utilisateur "' . $_SESSION['identifiant'] .'" est connecté et veut faire un traitement OCR.', $logs->typeInfo);
        if (isset($_POST['fichierOCR'], $_POST['traitement']) && is_array($_POST['fichierOCR'])) {
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
            /*$connexionSsh2 = ssh2_connect($config['ssh']['tesseract']['ip'], $config['variables']['ssh']['port']);
            // Authentification SSH avec mot de passe
            ssh2_auth_password($connexionSsh2,$config['ssh']['tesseract']['login'],$config['ssh']['tesseract']['password']);
            */// Pour chaque fichier récupéré, si il existe bien dans le répertoire souhaité
            foreach ($nomsFichiers as $nomFichier) {
                // Chemin complet du fichier courant
                $fichier = $repertoireUtilisateur . $nomFichier['DOCUMENT'];
                $logs->messageLog('Utilisateur "' . $_SESSION['identifiant'] .'" - Traitement du fichier "' . $nomFichier['DOCUMENT'] . '".', $logs->typeInfo);
                // S'il s'agit bien d'un fichier
                if (file_exists($fichier)) {
                    // Répertoire dédié aux résultats Tesseract de l'utilisateur connecté
                    $repertoireResultatsTesseract = $repertoireUtilisateur . 'resultats/';
                    // Si le répertoire n'existe pas
                    if (!file_exists($repertoireResultatsTesseract)) {
                        $logs->messageLog('Utilisateur "' . $_SESSION['identifiant'] .'" - Le répertoire "résultats" n\'existe pas pour cet utilisateur..', $logs->typeAlert);
                        // On tente de créer le répertoire
                        if (!mkdir($repertoireResultatsTesseract, 0777, TRUE) && !is_dir($repertoireResultatsTesseract))
                        {
                            $logs->messageLog('Utilisateur "' . $_SESSION['identifiant'] .'" - Tentative de création du répertoire "résultats" échouée.', $logs->typeCritical);
                        }
                    }
                    // TODO - DEBUT CODE A FINIR
                    $urlServeurPython = '192.168.30.35:5000/cas' . $_POST['traitement'];
                    $valeurPost = [
                        'entree' => '/home/tesseract/utilisateurs/' . $repertoires[0] . '/' . $repertoires[1] . '/' . $nomFichier['DOCUMENT'],
                        'sortie' => '/home/tesseract/utilisateurs/' . $repertoires[0] . '/' . $repertoires[1] . '/resultats/'
                    ];
                    $donneesPost = http_build_query($valeurPost);
                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, $urlServeurPython);
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $donneesPost);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    try {
                        $result = json_decode(curl_exec($curl), true, 512, JSON_THROW_ON_ERROR);
                        $logs->messageLog('RETOUR JSON. Exception: "' . $result['traitement'] . '".', $logs->typeDebug);
                        $requetes->nouveauTraitement($_SESSION['identifiant'], true);
                        // Document en entrée - vérifier si déjà en base
                        if ($requetes->documentDejaExistant($nomFichier['DOCUMENT'], $_SESSION['identifiant'])) {
                            $logs->messageLog('Document "' . $nomFichier['DOCUMENT'] . '" déjà présent en base, pas besoin de rajouter une entrée.', $logs->typeInfo);
                        } else {
                            $logs->messageLog('Document "' . $nomFichier['DOCUMENT'] . '" n\'est pas encore en base.', $logs->typeNotice);
                            if ($requetes->ajouterDocument($nomFichier['DOCUMENT'], $_SESSION['identifiant'])) {
                                $logs->messageLog('Document "' . $nomFichier['DOCUMENT'] . '" ajouté en base.', $logs->typeInfo);
                            } else {
                                $logs->messageLog('Document "' . $nomFichier['DOCUMENT'] . '" n\'a pas pu être ajouté en base.', $logs->typeError);
                            }
                        }
                        // Document en sortie - ajouter le résultat sorti TODO - Regarder le résultat, il peut y en avoir plusieurs
                        /*
                        if ($requetes->documentDejaExistant($nomFichier['DOCUMENT'] . '_', $_SESSION['identifiant'])) {
                            $logs->messageLog('Document "' . $nomFichier['DOCUMENT'] . '" déjà présent en base, pas besoin de rajouter une entrée.', $logs->typeInfo);
                        } else {
                            $logs->messageLog('Document "' . $nomFichier['DOCUMENT'] . '" n\'est pas encore en base.', $logs->typeNotice);
                            if ($requetes->ajouterDocument($nomFichier['DOCUMENT'], $_SESSION['identifiant'])) {
                                $logs->messageLog('Document "' . $nomFichier['DOCUMENT'] . '" ajouté en base.', $logs->typeInfo);
                            } else {
                                $logs->messageLog('Document "' . $nomFichier['DOCUMENT'] . '" n\'a pas pu être ajouté en base.', $logs->typeError);
                            }
                        }
                        */
                    } catch (JsonException $e) {
                        $logs->messageLog('Problème lors de la récupération du JSON. Exception: "' . $e->getMessage() . '".', $logs->typeError);
                        $requetes->nouveauTraitement($_SESSION['identifiant'], false);
                        header('Location: ./?erreur=2');
                        exit();
                    }
                    // TODO - FIN CODE A FINIR
                    /*// On envoie le fichier sur la machine tesseract
                    ssh2_scp_send($connexionSsh2, $fichier, '/home/tesseract/Documents/fichiersATraiter/' . $nomFichier['DOCUMENT'], 0777);
                    // On exécute une commande sur la machine dédiée à Tesseract
                    $stream = ssh2_exec($connexionSsh2, 'tesseract ~/Documents/fichiersATraiter/' . $nomFichier['DOCUMENT'] . ' ~/Documents/fichiersATraiter/' . $nomFichier['DOCUMENT']);// . $nomFichier['DOCUMENT']);
                    // On récupère le fichier résultat du traitement du fichier courant
                    ssh2_scp_recv($connexionSsh2, '/home/tesseract/Documents/fichiersATraiter/OK.txt', $repertoireResultatsTesseract . 'resultat-' . $nomFichier['DOCUMENT']);
                    */$logs->messageLog('Utilisateur "' . $_SESSION['identifiant'] .'" - Récupération du fichier soumi au traitement OCR Tesseract.', $logs->typeNotice);
                }
            }
            // close remote connection
            /*ssh2_exec($connexionSsh2, 'exit');
            $logs->messageLog('Utilisateur "' . $_SESSION['identifiant'] .'" - Connexion SSH2 fermée.', $logs->typeNotice);
            */header('Location: ../tableauDeBord/?succes=tok');
            //header('Location: ./?succes=tok');
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
