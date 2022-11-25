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

        /**
         * METHODES REPRISES DE MODEL
         */

        function templateMessageSucces($email, $text, $returnLink){
            echo '
            <head>
            <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">    
            </head>
            <body>
            <div class="card">
            <div style="border-radius:200px; height:200px; width:200px; background: #F8FAF5; margin:0 auto;">
            <i class="checkmark">✓</i>
            </div>
            <h1>Success</h1> ';
            echo $text;
            echo "<b>". $email ."</b></div>";
            echo $returnLink;
            echo '
            <style>
            body {
            text-align: center;
            padding: 40px 0;
            background: #EBF0F5;
            }
            h1 {
                color: #88B04B;
                font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
                font-weight: 900;
                font-size: 40px;
                margin-bottom: 10px;
            }
            p {
                color: #404F5E;
                font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
                font-size:20px;
                margin: 0;
            }
            i {
            color: #9ABC66;
            font-size: 100px;
            line-height: 200px;
            margin-left:-15px;
            }
            .card {
            background: white;
            padding: 60px;
            border-radius: 4px;
            box-shadow: 0 2px 3px #C8D0D8;
            display: inline-block;
            margin: 0 auto;
            margin-top: 150px;
            }
            </style>
            ';
        }

        function messageModification($motif){
            return "<p>Un lien de confirmation de modification de votre ".$motif." a été envoyé à l'adresse suivante :<br/></p>";
        }

        function messageConfirmation($motif){
            return "<p>Confirmation du changement de votre ".$motif." !<br/></p> </div></body>";
        }

    }