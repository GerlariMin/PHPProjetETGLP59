<?php
require_once('../ressources/php/Model.php');
/**
 * Classe dédiée aux requête propres au module de suppression de fichier.
 */
class RequetesSupprimerDocument extends Model
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
     * Suppression d'un document pour un utilisateur donné
     * @param string $nomDocument
     * @param string $identifiant
     * @return bool
     */
    public function supprimerDocument(string $nomDocument, string $identifiant)
    {
        // Texte SQL qui va alimenter la requête
        $texteRequete = 'DELETE FROM documents WHERE nomDocument = :nomDocument AND utilisateurLie = :identifiant;';
        // Requête SQL a exécuter
        $requete = $this->model->bdd->prepare($texteRequete);
        $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
        // Attribution des valeurs de la requête préparée
        $requete->bindValue(":nomDocument", $nomDocument);
        $requete->bindValue(":identifiant", $identifiant);
        $this->logs->messageLog('Paramètres: [nomDocument: "' . $nomDocument . '", identifiant: "' . $identifiant . '"].', $this->logs->typeDebug);
        // Exécution de la requête préparée
        return $requete->execute();
    }
}