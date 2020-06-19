$(document).ready(function(){

    console.log(server_messages);

    if (server_messages.error != "") {

        if (server_messages.is_admin == "1") {
            
            Swal.fire({
                title: "Ha surgido un error con tu configuración de PayPal",
                text: server_messages.error,
                icon: "error"
            });

        }

    }
    else {

        // Codigo a ejecutar de PayPal

        // Mostrar los botones
        paypal.Buttons({
            commit: false,
            createOrder: function(data, actions) {
                // This function sets up the details of the transaction, including the amount and line item details
                return actions.order.create({
                purchase_units: [{
                    amount: {
                    value: '2'
                    }
                }]
                });
            },
            onCancel: function (data) {
                // Show a cancel page, or return to cart
            },
            onApprove: function(data, actions) {
                // This function captures the funds from the transaction
                return actions.order.capture().then(function(details) {
                // This function shows a transaction success message to your buyer
                alert('Thanks for your purchase!');
                });
            }
        }).render('#rm_paypal_buttons');

        // Eligibility check for advanced credit and debit card payments

        console.log(paypal);
        console.log(paypal.HostedFields);
        console.log(paypal.HostedFields.isEligible());
        
        
        if (paypal.HostedFields.isEligible()) {
            paypal.HostedFields.render({
            createOrder: function () {return "order-ID";}, // replace order-ID with the order ID
            styles: {
                'input': {
                'font-size': '17px',
                'font-family': 'helvetica, tahoma, calibri, sans-serif',
                'color': '#3a3a3a'
                },
                ':focus': {
                'color': 'black'
                }
            },
            fields: {
                number: {
                selector: '#CreditCardNumber',
                placeholder: 'card number'
                },
                cvv: {
                selector: '#cvcInput',
                placeholder: 'card security number'
                },
                expirationDate: {
                selector: '#expirationDate',
                placeholder: 'mm/yy'
                }
            }
            }).then(function (hf) {
                
                $('#rm-paypal-checkout-card-form').submit(function (event) {
                    console.log($('#expirationDate').val());
                    console.log(hf);
                    
                    event.preventDefault();
                    hf.submit({
                    // Cardholder Name
                    cardholderName: "Hola",
                    // Billing Address
                    billingAddress: {
                        streetAddress: "Street",      // address_line_1 - street
                        extendedAddress: "Extended",       // address_line_2 - unit
                        region: "Region",           // admin_area_1 - state
                        locality: "Locality",          // admin_area_2 - town / city
                        postalCode: "11800",           // postal_code - postal_code
                        countryCodeAlpha2: "MX"   // country_code - country
                    }
                    });
                });
            });
        }
        else {
            $('#rm-paypal-checkout-card-form').hide();  // hides the advanced credit and debit card payments fields if merchant isn't eligible
        }

    }


});