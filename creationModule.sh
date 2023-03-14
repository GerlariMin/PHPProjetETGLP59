#!/bin/bash

: 'Sources ayant aidés au développement de ce script:
- https://devhints.io/bash
- http://www.iro.umontreal.ca/~lesagee/bash.html
'

: 'Détails du programme:
- Auteur: Morgan MINBIELLE
- Date de mise en service: 26/11/2022
- Objectif: Automatiser la création de nouveaux modules dans le projet ETGL P59.
- Version: 1.0.0
'

: '=================
   === FONCTIONS ===
   ================='

# Fonction fille de la création des fichiers à la demande, dédiée à la création du fichier action.php
function creationFichierAction() { # Argument 1 = emplacement racine du module, argument 2 = nom du module
    local action="$1/action.php" # Chemin complet de création du fichier action.php du module
    local nomModule=${2^} # Majuscule de la première lettre du deuxième argument, le nom du module
    echo -e "\t o Génération du fichier action.php du module $nomModule..."
    echo "<?php
    session_start();
    // Chargement des ressources utiles
    require_once('../ressources/config/config.inc.php');
    require_once('../ressources/php/Logs.php');
    require_once('mail.php'); // Décommenter si utilisation d'un fichier mail.php
    require_once('requetes.php'); // Décommenter si utilisation d'un fichier requetes.php
    global \$config;
    \$logs = new Logs(\$config);
    \$mail = new Mail$nomModule(\$config, \$logs); // Décommenter si utilisation d'un fichier mail.php
    \$requetes = new Requetes$nomModule(\$config, \$logs); // Décommenter si utilisation d'un fichier requetes.php
    // Vérification de la présence des données du formulaire soumit par l'utilisateur
    if (\$_POST) {
        // On récupère le formulaire
        \$formulaire = \$_POST;
        unset(\$_POST);

        /************************************
         * Code de traitement du formulaire *
         ************************************/

    } else {
        \$logs->messageLog('Aucune valeur saisie récupérée.', \$logs->typeError);
        // Affichage message erreur
        header('Location: ./?erreur=post');
    }
    exit();" > $action
    echo -e "\t o Génération du fichier action.php du module $nomModule terminée!"
    echo -e "\t o Fichier action.php créé à l'emplacement: $action."
}
# Fonction fille de la création des fichiers à la demande, dédiée à la création du fichier ajax.php
function creationFichierAJAX() { # Argument 1 = emplacement racine du module, argument 2 = nom du module
    local ajax="$1/ajax.php" # Chemin complet de création du fichier ajax.php du module
    local nomModule=${2^} # Majuscule de la première lettre du deuxième argument, le nom du module
    echo -e "\t o Génération du fichier ajax.php du module $nomModule..."
    echo "<?php
    session_start();
    // Chargement des ressources utiles
    require_once('../ressources/config/config.inc.php');
    require_once('../ressources/php/Logs.php');
    require_once('mail.php'); // Décommenter si utilisation d'un fichier mail.php
    require_once('requetes.php'); // Décommenter si utilisation d'un fichier requetes.php
    global \$config;
    \$logs = new Logs(\$config);
    \$mail = new Mail$nomModule(\$config, \$logs); // Décommenter si utilisation d'un fichier mail.php
    \$requetes = new Requetes$nomModule(\$config, \$logs); // Décommenter si utilisation d'un fichier requetes.php

    /******************************
    * METTRE CODE TRAITEMENT AJAX *
    *******************************/
    " > $ajax
    echo -e "\t o Génération du fichier ajax.php du module $nomModule terminée!"
    echo -e "\t o Fichier ajax.php créé à l'emplacement: $ajax."
}
# Fonction mère de la création des fichiers génériques pour les assets du module
function creationFichiersAssets() { # Argument 1 = emplacement racine du module, argument 2 = nom du module
    creationFichierAssetsCSS $1 $2
    creationFichierAssetsJavaScript $1 $2
}
# Fonction fille de la création du fichier xxx.css
function creationFichierAssetsCSS() { # Argument 1 = emplacement racine du module, argument 2 = nom du module
    local css="$1/css/$2.css" # Chemin complet de création du fichier xxx.css du module
    local nomModule=${2^} # Majuscule de la première lettre du deuxième argument, le nom du module
    echo -e "\t o Génération du fichier CSS du module $nomModule..."
    echo "/*Fichier CSS généré automatiquement*/" > $css
    echo -e "\t o Génération du fichier CSS du module $nomModule terminée!"
    echo -e "\t o Fichier CSS créé à l'emplacement: $css."
}
# Fonction fille de la création du fichier xxx.js
function creationFichierAssetsJavaScript() { # Argument 1 = emplacement racine du module, argument 2 = nom du module
    local js="$1/js/$2.js" # Chemin complet de création du fichier xxx.css du module
    local nomModule=${2^} # Majuscule de la première lettre du deuxième argument, le nom du module
    echo -e "\t o Génération du fichier JavaScript du module $nomModule..."
    echo "// On attend que le document HTML soit chargé avant d'effectuer des instructions
    \$(document).ready(function() {
        console.log('Document HTML prêt!'); // affichage dans la console du navigateur (accessible via F12 ou inspecter l'élément)
    });" > $js
    echo -e "\t o Génération du fichier JavaScript du module $nomModule terminée!"
    echo -e "\t o Fichier JavaScript créé à l'emplacement: $js."
}
# Fonction fille de la création des fichiers génériques par défaut, dédiée à la création du fichier index.php
function creationFichierIndex() { # Argument 1 = emplacement racine du module, argument 2 = nom du module
    local index="$1/index.php" # Chemin complet de création du fichier index.php du module
    local nomModule=${2^} # Majuscule de la première lettre du deuxième argument, le nom du module
    echo -e "\t o Génération de l'index du module $nomModule..."
    echo "<?php
    session_start();
    //if(isset(\$_SESSION['login'])) { // Décommenter si le module est accessible sans que l'utilisateur soit connecté
        // Chargement des ressources utiles
        include_once('../ressources/php/fichiers_communs.php');
        // Initialisation de la classe de traitement dédiée au module $2
        \$traitement = new Traitement$nomModule(\$render);
        // Appel de la méthode générant l'affichage du module $2
        \$traitement->traitementRendu(\$erreur);
    // Décommenter la suite si le module est accessible sans que l'utilisateur soit connecté
    /*}else {
        header('Location: ../connexion/?erreur=5');
        exit();
    }*/" > $index
    echo -e "\t o Génération de l'index du module $nomModule terminée!"
    echo -e "\t o Index créé à l'emplacement: $index."
}
# Fonction fille de la création des fichiers à la demande, dédiée à la création du fichier mail.php
function creationFichierMail() { # Argument 1 = emplacement racine du module, argument 2 = nom du module
    local mail="$1/mail.php" # Chemin complet de création du fichier mail.php du module
    local nomModule=${2^} # Majuscule de la première lettre du deuxième argument, le nom du module
    echo -e "\t o Génération du fichier mail.php du module $nomModule..."
    echo "<?php
    require_once('../ressources/php/Mail.php');
    /**
    * Classe Mail$nomModule dédiée aux requête propres au module $2.
    */
    class Mail$nomModule extends Mail {

        /**
        * Méthode d'exemple pour la classe Mail$nomModule
        * @return void
        */
        public function methodeExempleMail$nomModule() {
            \$destinataire = 'destinataire.generique@yopmail.com';
            \$sujet = '[EXEMPLE] - E-mail générique';
            \$headers  = 'MIME-Version: 1.0' . "\r\n";
            \$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
            \$headers .= 'From: Exemple <exemple.generique@yopmail.com>' . "\r\n";
            \$message = '
                <html>
                    <head>
                        <title>Exemple générique</title>
                    </head>
                    <body>
                        <p>Bonjour ,</p>
                        <p>Ceci est un e-mail générique de test.</p>
                        <p>Bien cordialement,</p>
                        <p>Générateur automatique.<p>
                    </body>
                </html>
                ';
            \$this->envoyerMail(\$destinataire, \$sujet, \$message, \$headers);
        }
    }" > $mail
    echo -e "\t o Génération du fichier mail.php du module $nomModule terminée!"
    echo -e "\t o Fichier mail.php créé à l'emplacement: $mail."
}
# Fonction fille de la création des fichiers génériques par défaut, dédiée à la création du fichier xxx.mustache
function creationFichierMustache() { # Argument 1 = emplacement racine du module, argument 2 = nom du module
    local mustache="$1/mustache/$2.mustache" # Chemin complet de création du fichier xxx.mustache du module
    local nomModule=${2^} # Majuscule de la première lettre du deuxième argument, le nom du module
    echo -e "\t o Génération du fichier Mustache du module $nomModule..."
    echo "<!--<link rel=\"stylesheet\" href=\"./assets/css/$2.css\"> --> <!-- Décommenter pour utiliser le fichier CSS associé -->
<!--<script src=\"./assets/js/$2.js\" type=\"text/javascript\"></script> --> <!-- Décommenter pour utiliser le fichier JavaScript associé -->
<div class=\"container h-100 mt-5 mb-5\">
    <!-- Début de l'affichage généré automatique pour visualiser la page du module -->
    <!-- Alerte info -->
    {{#alerte}}
        <div class=\"alert alert-info alert-dismissible fade show\" role=\"alert\">
            <i class=\"{{fontawesome}}\"></i> {{texte}}
            <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
        </div>
    {{/alerte}}
    <!-- Formulaire d'exemple, à décommenter si utile -->
    {{#formulaire}}
        <form action=\"{{action}}\" method=\"{{method}}\">
            <!-- Champs Formulaire -->
            {{#inputs}}
                <div class=\"input-group mb-3\">
                    <span class=\"input-group-text\"><i class=\"{{fontawesome}}\"></i></span>
                    <input class=\"form-control\" name=\"{{name}}\" placeholder=\"{{placeholder}}\" {{#required}}required{{/required}} type=\"{{type}}\">
                </div>
            {{/inputs}}
            <!-- Bouton de soumiision du formulaire -->
            {{#submit}}
                <div class=\"d-grid gap-2\">
                    <button class=\"btn btn-success\" type=\"submit\"><i class=\"{{fontawesome}}\"></i> {{texte}}</button>
                </div>
            {{/submit}}
        </form>
    {{/formulaire}}
    <!-- Fin de l'affichage généré automatique pour visualiser la page du module -->
    
    <!-- Ajouter du cCode HTML pour l'affichage du module Dossier -->

</div>" > $mustache
    echo -e "\t o Génération du fichier Mustache du module $nomModule terminée!"
    echo -e "\t o Fichier Mustache créé à l'emplacement: $mustache."
}
# Fonction fille de la création des fichiers à la demande, dédiée à la création du fichier requetes.php
function creationFichierRequetes() { # Argument 1 = emplacement racine du module, argument 2 = nom du module
    local requetes="$1/requetes.php" # Chemin complet de création du fichier requetes.php du module
    local nomModule=${2^} # Majuscule de la première lettre du deuxième argument, le nom du module
    echo -e "\t o Génération du fichier requetes.php du module $nomModule..."
    echo "<?php
    require_once('../ressources/php/Model.php');
    /**
    * Classe dédiée aux requêtes SQL propres au module $2.
    */
    class Requetes$nomModule extends Model {

        /**
        * @var Logs logs
        */
        private Logs \$logs;
        /**
        * @var Model model
        */
        private Model \$model;

        /**
        * Constructeur de la classe Requetes$nomModule qui va initialiser ses attributs privés dédiés aux logs et à l'accès à la base de données
        */
        public function __construct(array \$config, Logs \$logs) {
            \$this->logs = \$logs;
            \$this->model = Model::getModel(\$config, \$logs);
        }
        /**
        * Méthode d'exemple
        */
        public function MethodeExemple(String \$login): mixed
        {
            // Texte SQL qui va alimenter la requête
            \$texteRequete = 'SELECT * FROM table WHERE colonne = :colonne;';
            // Requête SQL a exécuter
            \$requete = \$this->model->bdd->prepare(\$texteRequete);
            \$this->logs->messageLog('Requete SQL préparée: ' . \$texteRequete . '.', \$this->logs->typeDebug);
            // Attribution des valeurs de la requête préparée
            \$requete->bindValue(':colonne', \$colonne);
            \$this->logs->messageLog('Paramètres: [colonne: ' . \$colonne . '].', \$this->logs->typeDebug);
            // Exécution de la requête préparée
            \$requete->execute();
            // La fonction retourne le résultat de la requête
            return \$requete->fetch(PDO::FETCH_ASSOC);
        }
    }" > $requetes
    echo -e "\t o Génération du fichier requetes.php du module $nomModule terminée!"
    echo -e "\t o Fichier requetes.php créé à l'emplacement: $requetes."
}
# Fonction fille de la création des fichiers génériques par défaut, dédiée à la création du fichier texte.php
function creationFichierTexte() { # Argument 1 = emplacement racine du module, argument 2 = nom du module
    local texte="$1/texte.php" # Chemin complet de création du fichier texte.php du module
    local nomModule=${2^} # Majuscule de la première lettre du deuxième argument, le nom du module
    echo -e "\t o Génération de la classe Texte$nomModule..."
    echo "<?php
    /**
    * Classe Texte$nomModule
    * Contient l'ensemble du texte à afficher pour le module $nomModule.
    */
    class Texte$nomModule
    {
        /**
        * @var array config
        */
        private array \$config;
        /**
        * Texte$nomModule constructor.
        *
        * @param Render \$rendu
        */
        public function __construct(array \$config)
        {
            \$this->config = \$config;
        }
        /**
         * Méthode d'exemple pour afficher l'alerte
         * 
         * @return array
         */
        public function texteAlerteExemple(): array
        {
            return [
                'fontawesome' => 'fa-solid fa-wand-magic-sparkles',
                'texte' => 'Bloc généré automatiquement pour tester l\'affichage de la page',
            ];
        }
        /**
         * Méthode d'exemple pour afficher le formulaire
         * 
         * @return array
         */
        public function texteFormulaireExemple(): array
        {
            return [
                // Clés de paramétrage du formulaire HTML
                'action' => 'action.php', // Fichier auquel on enverra les données du formulaire
                'method' => 'POST', // Méthode d'envoi des données
                // Paramétrage des champs du formulaire HTML
                'inputs' => [
                    // Clés associatives pour le premier champ de saisie
                    0 => [
                        'fontawesome' => 'fa-solid fa-pen', // icone
                        'name' => 'champ1', // nom du champ
                        'placeholder' => 'Champ exemple 1', // Texte d'exemple
                        'required' => true, // champ obligatoire
                        'type' => 'text' // champ texte
                    ],
                    // Clés associatives pour le premier champ de saisie
                    1 => [
                        'fontawesome' => 'fa-solid fa-pen', // icone
                        'name' => 'champ2', // nom du champ
                        'placeholder' => 'Champ exemple 2', // Texte d'exemple
                        'required' => false, // champ non obligatoire
                        'type' => 'text' // champ texte
                    ]
                ],
                // Paramétrage du bouton de soumission du formulaire
                'submit' => [
                    'fontawesome' => 'fa-solid fa-check', // icone
                    'texte' => 'Valider' // texte
                ],
            ];
        }

        /*************************************
        * Méthodes Propres à Texte$nomModule *
        **************************************/
        
        /**
        * Retourne le tableau formaté final utilisé pour générer le rendu intégral Mustache.
        *
        * @return array
        */
        public function texteFinal(): array
        {
            return
                [
                    'alerte' => \$this->texteAlerteExemple(), // exemple pour le fichier Mustache associé
                    'formulaire' => \$this->texteFormulaireExemple(), // exemple pour le fichier Mustache associé
                    /**************
                    * A compléter *
                    ***************/
                ];
        }
    }" > $texte
    echo -e "\t o Génération de la classe Texte$nomModule terminée!"
    echo -e "\t o Texte$nomModule créée à l'emplacement: $texte."
}
# Fonction fille de la création des fichiers génériques par défaut, dédiée à la création du fichier traitement.php
function creationFichierTraitement() { # Argument 1 = emplacement racine du module, argument 2 = nom du module
    local traitement="$1/traitement.php" # Chemin complet de création du fichier traitement.php du module
    local nomModule=${2^} # Majuscule de la première lettre du deuxième argument, le nom du module
    echo -e "\t o Génération de la classe Traitement$nomModule..."
    echo "<?php
    /**
    * Classe Traitement$nomModule
    */
    class Traitement$nomModule
    {
        /**
        * @var Render render
        */
        private Render \$render;
        /**
        * @var array config
        */
        private array \$config;
        /**
        * @var Texte$nomModule texte
        */
        private Texte$nomModule \$texte;

        /**
        * Traitement$nomModule constructor.
        *
        * @param Render \$render
        */
        public function __construct(Render \$render)
        {
            \$this->render = \$render;
            global \$config;
            \$this->config = \$config;
            \$this->texte = new Texte$nomModule(\$this->config);
        }

        /******************************************
        * Méthodes Propres à Traitement$nomModule *
        *******************************************/

        /**
        * Affichage de la page du module $nomModule.
        */
        public function traitementRendu(\$codeErreur = ''): void
        {
            \$data = \$this->texte->texteFinal();
            // Ajout des variables et valeurs utiles dans \$data pour l'affichage du module
            \$data['chemin'] = \$this->config['variables']['chemin']; // Penser à rajouter la ligne suivant dans le constructeur de la classe Render: new Mustache_Loader_FilesystemLoader(\$chemin . '$2/mustache'),
            \$data['$2'] = true;
            /**
            * Ajouter le bloc suivant dans ressources/mustache/Body.mustache (sans les * en début de ligne):
            *    {{#$2}}
            *        {{>$2}}
            *    {{/$2}}
            */
            // Envoi de la variable $data à la classe Mustache
            \$this->render->actionRendu(\$data);
        }
    }" > $traitement
    echo -e "\t o Génération de la classe Traitement$nomModule terminée!"
    echo -e "\t o Traitement$nomModule créée à l'emplacement: $traitement."
}
# Fonction mère de la création des fichiers génériques par défaut
function creationFichiersGeneriquesDefaut() { # Argument 1 = emplacement racine du module, argument 2 = nom du module
    echo -e "\t-> Génération des fichiers générique par défaut en cours..."
    creationFichierIndex $1 $2 # Création du fichier $1/index.php
    creationFichierMustache $1 $2 # Création du fichier $1/mustache/$2.mustache
    creationFichierTexte $1 $2 # Création du fichier $1/texte.php
    creationFichierTraitement $1 $2 # Création du fichier $1/traitement.php
    echo -e "\t-> Génération des fichiers générique par défaut terminée!"
}
# Création du Répertoire passé en argument
function creationRepertoire() {
    echo -e "\t-> Création du répertoire $1."
    # Si le répertoire $1 existe déjà
    if [[ -e "$1" ]]
    then
        echo "$nomModule existe déjà!\nSouhaitez-vous le remplacer? (o/n)"
        read confirmationCreationModule
        echo -e "\t-> Vous avez choisi: $confirmationCreationModule."
        # Si l'utilisateur confirme qu'il veut remplacer le répertoire déjà existant
        if [[ $confirmationCreationModule == "o" || $confirmationCreationModule == "O" || $confirmationCreationModule == "y" || $confirmationCreationModule == "Y" || $confirmationCreationModule == "oui" || $confirmationCreationModule == "yes" ]]
        then
            # Si la suppression du répertoire déjà existante s'est bien déroulée
            if rm -rf $1
            then
                echo -e "\t-> Répertoire existant supprimé!"
            else
                echo -e "\t-> Problème lors de la suppression du répertoire existant!"
                return 1 # code retour erreur
            fi
        else
            echo -e "\t Annulation..."
            return 1 # code retour erreur
        fi
    fi
    # Si la création du répertoire $1 s'est déroulée sans problèmes
    if mkdir $1; 
    then
        echo "Répertoire $1 créé avec succès!"
    else 
        echo "Problème rencontré lors de la création du répertoire $1..."
        return 1 # code retour erreur
    fi
}
# Fin du programme
function fermetureProgramme() {
    echo -e "Fermeture du programme..."
    exit
}

: '============
   === MAIN ===
   ============'

# Améliorations possibles:
# - Demander si méthode GET ou POST pour le formulaire HTML
# - Prendre en compte si mail, requetes, pour ajouter des exemples dans le fichier action.php
##################################################
# Création des répertoires génériques par défaut #
##################################################
echo -e -n "Bienvenue dans ce petit programme!\nIl permet de générer automatiquement le nouveau module à ajouter au projet ETGL P59!\n\n\n=== Attention, ce programme doit être exécuté à la racine du projet auquel vous voulez ajouter un module!!! ===\n\n\nVeuillez saisir le nom du module:\n-> " # -e permet d'interpréter les \
read nomModuleChoisi # Récupérer la valeur saisie par l'utilisateur
echo "\t-> Votre module s'intitulera: $nomModuleChoisi!"
echo "$nomModuleChoisi"
# Chemin du répertoire courant
cheminActuel=$(pwd) # Récupérer la valeur de la commande pwd
# Chemin du répertoire dédié au module
cheminModule="$cheminActuel/$nomModuleChoisi"
# Création du répertoire dédié au module
if creationRepertoire $nomModuleChoisi
then
    echo "Vous retrouverez le répertoire du module à cet emplacement: $cheminModule"
else # En cas de problème
    echo -e "\t-> Interruption du programme!"
    fermetureProgramme # On interrompt le programme
fi
# Chemin du répertoire Mustache
cheminMustacheModule="$cheminModule/mustache"
# Création du répertoire mustache propre au module
if creationRepertoire $cheminMustacheModule
then
    echo "Vous retrouverez le répertoire mustache du module à cet emplacement: $cheminMustacheModule"
else # En cas de problème
    echo -e "\t-> Interruption du programme!"
    fermetureProgramme # On interrompt le programme
fi
##################################################
# Création des répertoires génériques optionnels #
##################################################
# ASSETS
echo -e -n "Prévoyez-vous de développer des assets (JavaScript,CSS) propres à ce module, en dehors du fichier Mustache? (o/n):\n-> " # -e permet d'interpréter les \
read choixGenerationAssets
if [[ $choixGenerationAssets == "o" ]]
then
    # Chemins des répertoires d'assets à créer
    cheminAssetsModule="$cheminModule/assets"
    cheminAssetsCSSModule="$cheminAssetsModule/css"
    cheminAssetsJSModule="$cheminAssetsModule/js"
    echo -e "\t-> Génération des répertoires d'assets en cours..."
    creationRepertoire $cheminAssetsModule
    creationRepertoire $cheminAssetsCSSModule
    creationRepertoire $cheminAssetsJSModule
    creationFichiersAssets $cheminAssetsModule $nomModuleChoisi # création des fichiers CSS et JS dans le répertoire assets
    echo -e "\t-> Génération des répertoires d'assets terminée!"
fi
# AJAX
echo -e -n "Prévoyez-vous de développer des fichiers PHP pour le traitements de requêtes AJAX? (o/n):\n-> " # -e permet d'interpréter les \
read choixGenerationAJAX
if [[ $choixGenerationAJAX == "o" ]]
then
    # Chemin du répertoire AJAX à créer
    cheminAJAXModule="$cheminModule/ajax"
    echo -e "\t-> Génération du répertoire dédié aux requêtes AJAX en cours..."
    creationRepertoire $cheminAJAXModule # Création du répertoire AJAX
    creationFichierAJAX $cheminAJAXModule $nomModuleChoisi # Création du fichier AJAX
    echo -e "\t-> Génération du répertoire dédié aux requêtes AJAX terminée!"
fi
###############################################
# Création des fichiers génériques par défaut #
###############################################
creationFichiersGeneriquesDefaut $cheminModule $nomModuleChoisi
#################################################
# Création des fichiers génériques à la demande #
#################################################
# Requêtes SQL propres au module (requetes.php)
echo -e -n "Prévoyez-vous de développer des requêtes SQL propres à ce module? (o/n):\n-> " # -e permet d'interpréter les \
read choixGenerationRequetes
if [[ $choixGenerationRequetes == "o" ]]
then
    creationFichierRequetes $cheminModule $nomModuleChoisi
fi
# Envoi de mail propres au module (mail.php)
echo -e -n "Prévoyez-vous de d'envoyer des mail propres à ce module? (o/n):\n-> " # -e permet d'interpréter les \
read choixGenerationMail
if [[ $choixGenerationMail == "o" ]]
then
    creationFichierMail $cheminModule $nomModuleChoisi
fi
# Gestion du traitement des saisies du formulaire HTML (action.php)
echo -e -n "Prévoyez-vous de mettre en place un formulaire HTML à traiter dans ce module? (o/n):\n-> " # -e permet d'interpréter les \
read choixGenerationAction
if [[ $choixGenerationAction == "o" ]]
then
    creationFichierAction $cheminModule $nomModuleChoisi
fi
echo -e "\n\n\nN'oubliez pas d'ajouter les informations nécessaires dans la classe Render et dans le fichier Mustache Body.mustache!\nToutes les informations sont indiquées dans les fichiers générés automatiquement ci-besoin.\n\n\n"
echo -e "\n========================================\n||Le programme est arrivé à son terme!||\n||Merci d'avoir utiliser ce programme!||\n========================================"
fermetureProgramme