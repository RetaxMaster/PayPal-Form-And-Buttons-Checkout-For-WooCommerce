$(document).ready(function(){

    var isSandbox = true;
    var isBasic = true;

    // Muestra u oculta los paneles dependiendo de la configuración del usuario
    function togglePanels() {

        // Oculto todos
        $(".advanced-integration").addClass("hidden");
        $(".basic-integration").addClass("hidden");
        $(".sandbox").addClass("hidden");
        $(".production").addClass("hidden")
        
        // Dependiendo del modo muestro los paneles
        if (isSandbox && isBasic) // Sandbox básica
                $(".sandbox.basic-integration").removeClass("hidden");
        else if(isSandbox && !isBasic) // Sandbox avanzada
                $(".sandbox.advanced-integration").removeClass("hidden");
        else if(!isSandbox && isBasic) // Producción básica
                $(".production.basic-integration").removeClass("hidden");
        else if(!isSandbox && !isBasic) // Producción avanzada
                $(".production.advanced-integration").removeClass("hidden");

    }

    // Desactiva la integración avanzada al clicar la básica
    $("#BasicIntegration").on("click", function(){
        
        if (this.checked) {
            $("#AdvancedIntegration").prop("checked", false);
            isBasic = true;
        }

        togglePanels();

    });

    // Desactiva la integración básica al clicar la avanzada
    $("#AdvancedIntegration").on("click", function(){
        
        if (this.checked) {
            $("#BasicIntegration").prop("checked", false);
            isBasic = false;
        }

        togglePanels();
            
    });

    // Cambia entre el modo de pruebas y el modo de producción
    $("#InProduction").on("click", function(){
        
        isSandbox = !this.checked;
        togglePanels();
            
    });

});