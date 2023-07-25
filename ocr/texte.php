<?php

/**
 * Classe TexteOCR
 * Contient l'ensemble du texte à afficher pour la page du tableau de bord.
 */
class TexteOCR
{
    /**
     * Variables correspondant aux balises Mustache de la page.
     */

    /**
     * @var array
     */
    private array $config;

    private array $fichiers = array();

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param array $fichiers
     */
    public function setFichiers(array $fichiers): void
    {
        $this->fichiers = $fichiers;
    }

    private function texteForm(): array
    {
        return
        [
            'action' => 'action.php',
            'documents' => $this->texteFichiers(),
            'extraction' => $this->texteExtraction(),
            'method' => 'post',
            'submit' => $this->texteSubmitButton(),
        ];
    }

    private function texteExtraction()
    {
        return [
                    'ariaLabel' => 'Type de traitement OCR',
                    'class' => 'form-select',
                    'id' => 'traitement',
                    'label' => [
                        'class' => 'input-group-text',
                        'fontawesome' => 'fa-solid fa-gears',
                        'for' => 'traitement',
                        'text' => 'Extraction',
                    ],
                    'multiple' => false,
                    'name' => 'traitement',
                    'optiondefaut' => [
                        'disabled' => true,
                        'selected' => true,
                        'texte' => 'Sélectionnez le type de traitement à effectuer',
                    ],
                    'options' =>
                        [
                            0 => [
                                'value' => 1,
                                'text' => 'Extraction du texte du fichier',
                            ],
                            1 => [
                                'value' => 2,
                                'text' => 'Extraction d\'image(s) du fichier',
                            ],
                            2 => [
                                'value' => 3,
                                'text' => 'Extraction d\'image(s) et du texte du fichier (sans mise en forme)',
                            ],
                            3 => [
                                'value' => 4,
                                'text' => 'Extraction du texte manuscrit de l\'image',
                            ],
                            4 => [
                                'value' => 'CNI',
                                'text' => 'Extraction des données d\'une CNI',
                            ],
                        ],
                    'required' => true,
        ];
    }

    private function texteFichiers()
    {
        return [
            'input' => [
                'autocomplete' => 'off',
                'class' => 'btn-check',
                'id' => 'fichierOCR',
                'name' => 'fichierOCR[]',
                'type' => 'checkbox',
            ],
            'fichiers' => $this->fichiers,
            'fontawesomeFichierCourant' => 'fa-regular fa-file',
            'label' => [
                'class' => 'btn btn-outline-secondary',
                'for' => 'fichierOCR',
                'fontawesome' => 'fa-regular fa-circle-check',
                'label' => 'Choisir ce fichier',
            ],
            'nombreFichier' => count($this->fichiers),
            'titre' => [
                'fontawesome' => 'fa-solid fa-file-arrow-up',
                'texte' => 'Fichier(s) disponible(s) pour extraction',
            ],
        ];
    }

    private function texteSubmitButton(): array
    {
        return
        [
            'class' => 'btn btn-outline-secondary',
            'type' => 'submit',
            'fontawesome' => 'fa-solid fa-wand-magic-sparkles fa-beat',
            'texte' => 'Extraire le texte',
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
                "repertoire" => true,
                "form" => $this->texteForm(),
            ];
    }

}