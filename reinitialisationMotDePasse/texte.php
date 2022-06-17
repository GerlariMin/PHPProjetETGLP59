<?php

    /**
     * Classe TexteReinitialisationMotDePasse
     * Contient l'ensemble du texte à afficher pour la page de MotDePasseOublie.
     */
    class TexteReinitialisationMotDePasse
    {
        /**
         * Variables correspondant aux balises Mustache de la page.
         */

        /**
         * @var array
         */
        private array $config;
        private String $button_class = "button_class";
        private String $button_i_class = "button_i_class";
        private String $button_text = "button_text";
        private String $button_type = "button_type";
        /**
         * @var String div_class
         */
        private String $div_class = "div_class";
        private String $form_action = "form_action";
        private String $form_method = "form_method";
        /**
         * @var string input_class
         */
        private String $input_class = "input_class";
        /**
         * @var string input_id
         */
        private String $input_id = "input_id";
        /**
         * @var string input_name
         */
        private String $input_name = "input_name";
        /**
         * @var string input_placeholder
         */
        private String $input_placeholder = "input_placeholder";
        /**
         * @var string input_required
         */
        private String $input_required = "input_required";
        /**
         * @var string input_type
         */
        private String $input_type = "input_type";
        /**
         * @var string label_for
         */
        private String $label_for = "label_for";
        /**
         * @var string label_i_class
         */
        private String $label_i_class = "label_i_class";
        /**
         * @var string label_text
         */
        private String $label_text = "label_text";

        public function __construct(array $config)
        {
            $this->config = $config;
        }

        private function texteButtons(): array
        {
            return
                [
                    $this->button_class => "btn btn-outline-success",
                    $this->button_i_class => "fas fa-door-open",
                    $this->button_text => "Confirmer",
                    $this->button_type => "submit"
                ];
        }

        /**
         * Fonction texteLignes qui retourne un tableau formaté pour les différentes divs du formulaire de la page de connexion.
         *
         * @return array[]
         */
        private function texteDivs(): array
        {
            return
                [
                0 =>
                    [
                        $this->div_class => "form-floating mb-3",
                        $this->input_class => "form-control",
                        $this->input_id => "input1",
                        $this->input_name => "nouveauMotDePasse",
                        $this->input_placeholder => "Nouveau mot de passe",
                        $this->input_required => "true",
                        $this->input_type => "password",
                        $this->label_for => "input1",
                        $this->label_i_class => "fa-solid fa-lock",
                        $this->label_text => "Nouveau mot de passe"
                    ],
                1 =>
                    [
                        $this->div_class => "form-floating mb-3",
                        $this->input_class => "form-control",
                        $this->input_id => "input2",
                        $this->input_name => "confirmationMotDePasse",
                        $this->input_placeholder => "Confirmation du nouveau mot de passe",
                        $this->input_required => "true",
                        $this->input_type => "password",
                        $this->label_for => "input2",
                        $this->label_i_class => "fa-solid fa-shield-halved",
                        $this->label_text => "Confirmation nouveau mot de passe"
                    ]
                ];
        }

        /**
         * Retourn le tableau formaté pour les différents attributs de la balise <form>
         *
         * @return string[]
         */
        private function texteForm(): array
        {
            if(isset($_GET['token']))
            {
                $form_action = "action.php?token=" . $_GET['token'];
            }else{
                $form_action = "action.php";
            }
            return
                [
                    $this->form_action => $form_action,
                    $this->form_method => "POST"
                ];
        }

        /**
         * Retourne le tableau formaté final utilisé pour générer le rendu intégral.
         *
         * @return array
         */
        public function texteFinal():array
        {
            return
                [
                    "form" => $this->texteForm(),
                    "div" => $this->texteDivs(),
                    "button" => $this->texteButtons()
                ];
        }

    }