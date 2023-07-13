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
        private String $buttonClass = "button_class";
        private String $buttonIClass = "button_i_class";
        private String $buttonText = "button_text";
        private String $buttonType = "button_type";
        /**
         * @var String div_class
         */
        private String $divClass = "div_class";
        private String $formAction = "form_action";
        private String $formMethod = "form_method";
        /**
         * @var string input_class
         */
        private String $inputClass = "input_class";
        /**
         * @var string input_id
         */
        private String $inputId = "input_id";
        /**
         * @var string input_name
         */
        private String $inputName = "input_name";
        /**
         * @var string input_placeholder
         */
        private String $inputPlaceholder = "input_placeholder";
        /**
         * @var string input_required
         */
        private String $inputRequired = "input_required";
        /**
         * @var string input_type
         */
        private String $inputType = "input_type";
        /**
         * @var string label_for
         */
        private String $labelFor = "label_for";
        /**
         * @var string label_i_class
         */
        private String $labelIClass = "label_i_class";
        /**
         * @var string label_text
         */
        private String $labelText = "label_text";

        /**
         * @var String onchange
         */
        private String $onChange = "onChange";

        /**
         * @var String pattern
         */
        private String $pattern = "pattern";

        /**
         * @var String onBlur
         */
        private String $onBlur = "onBlur";



        public function __construct(array $config)
        {
            $this->config = $config;
        }

        private function texteButtons(): array
        {
            return
                [
                    $this->buttonClass => "btn btn-outline-success",
                    $this->buttonIClass => "fas fa-user-plus",
                    $this->buttonText => "S'inscrire",
                    $this->buttonType => "submit"
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
                        $this->divClass => "form-floating mb-3",
                        $this->inputClass => "form-control",
                        $this->inputId => "nom",
                        $this->inputName => "nom",
                        $this->inputPlaceholder => "Nom",
                        $this->inputRequired => "true",
                        $this->inputType => "text",
                        $this->labelFor => "Nom",
                        $this->labelIClass => "fa-solid fa-user",
                        $this->labelText => "Nom",
                    ],

                  1 => 
                    [
                        $this->divClass => "form-floating mb-3",
                        $this->inputClass => "form-control",
                        $this->inputId => "prenom",
                        $this->inputName => "prenom",
                        $this->inputPlaceholder => "Prenom",
                        $this->inputRequired => "true",
                        $this->inputType => "text",
                        $this->labelFor => "Prenom",
                        $this->labelIClass => "fa-solid fa-user",
                        $this->labelText => "Prenom"
                    ],

                  2 => 
                    [
                        $this->divClass => "form-floating mb-3",
                        $this->inputClass => "form-control",
                        $this->inputId => "username",
                        $this->inputName => "username",
                        $this->inputPlaceholder => "Nom d'utilisateur",
                        $this->inputRequired => "true",
                        $this->inputType => "text",
                        $this->labelFor => "username",
                        $this->labelIClass => "fa-solid fa-user",
                        $this->labelText => "Nom d'utilisateur"
                        
                    ],

                  3 => 
                    [
                        $this->divClass => "form-floating mb-3",
                        $this->inputClass => "form-control",
                        $this->inputId => "email",
                        $this->inputName => "email",
                        $this->inputPlaceholder => "Adresse Email",
                        $this->inputRequired => "true",
                        $this->inputType => "email",
                        $this->labelFor => "Adresse Email",
                        $this->labelIClass => "fa-solid fa-at",
                        $this->labelText => "Adresse Email",
                    ],

                  4 => 
                    [
                        $this->divClass => "form-floating mb-3",
                        $this->inputClass => "form-control",
                        $this->inputId => "password",
                        $this->inputName => "password",
                        $this->inputPlaceholder => "Mot de passe",
                        $this->inputRequired => "true",
                        $this->inputType => "password",
                        $this->labelFor => "Mot de passe",
                        $this->labelIClass => "fa-solid fa-lock",
                        $this->labelText => "Mot de passe",
                        $this->pattern => "(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}",
                        'aide' => [
                            'aideId' => 'aideMDP',
                            'aideClass' => 'form-text text-warning fw-bolder',
                            'aideFontawesome' => 'fa-solid fa-circle-info',
                            'aideText' => 'Le mot de passe doit contenir au moins 8 caractères avec un chiffre, un caratère spécial et une majuscule au minimum.',
                        ]
                    ],

                  5 => 
                    [
                        $this->divClass => "form-floating mb-3",
                        $this->inputClass => "form-control",
                        $this->inputId => "confirm",
                        $this->inputName => "confirm",
                        $this->inputPlaceholder => "Confirmation",
                        $this->inputRequired => "true",
                        $this->inputType => "password",
                        $this->labelFor => "confirm",
                        $this->labelIClass => "fa-solid fa-lock",
                        $this->labelText => "Confirmer mot de passe",
                        $this->pattern => "(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}",
                        'aide' => [
                            'aideId' => 'aideMDP',
                            'aideClass' => 'form-text text-warning fw-bolder',
                            'aideFontawesome' => 'fa-solid fa-circle-info',
                            'aideText' => 'Le mot de passe doit contenir au moins 8 caractères avec un chiffre, un caratère spécial et une majuscule au minimum.',
                        ]
                    ],
                    6 =>
                        [
                            $this->divClass => "form-check form-switch mb-3",
                            $this->inputClass => "form-check-input",
                            $this->inputId => "cgu",
                            $this->inputName => "cgu",
                            $this->inputPlaceholder => "CGU",
                            $this->inputRequired => "true",
                            'inputRole' => 'switch',
                            $this->inputType => "checkbox",
                            'labelClass' => 'form-check-label',
                            $this->labelFor => "cgu",
                            $this->labelIClass => "fa-solid fa-scale-balanced fa-bounce",
                            $this->labelText => "En vous inscrivant, vous acceptez ",
                            'labelHTML' => '<a class="text-decoration-none text-info fw-bolder" href="../cgu/" target="_blank" rel="noopener" title="CGU">les Conditions Générales d\'Utilisation (CGU) suivantes.</a>',
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
                    $this->formAction => "action.php",
                    $this->formMethod => "POST"
                ];
        }

        private function texteRedirections(): array
        {
            return
                [
                    0 =>
                        [
                            'divClass' => 'col',
                            'pText' => 'Vous avez déjà un compte ?',
                            'anchor' =>
                                [
                                    'HREF' => '../connexion/',
                                    'CLASS' => 'text-decoration-none text-success fw-bolder',
                                    'TEXT' => 'Connectez-vous ici',
                                    'FONTAWESOME' => 'fa-solid fa-up-right-from-square'
                                ]
                        ]
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
                    "button" => $this->texteButtons(),
                    'redirections' => $this->texteRedirections(),
                ];
        }

    }