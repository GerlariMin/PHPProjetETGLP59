<?php

    /**
     * Classe TexteConnexion
     * Contient l'ensemble du texte à afficher pour la page de connexion.
     */
    class TexteConnexion
    {
        /**
         * Variables correspondant aux balises Mustache de la page.
         */

        /**
         * @var array
         */
        private array $config;

         /**
         * @var String Button
         */
        private String $button_class = "btn btn-outline-primary btn-lg btn-block";

        /**
         * @var String div_class
         */
        private String $div_h1_class = "my-0 font-weight-normal";
        private String $div_small_class = "text-muted";
        private String $div_ul_class = "list-unstyled mt-3 mb-4";
       
        public function __construct(array $config)
        {
            $this->config = $config;
        }

        private function texteButtons(): array
        {
            return
                [
                    $this->button_class => "btn btn-outline-primary btn-lg btn-block"
                ];
        }

        /**
         * Fonction texteLignes qui retourne un tableau formaté pour les différentes divs du formulaire de la page de connexion.
         *
         * @return array[]
         */
        private function cardDiv(): array
        {
            return
                [
                    0 =>
                        [
                            $this->div_h1_class => "my-0 font-weight-normal",
                            $this->div_small_class => "text-muted",
                            $this->div_ul_class => "list-unstyled mt-3 mb-4"
                        ]
                ];
        }

        /**
         * Retourn le tableau formaté pour les différents attributs de la balise <form>
         *
         * @return string[]
         */
       

        /**
         * Retourne le tableau formaté final utilisé pour générer le rendu intégral.
         *
         * @return array
         */
        public function texteFinal(): array
        {
            return
                [
                    "div" => $this->cardDiv(),
                    "button" => $this->texteButtons()
                ];
        }

    }