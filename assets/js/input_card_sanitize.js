$(document).ready(function(){
    
    //Verificar inputs

    $("#card-form input").on("focus", function () {
        $(this).removeClass("is-invalid");
        //$(this).attr("placeholder", "");
    });

    $("#card-form input").on("blur", function () {
        if ($(this).val() == "") {
            $("span.card-errors").text("");
        }
    });

    //->Verificar inputs

    $("#card-form input").on("focus", function () {
        $(this).removeClass("is-invalid");
    });

    // Input del nombre

    $("#HolderName").on("keydown", function (e) {
        if (!isNaN(e.key) && e.keyCode != 32) e.preventDefault();
    });
    

    // ->Input del nombre

    //Input de la tarjeta
    $("#CreditCardNumber").on("keydown", function (e) {
        if ((this.value.length == 22 && e.keyCode != 8 && e.keyCode != 37 && e.keyCode != 39 && e.keyCode != 13) || e.keyCode == 32 || e.keyCode == 17 || (isNaN(e.key) && e.keyCode != 8 && e.keyCode != 39 && e.keyCode != 13)) e.preventDefault();
    });

    $("#CreditCardNumber").on("keyup", function (e) {
        if (this.value.length == 4 || this.value.length == 10 || this.value.length == 16) this.value = this.value + "  ";
        if (e.keyCode == 8) {
            var text = this.value.split("");
            if (text[text.length - 1] == " ") {
                text = text.join("").trim();
                this.value = text;
            }
        }
    });

    $("#CreditCardNumber").on("keypress", function () {
        if (this.value.length == 4 || this.value.length == 10 || this.value.length == 16) this.value = this.value + "  ";
    });

    $("#CreditCardNumber").on("paste", function (e) {
        setTimeout(function () {
            var string = e.currentTarget.value;
            var numbers = string.replace(/\D/igm, "");
            numbers = numbers.substr(0, 16);
            serie1 = numbers.substr(0, 4);
            serie2 = numbers.substr(4, 4);
            serie3 = numbers.substr(8, 4);
            serie4 = numbers.substr(12, 4);
            document.getElementById("CreditCardNumber").value = serie1 + "  " + serie2 + "  " + serie3 + "  " + serie4;
        }, 0);
    });

    $("#expirationDate").on("keydown", function (e) {
        if ((this.value.length == 7 && e.keyCode != 8 && e.keyCode != 37 && e.keyCode != 39 && e.keyCode != 13) || e.keyCode == 32 || (isNaN(e.key) && e.keyCode != 8 && e.keyCode != 37 && e.keyCode != 39 && e.keyCode != 13)) e.preventDefault();
    });

    $("#expirationDate").on("keyup", function (e) {
        if (this.value.length == 2) this.value = this.value + " / ";
        if (e.keyCode == 8) {
            var text = this.value.split("");
            if (text[text.length - 1] == " " || text[text.length - 1] == "/") {
                text = text.join("");
                text = (text[text.length - 1] == " ") ? text.split(" / ")[0] : text.split(" /")[0];
                this.value = text;
            }
        }
    });

    $("#expirationDate").on("keypress", function () {
        if (this.value.length == 2) this.value = this.value + " / ";
    });

    $("#cvcInput").on("keydown", function (e) {
        if ((this.value.length == 4 && e.keyCode != 8 && e.keyCode != 37 && e.keyCode != 39 && e.keyCode != 13) || e.keyCode == 32 || (isNaN(e.key) && e.keyCode != 8 && e.keyCode != 37 && e.keyCode != 39 && e.keyCode != 13)) e.preventDefault();
    });

});