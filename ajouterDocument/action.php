<?php
    session_start();
    require_once('../ressources/config/config.inc.php');
    require_once('../ressources/php/Logs.php');
    require_once('requetes.php');
    global $config;
    $logs = new Logs($config);
    $requetes = new RequetesAjouterDocument($config, $logs);
    // Cette partie n'est accessible que si l'utilisateur est connecté
    if (isset($_SESSION['login'])) {
        $documents = $_FILES['documents'];
        $indiceMax = count($documents['name']);
        $nomFichiers = $documents['name'];
        $emplacementTMP = $documents['tmp_name'];
        $tailleFichiers = $documents['size'];
        $tailleMaxFichier = ini_get('upload_max_filesize'); // On récupère la taille maximum autorisée sur le serveur
        $fichiers = [];
        $fichierTropVolumineux = false;
        $listeExtensionsAcceptees = ['.pdf', '.docx', '.txt', '.jpeg', '.png'];
        // On parcourt chaque fichiers
        for ($indice = 0; $indice < $indiceMax; $indice++) {
            // si le fichier courant a une taille supérieure à la capacité max supportée par le serveur ou spécifiée par le formulaire
            if ($documents['error'][$indice] === UPLOAD_ERR_INI_SIZE || $documents['error'][$indice] === UPLOAD_ERR_FORM_SIZE) { // voir https://www.php.net/manual/fr/features.file-upload.errors.php
                $fichierTropVolumineux = true;
                break;
            }
            $formatCorrect = false;
            foreach ($listeExtensionsAcceptees as $extension) {
                if (str_contains($nomFichiers[$indice], $extension)) {
                    $formatCorrect = true;
                }
            }
            if ($formatCorrect) {
                $fichiers[] =
                    [
                        'emplacementTemporaire' => $emplacementTMP[$indice],
                        'nom' => $nomFichiers[$indice],
                        'taille' => $tailleFichiers[$indice],
                    ];
            }
        }
        // Si au moins un fichier est trop volumineux, on redirige vers la page avec un code d'erreur
        if ($fichierTropVolumineux) {
            // On notifie le problème dans les logs
            $logs->messageLog('La procédure d\'upload de fichiers ne s\'est pas déroulée sans problèmes. Au moins un fichier dépasse la taille maximale indiquée par le serveur.', $logs->typeError);
            header('Location: ./?erreur=fsize');
            exit();
        }
        $identifiantUtilisateur = $_SESSION['identifiant'];
        if ($fichiers && is_array($fichiers)) {
            // Décomposition de l'identifiant utilisateur pour trouver le répertoire et sous répertoire où placer les fichiers
            $repertoireUtilisateur = substr($identifiantUtilisateur,0,5);
            $sousRepertoireUtilisateur = substr($identifiantUtilisateur,5,10);
            // Booléen pour détecter une erreur pour un upload sur serveur
            $problemeServeur = false;
            // Booléen pour détecter une erreur pour une insertion en base
            $problemeBDD = false;
            foreach ($fichiers as $fichier) {
                // Destination finale du fichier à ipload sur le servveur
                $destinationFichier = $config['variables']['repertoires']['utilisateurs'] . $repertoireUtilisateur . '/' . $sousRepertoireUtilisateur . '/' . $fichier['nom'];
                // Déplacement du fichier de son emplacement temporaire vers le répertoire voulu
                if (move_uploaded_file($fichier['emplacementTemporaire'], $destinationFichier)) {
                    // Si un problème survent lorsque l'on ajoute les informations propres au document en base
                    if (!$requetes->ajouterDocument($fichier['nom'], $identifiantUtilisateur)) {
                        // On change l'état du booléen dédié
                        $problemeBDD = true;
                        // On supprime le fichier du répertoire dans lequel on vient de le placer
                        exec('rm -f ' . $destinationFichier);
                        // On notifie le problème dans les logs
                        $logs->messageLog('Le document "' . $fichier['nom'] . '" n\'a pas été enregistré en base et a été supprimé du serveur.', $logs->typeError);
                    }
                } else {
                    // On change l'état du booléen dédié
                    $problemeServeur = true;
                    // On notifie le problème dans les logs
                    $logs->messageLog('Le document "' . $fichier['nom'] . '" n\'a pas été enregistré sur le serveur.', $logs->typeError);
                }
            }
            if ($problemeServeur || $problemeBDD) {
                // On notifie le problème dans les logs
                $logs->messageLog('La procédure d\'upload de fichiers ne s\'est pas déroulée sans problèmes.', $logs->typeError);
                header('Location: ./?erreur=fup');
                exit();
            }
            // Logs
            $logs->messageLog('La procédure d\'upload de fichiers s\'est déroulée sans problèmes.', $logs->typeNotice);
            header('Location: ./index.php?succes=fok');
            exit();
        } else {
            $logs->messageLog('L\'utilisateur n\'a soumit aucun fichier ou ceux-ci ne sont pas conformes.', $logs->typeError);
            header('Location: ./index.php?erreur=f');
            exit();
        }
    } else {
        $logs->messageLog('Utilisateur non connecté.', $logs->typeError);
        header('Location: ../connexion/?erreur=5');
        exit();
    }