<?php

/**
 * Classe TexteTableauDeBord
 * Contient l'ensemble du texte à afficher pour la page du tableau de bord.
 */
class TexteTableauDeBord
{
    /**
     * Variables correspondant aux balises Mustache de la page.
     */

    /**
     * @var array
     */
    private array $config;

    private array $fichiers = array();
    private array $fichiersResultats = array();

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

    /**
     * @param array $fichiers
     */
    public function setFichiersResultats(array $fichiersResultats): void
    {
        $this->fichiersResultats = $fichiersResultats;
    }

    /**
     * Fonction texteLignes qui retourne un tableau formaté pour les différentes divs statistiques de la page du tableau de bord.
     *
     * @return array[]
     */
    private function texteStatistiques(): array
    {
        return [
            0 => [
                  'cardTitleClass' => 'card-title',
                  'cardTitleFontAwesome' => 'fa-solid fa-floppy-disk',
                  'cardTitleText' => 'Espace disque utilisé (Max: 1,00 Go)',
                  'cardTextClass' => 'card-text progress mb-3',
                  'progressbarClass' => 'progress-bar bg-success',
                  'progressbarRole' => 'progressbar',
                  'progressbarPourcentage' => '25',
                  'progressbarMin' => '0',
                  'progressbarMax' => '1024',
                  'progressbarText' => '256 Mo'
            ],
            1 => [
                'cardTitleClass' => 'card-title',
                'cardTitleFontAwesome' => 'fa-regular fa-file',
                'cardTitleText' => 'Fichiers stockés (Max: 10 fichiers)',
                'cardTextClass' => 'card-text progress mb-3',
                'progressbarClass' => 'progress-bar bg-warning',
                'progressbarRole' => 'progressbar',
                'progressbarPourcentage' => '60',
                'progressbarMin' => '0',
                'progressbarMax' => '10',
                'progressbarText' => '6 Fichier(s)'
            ],
            2 => [
                'cardTitleClass' => 'card-title',
                'cardTitleFontAwesome' => 'fa-solid fa-chart-line',
                'cardTitleText' => 'Traitements quotidiens (Max: 10/j)',
                'cardTextClass' => 'card-text progress mb-3',
                'progressbarClass' => 'progress-bar bg-danger',
                'progressbarRole' => 'progressbar',
                'progressbarPourcentage' => '90',
                'progressbarMin' => '0',
                'progressbarMax' => '10',
                'progressbarText' => '9 Traitement(s)'
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
                'repertoire' => true,
                'fichiers' => $this->fichiers,
                'repertoireResultats' => [
                    'fichiersResultats' => $this->fichiersResultats,
                ],
                'statistiques' => $this->texteStatistiques()
            ];
    }

}