<?php

/**
 * Classe TraitementInscription
 */
class TraitementInscription
{

    /**
     * @var Render render
     */
    private Render $render;
    /**
     * @var array config
     */
    private array $config;
    /**
     * @var TexteInscription texte
     */
    private TexteInscription $texte;

    /**
     * Traitement_Accueil constructor.
     *
     * @param Render $rendu
     */
    public function __construct(Render $rendu)
    {
        $this->render = $rendu;
        global $config;
        $this->config = $config;
        $this->texte = new TexteInscription($this->config);
    }

    public function traitementErreur($codeErreur): String
    {
        return match ($codeErreur) {
            '1' => 'Vous n\'avez pas accepté les conditions d\'utilisation du site!',
            '2' => 'Vous n\'avez pas écrit le même mot de passe dans les 2 champs dédiés!',
            '3' => 'L\'email que vous avez déjà pris est déjà affecter à un compte existant!',
            '4' => 'Un des champs semble vide!',
            '5' => 'Un problème est survenu lors de la création de votre compte!',
            default => 'Une erreur est survenue!',
        };
    }

    /**
     * Affichage de la page de connexion.
     */
    public function traitementRendu($codeErreur = ''): void
    {
        $data = $this->texte->texteFinal();

        if($codeErreur) {
            date_default_timezone_set('Europe/Paris');
            $data['blocErreur'] =
                [
                    'i_class' => 'fa-solid fa-bug',
                    'strong' => 'Problème!',
                    'small' => date('H:i'),
                    'message' => $this->traitementErreur($codeErreur)
                ];
        }

        $data['chemin'] = $this->config['variables']['chemin'];
        $data['inscription'] = true;

        $this->render->actionRendu($data);
    }

}