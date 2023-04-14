<?php
/**
 * Classe TraitementTableauDeBord
 */
class TraitementOCR
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
     * @var RequetesOCR requetes
     */
    private RequetesOCR $requetes;
    /**
     * @var TexteOCR texte
     */
    private TexteOCR $texte;
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
     * @param Logs $logs
     */
    public function __construct(Render $rendu, Logs $logs)
    {
        $this->render = $rendu;
        global $config;
        $this->config = $config;
        $this->texte = new TexteOCR($this->config);
        $this->requetes = new RequetesOCR($this->config, $logs);
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
            case '1':
                $erreur[$this->iClass] = 'fa-solid fa-file-signature';
                $erreur[$this->strong] = 'Erreur';
                $erreur[$this->small] = 'Formulaire';
                $erreur[$this->message] = 'Au moins un des champs du formulaire est vide ou bien incorrect!';
                break;
            case '2':
                $erreur[$this->iClass] = 'fa-solid fa-print fa-spin-pulse';
                $erreur[$this->strong] = 'Erreur';
                $erreur[$this->small] = 'Traitement OCR';
                $erreur[$this->message] = 'Un problème est survenu lors de l\'opération! Veuillez réessayer dans quelques instants.';
                break;
            default:
                $erreur[$this->iClass] = 'fa-solid fa-bomb';
                $erreur[$this->strong] = 'Erreur';
                $erreur[$this->small] = 'inconnue';
                $erreur[$this->message] = 'Une erreur est survenue!';
                break;
        }
        // On retourne le tableau d'erreur formaté
        return $erreur;
    }
    /**
     * On récupère L'ensemble des fichiers présents dans le répertoire de l'utilisateur connecté et on vérifie qu'ils soient en base pour les afficher sur l'interface.
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
        if(is_dir($repertoireUtilisateur))
        {
            // On récupère les noms de fichiers du répertoires
            $fichiers = array_diff(scandir($repertoireUtilisateur), array('.', '..'));
        }
        // Appel de la fonction qui va s'assurer que les fichiers soient bien présents en base avant de les afficher
        $this->traitementInformationsFichiersDepuisBDD($fichiers);
    }
    /**
     * On vérifie que les fichiers réçus en paramètres soient en base pour les afficher sur l'interface.
     * @param array $tableauFichiers
     * @return void
     */
    public function traitementInformationsFichiersDepuisBDD(array $tableauFichiers): void
    {
        // Initialisation de la liste des fichiers
        $listeFichiers = '';
        // Pour chaque fichier trouvé
        foreach ($tableauFichiers as $fichier) {
            // On ajoute le nom à la liste
            $listeFichiers .= '\'' . $fichier . '\', ';
        }
        // SI la liste n'est pas vide
        if($listeFichiers !== '') {
            $listeFichiers = substr($listeFichiers, 0, -2);
            $fichiers = $this->requetes->recupererIdentifiantsDocuments($_SESSION['identifiant'], $listeFichiers);
            // On affiche l'ensemble des fichiers sur la page du tableau de bord
            $this->texte->setFichiers($fichiers);
        }
    }
    /**
     * Gestion des succès pour prévenir l'utilisateur.
     * @param string $codeSucces
     * @return array
     */
    private function traitementSucces(string $codeSucces = ''): array
    {
        // Initialisation du tableau de succès a retourner
        $erreur = array();
        // En fonction du code de succès reçu en paramètre, on rempli le tableau dédié à l'affichage du bloc succès
        switch ($codeSucces) {
            case 'tok':
                $erreur[$this->iClass] = 'fa-solid fa-wand-magic-sparkles';
                $erreur[$this->strong] = 'Succès';
                $erreur[$this->small] = 'Extraction Texte';
                $erreur[$this->message] = 'Opération effectuée avec succès!';
                break;
            default:
                $erreur[$this->iClass] = 'fa-solid fa-circle-check';
                $erreur[$this->strong] = 'Succès';
                $erreur[$this->small] = 'succès';
                $erreur[$this->message] = 'Opération effectuée avec succès!';
                break;
        }
        // On retourne le tableau de succès formaté
        return $erreur;
    }
    /**
     * Affichage de la page de traitement OCR.
     */
    public function traitementRendu(String $codeErreur = '', String $codeSucces = ''): void
    {
        if ((int) $this->requetes->recupererNbTraitementOCR($_SESSION['identifiant']) >= (int) $this->requetes->recupererLimiteTraitementOCR($_SESSION['identifiant'])) {
            header('Location: ../tableauDeBord/?erreur=arocr');
            exit();
        }
        $this->traitementFichiers();
        $data = $this->texte->texteFinal();
        // Ajout des variables et valeurs utiles dans $data pour l'affichage du module
        $data['chemin'] = $this->config['variables']['chemin'];
        $data['ocr'] = true;
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
