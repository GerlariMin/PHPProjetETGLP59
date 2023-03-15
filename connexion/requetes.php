<?php
require_once('../ressources/php/Model.php');
/**
 * Classe dédiée aux requête propres au module de connexion.
 */
class RequetesConnexion extends Model {

    /**
     * @var Logs logs
     */
    private Logs $logs;
    /**
     * @var Model model
     */
    private Model $model;

    /**
     * Constructeur de la classe qui va initialiser ses attributs privés dédiés aux logs et à l'accès à la base de données
     */
    public function __construct(array $config, Logs $logs) {
        $this->logs = $logs;
        $this->model = Model::getModel($config, $logs);
    }

    /**
     * Méthode dédiée à récupérer l'identifiant unique attribué à un utilisateur, à partir du login (unique) ou de l'adresse e-mail (unique) associée.
     */
    public function recupererIdentifiant(String $login): mixed
    {
        // Texte SQL qui va alimenter la requête
        $texteRequete = 'SELECT identifiantUtilisateur AS IDENTIFIANT FROM utilisateurs WHERE emailUtilisateur = :login OR loginUtilisateur = :login;';
        // Requête SQL a exécuter
        $requete = $this->model->bdd->prepare($texteRequete);
        $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
        // Attribution des valeurs de la requête préparée
        $requete->bindValue(':login', $login);
        $this->logs->messageLog('Paramètres: [login: ' . $login . '].', $this->logs->typeDebug);
        // Exécution de la requête préparée
        $requete->execute();
        // La fonction retourne le résultat de la requête
        return $requete->fetch(PDO::FETCH_ASSOC)['IDENTIFIANT'];
    }

    /**
     * Méthode dédiée à la récupération du mot de passe chiffré associé à un identifiant (unique) donné.
     */
    public function recupererMotDePasseCourant(String $identifiant): mixed
    {
        // Texte SQL qui va alimenter la requête
        $texteRequete = 'SELECT motDePasseChiffreUtilisateur AS PHRASE FROM utilisateurs WHERE identifiantUtilisateur = :identifiant AND motDePasseOublie = FALSE;';
        // Requête SQL a exécuter
        $requete = $this->model->bdd->prepare($texteRequete);
        $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
        // Attribution des valeurs de la requête préparée
        $requete->bindValue(':identifiant', $identifiant);
        $this->logs->messageLog('Paramètres: [identifiant: ' . $identifiant . '].', $this->logs->typeDebug);
        // Exécution de la requête préparée
        $requete->execute();
        // La fonction retourne le résultat de la requête
        return $requete->fetch(PDO::FETCH_ASSOC)['PHRASE'];
    }

    /**
     * Méthode dédiée à la récupération des informations utiles à la création de sessions d'un utilisateur, à partir d'un login (unique) et d'un identifiant (unique) donnés.
     */
    public function recupererUtilisateur(string $login, string $identifiant): mixed
    {
        // Texte SQL qui va alimenter la requête
        $texteRequete = 'SELECT loginUtilisateur AS LOGIN, nomUtilisateur AS NOM, prenomUtilisateur AS PRENOM, emailUtilisateur AS EMAIL FROM utilisateurs WHERE (emailUtilisateur = :login OR loginUtilisateur = :login) AND identifiantUtilisateur = :identifiant;';
        // Requête SQL a exécuter
        $requete = $this->model->bdd->prepare($texteRequete);
        $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
        // Attribution des valeurs de la requête préparée
        $requete->bindValue(':login', $login);
        $requete->bindValue(':identifiant', $identifiant);
        $this->logs->messageLog('Paramètres: [login: ' . $login . ', identifiant: ' . $identifiant . '].', $this->logs->typeDebug);
        // Exécution de la requête préparée
        $requete->execute();
        // La fonction retourne le résultat de la requête
        return $requete->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * 
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