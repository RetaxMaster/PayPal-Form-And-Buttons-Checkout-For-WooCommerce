<?php

// Inicialización de la API REST

/* function ajax_endpoints() {

    register_rest_route('put', 'cart', array(
        'methods'  => "POST",
        'callback' => 'add_to_cart',
    ) );

}

add_action('rest_api_init', 'ajax_endpoints');

// -> Inicialización de la API REST


// Evento ajax para cuando se agregue un nuevo producto al carrito
function add_to_cart() {

    global $woocommerce;
    $cart = $woocommerce->cart;
    $id = (int) $_POST["product_id"];
    $quantity = $_POST["quantity"];

    // Si ya estaba el producto en el carrito lo quito y lo vuelvo a meter
    $cart->remove_cart_item(get_key_from_product_id($cart->get_cart(), $id));
    $cart->add_to_cart($id, $quantity);

    $cart_parsed = parse_cart_data($cart->get_cart());

    $response["cart"] = $cart_parsed;
    $response["cart_total"] = $cart->get_total();
    $response["status"] = true;

    return json_encode($response);

} */

?>