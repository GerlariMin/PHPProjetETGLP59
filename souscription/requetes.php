<?php
require_once('../ressources/php/Model.php');
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
        $this->model = Model::get_model($config);
    }

    /**
     * Méthode dédiée à récupérer l'identifiant unique attribué à un utilisateur, à partir du login (unique) ou de l'adresse e-mail (unique) associée.
     */
    public function recupererAbonnementsDisponibles(): mixed
    {
        // Texte SQL qui va alimenter la requête
        $texteRequete = "SELECT identifiantAbonnement AS IDENTIFIANT,
                                typeAbonnement AS TYPE,
                                limiteDocuments AS DOCUMENTS,
                                limiteStockage AS STOCKAGE,
                                promotion AS PROMO,
                                pourcentagePromotion AS REDUCTION,
                                prixAbonnement AS PRIX
                        FROM abonnements
                        WHERE disponible = 1;";
        // Requête SQL a exécuter
        $requete = $this->model->bdd->prepare($texteRequete);
        $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
        $this->logs->messageLog('Paramètres: aucuns.', $this->logs->typeDebug);
        // Exécution de la requête préparée
        $requete->execute();
        // La fonction retourne le résultat de la requête
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }
}