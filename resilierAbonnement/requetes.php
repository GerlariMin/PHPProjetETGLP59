<?php
    require_once('../ressources/php/Model.php');
    /**
    * Classe dédiée aux requêtes SQL propres au module supprimerCompte.
    */
    class RequetesResilierAbonnement extends Model {

        /**
        * @var Logs logs
        */
        private Logs $logs;
        /**
        * @var Model model
        */
        private Model $model;

        /**
        * Constructeur de la classe RequetesSupprimerCompte qui va initialiser ses attributs privés dédiés aux logs et à l'accès à la base de données
        */
        public function __construct(array $config, Logs $logs) {
            $this->logs = $logs;
            $this->model = Model::getModel($config, $logs);
        }

        public function donneesUtilisateur(String $login): mixed
        {
            $texteRequete = 'SELECT * FROM utilisateurs WHERE loginUtilisateur IN (:login);';
            $requete = $this->model->bdd->prepare($texteRequete);
            $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
            $requete->bindValue(":login", $login);
            $this->logs->messageLog('Paramètres: [login: "' . $login . '"].', $this->logs->typeDebug);
            $requete->execute();
            return $requete->fetch(PDO::FETCH_ASSOC);
        }

        /**
         * L'utilisateur fait la demande de résiliation de son compte => dans souscription, faire en sorte que lors d'un abonnement que le flag 
         * dans la table utilisateur 'demandeResiliationAbonnement' soit mis à false
         */ 
        public function demandeResiliationAbonnement(String $idUtilisateur)
        {
            $texteRequete = 'UPDATE utilisateurs SET demandeResiliationAbonnement = TRUE WHERE identifiantUtilisateur = :idUtilisateur';
            $requete = $this->model->bdd->prepare($texteRequete);
            $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
            $requete->bindValue(":idUtilisateur", $idUtilisateur);
            $this->logs->messageLog('Paramètres: [login: "' . $idUtilisateur . '"].', $this->logs->typeDebug);
            return $requete->execute();
        }

        // Récupère l'échéance de la dernière facture de chaque utilisateur
        public function getDernierPaiement()
        {
            $texteRequete = 'SELECT utilisateurLie, MAX(dateDeFinAchat) AS dernierPaiement FROM Facturations GROUP BY utilisateurLie;';
            $requete = $this->model->bdd->prepare($texteRequete);
            $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
            $requete->execute();
            return $requete->fetchAll(PDO::FETCH_ASSOC);
        }

        // Verifie si l'utilisatuer à fait une demande de résiliation d'abonnement
        public function aDemandeResiliationAbonnement(String $login)
        {
            $texteRequete = 'SELECT demandeResiliationAbonnement AS abonnement FROM utilisateurs WHERE identifiantUtilisateur = :identifiantUtilisateur';
            $requete = $this->model->bdd->prepare($texteRequete);
            $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
            $requete->bindValue(':identifiantUtilisateur', $login);
            $this->logs->messageLog('Paramètres: [login: ' . $login . '].', $this->logs->typeDebug);
            $requete->execute();
            return $requete->fetch(PDO::FETCH_ASSOC);
        }

        // Résilie un abonnement utilisateur
        //TODO: 2e requête à fusionner, car avec clause AND cause des erruers de clé étrangères
        public function resilierAbonnement(String $login)
        {
            $texteRequete = 'UPDATE utilisateurs SET abonnementUtilisateur = (SELECT identifiantAbonnement FROM abonnements WHERE typeAbonnement = "gratuit") WHERE identifiantUtilisateur = :idUtilisateur';
            $texteRequete2 = 'UPDATE utilisateurs SET demandeResiliationAbonnement = FALSE WHERE identifiantUtilisateur = :idUtilisateur';
            $requete = $this->model->bdd->prepare($texteRequete);
            $requete2 = $this->model->bdd->prepare($texteRequete2);
            $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
            $requete->bindValue(":idUtilisateur", $login);
            $requete2->bindValue(":idUtilisateur", $login);
            $this->logs->messageLog('Paramètres: [login: "' . $login . '"].', $this->logs->typeDebug);
            $requete->execute();
            $requete2->execute();
        }

        // Résilie un abonnement si une demande à été faite et si la date du dernier paiement de sa facture est dépassé (à automatiser => cron...)
        public function verificationAbonnementExpire()
        {
            $dateActuelle = date('Y-m-d H:i:s', time());
            foreach($this->getDernierPaiement() as $abonnementResilie){
                $idUtilisateur = $abonnementResilie['utilisateurLie'];
                $demandeResiliation = $this->aDemandeResiliationAbonnement($idUtilisateur);
                // Si l'utilisateur possède une facture et qu'il a fait la demande de résiliation d'abonnement
                if($demandeResiliation['abonnement'] && $abonnementResilie['dernierPaiement'] > $dateActuelle){
                    $this->resilierAbonnement($idUtilisateur);
                }
            }
        }

        //TODO: griser le bouton de résilier si le compte est sous abonnement gratuit

        /**
         * 
         */
    }