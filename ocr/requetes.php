<?php
require_once('../ressources/php/Model.php');
/**
 * Classe dédiée aux requête propres au module OCR.
 */
class RequetesOCR extends Model {

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

    public function recupererIdentifiantsDocuments(String $identifiant, String $documents): mixed
    {
        // Texte SQL qui va alimenter la requête
        $texteRequete = 'SELECT identifiantDocument AS value, nomDocument AS text FROM documents WHERE utilisateurLie = :identifiant AND nomDocument IN (' . $documents .');';
        // Requête SQL a exécuter
        $requete = $this->model->bdd->prepare($texteRequete);
        $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
        // Attribution des valeurs de la requête préparée
        $requete->bindValue(':identifiant', $identifiant);
        //$requete->bindValue(':documents', $documents);
        $this->logs->messageLog('Paramètres: [identifiant: ' . $identifiant . ', documents: ' . $documents . '].', $this->logs->typeDebug);
        // Exécution de la requête préparée
        $requete->execute();
        // La fonction retourne le résultat de la requête
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    public function recupererNomsDocuments(String $identifiant, String $identifiantsDocuments): mixed
    {
        // Texte SQL qui va alimenter la requête
        $texteRequete = 'SELECT nomDocument AS DOCUMENT FROM documents WHERE utilisateurLie = :identifiant AND identifiantDocument IN (' . $identifiantsDocuments .');';
        // Requête SQL a exécuter
        $requete = $this->model->bdd->prepare($texteRequete);
        $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
        // Attribution des valeurs de la requête préparée
        $requete->bindValue(':identifiant', $identifiant);
        //$requete->bindValue(':identifiantsDocuments', $identifiantsDocuments);
        $this->logs->messageLog('Paramètres: [identifiant: ' . $identifiant . ', identifiantsDocuments: ' . $identifiantsDocuments . '].', $this->logs->typeDebug);
        // Exécution de la requête préparée
        $requete->execute();
        // La fonction retourne le résultat de la requête
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    public function nouveauTraitement(String $identifiantUtilisateur, bool $traitementAbouti): bool
    {
        $traitementAbouti = ($traitementAbouti) ? '1' : '0';
        // Texte SQL qui va alimenter la requête
        $texteRequete = 'INSERT INTO traitements (utilisateurLie, date, traitementAbouti) VALUES (:identifiantUtilisateur, CURRENT_DATE, :traitementAbouti)';
        // Requête SQL a exécuter
        $requete = $this->model->bdd->prepare($texteRequete);
        $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
        // Attribution des valeurs de la requête préparée
        $requete->bindValue(":identifiantUtilisateur", $identifiantUtilisateur);
        $requete->bindValue(":traitementAbouti", $traitementAbouti);
        $this->logs->messageLog('Paramètres: [ identifiantUtilisateur: "' . $identifiantUtilisateur . '", traitementeAbouti: "' . $traitementAbouti . '"].', $this->logs->typeDebug);
        // Exécution de la requête préparée
        return $requete->execute();
    }
}