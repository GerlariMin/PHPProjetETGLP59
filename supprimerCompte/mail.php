<?php
    require_once('../ressources/php/Mail.php');
    /**
    * Classe MailSupprimerCompte dédiée aux requête propres au module supprimerCompte.
    */
    class MailSupprimerCompte extends Mail {

        /**
        * Méthode envoi de mail pour la classe MailSupprimerCompte
        * @return void
        */
        public function envoyerMailSupprimerCompte($destinataire) {
            $sujet = '[OCRSQUARE] - Suppresion compte';
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
            $headers .= 'From: OCRSQUARE <etglsquare@gmail.com>' . "\r\n";
            $message = '
                <html>
                    <head>
                        <title>Bonjour, </title>
                    </head>
                    <body>
                        <p>Bonjour ,</p>
                        <p>Vous venez de faire la demande de supression de compte, vos données seront conservées pendant 30 jours, et sans reconnexion pendant ces 30 jours, vos données seront définitivement supprimées.</p>
                        <p>Cordialement,</p>
                        <p>L\'équipe OCRSQUARE<p>
                    </body>
                </html>
                ';
            $this->envoyerMail($destinataire, $sujet, $message, $headers);
        }

        /**
        * Méthode envoi de mail pour la classe MailSupprimerCompte
        * @return void
        */
        public function envoyerMailPreventionCompte($destinataire) {
            $sujet = '[OCRSQUARE] - Rappel suppresion compte';
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
            $headers .= 'From: OCRSQUARE <etglsquare@gmail.com>' . "\r\n";
            $message = '
                <html>
                    <head>
                        <title>Bonjour, </title>
                    </head>
                    <body>
                        <p>Bonjour ,</p>
                        <p>Nous vous rapelons que vos données sont en cours de suppression, et sans reconnexion au site dans les 7 jours, vos données seront définitivement supprimées.</p>
                        <p>Cordialement,</p>
                        <p>L\'équipe OCRSQUARE<p>
                    </body>
                </html>
                ';
            $this->envoyerMail($destinataire, $sujet, $message, $headers);
        }
    }
