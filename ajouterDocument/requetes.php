<?php
require_once('../ressources/php/Model.php');
/**
 * Classe dédiée aux requête propres au module de connexion.
 */
class RequetesAjouterDocument extends Model
{
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
    public function __construct(array $config, Logs $logs)
    {
        $this->logs = $logs;
        $this->model = Model::getModel($config, $logs);
    }
    /**
     * Ajout du document téléchargé en base
     * @param string $nomDocument
     * @param string $identifiantUtilisateur
     * @return bool
     */
    public function ajouterDocument(string $nomDocument, string $identifiantUtilisateur): bool
    {
        $sql = "INSERT INTO documents(nomDocument, utilisateurLie) VALUES (:nomDocument, :identifiant);";
        $req = $this->model->bdd->prepare($sql);
        $req->bindValue(":nomDocument", $nomDocument);
        $req->bindValue(":identifiant", $identifiantUtilisateur);
        return $req->execute();
    }

    public function recupererLimiteDocuments(String $identifiant)
    {
        // Texte SQL qui va alimenter la requête
        $texteRequete = 'SELECT limiteDocuments AS LDOC FROM abonnements WHERE identifiantAbonnement = (SELECT abonnementUtilisateur FROM utilisateurs WHERE identifiantUtilisateur = :identifiant);';
        // Requête SQL a exécuter
        $requete = $this->model->bdd->prepare($texteRequete);
        $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
        // Attribution des valeurs de la requête préparée
        $requete->bindValue(':identifiant', $identifiant);
        //$requete->bindValue(':identifiantsDocuments', $identifiantsDocuments);
        $this->logs->messageLog('Paramètres: [identifiant: ' . $identifiant . '].', $this->logs->typeDebug);
        // Exécution de la requête préparée
        $requete->execute();
        // La fonction retourne le résultat de la requête
        return $requete->fetch(PDO::FETCH_ASSOC)['LDOC'];
    }
}