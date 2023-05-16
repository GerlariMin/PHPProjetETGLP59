<?php
/**
 * Classe TexteProfil
 * Contient l'ensemble du texte à afficher pour la page de Profil.
 */
class TexteProfil
{
    /**
     * Variables correspondant aux balises Mustache de la page.
     */

    /**
     * @var array
     */
    private array $config;
    private String $type_information = "type_information";
    private String $information_personnelle = "information_personnelle";
    private String $id_bouton_modifier = "id_bouton_modifier";
    private String $id_collapse = "id_collapse";
    private String $placeholder_input = "placeholder_input";
    private String $type_input = "type_input";
    private String $name_input = "name_input";
    private String $placeholder_input_2 = "placeholder_input_2";
    private String $type_input_2 = "type_input_2";
    private String $name_input_2 = "name_input_2";
    private String $id_infos_perso = 'id_infos_perso';
    private String $id_input_1 = 'id_input_1';
    private String $id_input_2 = 'id_input_2';
    private String $id_bouton_submit = 'id_bouton_submit';


    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Retourne le tableau formaté pour les différents attributs de la balise <form>
     *
     * @return string[]
     */
    private function texteListe(RequetesProfil $requetes): array
    {
        $donneesUtilisateur = $requetes->donneesUtilisateur($_SESSION['identifiant']);

        switch ($donneesUtilisateur['abonnementUtilisateur']) {
            case 1:
                $abonnement = "gratuit";
                break;
            case 2:
                $abonnement = "premium";
                break;
            case 3:
                $abonnement = "gold";
                break;
            default:
                $abonnement = "";
        }

        return
            [
                0 =>
                    [
                        $this->type_information => "Nom",
                        $this->information_personnelle => $donneesUtilisateur['prenomUtilisateur'] ." ". strtoupper($donneesUtilisateur['nomUtilisateur']),
                        $this->id_bouton_modifier => "modifierNom",
                        $this->id_collapse => "collapseNom",
                        $this->id_infos_perso => "infoNomPrenom",
                        $this->placeholder_input => "Nouveau nom",
                        $this->id_input_1 => "idInputNom",
                        $this->type_input => "text",
                        $this->name_input => "nom",
                        $this->placeholder_input_2 => "Nouveau prenom",
                        $this->id_input_2 => "idInputPrenom",
                        $this->type_input_2 => "text",
                        $this->name_input_2 => "prenom",
                        $this->id_bouton_submit => "boutonEnregistrerNomPrenom"
                    ],
                1 =>
                    [
                        $this->type_information => "Login",
                        $this->information_personnelle => $donneesUtilisateur['loginUtilisateur'],
                        $this->id_bouton_modifier => "modifierLogin",
                        $this->id_collapse => "collapseLogin",
                        $this->placeholder_input => "Nouveau login",
                        $this->id_input_1 => "idInputLogin",
                        $this->type_input => "text",
                        $this->name_input => "login",
                        $this->id_bouton_submit => "boutonEnregistrerLogin"
                    ],
                2 =>
                    [
                        $this->type_information => "Adresse e-mail",
                        $this->information_personnelle => $donneesUtilisateur['emailUtilisateur'],
                        $this->id_bouton_modifier => "modifierEmail",
                        $this->id_collapse => "collapseEmail",
                        $this->placeholder_input => "Nouvel email",
                        $this->id_input_1 => "idInputEmail",
                        $this->type_input => "email",
                        $this->name_input => "email",
                        $this->id_bouton_submit => "boutonEnregistrerEmail"
                    ],
                3 =>
                    [
                        $this->type_information => "Mot de passe",
                        $this->information_personnelle => "*************",
                        $this->id_bouton_modifier => "modifierMotDePasse",
                        $this->id_collapse => "collapseMotDePasse",
                        $this->placeholder_input => "Nouveau mot de passe",
                        $this->id_input_1 => "idInputMotDePasse",
                        $this->type_input => "password",
                        $this->name_input => "mdp",
                        $this->id_bouton_submit => "boutonEnregistrerMotDePasse"
                    ],
                4 =>
                    [
                        $this->type_information => "Type d'abonnement",
                        $this->information_personnelle => $abonnement,
                        $this->id_bouton_modifier => "modifierAbonnement",
                        'href_bouton_modifier' => '../souscription/',
                        'modificationAbonneent' => true,
                    ]
            ];
    }

    /**
     * Retourne le tableau formaté final utilisé pour générer le rendu intégral.
     *
     * @return array
     */
    public function texteFinal(RequetesProfil $requetes):array
    {
        return
            [
                "liste" => $this->texteListe($requetes),
            ];
    }

}