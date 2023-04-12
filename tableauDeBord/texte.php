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
     * @param array $fichiersResultats
     */
    public function setFichiersResultats(array $fichiersResultats): void
    {
        $this->fichiersResultats = $fichiersResultats;
    }

    /**
     * Définit la couleur de la barre de progression, par rapport à un pourcentage donné en paramètre.
     * @param int $valeur
     * @return String
     */
    private function couleurBarreProgressionParPourcentage(int $valeur): String
    {
        // Si inférieur 50% -> vert
        if ($valeur < 50) {
            return 'success';
        }
        // Si entre 50% et 80% -> jaune
        if ($valeur <= 80) {
            return 'warning';
        }
        // Sinon -> rouge
        return 'danger';
    }

    /**
     * Fonction qui retourne un tableau formaté pour les différentes divs statistiques de la page du tableau de bord.
     * @return array[]
     */
    private function texteStatistiques(array $limites): array
    {
        return [
            0 => [
                  'cardTitleClass' => 'card-title',
                  'cardTitleFontAwesome' => 'fa-solid fa-floppy-disk',
                  'cardTitleText' => 'Espace disque utilisé (Max: ' . $limites['stockage']['max'] . ' Go)',
                  'cardTextClass' => 'card-text progress mb-3',
                  'progressbarClass' => 'progress-bar bg-' . $this->couleurBarreProgressionParPourcentage($limites['stockage']['pourcentage']),
                  'progressbarRole' => 'progressbar',
                  'progressbarPourcentage' => $limites['stockage']['pourcentage'],
                  'progressbarMin' => '0',
                  'progressbarMax' => $limites['stockage']['max'],
                  'progressbarText' => $limites['stockage']['actuel'] . ' Go - (' . $limites['stockage']['pourcentage'] . '%)'
            ],
            1 => [
                'cardTitleClass' => 'card-title',
                'cardTitleFontAwesome' => 'fa-regular fa-file',
                'cardTitleText' => 'Fichiers stockés (Max: ' . $limites['fichiers']['max'] . ' fichiers)',
                'cardTextClass' => 'card-text progress mb-3',
                'progressbarClass' => 'progress-bar bg-' . $this->couleurBarreProgressionParPourcentage($limites['fichiers']['pourcentage']),
                'progressbarRole' => 'progressbar',
                'progressbarPourcentage' => $limites['fichiers']['pourcentage'],
                'progressbarMin' => '0',
                'progressbarMax' => $limites['fichiers']['max'],
                'progressbarText' => $limites['fichiers']['actuel'] . ' Fichier(s) - (' . $limites['fichiers']['pourcentage'] . '%)'
            ],
            2 => [
                'cardTitleClass' => 'card-title',
                'cardTitleFontAwesome' => 'fa-solid fa-chart-line',
                'cardTitleText' => 'Traitements quotidiens (Max: ' . $limites['traitements']['max'] . '/j)',
                'cardTextClass' => 'card-text progress mb-3',
                'progressbarClass' => 'progress-bar bg-' . $this->couleurBarreProgressionParPourcentage($limites['traitements']['pourcentage']),
                'progressbarRole' => 'progressbar',
                'progressbarPourcentage' => $limites['traitements']['pourcentage'],
                'progressbarMin' => '0',
                'progressbarMax' => $limites['traitements']['max'],
                'progressbarText' => $limites['traitements']['actuel'] . ' Traitement(s)'
            ]
        ];
    }

    /**
     * Données utiles pour l'affichage du bloc Mon abonnement
     * @param array $limites
     * @return array
     */
    private function texteAbonnement(array $limites): array
    {
        return [
            'typeAbonnement' => $limites['type'],
            'volumetrieMax' => $limites['stockage']['max'],
            'nombreFichiersMax' => $limites['fichiers']['max'],
            'traitementsMax' => $limites['traitements']['max'],
        ];
    }

    /**
     * Retourne le tableau formaté final utilisé pour générer le rendu intégral.
     * @return array
     */
    public function texteFinal(array $limites):array
    {
        return [
            'repertoire' => true,
            // Mon abonnement
            'abonnement' => $this->texteAbonnement($limites),
            'desactiverBoutonAjoutFichier' => $limites['desactiverBoutonAjoutFichier'],
            'desactiverBoutonOCR' => $limites['desactiverBoutonOCR'],
            // Vos documents
            'fichiers' => $this->fichiers,
            'repertoireResultats' => [
                'fichiersResultats' => $this->fichiersResultats,
            ],
            // Votre espace
            'statistiques' => $this->texteStatistiques($limites),
        ];
    }

}