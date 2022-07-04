<?php

    /**
     * Classe TraitementReinitialisationMotDePasse
     */
    class TraitementReinitialisationMotDePasse
    {

        /**
         * @var Render render
         */
        private Render $render;
        /**
         * @var array config
         */
        private array $config;
        /**
         * @var TexteReinitialisationMotDePasse texte
         */
        private TexteReinitialisationMotDePasse $texte;

        /**
         * Traitement_Accueil constructor.
         *
         * @param Render $rendu
         */
        public function __construct(Render $rendu)
        {
            $this->render = $rendu;
            global $config;
            $this->config = $config;
            $this->texte = new TexteReinitialisationMotDePasse($this->config);
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
                case 'token':
                    $erreur['i_class'] = 'fa-solid fa-id-badge';
                    $erreur['strong'] = 'Erreur';
                    $erreur['small'] = 'token';
                    $erreur['message'] = 'le token n\'existe plus ou a expiré';
                    break;
                case 'mdp-differents':
                    $erreur['i_class'] = 'fa-solid fa-id-badge';
                    $erreur['strong'] = 'Erreur';
                    $erreur['small'] = 'token';
                    $erreur['message'] = 'les deux mots de passe sont différents';
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
         * Affichage de la page de mot de passe oublié.
         */
        public function traitementRendu(String $codeErreur = ''): void
        {
            $data = $this->texte->texteFinal();

            $data['chemin'] = $this->config['variables']['chemin'];
            $data['reinitialisationMotDePasse'] = true;

            if($codeErreur) {
                $data['blocErreur'] = $this->traitementErreur($codeErreur);
            }

            $this->render->actionRendu($data);
        }

    }