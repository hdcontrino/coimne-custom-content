<?php

if (!defined('ABSPATH')) {
    exit;
}

class Coimne_Ajax
{
    public function __construct()
    {
        add_action('wp_ajax_coimne_login', [$this, 'handle_login']);
        add_action('wp_ajax_nopriv_coimne_login', [$this, 'handle_login']);
    }

    public function handle_login()
    {
        if (empty($_POST['username']) || empty($_POST['password'])) {
            wp_send_json([
                'success' => false,
                'message' => __('Faltan datos: usuario y contraseña son obligatorios.', 'coimne-custom-content')
            ]);
            wp_die();
        }

        $api = new Coimne_API();

        // Validar reCAPTCHA
        $recaptcha_response = sanitize_text_field($_POST['g-recaptcha-response']);
        $recaptcha_validation = $api->verify_recaptcha($recaptcha_response);

        if (!$recaptcha_validation['success']) {
            wp_send_json([
                'success' => false,
                'message' => $recaptcha_validation['message']
            ]);
            wp_die();
        }

        $username = sanitize_text_field($_POST['username']);
        $password = sanitize_text_field($_POST['password']);
        $response = $api->authenticate_user($username, $password);

        if (!isset($response['success']) || !$response['success']) {
            $error_message = isset($response['message']) ? $response['message'] : __('Error desconocido.', 'coimne-custom-content');

            wp_send_json([
                'success' => false,
                'message' => $error_message
            ]);
            wp_die();
        }

        // Si la autenticación fue exitosa, guardamos el token y los datos en la sesión
        if (isset($response['token']) && isset($response['user'])) {
            $session = new Coimne_Session();
            $session->set_session('coimne_token', $response['token']);
            $session->set_session('coimne_user_data', $response['user']);

            wp_send_json([
                'success' => true,
                'message' => __('Autenticación exitosa.', 'coimne-custom-content'),
                'user' => $response['user']
            ]);
        } else {
            wp_send_json([
                'success' => false,
                'message' => __('Error en la autenticación: datos de usuario no recibidos.', 'coimne-custom-content')
            ]);
        }

        wp_die();
    }
}

new Coimne_Ajax();
