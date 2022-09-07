<?php

    /**
     * Classe TraitementSouscription
     */
    class TraitementSouscription
    {
        /**
         * @var Logs logs
         */
        private Logs $logs;
        /**
         * @var Render render
         */
        private Render $render;
        /**
         * @var array config
         */
        private array $config;
        /**
         * @var TexteSouscription texte
         */
        private TexteSouscription $texte;
        /**
         * @var string dédiées à la gestion des erreurs
         */
        private String $iClass = 'iClass';
        private String $strong = 'strong';
        private String $small = 'small';
        private String $message = 'message';

        /**
         * Traitement_Accueil constructor.
         * @param Logs $logs
         * @param Render $rendu
         */
        public function __construct(Logs $logs, Render $rendu)
        {
            global $config;
            $this->config = $config;
            $this->logs = $logs;
            $this->render = $rendu;
            $this->texte = new TexteSouscription($this->config);
        }

        /**
         * Gestion des erreurs pour prévenir l'utilisateur.
         * @param string $codeErreur
         * @return array
         */
        private function traitementErreur(string $codeErreur = ''): array
        {
            // Initialisation du tableau d'erreur a retourner
            $erreur = array();
            // En fonction du code d'erreur reçu en paramètre, on rempli le tableau dédié à l'affichage du bloc d'erreur
            switch($codeErreur) {
                case '1':
                    $erreur[$this->iClass] = 'fa-solid fa-file-signature';
                    $erreur[$this->strong] = 'Erreur';
                    $erreur[$this->small] = 'Formulaire';
                    $erreur[$this->message] = 'Au moins un des champs du formulaire est vide ou bien incorrect!';
                    break;
                default:
                    $erreur[$this->iClass] = 'fa-solid fa-bomb';
                    $erreur[$this->strong] = 'Erreur';
                    $erreur[$this->small] = 'inconnue';
                    $erreur[$this->message] = 'Une erreur est survenue!';
                    break;
            }
            // On retourne le tableau d'erreur formaté
            return $erreur;
        }

        /**
         * Affichage de la page de connexion.
         * @param string $codeErreur
         */
        public function traitementRendu(string $codeErreur = ''): void
        {
            try {
                // On récupère le tableau formaté pour Mustache
                $data = $this->texte->texteFinal();
                // On rajoute des clés au tableau dédié à Mustache pour afficher la page de connexion
                $data['chemin'] = $this->config['variables']['chemin'];
                $data['souscription'] = true;
                // Si un codeErreur existe, on ajoute les donées au tableau Mustache pour afficher le blocErreur
                if($codeErreur) {
                    $data['blocErreur'] = $this->traitementErreur($codeErreur);
                }
                // On génère l'affichage Mustache
                $this->render->actionRendu($data);
            } catch(Exception $e) {
                // En cas de problème, on affiche le résultat dans les logs
                $this->logs->messageLog('Erreur lors de l\'affichage du module de connexion. Erreur: ' . $e->getMessage(), $this->logs->typeCritical);
            }
        }

    }