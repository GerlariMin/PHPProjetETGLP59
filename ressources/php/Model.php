<?php

    /**
     * Classe Model
     * Elle permet d'assurer la connexion à une base de données et d'assurer les différentes requêtes communes aux différents modules à effectuer.
     * Particularité: elle n'est pas directement instanciable via un new Model() car son constructeur est privé.
     * Il faut utiliser la méthode get_model(), qui permet d'instancier la classe, une seule instance sera créée (donc une seule connexion à la base).
     */
    class Model {
        /**
         * @var PDO
         */
        protected PDO $bdd;
        /**
         * @var array
         */
        private array $config;
        /**
         * @var Logs
         */
        private Logs $logs;
        /**
         * @var Model|null
         */
        private static ?Model $instance = null;

        /**
         * Constructeur privé
         * @param array $config
         * @param Logs $logs
         */
        private function __construct(array $config, Logs $logs)
        {
            $this->config = $config;
            $this->logs = $logs;
            try {
                $this->bdd = new PDO($this->config['bdd']['dsn'], $this->config['bdd']['username'], $this->config['bdd']['password']);
            } catch(PDOException $e) {
                $erreur = 'Connexion échouée: '. $e->getMessage();
                error_log($erreur);
                header('Location: ../connexion/?erreur=ConnexionBDD');
                exit();
            }
        }

        // TODO - AJOUTER LES LOGS EN CONSTRUCTEUR [x]
        // TODO - UTILISER LES LOGS DANS LES METHODES [x]
        // TODO - NE GARDER QUE LES REQUETES GLOBALES []
        // TODO - BIEN PREPARER ET UTILISER BINDVALUE POUR LES REQUETES [x]

        /**
         * Méthode qui permet d'instancier la classe
         * @param array $config
         * @param Logs $logs
         * @return Model|null
         */
        public static function getModel(array $config, Logs $logs): ?Model
        {
            $logs->messageLog('Instanciation classe Model', $logs->typeDebug);
            if(is_null(self::$instance)){
                $logs->messageLog('Création d\'une instance de la classe Model', $logs->typeNotice);
                self::$instance = new Model($config, $logs);
            }
            return self::$instance;
        }

        /**
         * @param String $email
         * @return mixed
         */
        public function verifierEmail(String $email): mixed
        {
            // Texte SQL qui va alimenter la requête
            $texteRequete = 'SELECT emailUtilisateur AS EMAIL FROM utilisateurs WHERE emailUtilisateur IN (:email);';
            // Requête SQL a exécuter
            $requete = $this->bdd->prepare($texteRequete);
            $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
            // Attribution des valeurs de la requête préparée
            $requete->bindValue(":email", $email);
            $this->logs->messageLog('Paramètres: [email: ' . $email . '].', $this->logs->typeDebug);
            // Exécution de la requête préparée
            $requete->execute();
            // La fonction retourne le résultat de la requête
            return $requete->fetch(PDO::FETCH_ASSOC);
        }

        /**
         * @param String $user
         * @return mixed
         */
        public function verifierLogin(String $user): mixed
        {
            // Texte SQL qui va alimenter la requête
            $texteRequete = 'SELECT identifiantUtilisateur FROM utilisateurs WHERE loginUtilisateur IN (:user);';
            // Requête SQL a exécuter
            $requete = $this->bdd->prepare($texteRequete);
            $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
            // Attribution des valeurs de la requête préparée
            $requete->bindValue(":user", $user);
            $this->logs->messageLog('Paramètres: [user: ' . $user . '].', $this->logs->typeDebug);
            // Exécution de la requête préparée
            $requete->execute();
            // La fonction retourne le résultat de la requête
            return $requete->fetch(PDO::FETCH_ASSOC);
        }

        /**
         * @param String $email
         * @param String $token
         * @return bool
         */
        public function motDePasseOublie(String $email, String $token): bool
        {
            // Texte SQL qui va alimenter la requête
            $texteRequete = "UPDATE utilisateurs SET motDePasseOublie = '1', motDePasseOublieToken = :token WHERE emailUtilisateur IN (:email);";
            // Requête SQL a exécuter
            $requete = $this->bdd->prepare($texteRequete);
            $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
            // Attribution des valeurs de la requête préparée
            $requete->bindValue(":token", $token);
            $requete->bindValue(":email", $email);
            $this->logs->messageLog('Paramètres: [token: "' . $token . '", email: "' . $email . '"].', $this->logs->typeDebug);
            // Exécution de la requête préparée
            return $requete->execute();
        }

        /**
         * @param String $nouveauMotDePasse
         * @param String $token
         * @return bool
         */
        public function modificationMotDePasse(String $nouveauMotDePasse, String $token): bool
        {
            // Variable spécifique pour la requête
            $nouveauMotDePasseChiffre = password_hash($nouveauMotDePasse, PASSWORD_DEFAULT);
            // Texte SQL qui va alimenter la requête
            $texteRequete = "UPDATE utilisateurs SET motDePasseChiffreUtilisateur = :nouveauMotDePasseChiffre, motDePasseOublie = '0', motDePasseOublieToken = NULL, expirationToken = NULL WHERE motDePasseOublieToken IN (:token);";
            // Requête SQL a exécuter
            $requete = $this->bdd->prepare($texteRequete);
            $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
            // Attribution des valeurs de la requête préparée
            $requete->bindValue(":nouveauMotDePasseChiffre", $nouveauMotDePasseChiffre);
            $requete->bindValue(":token", $token);
            $this->logs->messageLog('Paramètres: [nouveauMotDePasseChiffre: "' . $nouveauMotDePasseChiffre . '", token: "' . $token . '"].', $this->logs->typeDebug);
            // Exécution de la requête préparée
            return $requete->execute();
        }

        /**
         * @param String $token
         * @return mixed
         */
        public function verifierToken(String $token): mixed
        {
            // Variable spécifique pour la requête
            $dateExpirationToken = date('Y-m-d h:i:s');
            // Texte SQL qui va alimenter la requête
            $texteRequete = 'SELECT loginUtilisateur FROM utilisateurs WHERE motDePasseOublieToken IN (:token) AND expirationToken > (:expirationToken);';
            // Requête SQL a exécuter
            $requete = $this->bdd->prepare($texteRequete);
            $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
            // Attribution des valeurs de la requête préparée
            $requete->bindValue(":token", $token);
            $requete->bindValue(":expirationToken", $dateExpirationToken);
            $this->logs->messageLog('Paramètres: [token: "' . $token . '", expirationToken: "' . $dateExpirationToken . '"].', $this->logs->typeDebug);
            // Exécution de la requête préparée
            $requete->execute();
            // La fonction retourne le résultat de la requête
            return $requete->fetch(PDO::FETCH_ASSOC);
        }

        /**
         * @param String $uuid
         * @return int
         */
        public function isUuid(String $uuid): int
        {
            // Texte SQL qui va alimenter la requête
            $texteRequete = 'SELECT COUNT(identifiantUtilisateur) as BOOL FROM utilisateurs WHERE identifiantUtilisateur = :identifiant;';
            // Requête SQL a exécuter
            $requete = $this->bdd->prepare($texteRequete);
            $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
            // Attribution des valeurs de la requête préparée
            $requete->bindValue(':identifiant', $uuid);
            $this->logs->messageLog('Paramètres: [identifiant: "' . $uuid . '"].', $this->logs->typeDebug);
            // Exécution de la requête préparée
            $requete->execute();
            // La fonction retourne le résultat de la requête
            return (int) $requete->fetch(PDO::FETCH_ASSOC)['BOOL'];
        }
    }