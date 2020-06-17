<?php

$basic_integration = get_option("rm_paypal_checkout_basic_integration", "checked");
$advanced_integration = get_option("rm_paypal_checkout_advanced_integration", "");
$production_mode = get_option("rm_paypal_checkout_production_mode", "");

$isSandbox = $production_mode != "checked";
$isBasic = $basic_integration == "checked";

$sandbox_basic_account = get_option("rm_paypal_checkout_s_Account", "");
$sandbox_basic_client_id = get_option("rm_paypal_checkout_s_clientId", "");
$sandbox_basic_secret = get_option("rm_paypal_checkout_s_secret", "");

$sandbox_advanced_username = get_option("rm_paypal_checkout_s_username", "");
$sandbox_advanced_password = get_option("rm_paypal_checkout_s_password", "");
$sandbox_advanced_signature = get_option("rm_paypal_checkout_s_signature", "");

$production_basic_account = get_option("rm_paypal_checkout_p_Account", "");
$production_basic_client_id = get_option("rm_paypal_checkout_p_clientId", "");
$production_basic_secret = get_option("rm_paypal_checkout_p_secret", "");

$production_advanced_username = get_option("rm_paypal_checkout_p_username", "");
$production_advanced_password = get_option("rm_paypal_checkout_p_password", "");
$production_advanced_signature = get_option("rm_paypal_checkout_p_signature", "");

$hide_sandbox_basic = $isSandbox && $isBasic ? "" : "hidden";
$hide_sandbox_advanced = $isSandbox && !$isBasic ? "" : "hidden";
$hide_production_basic = !$isSandbox && $isBasic ? "" : "hidden";
$hide_production_advanced = !$isSandbox && !$isBasic ? "" : "hidden";

?>