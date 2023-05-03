<?php
if (file_exists('../ressources/php/Model.php')) {
    require_once('../ressources/php/Model.php');
}
/**
 * Classe dédiée aux requête propres au module de connexion.
 */
class RequetesSouscription extends Model {

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
    public function recupererAbonnementsDisponibles(): mixed
    {
        // Texte SQL qui va alimenter la requête
        $texteRequete = 'SELECT identifiantAbonnement AS IDENTIFIANT,
                                typeAbonnement AS TYPE,
                                limiteDocuments AS DOCUMENTS,
                                limiteStockage AS STOCKAGE,
                                promotion AS PROMO,
                                pourcentagePromotion AS REDUCTION,
                                prixAbonnement AS PRIX
                        FROM abonnements
                        WHERE disponible = 1;';
        // Requête SQL a exécuter
        $requete = $this->model->bdd->prepare($texteRequete);
        $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
        $this->logs->messageLog('Paramètres: aucuns.', $this->logs->typeDebug);
        // Exécution de la requête préparée
        $requete->execute();
        // La fonction retourne le résultat de la requête
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param $identifiantUtilisateur
     * @param $abonnement
     * @return bool
     */
    public function miseAJourAbonnementutilisateur($identifiantUtilisateur, $abonnement): bool
    {
        // Texte SQL qui va alimenter la requête
        $texteRequete = 'UPDATE utilisateurs SET abonnementUtilisateur = :abonnement WHERE identifiantUtilisateur IN (:identifiant);';
        // Requête SQL a exécuter
        $requete = $this->model->bdd->prepare($texteRequete);
        $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
        // Attribution des valeurs de la requête préparée
        $requete->bindValue(':abonnement', $abonnement);
        $requete->bindValue(':identifiant', $identifiantUtilisateur);
        $this->logs->messageLog('Paramètres: [abonnement: "' . $abonnement . '", identifiant: "' . $identifiantUtilisateur . '"].', $this->logs->typeDebug);
        // Exécution de la requête préparée
        return $requete->execute();
    }

    /**
     * @param $montant
     * @param $dateactuelle
     * @param $dateFin
     * @param $utilisateur
     * @return bool
     */
    public function ajouterFacturation($montant, $dateactuelle, $dateFin, $utilisateur)
    {
        // Texte SQL qui va alimenter la requête
        $texteRequete = "INSERT INTO Facturations(montant, datePaiement, dateDeFinAchat, utilisateurLie) VALUES (:montant, :dateactuelle, :dateFin, :utilisateur);";
        // Requête SQL a exécuter
        $req = $this->model->bdd->prepare($texteRequete);
        $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
        // Attribution des valeurs de la requête préparée
        $req->bindValue(":montant", $montant);
        $req->bindValue(":dateactuelle", $dateactuelle);
        $req->bindValue(":dateFin", $dateFin);
        $req->bindValue(":utilisateur", $utilisateur);
        $this->logs->messageLog('Paramètres: [montant: "' . $montant . '", dateactuelle: "' . $dateactuelle . '", dateFin: "' . $dateFin . '", utilisateur: "' . $utilisateur . '"].', $this->logs->typeDebug);
        // Exécution de la requête préparée
        return $req->execute();
    }
}