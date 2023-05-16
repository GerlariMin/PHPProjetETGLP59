<?php

    /**
     * Classe TraitementProfil
     */
    class TraitementProfil
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
         * @var TexteProfil texte
         */
        private TexteProfil $texte;
        /**
         * @var string dédiées à la gestion des erreurs
         */
        private String $iClass = 'iClass';
        private String $strong = 'strong';
        private String $small = 'small';
        private String $message = 'message';

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
            $this->texte = new TexteProfil($this->config);
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
                case 'mauvais-email':
                    $erreur['i_class'] = 'fa-solid fa-id-badge';
                    $erreur['strong'] = 'Erreur';
                    $erreur['small'] = 'email';
                    $erreur['message'] = 'L\'adresse mail renseignée n\'existe pas.';
                    $erreur['lien'] = '<a href="../inscription/"> Créer un compte </a>';
                    break;
                case 'email-exisant':
                    $erreur['i_class'] = 'fa-solid fa-id-badge';
                    $erreur['strong'] = 'Erreur';
                    $erreur['small'] = 'email';
                    $erreur['message'] = 'Cet email est déjà utilisé.';
                    $erreur['lien'] = '<a href="../connexion/"> Se connecter </a>';
                    break;
                case 'mdp-exisant':
                    $erreur['i_class'] = 'fa-solid fa-id-badge';
                    $erreur['strong'] = 'Erreur';
                    $erreur['small'] = 'mot de passe';
                    $erreur['message'] = 'Veuillez renseigner un mot de passe différent.';
                    break;
                case 'login-exisant':
                    $erreur['i_class'] = 'fa-solid fa-id-badge';
                    $erreur['strong'] = 'Erreur';
                    $erreur['small'] = 'login';
                    $erreur['message'] = 'Le login renseigné existe déjà. Veuillez en renseigner un nouveau.';
                    break;
                case 'modification':
                    $erreur[$this->iClass] = 'fa-solid fa-pen fa-shake';
                    $erreur[$this->strong] = 'Informations profil';
                    $erreur[$this->small] = 'Modification';
                    $erreur[$this->message] = 'Les modifications n\'ont pas pu être appliquées!';
                    break;
                case 'token':
                    $erreur[$this->iClass] = 'fa-solid fa-pen fa-shake';
                    $erreur[$this->strong] = 'Informations profil';
                    $erreur[$this->small] = 'Modification';
                    $erreur[$this->message] = 'Le token est invalide!';
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
         * Gestion des succès pour prévenir l'utilisateur.
         * @param string $codeSucces
         * @return array
         */
        private function traitementSucces(string $codeSucces = ''): array
        {
            // Initialisation du tableau de succès a retourner
            $erreur = array();
            // En fonction du code de succès reçu en paramètre, on rempli le tableau dédié à l'affichage du bloc succès
            switch ($codeSucces) {
                case 'modification':
                    $erreur[$this->iClass] = 'fa-solid fa-pen fa-bounce';
                    $erreur[$this->strong] = 'Informations profil';
                    $erreur[$this->small] = 'Modification';
                    $erreur[$this->message] = 'Les modifications ont été réalisées avec succès!';
                    break;
                default:
                    $erreur[$this->iClass] = 'fa-solid fa-circle-check';
                    $erreur[$this->strong] = 'Succès';
                    $erreur[$this->small] = 'succès';
                    $erreur[$this->message] = 'Opération effectuée avec succès!';
                    break;
            }
            // On retourne le tableau de succès formaté
            return $erreur;
        }

        /**
         * Affichage de la page de mot de passe oublié.
         */
        public function traitementRendu(RequetesProfil $requetes, String $codeErreur = '', String $codeSucces = ''): void
        {
            $data = $this->texte->texteFinal($requetes);

            $data['chemin'] = $this->config['variables']['chemin'];
            $data['profil'] = true;
            // Si un codeErreur existe, on ajoute les donées au tableau Mustache pour afficher le blocErreur
            if ($codeErreur) {
                $data['blocErreur'] = $this->traitementErreur($codeErreur);
            }
            // Si un codeSucces existe, on ajoute les donées au tableau Mustache pour afficher le blocSucces
            if ($codeSucces) {
                $data['blocSucces'] = $this->traitementSucces($codeSucces);
            }
            // affichage render
            $this->render->actionRendu($data);
        }

    }