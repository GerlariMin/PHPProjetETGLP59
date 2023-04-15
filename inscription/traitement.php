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
                case 'post':
                    $erreur['i_class'] = 'fa-solid fa-bug';
                    $erreur['strong'] = 'Erreur';
                    $erreur['small'] = 'Données formulaire';
                    $erreur['message'] = 'Une erreur est survenue lors du traitement de vos saisies. Veuilez réessayer.';
                    break;
                case 'nnom':
                    $erreur['i_class'] = 'fa-solid fa-bug';
                    $erreur['strong'] = 'Erreur';
                    $erreur['small'] = 'Champ vide';
                    $erreur['message'] = 'Champ nom vide. Veuilez remplir le formulaire à nouveau.';
                    break;
                case 'nprenom':
                    $erreur['i_class'] = 'fa-solid fa-bug';
                    $erreur['strong'] = 'Erreur';
                    $erreur['small'] = 'Champ vide';
                    $erreur['message'] = 'Champ prénom vide. Veuilez remplir le formulaire à nouveau.';
                    break;
                case 'npseudo':
                    $erreur['i_class'] = 'fa-solid fa-bug';
                    $erreur['strong'] = 'Erreur';
                    $erreur['small'] = 'Champ vide';
                    $erreur['message'] = 'Champ nom utilisateur vide. Veuilez remplir le formulaire à nouveau.';
                    break;
                case 'nemail':
                    $erreur['i_class'] = 'fa-solid fa-bug';
                    $erreur['strong'] = 'Erreur';
                    $erreur['small'] = 'Champ vide';
                    $erreur['message'] = 'Champ e-mail vide. Veuilez remplir le formulaire à nouveau.';
                    break;
                case 'nmdp':
                    $erreur['i_class'] = 'fa-solid fa-bug';
                    $erreur['strong'] = 'Erreur';
                    $erreur['small'] = 'Champ vide';
                    $erreur['message'] = 'Champ mot de passe vide. Veuilez remplir le formulaire à nouveau.';
                    break;
                case 'ncmdp':
                    $erreur['i_class'] = 'fa-solid fa-bug';
                    $erreur['strong'] = 'Erreur';
                    $erreur['small'] = 'Champ vide';
                    $erreur['message'] = 'Champ confirmation du mot de passe vide. Veuilez remplir le formulaire à nouveau.';
                    break;
                case 'mail':
                    $erreur['i_class'] = 'fa-solid fa-bug';
                    $erreur['strong'] = 'Erreur';
                    $erreur['small'] = 'Mail de confirmation';
                    $erreur['message'] = 'Un problème est survenu lors de l\'envoi du mail de confirmation. Votre compte a bien été créé néanmoins.';
                    break;
                case 'inscription':
                    $erreur['i_class'] = 'fa-solid fa-bug';
                    $erreur['strong'] = 'Erreur';
                    $erreur['small'] = 'Inscription';
                    $erreur['message'] = 'Le compte n\'a pas pu être créé. Veuilez remplir le formulaire à nouveau.';
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
            $repertoireUtilisateur =  $this->config['variables']['repertoires']['utilisateurs']."./{$dir}/{$subdir}/";
            $repertoireResultatsUtilisateur =  $this->config['variables']['repertoires']['utilisateurs']."./{$dir}/{$subdir}/resultats/";
            // Création du répertoire utilisateur
            if (!mkdir($repertoireUtilisateur, 0777, true) && !is_dir($repertoireUtilisateur)){
                $logs->messageLog('Le répertoire utilisateur "' . $repertoireUtilisateur .'" n\'a pas pu être créé.', $logs->typeError);
            } else {
                $logs->messageLog('Le répertoire utilisateur "' . $repertoireUtilisateur .'" a été créé.', $logs->typeNotice);
            }
            // Création du répertoire résultats du répertoire utilisateur
            if (!mkdir($repertoireResultatsUtilisateur, 0777, true) && !is_dir($repertoireResultatsUtilisateur)){
                $logs->messageLog('Le répertoire utilisateur "' . $repertoireResultatsUtilisateur .'" n\'a pas pu être créé.', $logs->typeError);
            } else {
                $logs->messageLog('Le répertoire utilisateur "' . $repertoireResultatsUtilisateur .'" a été créé.', $logs->typeNotice);
            }
        }

    }