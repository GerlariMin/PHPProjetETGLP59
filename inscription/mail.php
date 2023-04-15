<?php
require_once('../ressources/php/Mail.php');
/**
 * Classe dédiée aux requête propres au module profil.
 */
class MailInscription extends Mail
{

    /**
     * Reprise de la méthode templateEmailModification créée initialement dans Model
     * @param $destinataire
     * @param $motifModif
     * @param $lien
     * @return void
     */
    public function templateEmailModification($destinataire, $nomPrenom, $lien) : bool
    {
        $sujet = '[OCRSQUARE] Confirmation Inscription';
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        $headers .= 'From: OCRSQUARE <etglsquare@gmail.com>' . "\r\n";
        $message = '
            <html>
                 <head>
                 <title>Inscription réussie</title>
                 </head>
                 <body>
                 <p>Bonjour ' . $nomPrenom . ',</p>
                 <p> Félicitations ! Votre compte a bien été créé, vous pouvez à présent vous connecter en cliquant <a href="' . $lien . '">ici</a>.</p>
                 <p>Bien cordialement,</p>
                 <p>L\'équipe OCRSQUARE<p>
                 </body>
             </html>
            ';
        return $this->envoyerMail($destinataire, $sujet, $message, $headers);
    }
}
