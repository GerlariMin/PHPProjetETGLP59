<?php

/**
 * Classe TraitementConditionsGeneralesUtilisation
 */
class TraitementConditionsGeneralesUtilisation
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
     * @var TexteConditionsGeneralesUtilisation texte
     */
    private TexteConditionsGeneralesUtilisation $texte;

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
        $this->texte = new TexteConditionsGeneralesUtilisation($this->config);
    }

    /**
     * Affichage de la page cgu.
     */
    public function traitementRendu($codeErreur = ''): void
    {
        $data = $this->texte->texteFinal();

        $data['chemin'] = $this->config['variables']['chemin'];
        $data['cgu'] = true;

        $this->render->actionRendu($data);
    }

}