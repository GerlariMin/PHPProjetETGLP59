<?php
    require_once('/var/www/html/PHPProjetETGLP59-sandbox/ressources/php/Model.php');
    require_once('mail.php');
    /**
    * Classe dédiée aux requêtes SQL propres au module supprimerCompte.
    */
    class RequetesSupprimerCompte extends Model {

        /**
        * @var Logs logs
        */
        private Logs $logs;
        /**
        * @var Model model
        */
        private Model $model;

        private MailSupprimerCompte $emailSupprimerCompte;

        /**
        * Constructeur de la classe RequetesSupprimerCompte qui va initialiser ses attributs privés dédiés aux logs et à l'accès à la base de données
        */
        public function __construct(array $config, Logs $logs) {
            $this->logs = $logs;
            $this->model = Model::getModel($config, $logs);
            $this->emailSupprimerCompte = new MailSupprimerCompte($config, $logs);
        }

        /**
         * @param String $login
         * @return mixed
         */
        public function donneesUtilisateur(String $login): mixed
        {
            // Texte SQL qui va alimenter la requête
            $texteRequete = 'SELECT * FROM utilisateurs WHERE loginUtilisateur IN (:login);';
            // Requête SQL a exécuter
            $requete = $this->model->bdd->prepare($texteRequete);
            $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
            // Attribution des valeurs de la requête préparée
            $requete->bindValue(":login", $login);
            $this->logs->messageLog('Paramètres: [login: "' . $login . '"].', $this->logs->typeDebug);
            // Exécution de la requête préparée
            $requete->execute();
            // La fonction retourne le résultat de la requête
            return $requete->fetch(PDO::FETCH_ASSOC);
        }

        /**
         * Récupération de la date et de l'heure de la dernière connexion au site
         */
        public function getDerniereConnexion(String $login){
            // Texte SQL qui va alimenter la requête
            $texteRequete = 'SELECT derniereConnexion FROM utilisateurs WHERE loginUtilisateur = :loginUtilisateur;';
            // Requête SQL a exécuter
            $requete = $this->model->bdd->prepare($texteRequete);
            $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
            // Attribution des valeurs de la requête préparée
            $requete->bindValue(':loginUtilisateur', $login);
            $this->logs->messageLog('Paramètres: [login: ' . $login . '].', $this->logs->typeDebug);
            // Exécution de la requête préparée
            $requete->execute();
            // La fonction retourne le résultat de la requête
            return $requete->fetch(PDO::FETCH_ASSOC);
        }

        /**
         * Récupère la date de deadline de la suppression de compte, retourne NULL si aucune deadline
         * Permet ainsi de vérifier si une demande de suppression a été faite.
         */
        public function getDeadlineCompte(String $login){
            // Texte SQL qui va alimenter la requête
            $texteRequete = 'SELECT deadlineCompte FROM utilisateurs WHERE loginUtilisateur = :loginUtilisateur;';
            // Requête SQL a exécuter
            $requete = $this->model->bdd->prepare($texteRequete);
            $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
            // Attribution des valeurs de la requête préparée
            $requete->bindValue(':loginUtilisateur', $login);
            $this->logs->messageLog('Paramètres: [login: ' . $login . '].', $this->logs->typeDebug);
            // Exécution de la requête préparée
            $requete->execute();
            return $requete->fetch(PDO::FETCH_ASSOC);
        }

        /**
         * La colonne deadlineCompte sera initialisé à dernièreConnexion + 30 jours
         */
        public function setDeadlineCompte(String $login){
            // Texte SQL qui va alimenter la requête (pas la peine de vérifier si derniereConnexion est NULL)
            $texteRequete = 'UPDATE utilisateurs SET deadlineCompte = DATE_ADD(:derniereConnexion, INTERVAL 30 DAY) WHERE loginUtilisateur = :loginUtilisateur;';
            // Requête SQL a exécuter
            $requete = $this->model->bdd->prepare($texteRequete);
            $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
            // Attribution des valeurs de la requête préparée
            $requete->bindValue(':loginUtilisateur', $login);
            $derniereConnexion = $this->getDerniereConnexion($login);
            $requete->bindValue(':derniereConnexion', $derniereConnexion['derniereConnexion']);
            $this->logs->messageLog('Paramètres: [login: ' . $login . ', derniereConnexion: '. $derniereConnexion['derniereConnexion'] .'].', $this->logs->typeDebug);
            // Exécution de la requête préparée
            return $requete->execute();
        }

        /**
         * En cas de reconnexion avec une deadline non NULL (demande de suppression active) et non dépassée, la colonne du compte est remise à NULL
         */
        public function verificationDeadlineUtilisateur($login){
            $deadline = $this->getDeadlineCompte($login);
            if($deadline['deadlineCompte'] !== NUll){
                // Mettre deadlineCompte à NULL car reconnexion avant deadline
                $texteRequete = 'UPDATE utilisateurs SET deadlineCompte = NULL WHERE loginUtilisateur = :loginUtilisateur;';
                // Requête SQL a exécuter
                $requete = $this->model->bdd->prepare($texteRequete);
                $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
                // Attribution des valeurs de la requête préparée
                $requete->bindValue(':loginUtilisateur', $login);
                $this->logs->messageLog('Paramètres: [login: ' . $login . '].', $this->logs->typeDebug);
                // Exécution de la requête préparée
                $requete->execute();
            }
        }

        /**
         * Suppression d'un utlisateur dans la table utilisateurs
         */
        public function suppressionCompte($idUtilisateur){
            // supprimer tous les utilisateurs dont la date de deadline est dépassée
            $texteRequete = 'DELETE FROM utilisateurs WHERE identifiantUtilisateur = :idUtilisateur';
            // Requête SQL a exécuter
            $requete = $this->model->bdd->prepare($texteRequete);
            $requete->bindValue(':idUtilisateur', $idUtilisateur);
            $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
            // Exécution de la requête préparée
            return $requete->execute();
        }

        /**
         * Retourne tous les identifiants des comptes dont la deadline est dépassée
         */
        public function getIdComptesExpires(){
            // Sélectionne tous les utilisateurs dont la date de deadline est dépassée
            //TODO: < not >
            $texteRequete = 'SELECT identifiantUtilisateur FROM utilisateurs WHERE deadlineCompte > NOW();';
            // Requête SQL a exécuter
            $requete = $this->model->bdd->prepare($texteRequete);
            $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
            // Exécution de la requête préparée
            $requete->execute();
            return $requete->fetch(PDO::FETCH_ASSOC);
        }

        /**
         * Retourne toutes les tables
         */
        public function getTables(){
            $texteRequete = 'SHOW TABLES';
            // Requête SQL a exécuter
            $requete = $this->model->bdd->prepare($texteRequete);
            $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
            // Exécution de la requête préparée
            $requete->execute();
            return $requete->fetchAll(PDO::FETCH_COLUMN);
        }

        /**
         * Retourne toutes les colonnes d'une table
         */
        public function getColumns($tableName){
            $texteRequete = "SHOW COLUMNS FROM $tableName";
            // Requête SQL a exécuter
            $requete = $this->model->bdd->prepare($texteRequete);
            $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
            // Exécution de la requête préparée
            $requete->execute();
            return $requete->fetchAll(PDO::FETCH_COLUMN);
        }

        /**
         * Supprime toutes les données en base des utilisateurs dont la deadLine est dépassée
         */
        public function suppressionUtilisateur(){
            // suppression de toutes les entrées dans les tables liées à l'utilisateur
            foreach($this->getIdComptesExpires() as $compteExpire){
                foreach($this->getTables() as $tableName){
                    // si la colonne 'utilisateurLie' existe dans la table
                    if(in_array("utilisateurLie", $this->getColumns($tableName))){
                        $texteRequete = "DELETE FROM $tableName WHERE utilisateurLie = :idUtilisateur";
                        // Requête SQL a exécuter
                        $requete = $this->model->bdd->prepare($texteRequete);
                        // Attribution des valeurs de la requête préparée
                        $requete->bindValue(':idUtilisateur', $compteExpire);
                        $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
                        // Exécution de la requête préparée
                        $requete->execute();
                    }
                }
                // suppression de l'utilisateur
                $this->suppressionCompte($compteExpire);
            }
        }

        /**
         * Envoi à l'utilisateur d'un mail, 7 jours avant la suppression définitive de son compte
         */
        public function rappelSuppressionCompte(){
            // TODO: a changer lors de la mise en prod : (> au lieu de <)
            $texteRequete = 'SELECT emailUtilisateur AS EMAIL FROM utilisateurs WHERE NOW() < DATE_SUB(deadlineCompte, INTERVAL 7 DAY);';
            // Requête SQL a exécuter
            $requeteComptes = $this->model->bdd->prepare($texteRequete);
            $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
            // Exécution de la requête préparée
            $requeteComptes->execute();
            $emailsAvecDeadline = $requeteComptes->fetchAll(PDO::FETCH_ASSOC);
            // Si au moins 1 résultat de requêtes
            if(count($emailsAvecDeadline) > 0){
                foreach($emailsAvecDeadline as $email){
                    $this->emailSupprimerCompte->envoyerMailPreventionCompte($email['EMAIL']);
                }
            }
        }

        /**
         * Met à jour la date et l'heure de connexion d'un compte
         */
        public function horodatageConnexion(string $login): mixed
        {
            // Texte SQL qui va alimenter la requête
            $texteRequete = 'UPDATE utilisateurs SET derniereConnexion = NOW() WHERE loginUtilisateur = :loginUtilisateur;';
            // Requête SQL a exécuter
            $requete = $this->model->bdd->prepare($texteRequete);
            $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
            // Attribution des valeurs de la requête préparée
            $requete->bindValue(':loginUtilisateur', $login);
            $this->logs->messageLog('Paramètres: [login: ' . $login . '].', $this->logs->typeDebug);
            // Exécution de la requête préparée
            return $requete->execute();
        }
    }