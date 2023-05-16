<?php
if (file_exists('../ressources/php/Model.php')) {
    require_once('../ressources/php/Model.php');
}
/**
 * Classe dédiée aux requête propres au module profil.
 */
class RequetesProfil extends Model {

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
     * @param String $identifiant
     * @param String $nouveauNom
     * @return bool
     */
    public function modificationNom(String $identifiant, String $nouveauNom): bool
    {
        // Texte SQL qui va alimenter la requête
        $texteRequete = 'UPDATE utilisateurs SET nomUtilisateur = :nouveauNom WHERE identifiantUtilisateur IN (:identifiant);';
        // Requête SQL a exécuter
        $requete = $this->model->bdd->prepare($texteRequete);
        $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
        // Attribution des valeurs de la requête préparée
        $requete->bindValue(':nouveauNom', $nouveauNom);
        $requete->bindValue(':identifiant', $identifiant);
        $this->logs->messageLog('Paramètres: [nouveauNom: "' . $nouveauNom . '", identifiant: "' . $identifiant . '"].', $this->logs->typeDebug);
        // Exécution de la requête préparée
        return $requete->execute();
    }

    /**
     * @param String $identifiant
     * @param String $nouveauPrenom
     * @return bool
     */
    public function modificationPrenom(String $identifiant, String $nouveauPrenom): bool
    {
        // Texte SQL qui va alimenter la requête
        $texteRequete = 'UPDATE utilisateurs SET prenomUtilisateur = :nouveauPrenom WHERE identifiantUtilisateur IN (:identifiant);';
        // Requête SQL a exécuter
        $requete = $this->model->bdd->prepare($texteRequete);
        $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
        // Attribution des valeurs de la requête préparée
        $requete->bindValue(':nouveauPrenom', $nouveauPrenom);
        $requete->bindValue(':identifiant', $identifiant);
        $this->logs->messageLog('Paramètres: [nouveauPrenom: "' . $nouveauPrenom . '", identifiant: "' . $identifiant . '"].', $this->logs->typeDebug);
        // Exécution de la requête préparée
        return $requete->execute();
    }

    /**
     * @param String $identifiant
     * @return mixed
     */
    public function donneesUtilisateur(String $identifiant): mixed
    {
        // Texte SQL qui va alimenter la requête
        $texteRequete = 'SELECT * FROM utilisateurs WHERE identifiantUtilisateur IN (:identifiant);';
        // Requête SQL a exécuter
        $requete = $this->model->bdd->prepare($texteRequete);
        $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
        // Attribution des valeurs de la requête préparée
        $requete->bindValue(":identifiant", $identifiant);
        $this->logs->messageLog('Paramètres: [identifiant: "' . $identifiant . '"].', $this->logs->typeDebug);
        // Exécution de la requête préparée
        $requete->execute();
        // La fonction retourne le résultat de la requête
        return $requete->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @param String $identifiant
     * @return mixed
     */
    public function recupererEmailUtilisateur(String $identifiant): mixed
    {
        // Texte SQL qui va alimenter la requête
        $texteRequete = 'SELECT emailUtilisateur FROM utilisateurs WHERE identifiantUtilisateur IN (:identifiant);';
        // Requête SQL a exécuter
        $requete = $this->model->bdd->prepare($texteRequete);
        $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
        // Attribution des valeurs de la requête préparée
        $requete->bindValue(":identifiant", $identifiant);
        $this->logs->messageLog('Paramètres: [identifiant: "' . $identifiant . '"].', $this->logs->typeDebug);
        // Exécution de la requête préparée
        $requete->execute();
        // La fonction retourne le résultat de la requête
        return $requete->fetch(PDO::FETCH_ASSOC)['emailUtilisateur'];
    }

    /**
     * @param String $typeModification
     * @param String $identifiantUtilisateur
     * @return bool
     */
    private function modificationTableUtilisateurs(String $typeModification, String $identifiantUtilisateur): bool
    {
        // Texte SQL qui va alimenter la requête
        $texteRequete = match ($typeModification) {
            'motDePasse' => "UPDATE utilisateurs SET motDePasseModifie = '1' WHERE identifiantUtilisateur IN (:identifiant);",
            'login' => "UPDATE utilisateurs SET loginModifie = '1' WHERE identifiantUtilisateur IN (:identifiant);",
            'email' => "UPDATE utilisateurs SET emailModifie = '1' WHERE identifiantUtilisateur IN (:identifiant);",
            default => false
        };
        // Si on a bien une requête a exécuter
        if ($texteRequete !== false) {
            // Requête SQL a exécuter
            $requete = $this->model->bdd->prepare($texteRequete);
            $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
            // Attribution des valeurs de la requête préparée
            $requete->bindValue(":identifiant", $identifiantUtilisateur);
            $this->logs->messageLog('Paramètres: [identifiant: "' . $identifiantUtilisateur . '"].', $this->logs->typeDebug);
            // On retourne le résultat de l'exécution
            return $requete->execute();
        }
        // Si aucune requête à exécuter, on retourne false
        return false;
    }

    /**
     * @param String $identifiantUtilisateur
     * @param String $typeModification
     * @param String $modification
     * @param String $token
     * @return bool
     */
    private function modificationTableModifications(String $identifiantUtilisateur, String $typeModification, String $modification, String $token): bool
    {
        // Texte SQL qui va alimenter la requête
        $texteRequete = 'INSERT INTO modifications (typeModification, modification, token, utilisateurLie) VALUES (:typeModification, :modification, :token, :identifiantUtilisateur)';
        // Requête SQL a exécuter
        $requete = $this->model->bdd->prepare($texteRequete);
        $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
        // Attribution des valeurs de la requête préparée
        $requete->bindValue(":typeModification", $typeModification);
        $requete->bindValue(":modification", $modification);
        $requete->bindValue(":token", $token);
        $requete->bindValue(":identifiantUtilisateur", $identifiantUtilisateur);
        $this->logs->messageLog('Paramètres: [typeModification: "' . $typeModification . '", modification: "' . $modification . '", token: "' . $token . '", identifiantUtilisateur: "' . $identifiantUtilisateur . '"].', $this->logs->typeDebug);
        // Exécution de la requête préparée
        return $requete->execute();
    }

    /**
     * On place dans la table 'motDePasse' le nouveau mdp en attente de confirmation par mail
     * @param String $identifiantUtilisateur
     * @param String $typeModification
     * @param String $modification
     * @param String $token
     * @return bool
     */
    public function modification(String $identifiantUtilisateur, String $typeModification, String $modification, String $token): bool
    {
        // Modifications pour la table utilisateurs
        $modificationUtilisateurs = $this->modificationTableUtilisateurs($typeModification, $identifiantUtilisateur);
        $this->logs->messageLog('Modification table utilisateurs: ' . $modificationUtilisateurs . '.', $this->logs->typeDebug);
        // Modifications pour la table modifications
        $modificationModifications = $this->modificationTableModifications($identifiantUtilisateur, $typeModification, $modification, $token);
        $this->logs->messageLog('Modification table modifications: ' . $modificationModifications . '.', $this->logs->typeDebug);
        // Check si tout s'est bien déroulé
        if ($modificationUtilisateurs && $modificationModifications) {
            $this->logs->messageLog('Modifications faites sans problèmes.', $this->logs->typeDebug);
            return true;
        }
        // Si ça c'est mal déroulé
        $this->logs->messageLog('Les modifications des tables utilisateurs et/ ou modifications ont rencontrés des problèmes.', $this->logs->typeWarning);
        return false;
    }

    /**
     * @param String $token
     * @return mixed
     */
    private function recupererModification(String $token): mixed
    {
        // Texte SQL qui va alimenter la requête
        $texteRequete = 'SELECT modification, typeModification FROM modifications WHERE token IN (:token);';
        // Requête SQL a exécuter
        $requete = $this->model->bdd->prepare($texteRequete);
        $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
        // Attribution des valeurs de la requête préparée
        $requete->bindValue(":token", $token);
        $this->logs->messageLog('Paramètres: [token: "' . $token . '"].', $this->logs->typeDebug);
        // Exécution de la requête préparée
        $requete->execute();
        // La fonction retourne le résultat de la requête
        return $requete->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @param String $identifiant
     * @param String $typeModification
     * @param String $nouvelleModification
     * @return bool
     */
    private function miseAJourUtilisateursSelonTypeModification(String $identifiant, String $typeModification, String $nouvelleModification): bool
    {
        // Texte SQL qui va alimenter la requête
        $texteRequete = match ($typeModification) {
            'motDePasse' => "UPDATE utilisateurs SET motDePasseChiffreUtilisateur = :nouvelleModification, motDePasseModifie = 0 WHERE identifiantUtilisateur IN (:identifiant);",
            'login' => "UPDATE utilisateurs SET loginUtilisateur = :nouvelleModification, loginModifie = 0 WHERE identifiantUtilisateur IN (:identifiant);",
            'email' => "UPDATE utilisateurs SET emailUtilisateur = :nouvelleModification, emailModifie = 0 WHERE identifiantUtilisateur IN (:identifiant);",
            default => false
        };
        // Requête SQL a exécuter
        $requete = $this->model->bdd->prepare($texteRequete);
        $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
        // Attribution des valeurs de la requête préparée
        $requete->bindValue(":identifiant", $identifiant);
        $requete->bindValue(":nouvelleModification", $nouvelleModification);
        $this->logs->messageLog('Paramètres: [identifiant: "' . $identifiant . '", nouvelleModification: "' . $nouvelleModification .'"].', $this->logs->typeDebug);
        // Exécution de la requête préparée
        return $requete->execute();
    }

    /**
     * @param String $token
     * @return bool
     */
    private function supprimerModificationSelonToken(String $token): bool
    {
        // Texte SQL qui va alimenter la requête
        $texteRequete = 'DELETE FROM modifications WHERE token IN (:token);';
        // Requête SQL a exécuter
        $requete = $this->model->bdd->prepare($texteRequete);
        $this->logs->messageLog('Requete SQL préparée: ' . $texteRequete . '.', $this->logs->typeDebug);
        // Attribution des valeurs de la requête préparée
        $requete->bindValue(":token", $token);
        $this->logs->messageLog('Paramètres: [token: "' . $token . '"].', $this->logs->typeDebug);
        // Exécution de la requête préparée
        return $requete->execute();
    }

    /**
     * @param String $identifiant
     * @param String $token
     * @return bool
     */
    public function confirmerModification(String $identifiant, String $token): bool
    {
        // récupération de la nouvelle modification via le token
        $modification = $this->recupererModification($token);
        // si le token de la modification est dans la base, mettre à jour l'utilisateur dans la base et mettre son bool à 0
        if ($modification['typeModification']) {
            $modificationUtilisateurs = $this->miseAJourUtilisateursSelonTypeModification($identifiant, $modification['typeModification'], $modification['modification']);
            $this->logs->messageLog('Modification table utilisateurs: ' . $modificationUtilisateurs . '.', $this->logs->typeDebug);
            // suppression de l'insertion dans la table 'modifications' via le token
            $suppressionModification = $this->supprimerModificationSelonToken($token);
            $this->logs->messageLog('Suppression table modifications: ' . $suppressionModification . '.', $this->logs->typeDebug);
            if ($modificationUtilisateurs && $suppressionModification) {
                $this->logs->messageLog('Modification table utilisateurs et suppression table modifications réalisées sans problèmes.', $this->logs->typeDebug);
                return true;
            } else {
                $this->logs->messageLog('La modification de la table utilisateurs et/ ou la suppression de la table modifications a/ ont rencontrée/s un problème.', $this->logs->typeWarning);
                return false;
            }
        }
    }

}
