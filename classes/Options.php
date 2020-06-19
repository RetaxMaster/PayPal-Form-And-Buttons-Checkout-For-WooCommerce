<?php

namespace Classes;

/**
 * 
 * Esta clase obtiene todas las respuestas del usuario del formulario de administración
 * y las pone en un objeto, también indica algunas variables booleanas
 * 
 */

class Options  {

    public $paypal_checkout;
    public $paypal_plus_mexico_brazil;
    public $production_mode;
    public $sandbox_client_id;
    public $sandbox_client_secret;
    public $production_client_id;
    public $production_client_secret;

    public $isSandbox;
    public $isPayPalCheckout;
    public $isPayPalPlusMexicoBrazil;

    public function __construct() {

        $this->paypal_checkout = get_option("rm_paypal_checkout_paypal_checkout", "checked");
        $this->paypal_plus_mexico_brazil = get_option("rm_paypal_checkout_paypal_plus_mexico_brazil", "");
        $this->payment_experience = get_option("rm_paypal_checkout_payment_experience", "");
        $this->production_mode = get_option("rm_paypal_checkout_production_mode", "");
        $this->sandbox_client_id = get_option("rm_paypal_checkout_s_clientId", "");
        $this->sandbox_client_secret = get_option("rm_paypal_checkout_s_secret", "");
        $this->production_client_id = get_option("rm_paypal_checkout_p_clientId", "");
        $this->production_client_secret = get_option("rm_paypal_checkout_p_secret", "");

        $this->isSandbox = $this->production_mode != "checked";
        $this->isProduction = $this->production_mode == "checked";
        $this->isPayPalCheckout = $this->paypal_checkout == "checked";
        $this->isPayPalPlusMexicoBrazil = $this->paypal_plus_mexico_brazil == "checked";
        $this->isPaymentExperience = $this->payment_experience == "checked";

    }

}


?>