
$(() => {

    // affichage du popup lors du click sur le bouton "résilier abonnement"
    $('#resiliation').on('click', function(){
        $('#exampleModal').modal('show');
    });

    $('.close').on('click', function(){
        $('#exampleModal').modal('hide');
    });

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
});