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
        $this->model = Model::get_model($config);
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
}