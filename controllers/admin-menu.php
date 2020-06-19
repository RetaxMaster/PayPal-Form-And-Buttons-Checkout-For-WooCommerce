<?php

$options = self::$options;

// Obtenemos las opciones marcadas
$PayPalCheckout = $options->paypal_checkout;
$PayPalPlusMexicoBrazil = $options->paypal_plus_mexico_brazil;
$PaymentExperience = $options->payment_experience;
$production_mode = $options->production_mode;

// Obtenemos los valores
$sandbox_client_id = $options->sandbox_client_id;
$sandbox_secret = $options->sandbox_client_secret;

$production_client_id = $options->production_client_id;
$production_secret = $options->production_client_secret;

// Determinamos qué opciones se ocultaran y cuáles se mostrarán
$show_sandbox = $options->isSandbox ? "" : "hidden";
$show_production = !$options->isSandbox ? "" : "hidden";

$show_client_id = ($options->isPayPalCheckout || $options->isPayPalPlusMexicoBrazil) ? "" : "hidden";
$show_client_secret = $options->isPayPalPlusMexicoBrazil ? "" : "hidden";

?>