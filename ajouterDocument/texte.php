<?php
    /**
     * Classe TexteAjouterDocument
     * Contient l'ensemble du texte à afficher pour la page de connexion.
     */
    class TexteAjouterDocument
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
         * @param array $config
         */
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
                    $this->buttonIClass => 'fa-solid fa-file-arrow-down',
                    $this->buttonText => 'Déposer le(s) document(s)',
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
                            $this->divClass => 'mb-3',
                            'inputAccept' => '.pdf, .docx, .txt, .jpeg, jpg, .png',
                            $this->inputClass => 'form-control',
                            $this->inputId => 'input1',
                            'inputMultiple' => true,
                            $this->inputName => 'documents[]',
                            $this->inputPlaceholder => 'Document(s) à télécharger',
                            $this->inputRequired => true,
                            $this->inputType => 'file',
                            'labelClass' => 'mb-2',
                            $this->labelFor => 'input1',
                            $this->labelIClass => 'fa-solid fa-file',
                            $this->labelText => 'Document(s) à télécharger'
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
                ];
        }
    }