<?php

class Mail
{
    /**
     * @var array
     */
    private array $config;
    /**
     * @var Logs
     */
    private Logs $logs;

    /**
     * @param array $config
     * @param Logs $logs
     */
    public function __construct(array $config, Logs $logs)
    {
        $this->config = $config;
        $this->logs = $logs;
        $this->logs->messageLog('Initialisation de la classe Mail terminée', $this->logs->typeNotice);
    }

    /**
     * @param String $email
     * @param String $sujet
     * @param String $message
     * @param String $headers
     * @return bool
     */
    protected function envoyerMail(String $email, String $sujet, String $message, String $headers): bool
    {
        $this->logs->messageLog('Tentative d\'envoi de mail à l\'adresse "' . $email . '".', $this->logs->typeDebug);
        // Si l'envoi de mail s'est bien déroulé
        if (mail($email, $sujet, $message, $headers)) {
            $this->logs->messageLog('Mail envoyé.', $this->logs->typeNotice);
            return true;
        }
        // Le mail ne s'est pas envoyé
        $this->logs->messageLog('Problème lors de l\'envoi du mail!', $this->logs->typeAlert);
        return false;
    }
}