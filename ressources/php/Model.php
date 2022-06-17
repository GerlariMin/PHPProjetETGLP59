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
         * @var Model|null
         */
        private static ?Model $instance = null;

        /**
         * Constructeur privé
         * @param $config
         */
        private function __construct($config)
        {
            $this->config = $config;
            try{
                $this->bdd = new PDO($this->config['bdd']['dsn'], $this->config['bdd']['username'], $this->config['bdd']['password']);
            }catch(PDOException $e){
                $erreur = 'Connexion échouée: '. $e->getMessage();
                error_log($erreur);
                header("Location: ../../erreur/?erreur=500");
                exit();
            }
        }

        /**
         * Méthode qui permet d'instancier la classe
         * @param $config
         * @return Model|null
         */
        public static function get_model($config): ?Model
        {
            if(is_null(self::$instance)){
                self::$instance = new Model($config);
            }
            return self::$instance;
        }

        public function verifierEmail(String $email)
        {
            $req = $this->bdd->prepare("SELECT emailUtilisateur AS EMAIL FROM projetetglp59.utilisateurs WHERE emailUtilisateur IN (:email);");
            $req->bindValue(":email", $email);

            $req->execute();

            return $req->fetch(PDO::FETCH_ASSOC);
        }

        public function envoyerMail(String $email, String $sujet, String $message, String $headers): void
        {
            mail($email, $sujet, $message, $headers);
        }

        public function motDePasseOublie(String $email, String $token): void
        {
            $req = $this->bdd->prepare("UPDATE utilisateurs SET motDePasseOublie = '1', motDePasseOublieToken = '$token' WHERE emailUtilisateur IN (:email);");
            $req->bindValue(":email", $email);

            $req->execute();
        }

        public function modificationMotDePasse(String $nouveauMotDePasse, String $token)
        {
            $nouveauMotDePasseChiffre = password_hash($nouveauMotDePasse, PASSWORD_DEFAULT);
            // ajout nouveau mot de passe chiffré et suppression du token
            $req = $this->bdd->prepare("UPDATE utilisateurs SET motDePasseChiffreUtilisateur = '$nouveauMotDePasseChiffre', motDePasseOublie = '0', motDePasseOublieToken = 'NULL' WHERE motDePasseOublieToken IN (:token);");
            $req->bindValue(":token", $token);

            $req->execute();
        }

        public function verifierToken(String $token){
            $req = $this->bdd->prepare("SELECT loginUtilisateur FROM projetetglp59.utilisateurs WHERE motDePasseOublieToken IN (:token) AND expirationToken > (:expirationToken);");
            $req->bindValue(":token", $token);
            $req->bindValue(":expirationToken", date('Y-m-d h:i:s'));

            $req->execute();

            return $req->fetch(PDO::FETCH_ASSOC);
        }

    }