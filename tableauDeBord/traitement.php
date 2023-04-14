<?php

/**
 * Classe TraitementTableauDeBord
 */
class TraitementTableauDeBord
{

    /**
     * @var Render render
     */
    private Render $render;
    /**
     * @var array config
     */
    private array $config;
    /**
     * @var TexteTableauDeBord texte
     */
    private TexteTableauDeBord $texte;
    /**
     * @var string dédiées à la gestion des erreurs
     */
    private String $iClass = 'iClass';
    private String $strong = 'strong';
    private String $small = 'small';
    private String $message = 'message';

    /**
     * Traitement_Accueil constructor.
     *
     * @param Render $rendu
     */
    public function __construct(Render $rendu)
    {
        $this->render = $rendu;
        global $config;
        $this->config = $config;
        $this->texte = new TexteTableauDeBord($this->config);
    }

    /**
     * Permet de passer un tableau formaté à la classe Texte dédiée contenant les informations des fichiers contenus dans le répertoire résultat.
     * @return void
     */
    private function traitementFichiersResultats()
    {
        // On récupère le répertoire et sous répertoire dans lesquels les fichiers de l'utilisateur connecté sont stockés
        $repertoires = str_split($_SESSION['identifiant'], 5);
        // On récupère le chemin complet de l'endroit où sont stockés les fichiers de l'utilisateur connecté
        $repertoireUtilisateur = $this->config['variables']['repertoires']['utilisateurs'] . $repertoires[0] . '/' . $repertoires[1] . '/resultats/';
        // Tableau qui contiendra l'ensemble des fichiers compris dans ce répertoire
        $fichiers = array();
        // Si il s'agit bien d'un répertoire
        // Accès au répertoire
        if (is_dir($repertoireUtilisateur) && $iteration = opendir($repertoireUtilisateur)) {
            // On parcourt chaque fichier du répertoire
            while (($fichier = readdir($iteration)) !== false) {
                // On tri les fichiers correspondants aux répertoires ou autres fichiers non liés au site
                if ($fichier !== "Thumbs.db" && !is_dir($repertoireUtilisateur.$fichier)) {
                    if (str_contains($fichier, '.jpg') || str_contains($fichier, '.jpeg')) {
                        $type = 1;
                    } elseif (str_contains($fichier, '.png')) {
                        $type = 2;
                    } elseif (str_contains($fichier, '.txt')) {
                        $type = 3;
                    } else {
                        $type = 0;
                    }
                    $href = '../visualiserDocument/?document=' . $fichier . '&type=' . $type . '&resultat=true';
                    $fichiers[] =
                        [
                            'href' => $href,
                            'text' => $fichier, // Nom du fichier
                            'taille' => filesize($repertoireUtilisateur . $fichier) // Taille du fichier
                        ];
                }
            }
            // On ferme l'accès au répertoire
            closedir($iteration);
        }
        $this->texte->setFichiersResultats($fichiers);
        unset($fichiers);
    }

    /**
     * Permet de passer un tableau formaté à la classe Texte dédiée contenant les informations des fichiers contenus dans le répertoire principal de l'utilisateur.
     * @return void
     */
    public function traitementFichiers(): void
    {
        // On récupère le répertoire et sous répertoire dans lesquels les fichiers de l'utilisateur connecté sont stockés
        $repertoires = str_split($_SESSION['identifiant'], 5);
        // On récupère le chemin complet de l'endroit où sont stockés les fichiers de l'utilisateur connecté
        $repertoireUtilisateur = $this->config['variables']['repertoires']['utilisateurs'] . $repertoires[0] . '/' . $repertoires[1] . '/';
        // Tableau qui contiendra l'ensemble des fichiers compris dans ce répertoire
        $fichiers = array();
        // Si il s'agit bien d'un répertoire
        // Accès au répertoire
        if (is_dir($repertoireUtilisateur) && $iteration = opendir($repertoireUtilisateur)) {
            // On parcourt chaque fichier du répertoire
            while (($fichier = readdir($iteration)) !== false) {
                // On trie les fichiers correspondants aux répertoires ou autres fichiers non liés au site
                if ($fichier !== "Thumbs.db" && !is_dir($repertoireUtilisateur . $fichier)) {
                    if (str_contains($fichier, '.jpg') || str_contains($fichier, '.jpeg')) {
                        $type = 1;
                    } elseif (str_contains($fichier, '.png')) {
                        $type = 2;
                    } else {
                        $type = 0;
                    }
                    $href = '../visualiserDocument/?document=' . $fichier . '&type=' . $type;
                    $fichiers[] =
                        [
                            'href' => $href,
                            'text' => $fichier, // Nom du fichier
                            'taille' => filesize($repertoireUtilisateur . $fichier) // Taille du fichier
                        ];
                }
            }
            // On ferme l'accès au répertoire
            closedir($iteration);
        }
        // On affiche l'ensemble des fichiers sur la page du tableau de bord
        $this->texte->setFichiers($fichiers);
        unset($fichiers);
    }

    /**
     * Calcul de la taille qu'occupe l'ensemble des fichiers du répertoire donné en paramètre (en octet).
     * @param String $repertoire
     * @return int
     */
    private function calculTailleFichiersRepertoire(String $repertoire)
    {
        $tailleRepertoire = 0;
        // Accès au répertoire
        if (is_dir($repertoire) && $iteration = opendir($repertoire)) {
            // On parcourt chaque fichier du répertoire
            while (($fichier = readdir($iteration)) !== false) {
                // On trie les fichiers correspondants aux répertoires ou autres fichiers non liés au site
                if ($fichier !== "Thumbs.db" && !is_dir($repertoire . $fichier)) {
                    $tailleRepertoire += filesize($repertoire . $fichier);
                }
            }
            // On ferme l'accès au répertoire
            closedir($iteration);
        }
        return $tailleRepertoire;
    }

    /**
     * Calcul du pourcentage de l'espace dique utilisé par l'utilisateur sur le serveur, par rapport à la limite accordée par l'abonnement auquel il est lié.
     * @param array $limites
     * @return float
     */
    private function calculsPourcentageStockage(array $limites)
    {
        // Limite en Giga
        $nombreLimiteVolumeStockage = (float) $limites['LSTOCK'];
        $volumeTotalFichiersUtilisateur = 0.0; //3 * 1000000000;
        // On récupère le répertoire et sous répertoire dans lesquels les fichiers de l'utilisateur connecté sont stockés
        $repertoires = str_split($_SESSION['identifiant'], 5);
        // On récupère le chemin complet de l'endroit où sont stockés les fichiers de l'utilisateur connecté
        $repertoireUtilisateur = $this->config['variables']['repertoires']['utilisateurs'] . $repertoires[0] . '/' . $repertoires[1] . '/';
        // On récupère le chemin complet de l'endroit où sont stockés les fichiers résultats de l'utilisateur connecté
        $repertoireResultatsUtilisateur = $this->config['variables']['repertoires']['utilisateurs'] . $repertoires[0] . '/' . $repertoires[1] . '/resultats/';
        // Calcul de la taille des fichiers dans les répertoires de l'utilisateur
        $volumeTotalFichiersUtilisateur += (float)$this->calculTailleFichiersRepertoire($repertoireUtilisateur) + (float)$this->calculTailleFichiersRepertoire($repertoireResultatsUtilisateur);
        // Conversion en Giga
        $volumeTotalFichiersUtilisateur /= 1000000000;
        // Calcul pourcentage
        return round(($volumeTotalFichiersUtilisateur / $nombreLimiteVolumeStockage) * 100, 2);
    }

    /**
     * Calcul du pourcentage du nombre de fichiers stockés par l'utilisateur sur le serveur, par rapport à la limite accordée par l'abonnement auquel il est lié.
     * @param array $limites
     * @return float
     */
    private function calculsPourcentageFichiers(array $limites)
    {
        $nombreLimiteDocumentsStockes = (int) $limites['LDOC'];
        // On récupère le répertoire et sous répertoire dans lesquels les fichiers de l'utilisateur connecté sont stockés
        $repertoires = str_split($_SESSION['identifiant'], 5);
        // On récupère le chemin complet de l'endroit où sont stockés les fichiers de l'utilisateur connecté
        $repertoireUtilisateur = $this->config['variables']['repertoires']['utilisateurs'] . $repertoires[0] . '/' . $repertoires[1] . '/';
        // On récupère le chemin complet de l'endroit où sont stockés les fichiers résultats de l'utilisateur connecté
        $repertoireResultatsUtilisateur = $this->config['variables']['repertoires']['utilisateurs'] . $repertoires[0] . '/' . $repertoires[1] . '/resultats/';
        // On comptes les fichiers dans les différents espaces qu'utilise l'utilisateur connecté
        $nombreFichiersDansRepertoireUtilisateur = count(array_diff(scandir($repertoireUtilisateur), array('.', '..', 'resultats')));
        $nombreFichiersDansRepertoireResultatsUtilisateur = count(array_diff(scandir($repertoireResultatsUtilisateur), array('.', '..')));
        // Affectation du nombre total de fichiers stockés sur l'espace utilisateur dans un attribut de la classe
        $nombreTotalFichiersUtilisateur = $nombreFichiersDansRepertoireResultatsUtilisateur + $nombreFichiersDansRepertoireUtilisateur;
        // Calcul d'un pourcentage arrondi au centième
        return round(($nombreTotalFichiersUtilisateur / $nombreLimiteDocumentsStockes) * 100, 2);
    }

    /**
     * Calcul du pourcentage du nombre de traitements effectués aujourd'hui par l'utilisateur sur le serveur, par rapport à la limite accordée par l'abonnement auquel il est lié.
     * @param array $limites
     * @param int $traitementsQuotidienUtilisateur
     * @return float
     */
    private function calculsPourcentageTraitements(array $limites, int $traitementsQuotidienUtilisateur)
    {
        $nombreLimiteTraitements = (int) $limites['LOCR'];
        // Calcul d'un pourcentage arrondi au centième
        return round(($traitementsQuotidienUtilisateur / $nombreLimiteTraitements) * 100, 2);
    }

    /**
     * Traitement et formatage du tableau servant à la classe Texte dédiée pour générer l'affichage des différentes statistiques du tableau de bord
     * @param RequetesTableauDeBord $requetes
     * @return array
     */
    private function traitementRequetes(RequetesTableauDeBord $requetes): array
    {
        $tableau = [];
        // On récupère l'identifiant de l'abonnement auquel l'utilisateur a souscrit
        $abonnement = $requetes->recupererAbonnementUtilisateur($_SESSION['identifiant']);
        // On récupère les détails des limités de l'abonnement
        $limites = $requetes->recupererLimitesAbonnement($abonnement);
        // Si on a bien récupéré les détails
        if ($limites) {
            // On récupère le répertoire et sous répertoire dans lesquels les fichiers de l'utilisateur connecté sont stockés
            $repertoires = str_split($_SESSION['identifiant'], 5);
            // On récupère le chemin complet de l'endroit où sont stockés les fichiers de l'utilisateur connecté
            $repertoireUtilisateur = $this->config['variables']['repertoires']['utilisateurs'] . $repertoires[0] . '/' . $repertoires[1] . '/';
            // On récupère le chemin complet de l'endroit où sont stockés les fichiers résultats de l'utilisateur connecté
            $repertoireResultatsUtilisateur = $this->config['variables']['repertoires']['utilisateurs'] . $repertoires[0] . '/' . $repertoires[1] . '/resultats/';
            // Calcul de la taille des fichiers dans les répertoires de l'utilisateur
            $volumeTotalFichiersUtilisateur = (float) $this->calculTailleFichiersRepertoire($repertoireUtilisateur) + $this->calculTailleFichiersRepertoire($repertoireResultatsUtilisateur);// round(($this->calculTailleFichiersRepertoire($repertoireUtilisateur) + $this->calculTailleFichiersRepertoire($repertoireResultatsUtilisateur)) / 1000000000, 2);
            // Calcul du nombre de fichiers dans les répertoires de l'utilisateur
            $nombreTotalFichiersUtilisateur = count(array_diff(scandir($repertoireUtilisateur), array('.', '..', 'resultats'))) + count(array_diff(scandir($repertoireResultatsUtilisateur), array('.', '..')));
            // On récupère le nombre de traitements qu'a effectué l'utilisateur à la date actuelle
            $traitements = (int) $requetes->recupererTraitementsUtilisateur($_SESSION['identifiant']);
            // Vérification des conditions pour faire un traitement OCR ou non (a dépassé son nombre de traitements quotidien OU a dépassé le nombre de fichiers stockés)
            $desactiverBoutonAjoutFichier = $desactiverBoutonOCR = false;
            if (($nombreTotalFichiersUtilisateur >= (int) $limites['LDOC'])
                || ($volumeTotalFichiersUtilisateur >= ((float) $limites['LSTOCK'] * 1000000000))) {
                $desactiverBoutonAjoutFichier = true;
                $desactiverBoutonOCR = true;
            }
            if ($traitements >= (int) $limites['LOCR']) {
                $desactiverBoutonOCR = true;
            }
            // On prépare un tableau formaté pour la classe de Texte dédiée
            $tableau = [
                'desactiverBoutonAjoutFichier' => $desactiverBoutonAjoutFichier,
                'desactiverBoutonOCR' => $desactiverBoutonOCR,
                'fichiers' =>
                    [
                        'max' => $limites['LDOC'],
                        'pourcentage' => $this->calculsPourcentageFichiers($limites),
                        'actuel' => $nombreTotalFichiersUtilisateur,
                    ],
                'stockage' =>
                [
                    'max' => $limites['LSTOCK'],
                    'pourcentage' => $this->calculsPourcentageStockage($limites),
                    'actuel' => $volumeTotalFichiersUtilisateur,
                ],
                'traitements' =>
                [
                    'max' => $limites['LOCR'],
                    'pourcentage' => $this->calculsPourcentageTraitements($limites, $traitements),
                    'actuel' => $traitements,
                ],
                'type' => $limites['TYPE']
            ];
        }

        return $tableau;
    }

    /**
     * Gestion des erreurs pour prévenir l'utilisateur.
     * @param string $codeErreur
     * @return array
     */
    private function traitementErreur(string $codeErreur = ''): array
    {
        // Initialisation du tableau d'erreur a retourner
        $erreur = array();
        // En fonction du code d'erreur reçu en paramètre, on rempli le tableau dédié à l'affichage du bloc d'erreur
        switch ($codeErreur) {
            case 'nfsup':
                $erreur[$this->iClass] = 'fa-solid fa-trash-can fa-bounce';
                $erreur[$this->strong] = 'Erreur';
                $erreur[$this->small] = 'Suppression Fichier';
                $erreur[$this->message] = 'Aucun document n\'a été soumis pour la suppression!';
                break;
            case 'pfsup':
                $erreur[$this->iClass] = 'fa-solid fa-trash-can fa-bounce';
                $erreur[$this->strong] = 'Erreur';
                $erreur[$this->small] = 'Suppression Fichier';
                $erreur[$this->message] = 'Problème lors de la suppression du fichier! Veuillez réessayer dans quelques instants.';
                break;
            case 'arocr': // accès refusé ocr
                $erreur[$this->iClass] = 'fa-solid fa-bomb fa-shake fa-bounce';
                $erreur[$this->strong] = 'Erreur';
                $erreur[$this->small] = 'Traitement OCR refusé';
                $erreur[$this->message] = 'Vous avez dépassé votre quota quotidien de traitement OCR. Attendez demain ou modifier votre abonnement.';
                break;
            case 'ardoc': // accès refusé ajout de fichier
                $erreur[$this->iClass] = 'fa-solid fa-bomb fa-shake fa-bounce';
                $erreur[$this->strong] = 'Erreur';
                $erreur[$this->small] = 'Ajout de fichier refusé';
                $erreur[$this->message] = 'Vous avez dépassé votre quota de documents stockés. Veuillez en supprimer.';
                break;
            default:
                $erreur[$this->iClass] = 'fa-solid fa-bomb fa-shake';
                $erreur[$this->strong] = 'Erreur';
                $erreur[$this->small] = 'inconnue';
                $erreur[$this->message] = 'Une erreur est survenue!';
                break;
        }
        // On retourne le tableau d'erreur formaté
        return $erreur;
    }

    /**
     * Gestion des succès pour prévenir l'utilisateur.
     * @param string $codeSucces
     * @return array
     */
    private function traitementSucces(string $codeSucces = ''): array
    {
        // Initialisation du tableau de succès a retourner
        $succes = array();
        // En fonction du code de succès reçu en paramètre, on rempli le tableau dédié à l'affichage du bloc succès
        switch ($codeSucces) {
            case 'fsup':
                $succes[$this->iClass] = 'fa-solid fa-trash-can fa-bounce';
                $succes[$this->strong] = 'Succès';
                $succes[$this->small] = 'Suppression du fichier';
                $succes[$this->message] = 'Opération effectuée avec succès!';
                break;
            case 'tok':
                $succes[$this->iClass] = 'fa-solid fa-wand-magic-sparkles fa-shake';
                $succes[$this->strong] = 'Succès';
                $succes[$this->small] = 'Traitement OCR';
                $succes[$this->message] = 'Opération effectuée avec succès!';
                break;
            default:
                $succes[$this->iClass] = 'fa-solid fa-circle-check';
                $succes[$this->strong] = 'Succès';
                $succes[$this->small] = 'succès';
                $succes[$this->message] = 'Opération effectuée avec succès!';
                break;
        }
        // On retourne le tableau de succès formaté
        return $succes;
    }

    /**
     * Affichage de la page de connexion.
     */
    public function traitementRendu(RequetesTableauDeBord $requetes, String $codeErreur = '', String $codeSucces = ''): void
    {
        $limites = $this->traitementRequetes($requetes);
        $this->traitementFichiers();
        $this->traitementFichiersResultats();
        $data = $this->texte->texteFinal($limites);
        // Ajout des variables et valeurs utiles dans $data pour l'affichage du module
        $data['chemin'] = $this->config['variables']['chemin'];
        $data['tableauDeBord'] = true;
        $data['blocDemonstration'] = false;
        $data['utilisateur'] = $_SESSION['login'];
        // Si un codeErreur existe, on ajoute les donées au tableau Mustache pour afficher le blocErreur
        if ($codeErreur) {
            $data['blocErreur'] = $this->traitementErreur($codeErreur);
        }
        // Si un codeSucces existe, on ajoute les donées au tableau Mustache pour afficher le blocErreur
        if ($codeSucces) {
            $data['blocSucces'] = $this->traitementSucces($codeSucces);
        }
        // Envoi de la variable $data à la classe Mustache
        $this->render->actionRendu($data);
    }

}
