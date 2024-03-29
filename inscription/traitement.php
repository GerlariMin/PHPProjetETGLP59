<?php

    /**
     * Classe TraitementInscription
     */
    class TraitementInscription
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
         * @var TexteInscription texte
         */
        private TexteInscription $texte;

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
            $this->texte = new TexteInscription($this->config);
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
                case 'mauvais-email':
                    $erreur['i_class'] = 'fa-solid fa-id-badge';
                    $erreur['strong'] = 'Erreur';
                    $erreur['small'] = 'email';
                    $erreur['message'] = 'L\'adresse mail renseignée n\'existe pas.';
                    $erreur['lien'] = '<a href="../inscription/"> Créer un compte </a>';
                    break;
                case 'post':
                    $erreur['i_class'] = 'fa-solid fa-bug';
                    $erreur['strong'] = 'Erreur';
                    $erreur['small'] = 'Données formulaire';
                    $erreur['message'] = 'Une erreur est survenue lors du traitement de vos saisies. Veuilez réessayer.';
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
            $data['inscription'] = true;
            // Si un codeErreur existe, on ajoute les donées au tableau Mustache pour afficher le blocErreur
            if($codeErreur) {
                $data['blocErreur'] = $this->traitementErreur($codeErreur);
            }
            // affichage render
            $this->render->actionRendu($data);
        }

        public function traitementGetUuid(RequetesInscription $requete) {
            $var = substr(uniqid('', true),0,10);

            $test = $requete->isUuid($var);

            if ($test === 0) {
                return $var;
            } else {
                // Sinon, on fait un appel récursif pour générer un nouvel unique id
                $this->traitementGetUuid($requete);
            }
        }

        public function traitementCreationRepertoireUtilisateur(Logs $logs, String $uuid) {
            // Décomposition de l'identifiant de l'utilisateur pour créer son répertoire.
            $dir = substr($uuid,0,5);
            $subdir=substr($uuid,5,10);
            $repertoireUtilisateur =  $this->config['variables']['repertoires']['utilisateurs']."./{$dir}/{$subdir}";

            if (!mkdir($repertoireUtilisateur, 0777, true) && !is_dir($repertoireUtilisateur)){
                $logs->messageLog('Le répertoire utilisateur "' . $repertoireUtilisateur .'" n\'a pas pu être créé.', $logs->typeError);
            } else {

            }
        }

    }