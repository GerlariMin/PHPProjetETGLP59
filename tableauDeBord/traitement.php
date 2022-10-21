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
        if(is_dir($repertoireUtilisateur))
        {
            // Accès au répertoire
            if($iteration = opendir($repertoireUtilisateur))
            {
                // On parcourt chaque fichier du répertoire
                while(($fichier = readdir($iteration)) !== false)
                {
                    // On trie les fichiers correspondants aux répertoires parents ou autres fichiers non liées au site
                    if($fichier !== "." && $fichier !== ".." && $fichier !== "Thumbs.db")
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
        // On affiche l'ensemble des fichiers sur la page du tableau de bord
        $this->texte->setFichiers($fichiers);
    }

    /**
     * Affichage de la page de connexion.
     */
    public function traitementRendu($codeErreur = ''): void
    {
        $this->traitementFichiers();
        $data = $this->texte->texteFinal();

        $data['chemin'] = $this->config['variables']['chemin'];
        $data['tableauDeBord'] = true;
        $data['blocDemonstration'] = true;
        $data['utilisateur'] = $_SESSION['login'];

        $this->render->actionRendu($data);
    }

}
