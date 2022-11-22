<?php

    /**
     * Classe TexteSouscription
     * Contient l'ensemble du texte à afficher pour la page de connexion.
     */
    class TexteSouscription
    {
        /**
         * Variables correspondants aux balises Mustache de la page.
         */

        /**
         * @var array
         */
        private array $config;
        /**
         * @var Logs
         */
        private Logs $logs;
        /**
         * @var RequetesSouscription
         */
        private RequetesSouscription $requetes;
        /**
         * @var String div_class
         */
        private String $divColClass = 'col-4 mb-4';
        private String $divH1Class = 'mt-3 my-0 font-weight-normal';
        private String $divSmallClass = 'text-muted';
        private String $divUlClass = "fa-ul list-unstyled mt-3 mb-4";
        /**
         * @var array|string[] pour attribuer une couleur à la carte de présentation d'un abonnement donné
         */
        private array $couleursAbonnementsPayants =
            [
                0 => 'primary',
                1 => 'secondary',
                2 => 'danger',
                3 => 'warning',
                4 => 'info',
                5 => 'dark'
            ];
       
        public function __construct(array $config)
        {
            $this->config = $config;
            $this->logs = new Logs($this->config);
            $this->requetes = new RequetesSouscription($this->config, new Logs($this->config));
        }

        /**
         * Retourne un tableau formaté pour l'affichage des abonnements disponibles
         *
         * @return array
         */
        private function texteAbonnements(): array
        {
            $texteAbonnements = array();
            try {
                // On récupère les abonnements disponibles à l'achat
                $abonnements = $this->requetes->recupererAbonnementsDisponibles();
                // On regarde si il existe au moins un abonnement
                if(is_array($abonnements)) {
                    // On parcourt les abonnements trouvés
                    foreach ($abonnements as $abonnement) {
                        // On définit les valeurs par défaut d'un abonnement (en se basant sur un abonnement gratuit)
                        $bouton = false;
                        $couleur = 'success';
                        $href = '';
                        $prix = 0;
                        $reduction = 0;
                        $type = strtoupper($abonnement['TYPE']);
                        $limiteDocuments = $abonnement['DOCUMENTS'];
                        $limiteStockage = $abonnement['STOCKAGE'];
                        // S'il s'agit d'un abonnement payant
                        if($type === 'PAYANT') {
                            // on active le bouton de souscription à l'offre courante
                            $bouton = true;
                            // On tire au sort la couleur de la carte de l'offre
                            $couleur = $this->couleursAbonnementsPayants[random_int(0, 5)];
                            // On définir le lien qui permettra de régler le paiement de l'offre
                            $href = '';
                            // On définit le prix
                            $prix = (float) $abonnement['PRIX'];
                            // Si une réduction est active sur l'abonnement courant
                            if($abonnement['PROMO'] && $abonnement['REDUCTION']) {
                                // On récupère le pourcentage de réduction
                                $reduction = (float) $abonnement['REDUCTION'];
                                // On calcule le prix final
                                $calculPrix = round($prix - ($prix * $reduction / 100), 2);
                                // On attribut le prix final à la variable du prix
                                $prix = $calculPrix;
                            }
                        }
                        // On ajoute l'ensemble des données dans le tableau formaté à la suite de l'indice courant
                        $texteAbonnements[] =
                            [
                                'divColClass' => $this->divColClass,
                                'divH1Class' => $this->divH1Class,
                                'divSmallClass' => $this->divSmallClass,
                                'divUlClass' => $this->divUlClass,
                                'BOUTON' => $bouton,
                                'COULEUR' => $couleur,
                                'DOCUMENTS' => $limiteDocuments,
                                'HREF' => $href,
                                'PRIX' => $prix,
                                'REDUCTION' => $reduction,
                                'STOCKAGE' => $limiteStockage,
                                'TYPE' => $type
                            ];
                    }
                }
            } catch (Exception $e) {
                $this->logs->messageLog('Erreur lors de la récupération des abonnements. Exception: ' . $e->getMessage() . '.', $this->logs->typeError);
            }
            // On retourne les abonnements disponibles
            return $texteAbonnements;
        }

        /**
         * Retourne le tableau formaté final utilisé pour générer le rendu intégral.
         *
         * @return array
         */
        public function texteFinal(): array
        {
            return
                [
                    'abonnement' => $this->texteAbonnements()
                ];
        }

    }