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
        $repertoireUtilisateur = $config['variables']['repertoires']['utilisateurs'] . $repertoires[0] . '/' . $repertoires[1] . '/';
        if(is_dir($repertoireUtilisateur))
        {
            if($iteration = opendir($repertoireUtilisateur))
            {
                while(($fichier = readdir($iteration)) !== false)
                {
                    if($fichier != "." && $fichier != ".." && $fichier != "Thumbs.db")
                    {
                        echo '<a href="' . $repertoireUtilisateur . $fichier . '" target="_blank" >' . $fichier . ' ' . filesize($repertoireUtilisateur . $fichier) . '</a><br />'."\n";
                    }
                }
                closedir($iteration);
            }
        }
    }

    /**
     * Affichage de la page de connexion.
     */
    public function traitementRendu($codeErreur = ''): void
    {
        $data = $this->texte->texteFinal();

        $data['chemin'] = $this->config['variables']['chemin'];
        $data['tableau-de-bord'] = true;
        $data['utilisateur'] = $_SESSION['login'];

        $this->render->actionRendu($data);
    }

}