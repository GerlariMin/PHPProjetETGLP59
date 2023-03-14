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
            'method' => 'post',
            'select' => $this->texteSelect(),
            'submit' => $this->texteSubmitButton(),
        ];
    }

    private function texteSelect(): array
    {
        return
        [
            'ariaLabel' => 'Sélection du fichier à soummettre au traitement OCR',
            'class' => 'form-select',
            'multiple' => false,
            'name' => 'fichierOCR[]',
            'optiondefaut' => [
                'disabled' => true,
                'selected' => true,
                'texte' => 'Sélectionnez un fichier',
            ],
            'options' => $this->fichiers,
        ];
    }

    private function texteSubmitButton(): array
    {
        return
        [
            'class' => 'btn btn-outline-secondary',
            'type' => 'submit',
            'fontawesome' => 'fa-solid fa-wand-magic-sparkles',
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
                //"fichiers" => $this->fichiers,
                "form" => $this->texteForm(),
            ];
    }

}