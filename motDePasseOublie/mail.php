<?php
require_once('../ressources/php/Mail.php');
/**
 * Classe dédiée aux requête propres au module profil.
 */
class MailMotDePasseOublie extends Mail {

    /**
     * Reprise de la méthode templateEmailModification créée initialement dans Model
     * @param $destinataire
     * @param $lien
     * @return void
     */
    public function templateEmailMotDePasseOublie($destinataire, $lien): void
    {
        $sujet = '[OCRSQUARE] Demande de mot de passe oublié';
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
                    <p>Vous venez de faire la demande de réinitialisation de mot de passe. Pour le renouveler cliquer <a href="' . $lien . '">ici</a>.</p>
                    <p>Merci,</p>
                    <p>L\'équipe OCRSQUARE<p>
                </body>
            </html>
            ';
        $this->envoyerMail($destinataire, $sujet, $message, $headers);
    }
}