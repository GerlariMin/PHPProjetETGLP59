<?php
require_once('../ressources/php/Model.php');
/**
 * Classe dédiée aux requête propres au module de connexion.
 */
class RequetesTableauDeBord extends Model {

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

    public function recupererLimitesAbonnement(String $identifiantAbonnement): mixed
    {
        // Texte SQL qui va alimenter la requête
        $texteRequete = 'SELECT limiteDocuments AS LDOC, limiteStockage AS LSTOCK, limiteTraitements AS LOCR, typeAbonnement AS TYPE FROM abonnements WHERE identifiantAbonnement = :identifiantAbonnement;';
        // Requête SQL a exécuter
        $requete = $this->model->bdd->prepare($texteRequete);
        $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
        // Attribution des valeurs de la requête préparée
        $requete->bindValue(':identifiantAbonnement', $identifiantAbonnement);
        $this->logs->messageLog('Paramètres: [identifiantAbonnement: ' . $identifiantAbonnement . '].', $this->logs->typeDebug);
        // Exécution de la requête préparée
        $requete->execute();
        // La fonction retourne le résultat de la requête
        return $requete->fetch(PDO::FETCH_ASSOC);
    }

    public function recupererAbonnementUtilisateur(String $identifiant): mixed
    {
        // Texte SQL qui va alimenter la requête
        $texteRequete = 'SELECT abonnementUtilisateur AS ABONNEMENT FROM utilisateurs WHERE identifiantUtilisateur = :identifiant;';
        // Requête SQL a exécuter
        $requete = $this->model->bdd->prepare($texteRequete);
        $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
        // Attribution des valeurs de la requête préparée
        $requete->bindValue(':identifiant', $identifiant);
        $this->logs->messageLog('Paramètres: [identifiant: ' . $identifiant . '].', $this->logs->typeDebug);
        // Exécution de la requête préparée
        $requete->execute();
        // La fonction retourne le résultat de la requête
        return $requete->fetch(PDO::FETCH_ASSOC)['ABONNEMENT'];
    }

    public function recupererTraitementsUtilisateur(String $identifiant): mixed
    {
        // Texte SQL qui va alimenter la requête
        $texteRequete = 'SELECT count(*) AS NBT FROM traitements WHERE utilisateurLie = :identifiant AND traitementAbouti = 1;';
        // Requête SQL a exécuter
        $requete = $this->model->bdd->prepare($texteRequete);
        $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
        // Attribution des valeurs de la requête préparée
        $requete->bindValue(':identifiant', $identifiant);
        $this->logs->messageLog('Paramètres: [identifiant: ' . $identifiant . '].', $this->logs->typeDebug);
        // Exécution de la requête préparée
        $requete->execute();
        // La fonction retourne le résultat de la requête
        return $requete->fetch(PDO::FETCH_ASSOC)['NBT'];
    }
}