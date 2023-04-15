// Variables utiles
let patternEmail = '^((?!\\.)[\\w\\-_.]*[^.])(@\\w+)(\\.\\w+(\\.\\w+)?[^.\\W])$';
let selecteurChampNom = '#nom';
let selecteurChampPrenom = '#prenom';
let selecteurChampNomUtilisateur = '#username';
let selecteurChampAdresseEmail = '#email';
let selecteurChampMotDePasse = '#password';
let selecteurChampConfirmationMotDePasse = '#confirm';
let urlAPIEmail = './api/verifierExistenceEmail.php'
let urlFichierPseudonymes = 'https://raw.githubusercontent.com/jeanphorn/wordlist/master/usernames.txt';

/**
 * Génération d'un pseudonyme en fonction du paramètre reçu
 * @param prenom
 * @param nom
 */
function genererNomUtilisateur(prenom = '', nom = '')
{
    // Si pas de nom et prenom saisis
    if (!prenom || !nom) {
        // On récupère une liste de pseudonymes
        $.get(urlFichierPseudonymes, function (retour) {
            // On découpé le texte en tableau
            let pseudonymes = retour.split('\n')
            // On génère un pseudonyme aléatoirement
            let nomUtilisateurGenere = pseudonymes[Math.floor(Math.random() * pseudonymes.length)] + '-' + Math.floor((Math.random() * 1000) + 1);
            // On remplit la valeur du champ avec ce pseudonyme
            $(selecteurChampNomUtilisateur).val(nomUtilisateurGenere);
        });
    } else {
        // On remplit la valeur du champ avec ce pseudonyme
        $(selecteurChampNomUtilisateur).val(prenom + '.' + nom);
    }
}

/**
 * Vérification que l'email reçu en paramètre ne soit pas déjà en base
 * @param email
 */
function verifierSiEmailExisteDeja(email)
{
    $.post( urlAPIEmail, { 'email': email}, function( retour ) {
        // Si email === true, alors l'émail reçu en paramètre existe déjà en base
        if (retour.email === true) {
            swal ( "Problème" ,  "L'adresse saisie est déjà associée à un compte! S'il s'agit de votre compte, vous pouvez utiliser la section mot de passe oublié dans la page de connexion." ,  "error" );
            $(selecteurChampAdresseEmail).val('');
            swal({
                title: "Adresse déjà utilisée!",
                text: "Cette adresse est déjà liée à un compte! Vous pouvez utiliser la réinitialisation de mot de passe pour récupérer votre compte.",
                icon: "warning",
                dangerMode: true,
            })
        }
    }, 'json');
}
// On attend que la page soit chargée avant d'effectuer des actions
$('body').ready(function (){
    // Génération d'un pseudonyme par défaut
    genererNomUtilisateur();
    // Lorsque l'utilisateur sort du champ dédié au nom
    $(selecteurChampNom).focusout(function (){
        // On regarde la taille des champs dédiés au prénom et au nom
        if ($(this).val().length >= 3 && $(selecteurChampPrenom).val().length >= 3) {
            // On génère un pseudonyme
            genererNomUtilisateur($(selecteurChampPrenom).val(), $(this).val());
        }
    });
    // Lorsque l'utilisateur sort du champ dédié au prénom
    $(selecteurChampPrenom).focusout(function (){
        // On regarde la taille des champs dédiés au prénom et au nom
        if ($(this).val().length >= 3 && $(selecteurChampNom).val().length >= 3) {
            // On génère un pseudonyme
            genererNomUtilisateur($(this).val(), $(selecteurChampNom).val());
        }
    });
    // Lorsque l'utilisateur change la valeur du champ dédié au prénom
    $(selecteurChampAdresseEmail).change(function (){
        // Si l'utilisateur a saisi une adresse mail et qu'elle correspond au regex d'un email
        if ($(this).val().length > 0 && $(this).val().match(patternEmail)) {
            verifierSiEmailExisteDeja($(this).val());
        } else {
            $(selecteurChampAdresseEmail).val('');
            swal({
                title: "Format e-mail!",
                text: "L\'e-mail saisi est invalide! Veuillez saisir une adresse valide.",
                icon: "error",
                dangerMode: true,
            })
        }
    });
    // Lorsque l'utilisateur sort du champ dédié à la confirmation du mot de passe
    $(selecteurChampConfirmationMotDePasse).focusout(function (){
        // Si l'utilisateur n'a pas rempli le champ ou le champ est différent du champ mot de passe
        if ($(this).val().length === 0 || $(this).val() !== $(selecteurChampMotDePasse).val()) {
            $(this).val('');
            swal({
                title: "Confirmation du mot de passe!",
                text: "Le mot de passe et sa confirmation ne sont pas identiques! Veuillez réessayer.",
                icon: "error",
                dangerMode: true,
            })
        }
    });
});