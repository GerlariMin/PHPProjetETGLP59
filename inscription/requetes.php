<?php
require_once('../ressources/php/Model.php');
/**
 * Classe dédiée aux requête propres au module de connexion.
 */
class RequetesInscription extends Model {

    /**
     * @var Logs logs
     */
    private Logs $logs;
    /**
     * @var Model model
     */
    private Model $model;

    /**
     * Constructeur de la classe
     * Initialise ses attributs privés dédiés aux logs et à l'accès à la base de données
     */
    public function __construct(array $config, Logs $logs) {
        $this->logs = $logs;
        $this->model = Model::getModel($config, $logs);
    }

    /**
     * @param String $uuid
     * @param String $nom
     * @param String $prenom
     * @param String $user
     * @param String $email
     * @param String $aboUser
     * @param String $password
     * @return bool
     */
    public function insererUtilisateur(String $uuid, String $nom, String $prenom, String $user, String $email, String $password, int $aboUser = 1): bool
    {
        // vérification séparée pour bien indiquer à l'utilisateur quel est le problème
        // Si l'adresse e-mail saisie est déjà stockée en base, on retourne false
        if ($this->model->verifierEmail($email)) {
            $this->logs->messageLog('L\'adresse e-mail saisie par l\'utilisateur est déjà présente en base!', $this->logs->typeAlert);
            return false;
        }
        // Si le login saisi est déjà stocké en base, on retourne false
        if ($this->verifierLogin($user)) {
            $this->logs->messageLog('Le login saisi par l\'utilisateur est déjà présent en base!', $this->logs->typeAlert);
            return false;
        }
        // Texte SQL qui va alimenter la requête
        $sql = "INSERT INTO utilisateurs (identifiantUtilisateur,nomUtilisateur, prenomUtilisateur, loginUtilisateur, emailUtilisateur, abonnementUtilisateur, motDePasseChiffreUtilisateur,motDePasseOublie, motDePasseOublieToken, expirationToken, motDePasseModifie, loginModifie, emailModifie) VALUES (:identifiant, :nom, :prenom, :login, :email, :abonnement, :motDePasse, NULL, NULL, NULL, NULL, NULL, NULL)";
        // Requête SQL a exécuter
        $requete = $this->model->bdd->prepare($sql);
        $this->logs->messageLog('Requete SQL préparée: "' . $sql . '".', $this->logs->typeDebug);
        // Attribution des valeurs de la requête préparée
        $requete->bindValue(':identifiant', $uuid);
        $requete->bindValue(':nom', $nom);
        $requete->bindValue(':prenom', $prenom);
        $requete->bindValue(':login', $user);
        $requete->bindValue(':email', $email);
        $requete->bindValue(':abonnement', $aboUser);
        $requete->bindValue(':motDePasse', $password);
        $this->logs->messageLog('Paramètres: [identifiant: ' . $uuid . ', login: ' . $user . '].', $this->logs->typeDebug);
        // La fonction retourne le résultat de l'exécution de la requête préparée
        return $requete->execute();
    }

    /**
     * @param String $user
     * @return mixed
     */
    private function verifierLogin(String $user): mixed
    {
        // Texte SQL qui va alimenter la requête
        $texteRequete = 'SELECT identifiantUtilisateur FROM utilisateurs WHERE loginUtilisateur IN (:user);';
        // Requête SQL a exécuter
        $requete = $this->model->bdd->prepare($texteRequete);
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
     * @param String $uuid
     * @return int
     */
    public function isUuid(String $uuid): int
    {
        // Texte SQL qui va alimenter la requête
        $texteRequete = 'SELECT COUNT(identifiantUtilisateur) as BOOL FROM utilisateurs WHERE identifiantUtilisateur = :identifiant;';
        // Requête SQL a exécuter
        $requete = $this->model->bdd->prepare($texteRequete);
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
