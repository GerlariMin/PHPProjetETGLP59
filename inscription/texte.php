<?php

    /**
     * Classe TexteInscription
     * Contient l'ensemble du texte à afficher pour la page d'inscription.
     */
    class TexteInscription
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
                    $this->button_i_class => "fas fa-paper-plane",
                    $this->button_text => "Envoyer",
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
                [ 0 =>
                    [
                        $this->div_class => "form-floating mb-3",
                        $this->input_class => "form-control",
                        $this->input_id => "nom",
                        $this->input_name => "nom",
                        $this->input_placeholder => "Nom",
                        $this->input_required => "true",
                        $this->input_type => "text",
                        $this->label_for => "Nom",
                        $this->label_i_class => "fa-solid fa-user",
                        $this->label_text => "Nom"
                    ],

                  1 => 
                    [
                        $this->div_class => "form-floating mb-3",
                        $this->input_class => "form-control",
                        $this->input_id => "prenom",
                        $this->input_name => "prenom",
                        $this->input_placeholder => "Prenom",
                        $this->input_required => "true",
                        $this->input_type => "text",
                        $this->label_for => "Prenom",
                        $this->label_i_class => "fa-solid fa-user",
                        $this->label_text => "Prenom"
                    ],

                  2 => 
                    [
                        $this->div_class => "form-floating mb-3",
                        $this->input_class => "form-control",
                        $this->input_id => "username",
                        $this->input_name => "username",
                        $this->input_placeholder => "Nom d'utilisateur",
                        $this->input_required => "true",
                        $this->input_type => "text",
                        $this->label_for => "username",
                        $this->label_i_class => "fa-solid fa-user",
                        $this->label_text => "Nom d'utilisateur"
                    ],

                  3 => 
                    [
                        $this->div_class => "form-floating mb-3",
                        $this->input_class => "form-control",
                        $this->input_id => "email",
                        $this->input_name => "email",
                        $this->input_placeholder => "Adresse Email",
                        $this->input_required => "true",
                        $this->input_type => "email",
                        $this->label_for => "Adresse Email",
                        $this->label_i_class => "fa-solid fa-at",
                        $this->label_text => "Adresse Email"
                    ],

                  4 => 
                    [
                        $this->div_class => "form-floating mb-3",
                        $this->input_class => "form-control",
                        $this->input_id => "password",
                        $this->input_name => "password",
                        $this->input_placeholder => "Mot de passe",
                        $this->input_required => "true",
                        $this->input_type => "password",
                        $this->label_for => "Mot de passe",
                        $this->label_i_class => "fa-solid fa-lock",
                        $this->label_text => "Mot de passe"
                    ],

                  5 => 
                    [
                        $this->div_class => "form-floating mb-3",
                        $this->input_class => "form-control",
                        $this->input_id => "confirm",
                        $this->input_name => "confirm",
                        $this->input_placeholder => "Confirmation",
                        $this->input_required => "true",
                        $this->input_type => "password",
                        $this->label_for => "Confirmation",
                        $this->label_i_class => "fa-solid fa-lock",
                        $this->label_text => "Confirmation"
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
            return
                [
                    $this->form_action => "action.php",
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