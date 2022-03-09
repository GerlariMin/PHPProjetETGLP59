<?php

    /**
     * Classe Model
     * Elle permet d'assurer la connexion à une base de données et d'assurer les différentes requêtes à effectuer.
     * Particularité: elle n'est pas directement instanciable via un new Model() car son constructeur est privé.
     * Il faut utiliser la méthode get_model(), qui permet d'instancier la classe, une seule instance sera créée (donc une seule connexion à la base).
     */
    class Model {
        /**
         * @var PDO
         */
        private PDO $bdd;
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

        public function recupererUtilisateur(String $email, String $identifiant)
        {
            $req = $this->bdd->prepare("SELECT loginUtilisateur AS LOGIN, nomUtilisateur AS NOM, prenomUtilisateur AS PRENOM, emailUtilisateur AS EMAIL FROM utilisateurs WHERE emailUtilisateur = :email AND identifiantUtilisateur = :identifiant;");
            $req->bindValue(":email", $email);
            $req->bindValue(":identifiant", $identifiant);

            $req->execute();

            return $req->fetch(PDO::FETCH_ASSOC);
        }

        public function recupererIdentifiant(String $email)
        {
            $req = $this->bdd->prepare("SELECT identifiantUtilisateur AS IDENTIFIANT FROM utilisateurs WHERE emailUtilisateur = :email;");
            $req->bindValue(":email", $email);

            $req->execute();

            return $req->fetch(PDO::FETCH_ASSOC)['IDENTIFIANT'];
        }

        public function recupererMotDePassCourant(String $identifiant)
        {
            $req = $this->bdd->prepare("SELECT motDePasseChiffre AS PHRASE FROM motsdepasse WHERE utilisateurLie = :identifiant AND motDePasseCourant = true;");
            $req->bindValue(":identifiant", $identifiant);

            $req->execute();

            return $req->fetch(PDO::FETCH_ASSOC)['PHRASE'];
        }

        public function verifierEmail(String $email)
        {
            $req = $this->bdd->prepare("SELECT emailUtilisateur AS EMAIL FROM projetetglp59.utilisateurs WHERE emailUtilisateur IN (:email);");
            $req->bindValue(":email", $email);

            $req->execute();

            return $req->fetch(PDO::FETCH_ASSOC);
        }

        public function insererUtilisateur(String $identifiant,String $nom, String $prenom, String $login, String $email): bool
        {
            $req = $this->bdd->prepare("INSERT INTO utilisateurs(identifiantUtilisateur, nomUtilisateur, prenomUtilisateur, loginUtilisateur, emailUtilisateur, abonnementUtilisateur) VALUES (:identifiant, :nom, :prenom, :login, :email, true)");
            $req->bindValue(":identifiant", $identifiant);
            $req->bindValue(":nom", $nom);
            $req->bindValue(":prenom", $prenom);
            $req->bindValue(":login", $login);
            $req->bindValue(":email", $email);
            return $req->execute();
        }

        public function insererMotDePasse(String $motDePasseChiffre,String $identifiantUtilisateur): bool
        {
            $req = $this->bdd->prepare("INSERT INTO motsdepasse(motDePasseChiffre, motDePasseCourant, utilisateurLie) VALUES (:motDePasseChiffre, true, :identifiantUtilisateur);");
            $req->bindValue(":motDePasseChiffre", $motDePasseChiffre);
            $req->bindValue(":identifiantUtilisateur", $identifiantUtilisateur);

            return $req->execute();
        }

    }