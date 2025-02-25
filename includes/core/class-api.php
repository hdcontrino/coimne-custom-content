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

        $response = $this->post(
            $this->api_url . '/login',
            [ 'Content-Type' => 'application/x-www-form-urlencoded'],
            [ 'USER' => $username, 'PASS' => $password ]
        );

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

    public function check_session_status($response)
    {
        if (!is_array($response) || !isset($response['status']) || $response['status'] !== false) {
            return;
        }

        if (isset($response['desc']['error']) && $response['desc']['error'] === 'Token invalido') {
            $this->session->clear_session();

            return $this->homeRedirect();
        }
    }

    public function get_user_data()
    {
        $userData = $this->session->get_session('coimne_user_data');
        if (!$userData) {
            return $this->homeRedirect();
        }

        return $userData;
    }

    public function get_user_profile()
    {
        $userData = $this->get_user_data();
        if (!$userData) return null;

        $user_id = $userData['cttId'];
        $user_type = $userData['tip'];
        
        $api_url = get_option('coimne_api_url', '');
        $endpoint = "/Perfil?CTT_ID=$user_id&TIP=$user_type";

        $response = $this->get(
            rtrim($api_url, '/') . $endpoint,
            [
                'Authorization' => 'Bearer ' . $userData['token'],
                'Accept' => 'application/json'
            ]
        );

        if (is_wp_error($response)) {
            return null;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        $this->check_session_status($data);

        if (!$data || empty($data['status']) || empty($data['desc']['list'][0])) {
            return null;
        }

        return $data['desc']['list'][0];
    }

    public function set_user_profile($data)
    {
        $userData = $this->get_user_data();
        if (!$userData) {
            return ['success' => false, 'message' => __('No estás autenticado.', 'coimne-custom-content')];
        }

        $token = $userData['token'];
        if (!$token) {
            return ['success' => false, 'message' => __('Token no encontrado.', 'coimne-custom-content')];
        }

        // Endpoint a confirmar
        $api_url = get_option('coimne_api_url', '');
        $endpoint = "/Perfil/Actualizar"; // Ajustar cuando se confirme el endpoint correcto

        $response = $this->post(
            rtrim($api_url, '/') . $endpoint,
            [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type'  => 'application/json'
            ],
            $data
        );

        if (is_wp_error($response)) {
            return ['success' => false, 'message' => __('Error de conexión con la API.', 'coimne-custom-content')];
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);

        if (!$body || empty($body['status'])) {
            return ['success' => false, 'message' => __('Respuesta inválida del servidor.', 'coimne-custom-content')];
        }

        if ($body['status'] === false) {
            return ['success' => false, 'message' => $body['desc']['error'] ?? __('Error desconocido.', 'coimne-custom-content')];
        }

        // Acá habría que actualizar sesión con los nuevos datos del usuario
        //
        //

        return ['success' => true, 'message' => __('Perfil actualizado correctamente.', 'coimne-custom-content')];
    }

    private function get($url, $headers = [], $body = [])
    {
        return wp_remote_get($url, [
            'headers' => $headers,
            'sslverify' => $this->ssl_verify,
        ]);
    }

    private function post($url, $headers = [], $body = [])
    {
        return wp_remote_post($url, [
            'body' => http_build_query($body),
            'headers' => $headers,
            'timeout' => 15,
            'sslverify' => $this->ssl_verify,
        ]);
    }

    private function homeRedirect()
    {
        if (!headers_sent()) {
            wp_safe_redirect(home_url());
            exit;
        } else {
            echo '<script>window.location.href="' . esc_url(home_url()) . '";</script>';
            exit;
        }
    }
}
