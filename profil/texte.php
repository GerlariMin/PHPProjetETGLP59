<?php
    include_once("../ressources/php/Logs.php");
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
                         $this->placeholder_input => "Nouveau nom",
                         $this->type_input => "text",
                         $this->name_input => "nom",
                         $this->placeholder_input_2 => "Nouveau prenom",
                         $this->type_input_2 => "text",
                         $this->name_input_2 => "prenom"
                    ],
               1 =>
                    [
                         $this->type_information => "Login",
                         $this->information_personnelle => $donneesUtilisateur['loginUtilisateur'],
                         $this->id_bouton_modifier => "modifierLogin",
                         $this->id_collapse => "collapseLogin",
                         $this->placeholder_input => "Nouveau login",
                         $this->type_input => "text",
                         $this->name_input => "login"
                    ],
               2 =>
                    [
                         $this->type_information => "Adresse e-mail",
                         $this->information_personnelle => $donneesUtilisateur['emailUtilisateur'],
                         $this->id_bouton_modifier => "modifierEmail",
                         $this->id_collapse => "collapseEmail",
                         $this->placeholder_input => "Nouvel email",
                         $this->type_input => "email",
                         $this->name_input => "email"
                    ],
               3 =>
                    [
                         $this->type_information => "Mot de passe",
                         $this->information_personnelle => "*************",
                         $this->id_bouton_modifier => "modifierMotDePasse",
                         $this->id_collapse => "collapseMotDePasse",
                         $this->placeholder_input => "Nouveau mot de passe",
                         $this->type_input => "password",
                         $this->name_input => "mdp"
                    ],
               4 =>
                    [
                         $this->type_information => "Type d'abonnement",
                         $this->information_personnelle => $abonnement,
                         $this->id_bouton_modifier => "modifierAbonnement",
                         $this->id_collapse => "collapseAbonnement",
                         $this->placeholder_input => "Nouvel abonnement",
                         $this->type_input => "text",
                         $this->name_input => "abonnement"
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

        function messageModification($motif){
            return "<p>Un lien de confirmation de modification de votre ".$motif." a été envoyé à l'adresse suivante :<br/></p>";
        }

        function messageConfirmation($motif){
            return "<p>Confirmation du changement de votre ".$motif." !<br/></p> </div></body>";
        }

    }