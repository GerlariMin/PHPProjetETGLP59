<?php

/**
 * Classe TexteConditionsGeneralesUtilisation
 * Contient l'ensemble du texte à afficher pour la page des conditions générales d'utilisation.
 */
class TexteConditionsGeneralesUtilisation
{
    /**
     * Variables correspondant aux balises Mustache de la page.
     */

    /**
     * @var array
     */
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    private function texteConditionsService(): String
    {
        return "L’utilisation du service se fait à vos propres risques. Le service est fourni tel quel.
Vous ne devez pas modifier un autre site afin de signifier faussement qu’il est associé avec ce service CAP-REL/AbulÉdu-fr.
Les comptes ne peuvent être créés et utilisés que par des humains. Les comptes créés par les robots ou autres méthodes automatisées sont interdits et pourront être supprimés sans avertissement.
Vous êtes le seul responsable de la sécurité de votre compte et de votre mot de passe. Vous en êtes responsable.
CAP-REL/AbulÉdu-fr ne peut pas et ne sera pas responsable de toutes pertes ou dommages résultant du non respect de cette obligation de sécurité qui vous incombe.
Vous êtes responsable de tout contenu affiché et de l’activité qui se produit sous votre compte.
Vous ne pouvez pas utiliser le service à des fins illégales ou non autorisées.
Vous ne devez pas transgresser les lois de votre pays.
Vous ne pouvez pas vendre, échanger, revendre, ou exploiter dans un but commercial non autorisé un compte du service utilisé.
La violation de l’un de ces accords entraînera au moins la résiliation de votre compte, voire des poursuites en justice. Vous comprenez et acceptez que CAP-REL et AbulÉdu-fr ne puissent être responsables pour les contenus publiés sur ce service.

Vous comprenez que la mise en ligne du service ainsi que de votre contenu implique une transmission (en clair ou chiffrée, suivant les services) sur divers réseaux.
Vous ne devez pas transmettre des vers, des virus, chevaux de Troie ou tout autre code de nature malveillante.
CAP-REL/AbulÉdu-fr ne garantit pas que
le service répondra à vos besoins spécifiques,
le service sera ininterrompu ou exempte de bugs,
que les erreurs dans le service seront corrigées. Vous comprenez et acceptez que CAP-REL/AbulÉdu-fr ne puisse être tenue responsable d’aucun dommage direct, indirect ou fortuit, comprenant les dommages pour perte de profits, de clientèle, d’accès, de données ou d’autres pertes intangibles (même si CAP-REL/AbulÉdu-fr est informée de la possibilité de tels dommages) et qui résulteraient de :
l’utilisation ou de l’impossibilité d’utiliser le service ;
l’accès non autorisé ou altéré de la transmission des données ;
les déclarations ou les agissements d’un tiers sur le service ;
la résiliation de votre compte ;
toute autre question relative au service.
L’échec de CAP-REL/AbulÉdu-fr à exercer ou à appliquer tout droit ou disposition des Conditions Générales d’Utilisation ne constitue pas une renonciation à ce droit ou à cette disposition. Les Conditions d’utilisation constituent l’intégralité de l’accord entre vous et CAP-REL/AbulÉdu-fr et régissent votre utilisation du service, remplaçant tous les accords antérieurs entre vous et CAP-REL/AbulÉdu-fr (y compris les versions précédentes des Conditions Générales d’Utilisation).
Les questions sur les conditions de service doivent être envoyées via ce formulaire de contact.";
    }

    /**
     * Retourne le tableau formaté final utilisé pour générer le rendu intégral.
     *
     * @return array
     */
    public function texteFinal():array
    {
        return
            [
                "conditions" => $this->texteConditionsService(),
            ];
    }

}