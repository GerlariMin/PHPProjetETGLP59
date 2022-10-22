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
                "fichiers" => $this->fichiers,
            ];
        $data['utilisateur'] = $_SESSION['login'];
    }

}