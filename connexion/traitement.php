<?php

    /**
     * Classe TraitementConnexion
     */
    class TraitementConnexion
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
         * @var TexteConnexion texte
         */
        private TexteConnexion $texte;

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
            $this->texte = new TexteConnexion($this->config);
        }

        /**
         * Affichage de la page de connexion.
         */
        public function traitementRendu(): void
        {
            $data = $this->texte->texteFinal();

            $data['chemin'] = $this->config['variables']['chemin'];
            $data['connexion'] = true;

            $this->render->actionRendu($data);
        }

    }