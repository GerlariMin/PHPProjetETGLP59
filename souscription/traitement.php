<?php

    /**
     * Classe TraitementConnexion
     */
    class TraitementConnexion
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
         * @var TexteConnexion texte
         */
        private TexteConnexion $texte;

        /**
         * Traitement_Accueil constructor.
         * @param Render $rendu
         */
        public function __construct(Logs $logs, Render $rendu)
        {
            global $config;
            $this->config = $config;
            $this->logs = $logs;
            $this->render = $rendu;
            $this->texte = new TexteConnexion($this->config);
        }

        /**
         * Gestion des erreurs pour prévenir l'utilisateur.
         * @param string $codeErreur
         */
        private function traitementErreur(string $codeErreur = ''): array
        {
            // Initialisation du tableau d'erreur a retourner
            $erreur = array();
            // En fonction du code d'erreur reçu en paramètre, on rempli le tableau dédié à l'affichage du bloc d'erreur
            switch($codeErreur) {
                case '1':
                    $erreur['i_class'] = 'fa-solid fa-file-signature';
                    $erreur['strong'] = 'Erreur';
                    $erreur['small'] = 'Formulaire';
                    $erreur['message'] = 'Au moins un des champs du formulaire est vide ou bien incorrect!';
                    break;
                default:
                    $erreur['i_class'] = 'fa-solid fa-bomb';
                    $erreur['strong'] = 'Erreur';
                    $erreur['small'] = 'inconnue';
                    $erreur['message'] = 'Une erreur est survenue!';
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