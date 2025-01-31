<?php

if (!defined('ABSPATH')) {
    exit;
}

class Coimne_API
{
    private $api_url;
    private $session;
    private $ssl_verify;

    public function __construct()
    {
        $this->api_url = get_option('coimne_api_url', '');
        $this->session = new Coimne_Session();
        $this->ssl_verify = get_option('coimne_dev_mode', false) ? false : true;
    }

    public function verify_recaptcha($recaptcha_response)
    {
        $recaptcha_secret = get_option('coimne_recaptcha_secret_key', '');
        if (empty($recaptcha_secret)) {
            return ['success' => false, 'message' => __('La clave secreta de reCAPTCHA no está configurada.', 'coimne-custom-content')];
        }

        $response = wp_remote_post("https://www.google.com/recaptcha/api/siteverify", [
            'body' => [
                'secret' => $recaptcha_secret,
                'response' => $recaptcha_response,
                'remoteip' => $_SERVER['REMOTE_ADDR']
            ]
        ]);

        if (is_wp_error($response)) {
            return ['success' => false, 'message' => __('Error de conexión con Google reCAPTCHA.', 'coimne-custom-content')];
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);
        if (!$body['success']) {
            return ['success' => false, 'message' => __('La validación de reCAPTCHA ha fallado.', 'coimne-custom-content')];
        }

        return ['success' => true];
    }

    public function authenticate_user($username, $password)
    {
        if (empty($this->api_url)) {
            return ['success' => false, 'message' => __('La URL de la API no está configurada.', 'coimne-custom-content')];
        }

        $response = wp_remote_post($this->api_url . '/login', [
            'body' => http_build_query([
                'USER' => $username,
                'PASS' => $password
            ]),
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            'timeout' => 15,
            'sslverify' => $this->ssl_verify,
        ]);

        if (is_wp_error($response)) {
            return ['success' => false, 'message' => __('Error de conexión con la API.', 'coimne-custom-content')];
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (!isset($data['status'])) {
            return ['success' => false, 'message' => __('Respuesta inválida del servidor.', 'coimne-custom-content')];
        }

        if ($data['status'] === false) {
            return [
                'success' => false,
                'message' => isset($data['desc']['error']) ? $data['desc']['error'] : __('Error desconocido.', 'coimne-custom-content')
            ];
        }

        if (!isset($data['desc']['token'])) {
            return ['success' => false, 'message' => __('No se recibió un token válido.', 'coimne-custom-content')];
        }

        $this->session->set_session('coimne_user_data', $data['desc']);

        return [
            'success' => true,
            'token' => $data['desc']['token'],
            'user' => $data['desc']
        ];
    }

    public function get_user_data()
    {
        return $this->session->get_session('coimne_user_data');
    }
}
