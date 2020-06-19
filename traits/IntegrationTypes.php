<?php

namespace Traits;

/**
 * Trait con métodos para ejecutar cada tipo de integración
 */
trait IntegrationTypes {

    private static function PayPalCheckout() : void {
        wp_enqueue_script('rm_paypal_checkout_paypal_checkout',  plugins_url('assets/js/paypal_checkout.js', __FILE__), ['rm_paypal_checkout_scripts'], "1.0", true);
    }

    private static function PayPalPlusMexicoBrazil() : void {

        // Creo el payment request

        
        wp_enqueue_script('rm_paypal_checkout_paypal_plus_mexico_brazil',  plugins_url('assets/js/paypal_plus_mexico_brazil.js', __FILE__), ['rm_paypal_checkout_scripts'], "1.0", true);
    }

    private static function PaymentExperience() : void {

        // Creo el payment request

        wp_enqueue_script('rm_paypal_checkout_payment_experience',  plugins_url('assets/js/payment_experience.js', __FILE__), ['rm_paypal_checkout_scripts'], "1.0", true);
    }
    
}


?>