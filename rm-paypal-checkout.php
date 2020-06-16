<?php

/*
Plugin Name: PayPal Form And Buttons Checkout For WooCommerce
Description: Plugin para hacer un checkout con PayPal mediante un formulario de cobros eb tu web o usando los botones de PayPal (Requiere Bootstrap y FontAwesome), puedes incluir el carrito de compras con el shortcode [rm_paypal_cart attrs='tus atributos para el contenedor'], puedes incluir el formulario de cobro con el shortcode [rm_paypal_form attrs='tus atributos para el contenedor'] o puedes incluir ambos con el shortcode [rm_paypal_all cart_attrs='tus atributos para el contenedor del carrito' form_attrs='tus atributos para el contenedor del formulario de cobro']
Version: 1.0
Author: RetaxMaster
License: GPLv2
*/

// El plugin requiere de WooCommerce para funcionar

function check_some_other_plugin() {

    if (!is_plugin_active("woocommerce/woocommerce.php")) {

        add_action('admin_notices', function() {
            echo '<div class="notice notice-warning is-dismissible">
                    <p>WooCommerce no está activado, PayPal Form And Buttons Checkout For WooCommerce necesita WooCommerce para funcionar.
                    <br>
                    <a href="' . admin_url( 'plugins.php' ) . '">&laquo; Por favor activa WooCommerce</a>
                    </p>
                </div>';
        });

        deactivate_plugins( plugin_basename( __FILE__ ) ); 

        if ( isset($_GET['activate']) ) {
            unset($_GET['activate']);
        }

    }

}

add_action( 'admin_init', 'check_some_other_plugin' );

// -> El plugin requiere de WooCommerce para funcionar

// Determinará si la función ya fue llamada para no duplicar los enqueue en caso de haber dos shortcodes que la llamen
$calledPluginScripts = false;

// Seaparador del menú
function add_admin_menu_separator($position) {
    global $menu;
    $index = 0;
    foreach($menu as $offset => $section) {
        if (substr($section[2],0,9)=='separator')
        $index++;
        if ($offset>=$position) {
            $menu[$position] = array('','read',"separator{$index}",'','wp-menu-separator');
            break;
        }
    }
    ksort( $menu );
}

// Añade un menú al admin panel
function custom_menu() {

    add_menu_page('PayPal Checkout', 'PayPal Checkout', 'edit_posts', 'rm_paypal_checkout', function() {
        require("includes/admin-menu.php");
    }, 'dashicons-media-spreadsheet', 59);
    
    add_admin_menu_separator(60);

}

// Añade los estilos y scripts para esta página
function load_assets($hook) {

	// Load only on ?page=mypluginname
    if( $hook != 'toplevel_page_rm_paypal_checkout' ) return;
    
    wp_enqueue_style('bootstrap', "https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css", [], "4.5.0");
    wp_enqueue_style('rm_styles', plugins_url('assets/css/style.css', __FILE__), ["bootstrap"], "1.0");

    wp_enqueue_script('popper', "https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js", ['jquery'], "1.16.0", true);
    wp_enqueue_script('bootstrap', "https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js", ['popper'], "4.5.0", true);

}

// Añade los estilos y scripts para el front
function add_plugin_scripts() {

    global $calledPluginScripts;
    $calledPluginScripts = true;

    wp_enqueue_script('paypal_sdk', "https://www.paypal.com/sdk/js?client-id=AaDMNVwbNPRd-TQbtltp-Z_ZGbhRcaRPRf2SXtTnUnc1gs-Gz9yOxaO3r71spFzc1fn1kLgK6qzlRfOd&locale=es_MX", [], null, true);
    wp_enqueue_script('card_form_validation', plugins_url('assets/js/validation.js', __FILE__), ['jquery'], "1.0", true);
}

// Guarda los datos enviados del menú
function save_paypal_keys() {

    // Ignoramos los auto guardados.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Si no está el nonce declarado antes o no podemos verificarlo no seguimos.
    if (!isset( $_POST['paypal_checkout_config']) || !wp_verify_nonce($_POST['paypal_checkout_config'], 'paypal_config_nonce')) {
        return;
    }

    // Si el usuario actual no puede editar entradas no debería estar aquí.
    if (!current_user_can('edit_posts')) {
        return;
    }

    // Nos aseguramos de que hay información que guardar.
    if (isset($_POST['username'])) {
        $value = sanitize_text_field($_POST['username']);
        update_option('rm_paypal_checkout_username', $value);
    }

    // Nos aseguramos de que hay información que guardar.
    if (isset($_POST['password'])) {
        $value = sanitize_text_field($_POST['password']);
        update_option('rm_paypal_checkout_password', $value);
    }

    // Nos aseguramos de que hay información que guardar.
    if (isset($_POST['signature'])) {
        $value = sanitize_text_field($_POST['signature']);
        update_option('rm_paypal_checkout_signature', $value);
    }

    // Obtenemos la url de retorno sanitizada
    $url = sanitize_text_field(
        wp_unslash($_POST['_wp_http_referer'])
    );

    $url = add_query_arg('success', 'true', $url);
 
    // Y reedirigimos a la página para guardar la información
    wp_safe_redirect(urldecode($url));
    exit;

}

// Crea el shortcode para insertar el formulario de cobro
function rm_add_paypal_form($attrs) {

    global $calledPluginScripts;
    if(!$calledPluginScripts) add_plugin_scripts();

    $form_attrs = $attrs["attrs"] ?? "class='col-12 col-sm-6 mb-4'";
    require("includes/payment-form.php");
}

// Crea el shortcode para insertar el carrito de compras
function rm_add_paypal_cart($attrs) {
    $cart_attrs = $attrs["attrs"] ?? "class='col-12 col-sm-6 mb-4'";
    require("includes/cart.php");
}

// Crea el shortcode para insertar el carrito de compras junto al formulario de cobro
function rm_add_paypal_shipment($attrs) {

    global $calledPluginScripts;
    if(!$calledPluginScripts) add_plugin_scripts();

    $ship_attrs = $attrs["attrs"] ?? "class='col-12 col-sm-6 mb-4'";
    require("includes/shipment.php");

}

// Crea el shortcode para insertar el carrito de compras junto al formulario de cobro
function rm_add_paypal_all($attrs) {

    global $calledPluginScripts;
    if(!$calledPluginScripts) add_plugin_scripts();

    $cart_attrs = $attrs["cart_attrs"] ?? "class='col-12 col-sm-6 mb-4'";
    $form_attrs = $attrs["form_attrs"] ?? "class='col-12 col-sm-6 mb-4'";
    $ship_attrs = $attrs["ship_attrs"] ?? "class='col-12 col-sm-6 mb-4'";
    require("includes/all.php");

}

add_action('admin_enqueue_scripts', 'load_assets');
add_action('admin_menu', 'custom_menu');
add_action('admin_post_rm_paypal_checkout_saving', 'save_paypal_keys');

if(!is_admin()) {

    add_shortcode('rm_paypal_form', 'rm_add_paypal_form');
    add_shortcode('rm_paypal_cart', 'rm_add_paypal_cart');
    add_shortcode('rm_paypal_shipment', 'rm_add_paypal_shipment');
    add_shortcode('rm_paypal_all', 'rm_add_paypal_all');

}



?>