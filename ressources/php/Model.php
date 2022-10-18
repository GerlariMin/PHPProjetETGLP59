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
                header('Location: ../../connexion/?erreur=ConnexionBDD');
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
            $req = $this->bdd->prepare("UPDATE utilisateurs SET motDePasseChiffreUtilisateur = '$nouveauMotDePasseChiffre', motDePasseOublie = '0', motDePasseOublieToken = NULL, expirationToken = NULL WHERE motDePasseOublieToken IN (:token);");
            $req->bindValue(":token", $token);

            $req->execute();
        }

        public function confirmationMotDePasse(String $nouveauMotDePasse, String $token)
        {
            $nouveauMotDePasseChiffre = password_hash($nouveauMotDePasse, PASSWORD_DEFAULT);
            // ajout nouveau mot de passe chiffré et suppression du token et de sa date d'expiration
            $req = $this->bdd->prepare("UPDATE utilisateurs SET motDePasseChiffreUtilisateur = '$nouveauMotDePasseChiffre', motDePasseOublie = '0', motDePasseOublieToken = NULL, expirationToken = NULL WHERE motDePasseOublieToken IN (:token);");
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

        public function insererUtilisateur(String $uuid, String $nom, String $prenom, String $user, String $email, String $password){
            $sql = "INSERT INTO utilisateurs (identifiantUtilisateur,nomUtilisateur, prenomUtilisateur, loginUtilisateur, emailUtilisateur, motDePasseChiffreUtilisateur, AbonnementUtilisateur) 
            VALUES ('{$uuid}','{$nom}','{$prenom}','{$user}','{$email}','{$password}','1')";
            $req = $this->bdd->prepare($sql);
            return $req->execute();
        }

        public function isUuid(String $uuid){
            $sql = "SELECT COUNT(identifiantUtilisateur) as bool FROM utilisateurs WHERE identifiantUtilisateur='{$uuid}'";
            $req = $this->bdd->prepare($sql);
            $req->execute();
            return (int) $req->fetch(PDO::FETCH_ASSOC)['bool'];
        }

        public function recuperDonneesUtilisateur(String $identifiant){
            $sql = "SELECT * FROM utilisateurs WHERE identifiantUtilisateur = :identifiant;";
            $req = $this->bdd->prepare($sql);
            $req->bindValue(":identifiant", $identifiant);
            $req->execute();
            return $req->fetch(PDO::FETCH_ASSOC);
        }

       // MODIFICATIONS UTILISATEUR

        public function modificationNom(String $identifiant, String $nouveauNom)
        {
            $req = $this->bdd->prepare("UPDATE utilisateurs SET nomUtilisateur = '$nouveauNom' WHERE identifiantUtilisateur IN (:identifiant);");
            $req->bindValue(":identifiant", $identifiant);
            $req->execute();
        }

        public function modificationPrenom(String $identifiant, String $nouveauPrenom)
        {
            $req = $this->bdd->prepare("UPDATE utilisateurs SET prenomUtilisateur = '$nouveauPrenom' WHERE identifiantUtilisateur IN (:identifiant);");
            $req->bindValue(":identifiant", $identifiant);
            $req->execute();
        }

        public function modificationEmail(String $identifiant, String $nouvelEmail)
        {
            if(verifierEmail($nouvelEmail)){
                //TODO: message d'erreur, nouvel email déjà utilisé
            }else{
                $req = $this->bdd->prepare("UPDATE utilisateurs SET emailUtilisateur = '$nouvelEmail' WHERE identifiantUtilisateur IN (:identifiant);");
                $req->bindValue(":identifiant", $identifiant);
                $req->execute();
            }
        }

        // à vérifier également car la connexion peut également se faire via le login
        public function modificationLogin(String $identifiant, String $nouveauLogin)
        {
            if($this->verifierLogin($nouveauLogin)){
                //TODO: message d'erreur, nouveau login déjà utilisé
                var_dump("login déjà utilisé");
                exit();
            }else{
                $req = $this->bdd->prepare("UPDATE utilisateurs SET loginUtilisateur = '$nouveauLogin' WHERE identifiantUtilisateur IN (:identifiant);");
                $req->bindValue(":identifiant", $identifiant);
                $req->execute();
            }
        }

        public function donneesUtilisateur(String $identifiant)
        {
            $req = $this->bdd->prepare("SELECT * FROM utilisateurs WHERE identifiantUtilisateur IN (:identifiant);");
            $req->bindValue(":identifiant", $identifiant);
            $req->execute();

            return $req->fetch(PDO::FETCH_ASSOC);
        }

        public function motDePasseModifie(String $identifiant, String $motDePasseChiffre, String $token)
        {
            // on place dans la table mot de passe le mdp provisoire en attente de confirmation par mail
            $req  = $this->bdd->prepare("UPDATE motDePasse SET motDePasseChiffre = '$motDePasseChiffre', token = '$token' WHERE utilisateurLie IN (:identifiant);");
            $req->bindValue(":identifiant", $identifiant);
            $req->execute();

            $req2 = $this->bdd->prepare("UPDATE utilisateurs SET motDePasseModifie = 1 WHERE identifiantUtilisateur IN (:identifiant)");
            $req2->bindValue(":identifiant", $identifiant);
            $req2->execute();

            return $req->fetch(PDO::FETCH_ASSOC);
        }

        public function confirmerMotDePasseModifie(String $identifiant, String $token)
        {
            $reqmodifmdp = $this->bdd->prepare("SELECT motDePasseModifie FROM utilisateurs WHERE identifiantUtilisateur IN (:identifiant)");
            $reqmodifmdp->bindValue(":identifiant", $identifiant);
            $reqmodifmdp->execute();
            $utilisateur = $reqmodifmdp->fetch(PDO::FETCH_ASSOC);
            $modification = $utilisateur['motDePasseModifie'];

            // on vérifie bien que l'utilisateur à fait la demande de modification de mot de passe
            // sans ça n'importe qui disposant de l'id unique d'un utilisateur pourrait modifier son mdp
            if($modification){
                $reqMotDePasse = $this->bdd->prepare("SELECT motDePasseChiffre FROM motDePasse WHERE token IN (:token)");
                $reqMotDePasse->bindValue(":token", $token);
                $reqMotDePasse->execute();
                $motDePasse = $reqMotDePasse->fetch(PDO::FETCH_ASSOC);
                $nouveauMotDePasse = $motDePasse['motDePasseChiffre'];

                $req = $this->bdd->prepare("UPDATE utilisateurs SET motDePasseChiffreUtilisateur = '$nouveauMotDePasse', motDePasseModifie = NULL WHERE identifiantUtilisateur IN (:identifiant);");
                $req->bindValue(":identifiant", $identifiant);
                $req->execute();
                return true;
            }else{
                return false;
            }
        }
    }