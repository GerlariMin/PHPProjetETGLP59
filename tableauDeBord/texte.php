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
        return
            [
                'col' =>
                    [
                        0 =>
                            [
                                'col_class' => 'col',
                                'col_card_class' => 'card text-white bg-dark mb-3 col',
                                'col_card_header_class' => 'card-header',
                                'col_card_header_i_class' => 'fa-solid fa-floppy-disk',
                                'col_card_header_text' => 'Espace disque utilisé (Max: 1,00 Go)',
                                'col_card_body_class' => 'card-body',
                                'progress_class' => 'progress',
                                'progressbar_class' => 'progress-bar bg-success',
                                'progressbar_role' => 'progressbar',
                                'progressbar_pourcentage' => '25',
                                'progressbar_min' => '0',
                                'progressbar_max' => '100',
                                'progressbar_text' => '250 Mo'
                            ],
                        1 =>
                            [
                                'col_class' => 'col',
                                'col_card_class' => 'card text-white bg-dark mb-3 col',
                                'col_card_header_class' => 'card-header',
                                'col_card_header_i_class' => 'fa-solid fa-file',
                                'col_card_header_text' => 'Fichiers stockés (Max: 10 fichiers)',
                                'col_card_body_class' => 'card-body',
                                'progress_class' => 'progress',
                                'progressbar_class' => 'progress-bar bg-warning',
                                'progressbar_role' => 'progressbar',
                                'progressbar_pourcentage' => '60',
                                'progressbar_min' => '0',
                                'progressbar_max' => '100',
                                'progressbar_text' => '6 Fichier(s)'
                            ],
                        2 =>
                            [
                                'col_class' => 'col',
                                'col_card_class' => 'card text-white bg-dark mb-3 col',
                                'col_card_header_class' => 'card-header',
                                'col_card_header_i_class' => 'fa-solid fa-chart-line',
                                'col_card_header_text' => 'Traitements quotidiens (Max: 10 traitements)',
                                'col_card_body_class' => 'card-body',
                                'progress_class' => 'progress',
                                'progressbar_class' => 'progress-bar bg-danger',
                                'progressbar_role' => 'progressbar',
                                'progressbar_pourcentage' => '90',
                                'progressbar_min' => '0',
                                'progressbar_max' => '100',
                                'progressbar_text' => '9 Traitement(s)'
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
                'repertoire' => true,
                'fichiers' => $this->fichiers,
                'repertoireResultats' => [
                    'fichiersResultats' => $this->fichiersResultats,
                ],
                'statistiques' => $this->texteStatistiques()
            ];
    }

}