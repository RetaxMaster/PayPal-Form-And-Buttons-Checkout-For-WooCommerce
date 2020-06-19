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

require("classes/PayPal.php");

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
        require("controllers/admin-menu.php");
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

    wp_enqueue_script('scripts',  plugins_url('assets/js/scripts.js', __FILE__), ['jquery'], "1.0", true);
    wp_enqueue_script('admin',  plugins_url('assets/js/admin.js', __FILE__), ['scripts'], "1.0", true);

}

// Añade los estilos para el front
function add_plugin_styles() {

    wp_enqueue_style('rm_paypal_checkout_styles', plugins_url('assets/css/advanced.css', __FILE__), [], "1.0");

}

// Añade los scripts para el front
function add_plugin_scripts() {

    $client_id = PayPal::getClientId();

    wp_enqueue_script('paypal_sdk', "https://www.paypal.com/sdk/js?components=hosted-fields,buttons&client-id=$client_id", [], null, false);

    // Agrego los scripts base
    wp_enqueue_script('sweetalert2', "https://cdn.jsdelivr.net/npm/sweetalert2@9", [], "1.1", true);
    wp_enqueue_script('promise-polyfill', "https://cdn.jsdelivr.net/npm/promise-polyfill", [], "1.1", true);
    wp_enqueue_script('rm_paypal_checkout_scripts',  plugins_url('assets/js/scripts.js', __FILE__), ['jquery', 'paypal_sdk'], "1.0", true); // <- Se encargará de cargar el script de PayPal
    
    if (get_integration_type() == "basic") {
        
        // Si la integrtación en básica, cargo los scripts básico

        wp_enqueue_script('basic_paypal_checkout', plugins_url('assets/js/basic_paypal_checkout.js', __FILE__), ['rm_paypal_checkout_scripts'], "1.0", true);

    }
    else {

        // Si la integración es avanzada, cargo los scripts que validan los campos de la tarjeta, también cargo los scripts avanzados

        //wp_enqueue_script('input_card_sanitize', plugins_url('assets/js/input_card_sanitize.js', __FILE__), ['rm_paypal_checkout_scripts'], "1.0", true);
        wp_enqueue_script('advanced_paypal_checkout', plugins_url('assets/js/advanced_paypal_checkout.js', __FILE__), ['rm_paypal_checkout_scripts'], "1.0", true);

    }

}

// Intercepta scripts para modificar sus tributos
function add_attrs_to_scripts($tag, $handle, $src) {

    // Los scripts que queremos interceptar
    $scripts = array('paypal_sdk');

    if (in_array($handle, $scripts)) {
        
        if ("paypal_sdk") {

            // En este caso, paypal requiere también que en el script se le pase el token del cliente actual, entonces genero ese token usando la clase PayPal en classes/PayPal.php, como pueden haber errores (Como que el client_id o client_secret no sean correctos) encierro todo dentro de un try...catch, al script de advanced_paypal_checkout le paso los resultados de la generación de ese token

            try {
                $paypal = new PayPal();
                $client_token = $paypal->generateClientToken();
                $error = "";
            } catch (\Exception $e) {
                $client_token = null;
                $error = $e->getMessage();
            }
    
            wp_localize_script('advanced_paypal_checkout', 'server_messages', array(
                //'url'    => rest_url( '/delete/cart' ),
                'nonce'  => wp_create_nonce("wp_rest"),
                'error' => $error,
                'is_admin' => true // TODO: Investigar como verificar si un usuario es admin, is_admin() no funcionó
            ));
            
            $script = '<script type="text/javascript" src="' . $src . '" data-client-token="' . $client_token .  '"></script>' . "\n";
        }
        
        return $script;

    }

    return $tag;
}


// Añade los assets del plugin
function add_plugin_assets() {

    global $calledPluginScripts;

    if (!$calledPluginScripts) {
        $calledPluginScripts = true;
        add_plugin_styles();
        add_plugin_scripts();
    }

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

    // Guardado del tipo de integración
    
    if (isset($_POST['BasicIntegration']))
        update_option('rm_paypal_checkout_basic_integration', "checked");
    else
        update_option('rm_paypal_checkout_basic_integration', "");
    
    if (isset($_POST['AdvancedIntegration']))
        update_option('rm_paypal_checkout_advanced_integration', "checked");
    else
        update_option('rm_paypal_checkout_advanced_integration', "");

    if (isset($_POST['InProduction']))
        update_option('rm_paypal_checkout_production_mode', "checked");
    else
        update_option('rm_paypal_checkout_production_mode', "");
    
    // -> Guardado del tipo de integración

    // Guardado de las credenciales basicas de prueba
    
    // Nos aseguramos de que hay información que guardar.
    if (isset($_POST['sandboxAccount'])) {
        $value = sanitize_text_field($_POST['sandboxAccount']);
        update_option('rm_paypal_checkout_s_Account', $value);
    }

    // Nos aseguramos de que hay información que guardar.
    if (isset($_POST['clientId'])) {
        $value = sanitize_text_field($_POST['clientId']);
        update_option('rm_paypal_checkout_s_clientId', $value);
    }

    // Nos aseguramos de que hay información que guardar.
    if (isset($_POST['secret'])) {
        $value = sanitize_text_field($_POST['secret']);
        update_option('rm_paypal_checkout_s_secret', $value);
    }
    
    // -> Guardado de las credenciales basicas de prueba

    // Guardado de las credenciales avanzadas de prueba
    
    // Nos aseguramos de que hay información que guardar.
    if (isset($_POST['username'])) {
        $value = sanitize_text_field($_POST['username']);
        update_option('rm_paypal_checkout_s_username', $value);
    }

    // Nos aseguramos de que hay información que guardar.
    if (isset($_POST['password'])) {
        $value = sanitize_text_field($_POST['password']);
        update_option('rm_paypal_checkout_s_password', $value);
    }

    // Nos aseguramos de que hay información que guardar.
    if (isset($_POST['signature'])) {
        $value = sanitize_text_field($_POST['signature']);
        update_option('rm_paypal_checkout_s_signature', $value);
    }
    
    // -> Guardado de las credenciales avanzadas de prueba

    // Guardado de las credenciales basicas de producción
    
    // Nos aseguramos de que hay información que guardar.
    if (isset($_POST['p-Account'])) {
        $value = sanitize_text_field($_POST['p-Account']);
        update_option('rm_paypal_checkout_p_Account', $value);
    }

    // Nos aseguramos de que hay información que guardar.
    if (isset($_POST['p-clientId'])) {
        $value = sanitize_text_field($_POST['p-clientId']);
        update_option('rm_paypal_checkout_p_clientId', $value);
    }

    // Nos aseguramos de que hay información que guardar.
    if (isset($_POST['p-secret'])) {
        $value = sanitize_text_field($_POST['p-secret']);
        update_option('rm_paypal_checkout_p_secret', $value);
    }
    
    // -> Guardado de las credenciales basicas de producción

    // Guardado de las credenciales avanzadas de producción
    
    // Nos aseguramos de que hay información que guardar.
    if (isset($_POST['p-username'])) {
        $value = sanitize_text_field($_POST['p-username']);
        update_option('rm_paypal_checkout_p_username', $value);
    }

    // Nos aseguramos de que hay información que guardar.
    if (isset($_POST['p-password'])) {
        $value = sanitize_text_field($_POST['p-password']);
        update_option('rm_paypal_checkout_p_password', $value);
    }

    // Nos aseguramos de que hay información que guardar.
    if (isset($_POST['p-signature'])) {
        $value = sanitize_text_field($_POST['p-signature']);
        update_option('rm_paypal_checkout_p_signature', $value);
    }
    
    // -> Guardado de las credenciales avanzadas de producción


    // Obtenemos la url de retorno sanitizada
    $url = sanitize_text_field(
        wp_unslash($_POST['_wp_http_referer'])
    );

    $url = add_query_arg('success', 'true', $url);
 
    // Y reedirigimos a la página para guardar la información
    wp_safe_redirect(urldecode($url));
    exit;

}

// Obtiene el tipo de integración con el que el usuario está trabajando
function get_integration_type() {
    $basic_integration = get_option("rm_paypal_checkout_basic_integration", "checked");
    return $basic_integration == "checked" ? "basic" : "advanced";
}

// Crea el shortcode para insertar el formulario de cobro
function rm_add_paypal_form($attrs) {

    add_plugin_assets();

    if (get_integration_type() == "advanced") {
        $form_attrs = $attrs["attrs"] ?? "class='col-12 col-sm-6 mb-4'";
        require("includes/payment-form.php");
    }
    else {
        require("includes/warning-using-basic.php");
    }

}

// Crea el shortcode para insertar el carrito de compras
function rm_add_paypal_cart($attrs) {

    add_plugin_assets();

    if (get_integration_type() == "advanced") {
        $cart_attrs = $attrs["attrs"] ?? "class='col-12 col-sm-6 mb-4'";
        require("includes/cart.php");
    }
    else {
        require("includes/warning-using-basic.php");
    }

}

// Crea el shortcode para insertar el carrito de compras junto al formulario de cobro
function rm_add_paypal_shipment($attrs) {

    add_plugin_assets();

    if (get_integration_type() == "advanced") {
        $ship_attrs = $attrs["attrs"] ?? "class='col-12 col-sm-6 mb-4'";
        require("includes/shipment.php");
    }
    else {
        require("includes/warning-using-basic.php");
    }

}

// Crea el shortcode para insertar el carrito de compras junto al formulario de cobro
function rm_add_paypal_all($attrs) {

    add_plugin_assets();

    if (get_integration_type() == "advanced") {

        $cart_attrs = $attrs["cart_attrs"] ?? "class='col-12 col-sm-6 mb-4'";
        $form_attrs = $attrs["form_attrs"] ?? "class='col-12 col-sm-6 mb-4'";
        $ship_attrs = $attrs["ship_attrs"] ?? "class='col-12 col-sm-6 mb-4'";
        require("includes/all.php");
    }
    else {
        require("includes/warning-using-basic.php");
    }

}

// Crea el shortcode para renderizar los botones de PayPal
function rm_add_paypal_render_buttons($attrs) {

    add_plugin_assets();
    
    if (get_integration_type() == "basic") {

        $render_attrs = $attrs["attrs"] ?? "class='col-12 col-sm-6 mb-4'";
        require("includes/render-buttons.php");

    }
    else {
        require("includes/warning-using-advanced.php");
    }

}

// Añade los endpoints para procesamientos con Ajax
require("functions/ajax_endpoints.php");

add_action('admin_enqueue_scripts', 'load_assets');
add_action('admin_menu', 'custom_menu');
add_action('admin_post_rm_paypal_checkout_saving', 'save_paypal_keys');
add_filter('script_loader_tag', 'add_attrs_to_scripts', 10, 3);

if(!is_admin()) {

    add_shortcode('rm_paypal_form', 'rm_add_paypal_form');
    add_shortcode('rm_paypal_cart', 'rm_add_paypal_cart');
    add_shortcode('rm_paypal_shipment', 'rm_add_paypal_shipment');
    add_shortcode('rm_paypal_all', 'rm_add_paypal_all');
    add_shortcode('rm_paypal_render_buttons', 'rm_add_paypal_render_buttons');

}



?>