<?php

/**
 * Classe TraitementConnexion
 */
class TraitementAjouterDocument
{
    /**
     * @var Logs logs
     */
    private Logs $logs;
    /**
     * @var Render render
     */
    private Render $render;
    /**
     * @var array config
     */
    private array $config;
    /**
     * @var TexteAjouterDocument texte
     */
    private TexteAjouterDocument $texte;
    /**
     * @var string dédiées à la gestion des erreurs
     */
    private String $iClass = 'iClass';
    private String $strong = 'strong';
    private String $small = 'small';
    private String $message = 'message';

    /**
     * Traitement_Accueil constructor.
     * @param Logs $logs
     * @param Render $rendu
     */
    public function __construct(Logs $logs, Render $rendu)
    {
        global $config;
        $this->config = $config;
        $this->logs = $logs;
        $this->render = $rendu;
        $this->texte = new TexteAjouterDocument($this->config);
    }

    /**
     * Gestion des erreurs pour prévenir l'utilisateur.
     * @param string $codeErreur
     * @return array
     */
    private function traitementErreur(string $codeErreur = ''): array
    {
        // Initialisation du tableau d'erreur a retourner
        $erreur = array();
        // En fonction du code d'erreur reçu en paramètre, on rempli le tableau dédié à l'affichage du bloc d'erreur
        switch($codeErreur) {
            case 'f':
                $erreur[$this->iClass] = 'fa-solid fa-file';
                $erreur[$this->strong] = 'Erreur';
                $erreur[$this->small] = 'Formulaire';
                $erreur[$this->message] = 'Aucun fichiers à transférer!';
                break;
            case 'fup':
                $erreur[$this->iClass] = 'fa-solid fa-server';
                $erreur[$this->strong] = 'Erreur';
                $erreur[$this->small] = 'Téléchargement sur serveur';
                $erreur[$this->message] = 'Un problème est survenu lors du téléchargement de vos fichiers vers notre serveur!';
                break;
            case 'fbdd':
                $erreur[$this->iClass] = 'fa-solid fa-database';
                $erreur[$this->strong] = 'Erreur';
                $erreur[$this->small] = 'Document non ajouté';
                $erreur[$this->message] = 'Un problème est survenu lors du téléchargement de vos fichiers vers notre serveur!';
                break;
            case 'fsize':
                $erreur[$this->iClass] = 'fa-solid fa-database';
                $erreur[$this->strong] = 'Erreur';
                $erreur[$this->small] = 'Taille Document';
                $erreur[$this->message] = 'Un ou plusieurs documents est trop volumineux! Veuillez sélectionner un document n\'excédant pas ' . ini_get('upload_max_filesize');
                break;
            default:
                $erreur[$this->iClass] = 'fa-solid fa-bomb';
                $erreur[$this->strong] = 'Erreur';
                $erreur[$this->small] = 'inconnue';
                $erreur[$this->message] = 'Une erreur est survenue!';
                break;
        }
        // On retourne le tableau d'erreur formaté
        return $erreur;
    }

    /**
     * Gestion des succès pour prévenir l'utilisateur.
     * @param string $codeSucces
     * @return array
     */
    private function traitementSucces(string $codeSucces = ''): array
    {
        // Initialisation du tableau de succès a retourner
        $erreur = array();
        // En fonction du code de succès reçu en paramètre, on rempli le tableau dédié à l'affichage du bloc succès
        switch ($codeSucces) {
            case 'fok':
                $erreur[$this->iClass] = 'fa-solid fa-thumbs-up';
                $erreur[$this->strong] = 'Succès';
                $erreur[$this->small] = 'Envoi fichier';
                $erreur[$this->message] = 'Opération effectuée avec succès!';
                break;
            default:
                $erreur[$this->iClass] = 'fa-solid fa-circle-check';
                $erreur[$this->strong] = 'Succès';
                $erreur[$this->small] = 'succès';
                $erreur[$this->message] = 'Opération effectuée avec succès!';
                break;
        }
        // On retourne le tableau de succès formaté
        return $erreur;
    }

    /**
     * Affichage de la page de connexion.
     * @param string $codeErreur
     * @param string $codeSucces
     */
    public function traitementRendu(string $codeErreur = '', string $codeSucces = ''): void
    {
        try {
            // On récupère le tableau formaté pour Mustache
            $data = $this->texte->texteFinal();
            // On rajoute des clés au tableau dédié à Mustache pour afficher la page de connexion
            $data['chemin'] = $this->config['variables']['chemin'];
            $data['ajouterDocument'] = true;
            // Si un codeErreur existe, on ajoute les donées au tableau Mustache pour afficher le blocErreur
            if ($codeErreur) {
                $data['blocErreur'] = $this->traitementErreur($codeErreur);
            }
            // Si un codeSucces existe, on ajoute les donées au tableau Mustache pour afficher le blocErreur
            if ($codeSucces) {
                $data['blocSucces'] = $this->traitementSucces($codeSucces);
            }
            // On génère l'affichage Mustache
            $this->render->actionRendu($data);
        } catch (Exception $e) {
            // En cas de problème, on affiche le résultat dans les logs
            $this->logs->messageLog('Erreur lors de l\'affichage du module de connexion. Erreur: ' . $e->getMessage(), $this->logs->typeCritical);
        }
    }

}