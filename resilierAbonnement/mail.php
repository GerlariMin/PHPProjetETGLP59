<?php
    require_once('../ressources/php/Mail.php');
    /**
    * Classe MailSupprimerCompte dédiée aux requête propres au module supprimerCompte.
    */
    class MailResiliationAbonnement extends Mail {

        /**
        * Méthode envoi de mail pour la classe MailSupprimerCompte
        * @return void
        */
        public function envoyerMailResiliationAbonnement($destinataire) {
            $sujet = '[OCRSQUARE] - Résiliation abonnement';
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
            $headers .= 'From: OCRSQUARE <etglsquare@gmail.com>' . "\r\n";
            $message = "
                <html>
                    <head>
                        <title>Bonjour, </title>
                    </head>
                    <body>
                        <p>Bonjour ,</p>
                        <p>Vous venez de faire la demande de résiliation d'abonnement. Vous pouvez toujours profiter de vos avantages jusqu'a échéance de votre abonnement.</p>
                        <p>Cordialement,</p>
                        <p>L\'équipe OCRSQUARE<p>
                    </body>
                </html>
                ";
            $this->envoyerMail($destinataire, $sujet, $message, $headers);
        }

        /**
        * Méthode envoi de mail pour la classe MailSupprimerCompte
        * @return void
        */
        public function envoyerMailPreventionAbonnement($destinataire) {
            $sujet = '[OCRSQUARE] - Rappel résiliation abonnement';
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
            $headers .= 'From: OCRSQUARE <etglsquare@gmail.com>' . "\r\n";
            $message = "
                <html>
                    <head>
                        <title>Bonjour, </title>
                    </head>
                    <body>
                        <p>Bonjour ,</p>
                        <p>Nous vous rapellons que vous avez fait la demande de résiliation d'abonnement, et sans reconnexion au site dans les 7 jours, votre avantages seront révoqués.</p>
                        <p>Cordialement,</p>
                        <p>L\'équipe OCRSQUARE<p>
                    </body>
                </html>
                ";
            $this->envoyerMail($destinataire, $sujet, $message, $headers);
        }
    }
