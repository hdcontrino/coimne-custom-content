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

        add_action('wp_ajax_coimne_logout', [$this, 'handle_logout']);
        add_action('wp_ajax_nopriv_coimne_logout', [$this, 'handle_logout']);

        add_action('wp_ajax_coimne_get_dynamic_content', [$this, 'get_dynamic_content']);
        add_action('wp_ajax_nopriv_coimne_get_dynamic_content', [$this, 'get_dynamic_content']);

        add_action('wp_ajax_coimne_set_user_profile', [$this, 'set_user_profile']);
        add_action('wp_ajax_nopriv_coimne_set_user_profile', [$this, 'set_user_profile']);
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

    public function handle_logout()
    {
        $session = new Coimne_Session();
        $session->clear_session();

        $redirect_url = home_url();

        wp_send_json([
            'success' => true,
            'message' => __('Sesión cerrada correctamente.', 'coimne-custom-content'),
            'redirect' => esc_url($redirect_url)
        ]);

        wp_die();
    }
    
    public function get_dynamic_content()
    {
        if (!isset($_GET['content'])) {
            wp_send_json_error(['message' => __('Solicitud inválida.', 'coimne-custom-content')]);
        }

        $content_type = sanitize_text_field($_GET['content']);
        $template_path = COIMNE_CUSTOM_CONTENT_DIR . "templates/dashboard-{$content_type}.php";

        if (!file_exists($template_path)) {
            wp_send_json_error(['message' => __('El contenido solicitado no existe.', 'coimne-custom-content')]);
        }

        ob_start();
        include $template_path;
        $content = ob_get_clean();

        wp_send_json_success(['content' => $content]);
    }

    public function set_user_profile()
    {
        $api = new Coimne_API();
        $response = $api->set_user_profile($_POST);

        wp_send_json($response);
        wp_die();
    }
}

new Coimne_Ajax();
