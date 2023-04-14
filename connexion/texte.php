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
         * @var string dédiées au bouton de validation
         */
        private String $buttonClass = 'buttonClass';
        private String $buttonIClass = 'buttonIClass';
        private String $buttonText = 'buttonText';
        private String $buttonType = 'buttonType';
        /**
         * @var string dédiées au paramétrage du formulaire
         */
        private String $formAction = 'formAction';
        private String $formMethod = 'formMethod';
        /**
         * @var string dédiées au paramétrage des inputs du formulaire
         */
        private String $divClass = 'divClass';
        private String $inputClass = 'inputClass';
        private String $inputId = 'inputId';
        private String $inputName = 'inputName';
        private String $inputPlaceholder = 'inputPlaceholder';
        private String $inputRequired = 'inputRequired';
        private String $inputType = 'inputType';
        /**
         * @var string dédiées aux labels liés aux inputs du formulaire
         */
        private String $labelFor = 'labelFor';
        private String $labelIClass = 'labelIClass';
        private String $labelText = 'labelText';
        /**
         * @param string dédiées aux redirections
         */
        private String $col = 'col';
        private String $pText = 'pText';
        private String $anchor = 'anchor';
        private String $anchorHREF = 'HREF';
        private String $anchorCLASS = 'CLASS';
        private String $anchorTEXT = 'TEXT';
        private String $anchorFONTAWESOME = 'FONTAWESOME';

        public function __construct(array $config)
        {
            $this->config = $config;
        }

        /**
         * @return string[]
         */
        private function texteButtons(): array
        {
            return
                [
                    $this->buttonClass => 'btn btn-outline-success',
                    $this->buttonIClass => 'fas fa-door-open',
                    $this->buttonText => 'Connexion',
                    $this->buttonType => 'submit'
                ];
        }

        /**
         * Retourne le tableau formaté pour les différents attributs de la balise <form>
         *
         * @return string[]
         */
        private function texteForm(): array
        {
            return
                [
                    $this->formAction => 'action.php',
                    $this->formMethod => 'POST'
                ];
        }

        /**
         * Fonction texteLignes qui retourne un tableau formaté pour les différentes divs du formulaire de la page de connexion.
         *
         * @return array[]
         */
        private function texteInputs(): array
        {
            return
                [
                    0 =>
                        [
                            $this->divClass => 'form-floating mb-3',
                            $this->inputClass => 'form-control',
                            $this->inputId => 'input1',
                            $this->inputName => 'login',
                            $this->inputPlaceholder => 'E-mail ou Login',
                            $this->inputRequired => true,
                            $this->inputType => 'text',
                            $this->labelFor => 'input1',
                            $this->labelIClass => 'fa-solid fa-user',
                            $this->labelText => 'E-mail ou Login'
                        ],
                    1 =>
                        [
                            $this->divClass => 'form-floating mb-3',
                            $this->inputClass => 'form-control',
                            $this->inputId => 'input2',
                            $this->inputName => 'password',
                            $this->inputPlaceholder => 'Mot de passe utilisateur',
                            $this->inputRequired => true,
                            $this->inputType => 'password',
                            $this->labelFor => 'input2',
                            $this->labelIClass => 'fas fa-key',
                            $this->labelText => 'Mot de passe'
                        ]
                ];
        }

        private function texteRedirections(): array
        {
            return
                [
                    0 =>
                        [
                            $this->divClass => $this->col,
                            $this->pText => 'Mot de passe oublié ?',
                            $this->anchor =>
                                [
                                    $this->anchorHREF => '../motDePasseOublie/',
                                    $this->anchorCLASS => 'text-decoration-none text-warning fw-bolder',
                                    $this->anchorTEXT => 'Remplacez-le ici',
                                    $this->anchorFONTAWESOME => 'fa-solid fa-up-right-from-square'
                                ]
                        ],
                    1 =>
                        [
                            $this->divClass => $this->col,
                            $this->pText => 'Vous n\'avez pas encore de compte ?',
                            $this->anchor =>
                                [
                                    $this->anchorHREF => '../inscription/',
                                    $this->anchorCLASS => 'text-decoration-none text-success fw-bolder',
                                    $this->anchorTEXT => 'Inscrivez-vous ici',
                                    $this->anchorFONTAWESOME => 'fa-solid fa-up-right-from-square'
                                ]
                        ]
                ];
        }

        /**
         * Retourne le tableau formaté final utilisé pour générer le rendu intégral.
         *
         * @return array
         */
        public function texteFinal(): array
        {
            return
                [
                    'form' => $this->texteForm(),
                    'inputs' => $this->texteInputs(),
                    'button' => $this->texteButtons(),
                    'redirections' => $this->texteRedirections()
                ];
        }

    }