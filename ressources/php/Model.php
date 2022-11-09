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

        public function verifierLogin(String $user)
        {
            $req = $this->bdd->prepare("SELECT identifiantUtilisateur FROM projetetglp59.utilisateurs WHERE loginUtilisateur IN (:user);");
            $req->bindValue(":user", $user);

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

        public function insererUtilisateur(String $uuid, String $nom, String $prenom, String $user, String $email, String $aboUser, String $password){
            // vérification séparée pour bien indiquer à l'utilisateur quel est le problème
            if($this->verifierEmail($email)){
                //TODO: log erreur mail déjà dans la base
                return false;
            }elseif($this->verifierLogin($user)){
                //TODO: log erreur login déjà dans la base
                return false;
            }else{
                $sql = "INSERT INTO utilisateurs (identifiantUtilisateur,nomUtilisateur, prenomUtilisateur, loginUtilisateur, emailUtilisateur, abonnementUtilisateur, motDePasseChiffreUtilisateur,motDePasseOublie, motDePasseOublieToken, expirationToken, motDePasseModifie, loginModifie, emailModifie) 
                VALUES                         ('{$uuid}',             '{$nom}',       '{$prenom}',       '{$user}',        '{$email}',       '{$aboUser}',          '{$password}',                 NULL,             NULL,                  NULL,            NULL,              NULL,         NULL)";
                $req = $this->bdd->prepare($sql);
                return $req->execute();
            }
        }

        public function insertionBDD(String $uuid){
            // $sql = "INSERT INTO motDePasse (motDePasseChiffre, token, utilisateurLie, motDePasseModifie) VALUES (NULL,NULL,'{$uuid}',0)";
            // $req = $this->bdd->prepare($sql);
            // $req->execute();
            // $sql1 = "INSERT INTO emails (email, token, utilisateurLie, emailModifie) VALUES (NULL,NULL,'{$uuid}',0)";
            // $req = $this->bdd->prepare($sql1);
            // $req->execute();
            // $sql2 = "INSERT INTO logins (`login`, token, utilisateurLie, loginModifie) VALUES (NULL,NULL,'{$uuid}',0)";
            // $req = $this->bdd->prepare($sql2);
            // $req->execute();
            // $sql2 = "INSERT INTO modifications (typeModification, modification, token, utilisateurLie) VALUES (NULL,NULL,NULL,'{$uuid}')";
            // $req = $this->bdd->prepare($sql2);
            // $req->execute();
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
            if($this->verifierEmail($nouvelEmail)){
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

        
        public function modificationEnCours(){
            //TODO: vérifier qu'un utilisateur ne peut pas avoir plusieurs modification du même type
        }

        // on place dans la table 'motDePasse' le nouveau mdp en attente de confirmation par mail
        //public function motDePasseModifie(String $identifiant, String $motDePasseChiffre, String $token)
        public function modification(String $identifiantUtilisateur, String $typeModification, String $modification, String $token)
        {
            switch($typeModification){
                case 'motDePasse':
                    $req1  = $this->bdd->prepare("UPDATE utilisateurs SET motDePasseModifie = '1' WHERE identifiantUtilisateur IN (:identifiant);");
                    $req1->bindValue(":identifiant", $identifiantUtilisateur);
                    break;
                case 'login':
                    $req1  = $this->bdd->prepare("UPDATE utilisateurs SET loginModifie = '1' WHERE identifiantUtilisateur IN (:identifiant);");
                    $req1->bindValue(":identifiant", $identifiantUtilisateur);
                    break;
                case 'email':
                    $req1  = $this->bdd->prepare("UPDATE utilisateurs SET emailModifie = '1' WHERE identifiantUtilisateur IN (:identifiant);");
                    $req1->bindValue(":identifiant", $identifiantUtilisateur);
                    break;
            }
            $req1->execute();

            $req  = $this->bdd->prepare("INSERT INTO modifications  (typeModification,     modification,     token,      utilisateurLie) 
                                         VALUES                   ('{$typeModification}','{$modification}','{$token}', '{$identifiantUtilisateur}')");
            $req->execute();

            return $req->fetch(PDO::FETCH_ASSOC);
        }

        // public function confirmerMotDePasseModifie(String $identifiant, String $token)
        public function confirmerModification(String $identifiant, String $token)
        {
            // récupération de la nouvelle modification via le token
            $reqmodif = $this->bdd->prepare("SELECT modification, typeModification FROM modifications WHERE token IN (:token)");
            $reqmodif->bindValue(":token", $token);
            $reqmodif->execute();
            $modif = $reqmodif->fetch(PDO::FETCH_ASSOC);
            $nouvelleModification = $modif['modification'];

            // si le token de la modification est dans la base, mettre à jour l'utilisateur dans la base et mettre son bool à 0
            if($nouvelleModification){
                switch($modif['typeModification']){
                    case 'motDePasse':
                        $req = $this->bdd->prepare("UPDATE utilisateurs SET motDePasseChiffreUtilisateur = '$nouvelleModification', motDePasseModifie = 0 WHERE identifiantUtilisateur IN (:identifiant);");
                        break;
                    case 'login':
                        $req = $this->bdd->prepare("UPDATE utilisateurs SET loginUtilisateur = '$nouvelleModification', loginModifie = 0 WHERE identifiantUtilisateur IN (:identifiant);");
                        break;
                    case 'email':
                        $req = $this->bdd->prepare("UPDATE utilisateurs SET emailUtilisateur = '$nouvelleModification', emailModifie = 0 WHERE identifiantUtilisateur IN (:identifiant);");
                        break;
                }
                $req->bindValue(":identifiant", $identifiant);
                $req->execute();

                // suppression de l'insertion dans la table 'modifications' via le token
                $reqmodifmdp = $this->bdd->prepare("DELETE FROM modifications WHERE token IN (:token)");
                $reqmodifmdp->bindValue(":token", $token);
                $reqmodifmdp->execute();
            }
            return $modif['typeModification'];
        }

        function templateEmailModification($destinataire, $motifModif, $lien){
            $sujet = '[OCRSQUARE] Confirmation changement '.$motifModif.'';
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
            $headers .= 'From: OCRSQUARE <etglsquare@gmail.com>' . "\r\n";
            $message = '
            <html>
            <head>
            <title>Bonjour</title>
            </head>
            <body>
            <p>Bonjour ,</p>
            <p>Vous venez de faire la demande de modification de votre '. $motifModif .'. Pour confirmer cliquer <a href="'.$lien.'">ici</a>.</p>
            <p>Merci,</p>
            <p>L\'équipe OCRSQUARE<p>
            </body>
            </html>
            ';
            $this->envoyerMail($destinataire, $sujet, $message, $headers);
        }

        function templateMessageSucces($email, $text, $returnLink){
            echo '
            <head>
            <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">    
            </head>
            <body>
            <div class="card">
            <div style="border-radius:200px; height:200px; width:200px; background: #F8FAF5; margin:0 auto;">
            <i class="checkmark">✓</i>
            </div>
            <h1>Success</h1> ';
            echo $text;
            echo "<b>". $email ."</b></div>";
            echo $returnLink;
            echo '
            <style>
            body {
            text-align: center;
            padding: 40px 0;
            background: #EBF0F5;
            }
            h1 {
                color: #88B04B;
                font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
                font-weight: 900;
                font-size: 40px;
                margin-bottom: 10px;
            }
            p {
                color: #404F5E;
                font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
                font-size:20px;
                margin: 0;
            }
            i {
            color: #9ABC66;
            font-size: 100px;
            line-height: 200px;
            margin-left:-15px;
            }
            .card {
            background: white;
            padding: 60px;
            border-radius: 4px;
            box-shadow: 0 2px 3px #C8D0D8;
            display: inline-block;
            margin: 0 auto;
            margin-top: 150px;
            }
            </style>
            ';
        }

        function messageModification($motif){
            return "<p>Un lien de confirmation de modification de votre ".$motif." a été envoyé à l'adresse suivante :<br/></p>";
        }

        function messageConfirmation($motif){
            return "<p>Confirmation du changement de votre ".$motif." !<br/></p> </div></body>";
        }
    }