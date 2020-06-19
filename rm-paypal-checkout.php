<?php

/*
Plugin Name: PayPal Form And Buttons Checkout For WooCommerce
Description: Plugin para hacer un checkout con PayPal mediante un formulario de cobros en tu web o usando los botones de PayPal (Requiere Bootstrap y FontAwesome), puedes incluir el carrito de compras con el shortcode [rm_paypal_cart attrs='tus atributos para el contenedor'], puedes incluir el formulario de cobro con el shortcode [rm_paypal_form attrs='tus atributos para el contenedor'] o puedes incluir ambos con el shortcode [rm_paypal_all cart_attrs='tus atributos para el contenedor del carrito' form_attrs='tus atributos para el contenedor del formulario de cobro']
Version: 1.0
Author: RetaxMaster
License: GPLv2
*/

require("classes/Options.php");
require("classes/PayPal.php");
require("traits/IntegrationTypes.php");
// Añade los endpoints para procesamientos con Ajax
require("functions/ajax_endpoints.php");

use Classes\PayPal;
use Classes\Options;
use Traits\IntegrationTypes;

class Rm_PayPal_Checkout {
    
    // Determinará si la función ya fue llamada para no duplicar los enqueue en caso de haber dos shortcodes que la llamen
    private static $calledPluginScripts = false;
    private static $setted_up = false;
    private static $options;

    use IntegrationTypes;

    public static function setup_plugin() : void {

        if (!self::$setted_up) {
            self::set_options();
            self::add_actions();
            self::$setted_up = true;
        }
        
    }

    private static function add_actions() : void {

        add_action('admin_init', array(self::class, "chek_for_wordpress"));
        add_action('admin_enqueue_scripts', array(self::class, 'load_assets'));
        add_action('admin_menu', array(self::class, 'custom_menu'));
        add_action('admin_post_rm_paypal_checkout_saving', array(self::class, 'save_paypal_keys'));
        //add_filter('script_loader_tag', array(self::class, 'add_attrs_to_scripts'), 10, 3);

        if(!is_admin()) {

            //add_shortcode('rm_paypal_shipment', array(self::class, 'rm_add_paypal_shipment'));
            add_shortcode('rm_paypal_form', array(self::class, 'rm_add_paypal_form'));
            add_shortcode('rm_paypal_cart', array(self::class, 'rm_add_paypal_cart'));
            add_shortcode('rm_paypal_all', array(self::class, 'rm_add_paypal_all'));
            add_shortcode('rm_paypal_render_buttons', array(self::class, 'rm_add_paypal_render_buttons'));

        }

    }

    // El plugin requiere de WooCommerce para funcionar
    public static function chek_for_wordpress() {
    
        if (!is_plugin_active("woocommerce/woocommerce.php")) {
    
            add_action('admin_notices', function() {
                echo '<div class="notice notice-warning is-dismissible">
                        <p>WooCommerce no está activado, PayPal Form And Buttons Checkout For WooCommerce necesita WooCommerce para funcionar.
                        <br>
                        <a href="' . admin_url('plugins.php') . '">&laquo; Por favor activa WooCommerce</a>
                        </p>
                    </div>';
            });
    
            deactivate_plugins(plugin_basename( __FILE__ )); 
    
            if (isset($_GET['activate']))
                unset($_GET['activate']);
    
        }
    
    }
    
    // Seaparador del menú
    private static function add_admin_menu_separator($position) {
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
    public static function custom_menu() : void {
    
        add_menu_page('PayPal Checkout', 'PayPal Checkout', 'edit_posts', 'rm_paypal_checkout', function() {
            require("controllers/admin-menu.php");
            require("includes/admin-menu.php");
        }, 'dashicons-media-spreadsheet', 59);
        
        self::add_admin_menu_separator(60);
    
    }
    
    // Obtiene los datos que el usuario seleccionó
    private static function set_options() : void {
        self::$options = new Options;

        // Establecemos el ambiente de PayPal justo después de establecer las opciones ya que la clase de PayPal igual requiere las opciones del usuario
        PayPal::setupEnvironment(self::$options);
    }
    
    // Añade los estilos y scripts para el admin
    public static function load_assets($hook) : void {
    
        // Load only on ?page=mypluginname
        if($hook != 'toplevel_page_rm_paypal_checkout') return;
        
        wp_enqueue_style('bootstrap', "https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css", [], "4.5.0");
        wp_enqueue_style('rm_styles', plugins_url('assets/css/style.css', __FILE__), ["bootstrap"], "1.0");
    
        wp_enqueue_script('popper', "https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js", ['jquery'], "1.16.0", true);
        wp_enqueue_script('bootstrap', "https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js", ['popper'], "4.5.0", true);
    
        wp_enqueue_script('scripts',  plugins_url('assets/js/scripts.js', __FILE__), ['jquery'], "1.0", true);
        wp_enqueue_script('admin',  plugins_url('assets/js/admin.js', __FILE__), ['scripts'], "1.0", true);
    
        // Paso las variables de cuáles opciones están habilitadas
        wp_localize_script('admin', 'admin', get_object_vars(self::$options));
    
    }
    
    // Añade los estilos para el front TODO: Revisar
    private static function add_plugin_styles() : void {
    
        wp_enqueue_style('rm_paypal_checkout_styles', plugins_url('assets/css/advanced.css', __FILE__), [], "1.0");
    
    }

    // Obtiene la URL del SDK de PayPal con los parámetros que deberán ser cargados según la configuración del usuario
    private static function get_paypal_sdk_url() : string {

        $client_id = PayPal::getClientId();
        $options = self::$options;
        
        $url_base = "https://www.paypal.com/sdk/js?";
        $url = $url_base;

        $query_params = ["client-id" => $client_id];

        if($options->isPayPalCheckout)
            $query_params["components"] = "buttons,funding-eligibility";

        $url .= http_build_query($query_params);
        $url = preg_replace("/%2F/", "/", $url);
        $url = preg_replace("/%2C/", ",", $url);

        return $url;

    }
    
    // Añade los scripts para el front (Igual es la función que se lanza cuando un shortcode es llamado, lo que significa que cualquier código que se requiera antes de cargar la página irá aquí)
    private static function add_plugin_scripts() : void {

        $paypal_url = self::get_paypal_sdk_url();
    
        wp_enqueue_script('paypal_sdk', $paypal_url, [], null, false);
    
        // Agrego los scripts base
        wp_enqueue_script('sweetalert2', "https://cdn.jsdelivr.net/npm/sweetalert2@9", [], "1.1", true);
        wp_enqueue_script('promise-polyfill', "https://cdn.jsdelivr.net/npm/promise-polyfill", [], "1.1", true);
        wp_enqueue_script('rm_paypal_checkout_scripts',  plugins_url('assets/js/scripts.js', __FILE__), ['jquery', 'paypal_sdk'], "1.0", true);

        self::execute_integrations_types();
    
    }

    // Verifico los tipos de integración que eligió el usuario hago los request payments a PayPal correspondientes para luego cargar sus scripts
    private static function execute_integrations_types() {

        $options = self::$options;

        if($options->isPayPalCheckout)
            self::PayPalCheckout();

        if($options->isPayPalPlusMexicoBrazil)
            self::PayPalPlusMexicoBrazil();

        if($options->isPaymentExperience)
            self::PaymentExperience();
        
    }
    
    // Intercepta scripts para modificar sus tributos TODO: Revisar
    public static function add_attrs_to_scripts($tag, $handle, $src) : string {
    
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
    private static function add_plugin_assets() : void {
    
        if (!self::$calledPluginScripts) {
            self::$calledPluginScripts = true;
            self::add_plugin_styles();
            self::add_plugin_scripts();
        }
    
    }
    
    // Guarda los datos enviados del menú
    public static function save_paypal_keys() : void {
    
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
        
        if (isset($_POST['PayPalCheckout']))
            update_option('rm_paypal_checkout_paypal_checkout', "checked");
        else
            update_option('rm_paypal_checkout_paypal_checkout', "");
        
        if (isset($_POST['PayPalPlusMexicoBrazil']))
            update_option('rm_paypal_checkout_paypal_plus_mexico_brazil', "checked");
        else
            update_option('rm_paypal_checkout_paypal_plus_mexico_brazil', "");

        if (isset($_POST['PaymentExperience']))
            update_option('rm_paypal_checkout_payment_experience', "checked");
        else
            update_option('rm_paypal_checkout_payment_experience', "");
    
        if (isset($_POST['InProduction']))
            update_option('rm_paypal_checkout_production_mode', "checked");
        else
            update_option('rm_paypal_checkout_production_mode', "");
        
        // -> Guardado del tipo de integración
    
        // Guardado de las credenciales de prueba
        
        if (isset($_POST['clientId'])) {
            $value = sanitize_text_field($_POST['clientId']);
            update_option('rm_paypal_checkout_s_clientId', $value);
        }
    
        if (isset($_POST['secret'])) {
            $value = sanitize_text_field($_POST['secret']);
            update_option('rm_paypal_checkout_s_secret', $value);
        }
        
        // -> Guardado de las credenciales de prueba
    
        // Guardado de las credenciales de producción
    
        if (isset($_POST['p_clientId'])) {
            $value = sanitize_text_field($_POST['p_clientId']);
            update_option('rm_paypal_checkout_p_clientId', $value);
        }
    
        if (isset($_POST['p_secret'])) {
            $value = sanitize_text_field($_POST['p_secret']);
            update_option('rm_paypal_checkout_p_secret', $value);
        }
        
        // -> Guardado de las credenciales de producción
    
        // Obtenemos la url de retorno sanitizada
        $url = sanitize_text_field(
            wp_unslash($_POST['_wp_http_referer'])
        );
    
        $url = add_query_arg('success', 'true', $url);
     
        // Y reedirigimos a la página para guardar la información
        wp_safe_redirect(urldecode($url));
        exit;
    
    }
    
    // Retorna si alguno de los tipos de integración están habilitados
    private static function is_integration_enabled(array $integrations) : bool {

        $enabled = false;

        foreach ($integrations as $integration)
            if(self::$options->$integration == "checked") $enabled = true;

        return $enabled;

    }
    
    // Crea el shortcode para insertar el formulario de cobro
    public static function rm_add_paypal_form($attrs) : void {
    
        self::add_plugin_assets();
    
        if (self::is_integration_enabled(["paypal_plus_mexico_brazil"])) {
            $form_attrs = $attrs["attrs"] ?? "class='col-12 col-sm-6 mb-4'";
            require("includes/payment-form.php");
        }
        else {
            require("includes/warning-bad-integration.php");
        }
    
    }
    
    // Crea el shortcode para insertar el carrito de compras
    public static function rm_add_paypal_cart($attrs) : void {
    
        self::add_plugin_assets();
        $cart_attrs = $attrs["attrs"] ?? "class='col-12 col-sm-6 mb-4'";
        require("includes/cart.php");
    
    }
    
    // Crea el shortcode para insertar el carrito de compras junto al formulario de cobro
    /* public static function rm_add_paypal_shipment($attrs) : void {
    
        self::add_plugin_assets();
    
        if (self::get_integration_type() == "advanced") {
            $ship_attrs = $attrs["attrs"] ?? "class='col-12 col-sm-6 mb-4'";
            require("includes/shipment.php");
        }
        else {
            require("includes/warning-using-basic.php");
        }
    
    } */
    
    // Crea el shortcode para insertar el carrito de compras junto al formulario de cobro
    public static function rm_add_paypal_all($attrs) : void {
    
        self::add_plugin_assets();
        $cart_attrs = $attrs["cart_attrs"] ?? "class='col-12 col-sm-6 mb-4'";
        $form_attrs = $attrs["form_attrs"] ?? "class='col-12 col-sm-6 mb-4'";
        $buttons_attrs = $attrs["button_attrs"] ?? "class='col-12 col-sm-6 mb-4'";
        require("includes/all.php");
    
    }
    
    // Crea el shortcode para renderizar los botones de PayPal
    public static function rm_add_paypal_render_buttons($attrs) : void {
    
        self::add_plugin_assets();
        $buttons_attrs = $attrs["attrs"] ?? "class='col-12 col-sm-6 mb-4'";
        require("includes/render-buttons.php");
    
    }

}

Rm_PayPal_Checkout::setup_plugin();


?>