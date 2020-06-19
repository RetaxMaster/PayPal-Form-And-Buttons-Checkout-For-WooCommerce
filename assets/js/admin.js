$(document).ready(function(){

    var isSandbox = admin.production_mode != "checked";
    var PayPalCheckout = admin.paypal_checkout == "checked";
    var PayPalPlusMexicoBrazil = admin.paypal_plus_mexico_brazil == "checked";
    var PaymentExperience = admin.payment_experience == "checked";

    // Muestra u oculta los paneles dependiendo de la configuración del usuario
    function togglePanels() {

        // Oculto todos
        $(".sandbox").addClass("hidden");
        $(".production").addClass("hidden")
        $(".client-id").addClass("hidden");
        $(".client-secret").addClass("hidden");
        
        // Dependiendo del modo muestro los paneles
        if (isSandbox) // Sandbox básica
            $(".sandbox").removeClass("hidden");
        else 
            $(".production").removeClass("hidden");

        if(PayPalCheckout || PayPalPlusMexicoBrazil || PaymentExperience) $(".client-id").removeClass("hidden");

        if(PayPalPlusMexicoBrazil || PaymentExperience) $(".client-secret").removeClass("hidden");

    }

    $("#PayPalCheckout").on("click", function(){
        PayPalCheckout = this.checked;
        togglePanels();
    });

    $("#PayPalPlusMexicoBrazil").on("click", function(){
        PayPalPlusMexicoBrazil = this.checked;
        togglePanels();
    });

    $("#PaymentExperience").on("click", function(){
        PaymentExperience = this.checked;
        togglePanels();
    });

    // Cambia entre el modo de pruebas y el modo de producción
    $("#InProduction").on("click", function(){
        isSandbox = !this.checked;
        togglePanels();
    });

});