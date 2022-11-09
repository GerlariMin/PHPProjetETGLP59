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

    public function traitementFichiers(): void
    {
        // On récupère le répertoire et sous répertoire dans lesquels les fichiers de l'utilisateur connecté sont stockés
        $repertoires = str_split($_SESSION['identifiant'], 5);
        // On récupère le chemin complet de l'endroit où sont stockés les fichiers de l'utilisateur connecté
        $repertoireUtilisateur = $this->config['variables']['repertoires']['utilisateurs'] . $repertoires[0] . '/' . $repertoires[1] . '/';
        // Tableau qui contiendra l'ensemble des fichiers compris dans ce répertoire
        $fichiers = array();
        // Si il s'agit bien d'un répertoire
        if (is_dir($repertoireUtilisateur))
        {
            // Accès au répertoire
            if ($iteration = opendir($repertoireUtilisateur))
            {
                // On parcourt chaque fichier du répertoire
                while (($fichier = readdir($iteration)) !== false)
                {
                    // On trie les fichiers correspondants aux répertoires ou autres fichiers non liés au site
                    if ($fichier !== "Thumbs.db" && !is_dir($repertoireUtilisateur.$fichier))
                    {
                        if(str_contains($fichier, '.jpg') || str_contains($fichier, '.jpeg')) {
                            $type = 1;
                        } else if(str_contains($fichier, '.png')) {
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
        }
        // On affiche l'ensemble des fichiers sur la page du tableau de bord
        $this->texte->setFichiers($fichiers);
    }

    private function traitementFichiersResultats()
    {
        // On récupère le répertoire et sous répertoire dans lesquels les fichiers de l'utilisateur connecté sont stockés
        $repertoires = str_split($_SESSION['identifiant'], 5);
        // On récupère le chemin complet de l'endroit où sont stockés les fichiers de l'utilisateur connecté
        $repertoireUtilisateur = $this->config['variables']['repertoires']['utilisateurs'] . $repertoires[0] . '/' . $repertoires[1] . '/resultats/';
        // Tableau qui contiendra l'ensemble des fichiers compris dans ce répertoire
        $fichiers = array();
        // Si il s'agit bien d'un répertoire
        if (is_dir($repertoireUtilisateur))
        {
            // Accès au répertoire
            if ($iteration = opendir($repertoireUtilisateur))
            {
                // On parcourt chaque fichier du répertoire
                while (($fichier = readdir($iteration)) !== false)
                {
                    // On trie les fichiers correspondants aux répertoires ou autres fichiers non liés au site
                    if ($fichier !== "Thumbs.db" && !is_dir($repertoireUtilisateur.$fichier))
                    {
                        $fichiers[] =
                            [
                                'href' => $repertoireUtilisateur . $fichier, // Lien d'accès au fichier
                                'text' => $fichier, // Nom du fichier
                                'taille' => filesize($repertoireUtilisateur . $fichier) // Taille du fichier
                            ];
                    }
                }
                // On ferme l'accès au répertoire
                closedir($iteration);
            }
        }
        $this->texte->setFichiersResultats($fichiers);
    }

    /**
     * Affichage de la page de connexion.
     */
    public function traitementRendu($codeErreur = ''): void
    {
        $this->traitementFichiers();
        $this->traitementFichiersResultats();
        $data = $this->texte->texteFinal();
        // Ajout des variables et valeurs utiles dans $data pour l'affichage du module
        $data['chemin'] = $this->config['variables']['chemin'];
        $data['tableauDeBord'] = true;
        $data['blocDemonstration'] = true;
        $data['utilisateur'] = $_SESSION['login'];
        // Envoi de la variable $data à la classe Mustache
        $this->render->actionRendu($data);
    }

}
