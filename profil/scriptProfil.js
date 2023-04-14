
const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: 'btn btn-success',
      cancelButton: 'btn btn-danger'
    },
    buttonsStyling: false
})

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
});