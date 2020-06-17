$(document).ready(function(){

    console.log(paypal);

    if (paypal.error != "") {

        if (paypal.is_admin == "1") {
            
            Swal.fire({
                title: "Ha surgido un error con tu configuración de PayPal",
                text: paypal.error,
                icon: "error"
            });

        }

    }
    else {
        alert("It's time to checkout!");

        // Codigo a ejecutar de PayPal

    }


});