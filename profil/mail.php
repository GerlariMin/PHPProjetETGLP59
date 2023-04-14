<?php
require_once('../ressources/php/Mail.php');
/**
 * Classe dédiée aux requête propres au module profil.
 */
class MailProfil extends Mail {

    /**
     * Reprise de la méthode templateEmailModification créée initialement dans Model
     * @param $destinataire
     * @param $motifModif
     * @param $lien
     * @return void
     */
    public function templateEmailModification($destinataire, $motifModif, $lien) {
        $sujet = '[OCRSQUARE] Confirmation changement '.$motifModif.'';
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        $headers .= 'From: OCRSQUARE <etglsquare@gmail.com>' . "\r\n";
        $message = '
            <html>
            <head>
            <title>Bonjour</title>
            </head>
            <body>
            <p>Bonjour ,</p>
            <p>Vous venez de faire la demande de modification de votre '. $motifModif .'. Pour confirmer cliquer <a href="'.$lien.'">ici</a>.</p>
            <p>Merci,</p>
            <p>L\'équipe OCRSQUARE<p>
            </body>
            </html>
            ';
        $this->envoyerMail($destinataire, $sujet, $message, $headers);
    }
}