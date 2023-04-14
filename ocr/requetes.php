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
        // Conversion du bolléen pour le tinyInt
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

    public function documentDejaExistant(String $nomDocument, String $identifiantUtilisateur): mixed
    {
        // Texte SQL qui va alimenter la requête
        $texteRequete = 'SELECT nomDocument AS DOCUMENT FROM documents WHERE utilisateurLie = :identifiant AND nomDocument = :nomDocument;';
        // Requête SQL a exécuter
        $requete = $this->model->bdd->prepare($texteRequete);
        $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
        // Attribution des valeurs de la requête préparée
        $requete->bindValue(':identifiant', $identifiantUtilisateur);
        $requete->bindValue(':nomDocument', $nomDocument);
        $this->logs->messageLog('Paramètres: [identifiant: ' . $identifiantUtilisateur . ', nomDocument: ' . $nomDocument . '].', $this->logs->typeDebug);
        // Exécution de la requête préparée
        $requete->execute();
        // La fonction retourne le résultat de la requête
        return $requete->fetch(PDO::FETCH_ASSOC);
    }

    public function recupererNbTraitementOCR(String $identifiant)
    {
        // Texte SQL qui va alimenter la requête
        $texteRequete = 'SELECT count(*) AS TOTAL FROM traitements WHERE utilisateurLie = :identifiant AND date = CURRENT_DATE AND traitementAbouti = 1;';
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
        return $requete->fetch(PDO::FETCH_ASSOC)['TOTAL'];
    }

    public function recupererLimiteTraitementOCR(String $identifiant)
    {
        // Texte SQL qui va alimenter la requête
        $texteRequete = 'SELECT limiteTraitements AS LOCR FROM abonnements WHERE identifiantAbonnement = (SELECT abonnementUtilisateur FROM utilisateurs WHERE identifiantUtilisateur = :identifiant);';
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
        return $requete->fetch(PDO::FETCH_ASSOC)['LOCR'];
    }
}