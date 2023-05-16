/**
 * Fonction appelée pour les changements de login, e-mail ou mdp
 * @param nomPost
 * @param valeur
 */
function modifierInfosProfil(nomPost, valeur) {
    // On s'assure que les deux inputs concernés ne soient pas vides
    if (valeur !== '') {
        let postAEnvoyer = {};
        postAEnvoyer[nomPost] = valeur;
        // On appelle le fichier PHP dédié à l'enregistrement
        $.post( './api/modifierInfosProfil.php', postAEnvoyer, function( retour ) {
            // Si modification === true, alors modification faite
            if (retour.modification === true) {
                Swal.fire({
                    icon: 'success',
                    title: 'Votre modification a été prise en compte! Un mail de confirmation vous a été envoyé.',
                    showConfirmButton: false,
                    timer: 3500,
                    timerProgressBar: true,
                }).then((result) => {
                    /* Read more about handling dismissals below */
                    if (result.dismiss === Swal.DismissReason.timer) {
                        window.location.href = "./index.php";
                    }
                })
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Un problème est survenu lors de l\'enregistrement de votre modification. Veuillez réessayer dans quelques minutes.',
                    showConfirmButton: false,
                    timer: 3500,
                    timerProgressBar: true,
                })
            }
        }, 'json');
    }
}
// On attend le chargement de la page HTML avant d'exécuter les instructions
$(() => {
    // lorsqu'on appuie sur le bouton modifier, un input texte apparaît, où l'on peut modifier ses information personnelles
    $('a[id^="modifier"]').on("click", function () {

        var boutonClique = $(this).attr('id').substring(8);
        var selecteur = '#collapse' + boutonClique;

        if($(selecteur).hasClass("show")){
            $(selecteur).collapse("hide");
        }else{
            $(selecteur).collapse("show");
        }
    });

    // retire les inputs vides
    $('div > input').each(function (){
        if($(this).attr("name") == ""){
            $(this).remove();
        }
    })

    $('#suppression').on("click", function(){
        swalWithBootstrapButtons.fire({
            title: 'Etes-vous sûr ?',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Oui',
            cancelButtonText: 'Non',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // swalWithBootstrapButtons.fire(
                //     'Suppression annulée',
                //     'Merci',
                //     'error'
                //     )
                window.location.href ='../supprimerCompte/index.php';
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire(
                    'Suppression annulée',
                    'Merci',
                    'error'
                )
            }
        })
    })

    $('#resiliation').on("click", function(){
        swalWithBootstrapButtons.fire({
            title: 'Etes-vous sûr ?',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Oui',
            cancelButtonText: 'Non',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                //TODO: add AJAX query + timer
                // swalWithBootstrapButtons.fire(
                //     'Suppression annulée',
                //     'Merci',
                //     'error'
                //     )
                window.location.href ='../resilierAbonnement/index.php';
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire(
                    'Suppression annulée',
                    'Merci',
                    'error'
                )
            }
        })
    })

    // AJAX pour modification nom et/ ou prénom
    $('#boutonEnregistrerNomPrenom').click(function () {
        // On s'assure que les deux inputs concernés ne soient pas vides
        if ($('#idInputNom').val() !== '' || $('#idInputPrenom').val() !== '') {
            // On appelle le fichier PHP dédié à l'enregistrement
            $.post( './api/modifierInfosProfil.php', { 'nom': $('#idInputNom').val(), 'prenom': $('#idInputPrenom').val() }, function( retour ) {
                // Si modification === true, alors modification faite
                if (retour.modification === true) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Votre modification a été prise en compte!',
                        showConfirmButton: false,
                        timer: 3500,
                        timerProgressBar: true,
                    }).then((result) => {
                        /* Read more about handling dismissals below */
                        if (result.dismiss === Swal.DismissReason.timer) {
                            window.location.href = "./index.php";
                        }
                    })
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Un problème est survenu lors de l\'enregistrement de votre modification. Veuillez réessayer dans quelques minutes.',
                        showConfirmButton: false,
                        timer: 3500,
                        timerProgressBar: true,
                    })
                }
            }, 'json');
        }
    });
    // AJAX pour modification login
    $('#boutonEnregistrerLogin').click(function () {
        modifierInfosProfil('login', $('#idInputLogin').val());
    });
    // AJAX pour modification email
    $('#boutonEnregistrerEmail').click(function () {
        modifierInfosProfil('mail', $('#idInputEmail').val());
    });
    // AJAX pour modification mdp
    $('#boutonEnregistrerMotDePasse').click(function () {
        modifierInfosProfil('mdp', $('#idInputMotDePasse').val());
    });

});