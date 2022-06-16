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
        $repertoires = str_split($_SESSION['identifiant'], 5);
        $repertoireUtilisateur = $this->config['variables']['repertoires']['utilisateurs'] . $repertoires[0] . '/' . $repertoires[1] . '/';
        $fichiers = array();
        if(is_dir($repertoireUtilisateur))
        {
            if($iteration = opendir($repertoireUtilisateur))
            {
                while(($fichier = readdir($iteration)) !== false)
                {
                    if($fichier != "." && $fichier != ".." && $fichier != "Thumbs.db")
                    {
                        $fichiers[] =
                            [
                                'href' => $repertoireUtilisateur . $fichier,
                                'text' => $fichier,
                                'taille' => filesize($repertoireUtilisateur . $fichier)
                            ];
                    }
                }
                closedir($iteration);
            }
        }
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