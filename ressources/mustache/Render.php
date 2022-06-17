<?php

    /**
     * Classe Render dédiée à l'affichage via le template Mustache.
     */
    class Render
    {
        /**
         * @var Mustache_Engine
         */
        private Mustache_Engine $mustache;
        /**
         * @var Logs
         */
        private Logs $logs;

        /**
         * Constructeur de la classe.
         * @param Logs $logs
         * @param string $chemin
         */
        public function __construct(Logs $logs, string $chemin = "")
        {
            $this->logs = $logs;
            try {
                $this->mustache = new Mustache_Engine(
                    [
                        'loader' => new Mustache_Loader_CascadingLoader(
                            [
                                new Mustache_Loader_FilesystemLoader($chemin . 'ressources/mustache'),
                                //new Mustache_Loader_FilesystemLoader($chemin . 'accueil/mustache'),
                                //new Mustache_Loader_FilesystemLoader($chemin . 'cgu/mustache'),
                                new Mustache_Loader_FilesystemLoader($chemin . 'connexion/mustache'),
                                //new Mustache_Loader_FilesystemLoader($chemin . 'erreur/mustache'),
                                new Mustache_Loader_FilesystemLoader($chemin . 'inscription/mustache'),
                                //new Mustache_Loader_FilesystemLoader($chemin . 'profil/mustache'),
                                new Mustache_Loader_FilesystemLoader($chemin . 'tableauDeBord/mustache'),
                            ]
                        )
                    ]
                );
            } catch(Exception $e) {
                $this->logs->messageLog('Initialisation du moteur Mustache échouée. Exception: ' . $e->getMessage(), $this->logs->typeError);
            }
        }

        /**
         * Fonction permettant d'afficher le contenu Mustache d'une page, avec les données contenues dans la variable $data
         * @param array $data
         */
        public function actionRendu(array $data = [])
        {
            try {
                //On extrait les données à afficher
                extract($data, EXTR_OVERWRITE);
                // Si l'utilisateur est connecté
                if(isset($_SESSION['login'])) {
                    $data['connecte'] = true;
                }
                $this->logs->messageLog('Génération de l\'affichage Mustache.', $this->logs->typeNotice);
                //On affiche les données voulues
                echo $this->mustache->render("Body", $data);
            } catch(Exception $e) {
                // En cas de problème, on affiche le résultat dans les logs
                $this->logs->messageLog('Erreur lors de la génération du rendu Mustache. Erreur: ' . $e->getMessage(), $this->logs->typeCritical);
            }
        }

    }

?>