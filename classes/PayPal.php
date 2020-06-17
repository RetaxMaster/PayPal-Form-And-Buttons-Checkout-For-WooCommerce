<?php

class PayPal {

    private static $APIDomain;
    private static $endpoints;
    private static $accessToken;

    public function __construct() {

        // Hago la autenticación para el ambiente en el que estoy trabajando
        self::setupEnvironment();

        // Manda a generar el token para el comprador
        $this->generateClientToken();

    }

    // Manda una cURL con los parámetros especificados
    private static function sendCurl(string $url, string $method, $params = [], string $responseType = "text", $extra = array()) {
        $ch = curl_init();
    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if (isset($extra["auth"])) {
            $auth = $extra["auth"];
            curl_setopt($ch, CURLOPT_USERPWD, $auth["user"] . ":" . $auth["password"]);
        }
        
        if(mb_strtoupper($method) != "GET"){

            $headers = isset($extra["headers"]) && is_array($extra["headers"]) ? $extra["headers"] :  array("Content-Type: application/json");

            $params = in_array("Content-Type: application/json", $headers) ? json_encode($params) : $params;
            
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        else {
            if(!empty($params)) $url .= "?".http_build_query($params);
            $url = preg_replace("/%2F/", "/", $url);
            $url = preg_replace("/%2C/", ",", $url);
        }
        
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return 'Error:' . curl_error($ch);
        }
    
        curl_close($ch);
        return ($responseType == "array") ? json_decode($result, true) : $result;
    }

    // Verifica si el usuario está usando el entorno de pruebas o de producción
    public static function isInProduction() : bool {
        $production_mode = get_option("rm_paypal_checkout_production_mode", "");
        return $production_mode == "checked";
    }

    // Establece la URL a la cual se enviarán las solicitudes a la API de PayPal
    private static function setAPIDomain() :void {
        self::$APIDomain = self::isInProduction() ? "https://api.paypal.com" : "https://api.sandbox.paypal.com";
    }

    // Establece los diferentes endpoints de PayPal a los cuales mandarles cURL
    private static function setPayPalEndpoints() : void {

        $base = self::$APIDomain;

        self::$endpoints = array(
            "get_access_token" => $base . "/v1/oauth2/token"
        );

    }

    // Obtiene las credenciasles de PayPal según el entorno
    private static function getPayPalCredentials() : array {
        
        if (self::isInProduction()) {
            $client_id = get_option("rm_paypal_checkout_p_clientId", "");
            $client_secret = get_option("rm_paypal_checkout_p_secret", "");
        }
        else {
            $client_id = get_option("rm_paypal_checkout_s_clientId", "");
            $client_secret = get_option("rm_paypal_checkout_s_secret", "");
        }

        return array(
            "client_id" => $client_id,
            "client_secret" => $client_secret
        );

    }

    // Establece las API Keys de PayPal
    private static function setAPIKeys() : void {

        $paypal_credentials = self::getPayPalCredentials();
        $clientId = $paypal_credentials["client_id"];
        $clientSecret = $paypal_credentials["client_secret"];

        $data = "grant_type=client_credentials";
        $extra = array(
            "auth" => array(
                "user" => $clientId,
                "password" => $clientSecret
            ),
            "headers" => array("Accept-Language: es_MX", "Accept: application/json")
        );

        $response = self::sendCurl(self::$endpoints["get_access_token"], "post", $data, "array", $extra);
        if (!is_array($response) || !isset($response["access_token"])) throw new Exception("Credenciales incorrectas", 1);
        self::$accessToken = $response["access_token"];

    }

    public static function setupEnvironment() : void {
        // Establece la URL base para mandar las solicitudes
        self::setAPIDomain();

        // Establce los diferentes endoints de PayPal a los cuales se les mandará la solicitud
        self::setPayPalEndpoints();

        // Establecimiento de API Keys
        self::setAPIKeys();
    }

    // Genera el client token para el comprado
    public function generateClientToken() {
        return self::$accessToken;
    }


    
}


?>