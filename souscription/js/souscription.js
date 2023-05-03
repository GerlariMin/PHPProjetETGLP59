let prixAPayer = 0;
let identifiantAbonnement = 0;

function showPaypalButton(prix, identifiant){
    document.getElementById('paypal').style.display = 'block';
    prixAPayer = prix;
    identifiantAbonnement = identifiant;
}
$('body').ready(function (){
    paypal.Buttons({
        // Sets up the transaction when a payment button is clicked
        createOrder: (data, actions) => {
            console.log(prixAPayer);
            console.log(identifiantAbonnement);
            return actions.order.create({
                "purchase_units": [{

                    "items": [
                        {
                            "name": "OCR SQUARE PREMIUM", /* Shows within upper-right dropdown during payment approval */
                            "description": "Abonnement payant pour effectuer des traitements OCR.", /* Item details will also be in the completed paypal.com transaction view */
                            "unit_amount": {
                                "currency_code": "EUR",
                                "value": "" + prixAPayer
                            },
                            "quantity": "1"
                        },
                    ],

                    "amount": {
                        "currency_code": "EUR",
                        "value": "" + prixAPayer,
                        "breakdown": {
                            "item_total": {  /* Required when including the items array */
                                "currency_code": "EUR",
                                "value": "" + prixAPayer
                            }
                        }
                    }

                }]
            });
        },
        // Finalize the transaction after payer approval
        onApprove: (data, actions) => {
            return actions.order.capture().then(function(orderData) {
                // Successful capture! For dev/demo purposes:
                console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
                const transaction = orderData.purchase_units[0].payments.captures[0];
                console.log(`Transaction ${transaction.status}: ${transaction.id}\n\nSee console for all available details`);
                $.post( './api/miseAJourAbonnement.php', { 'abonnement': identifiantAbonnement, 'prix': prixAPayer}, function( retour ) {
                    // Si email === true, alors l'émail reçu en paramètre existe déjà en base
                    if (retour.actualisation === true) {
                        // Swal pour proposer l'impression de la facture PayPal
                        /*
                        swal({
                            title: "Souhaitez-vous avoir une facture?",
                            text: "Once deleted, you will not be able to recover this imaginary file!",
                            icon: "success",
                            buttons: true,
                            dangerMode: true,
                        })
                            .then((willDelete) => {
                                if (willDelete) {
                                    swal("Poof! Your imaginary file has been deleted!", {
                                        icon: "success",
                                    });
                                } else {
                                    swal("Your imaginary file is safe!");
                                }
                            });
                        */
                        window.location.href = "../tableauDeBord/?succes=aok";
                    } else {
                        swal("Paiement!", "La tentative de facturation a échouée!", "error");
                    }
                }, 'json');
                //window.location.href = "../tableauDeBord/";
                // When ready to go live, remove the alert and show a success message within this page. For example:
                // const element = document.getElementById('paypal-button-container');
                // element.innerHTML = '<h3>Thank you for your payment!</h3>';
                // Or go to another URL:  actions.redirect('thank_you.html');
            });
        }
    }).render('#paypal-button-container');
});