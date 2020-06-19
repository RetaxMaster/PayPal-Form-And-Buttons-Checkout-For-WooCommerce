<?php 

// El carrito siempre se mandará a llamar, el formulario de cobro solo se mandará a llamar si PayPal Plus Mexico and Brazil está habilitado (Dentro de este, si PayPal Checkout también está habilitado mostrará los botones), si PayPal checkout está habilitado y PayPal Mexico and Brazil está deshabilitado, mostrará los botones (Se mostrará solo si PayPal México and Brazil está deshabilitado, porque si está habilitado, ya el propio formulario hace la pregunta para PayPal Checkout)

?>

<div class="row justify-content-center">
    <?php require("cart.php"); ?>
    <?php if(self::is_integration_enabled(["paypal_plus_mexico_brazil"])) require("payment-form.php"); ?>
    <?php if(!self::$options->isPayPalPlusMexicoBrazil && self::$options->isPayPalCheckout) require("render-buttons.php"); ?>
</div>