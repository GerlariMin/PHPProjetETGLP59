<?php
/**
 * Classe dédiée aux logs
 */
class Logs {

    private array $config = array();
    private string $fichierLog;
    private string $repertoireFichierLog;
    private array $typesLogs = [
            0 => 'ALERT',
            1 => 'CRITICAL',
            2 => 'DEBUG',
            3 => 'ERROR',
            4 => 'INFO',
            5 => 'NOTICE',
            6 => 'PANIC',
            7 => 'WARNING',
        ];
    // Variables publiques qui seront utilisées lors de l'appel de la fonction messageLog dans le code des fichiers PHP externes
    public int $typeAlert = 0;
    public int $typeCritical = 1;
    public int $typeDebug = 2;
    public int $typeError = 3;
    public int $typeInfo = 4;
    public int $typeNotice = 5;
    public int $typePanic = 6;
    public int $typeWarning = 7;
    // Constructeur
    public function __construct(array $config) {
        $this->config = $config;
        $this->repertoireFichierLog = $this->config['logs']['emplacement'];
        $dateDuJour = date('Y-m-d');
        $this->fichierLog = $this->config['logs']['fichier'] . '-' . $dateDuJour . '.log';
        $this->creerRepertoire();
        $this->creerFichierLog();
    }
    // Création du répertoire dédié aux logs
    private function creerRepertoire() {
        // Si le répertoire de logs n'existe pas
        if(!file_exists($this->repertoireFichierLog)) {
            // Création du répertoire
            mkdir($this->repertoireFichierLog);
        }
    }
    // Création du fichier de logs
    private function creerFichierLog() {
        // Si le fichier de logs n'existe pas
        if(!file_exists($this->repertoireFichierLog . $this->fichierLog)) {
            // On ouvre le fichier voulu pour la première fois, ce qui va le créer
            $fichierLog = fopen($this->repertoireFichierLog . $this->fichierLog, 'w');
            // On ferme le fichier
            fclose($fichierLog);
            // On verrouille le fichier en droits
            chmod($this->repertoireFichierLog . $this->fichierLog, 0444);
            // On indique dans le fichier de Log le moment de la création.
            $this->messageLog('Initialisation du fichier de Logs quotidien.', $this->typeNotice);
        }
    }
    // Génération du message de logs
    public function messageLog(string $messageLog, int $typeLog = 4) {
        $trace = ' [TRACE] - ';
        // On parcourt l'ensemble du tableau contenant la trace de l'origine de l'appel de la méthode
        foreach(debug_backtrace() as $traceCourante) {
            // On récupère le fichier source courant
            $trace .= $traceCourante['file'] . ' -> ';
        }
        // On rajoute la source de ce fichier comme destination finale de la trace
        $trace .= __FILE__;
        // On récupère le type de log qu'il faut afficher
        $type = $this->typesLogs[$typeLog];
        // On formate le message à afficher dans le fichier de logs
        $message = $type . ' - ' . date('Y-m-d h:i:s') . '\n\t [MESSAGE] - ' . $messageLog . '\n\t' . $trace . '\n';
        // On déverrouille le fichier de logs le temps d'ajouter une ligne
        chmod($this->repertoireFichierLog . $this->fichierLog, 0644);
        // On ouvre le fichier de logs
        $fichierLog = fopen($this->repertoireFichierLog . $this->fichierLog, 'a');
        // On écrit le message dans le fichier de logs
        fwrite($fichierLog, $message);
        // On ferme le fichier de logs
        fclose($fichierLog);
        // On verrouille le fichier de logs après avoir ajouter une ligne
        chmod($this->repertoireFichierLog . $this->fichierLog, 0444);
    }
}