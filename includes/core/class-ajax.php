<?php

if (!defined('ABSPATH')) {
    exit;
}

class Coimne_Ajax
{
    public function __construct()
    {
        add_action('wp_ajax_coimne_login_form', [$this, 'handle_login']);
        add_action('wp_ajax_nopriv_coimne_login_form', [$this, 'handle_login']);

        add_action('wp_ajax_coimne_logout', [$this, 'handle_logout']);
        add_action('wp_ajax_nopriv_coimne_logout', [$this, 'handle_logout']);

        add_action('wp_ajax_coimne_forgot_password', [$this, 'forgot_password']);
        add_action('wp_ajax_nopriv_coimne_forgot_password', [$this, 'forgot_password']);

        add_action('wp_ajax_coimne_get_dynamic_content', [$this, 'get_dynamic_content']);
        add_action('wp_ajax_nopriv_coimne_get_dynamic_content', [$this, 'get_dynamic_content']);

        add_action('wp_ajax_coimne_set_user_profile', [$this, 'set_user_profile']);
        add_action('wp_ajax_nopriv_coimne_set_user_profile', [$this, 'set_user_profile']);

        add_action('wp_ajax_coimne_set_user_account', [$this, 'set_user_account']);
        add_action('wp_ajax_nopriv_coimne_set_user_account', [$this, 'set_user_account']);

        add_action('wp_ajax_coimne_get_provinces', [$this, 'get_provinces']);
        add_action('wp_ajax_nopriv_coimne_get_provinces', [$this, 'get_provinces']);

        add_action('wp_ajax_coimne_get_towns', [$this, 'get_towns']);
        add_action('wp_ajax_nopriv_coimne_get_towns', [$this, 'get_towns']);

        add_action('wp_ajax_coimne_search_companies', [$this, 'search_companies']);
        add_action('wp_ajax_nopriv_coimne_search_companies', [$this, 'search_companies']);

        add_action('wp_ajax_coimne_submit_enrollment', [$this, 'submit_enrollment']);
        add_action('wp_ajax_nopriv_coimne_submit_enrollment', [$this, 'submit_enrollment']);

        add_action('wp_ajax_coimne_get_user_courses', [$this, 'get_user_courses']);
        add_action('wp_ajax_nopriv_coimne_get_user_courses', [$this, 'get_user_courses']);
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

    public function forgot_password()
    {
        $api = new Coimne_API();
        $response = $api->forgot_password($_POST);

        wp_send_json($response);
        wp_die();
    }
    
    public function get_dynamic_content()
    {
        if (!isset($_GET['content'])) {
            wp_send_json_error(['message' => __('Solicitud inválida.', 'coimne-custom-content')]);
        }

        ob_start();

        $content_type = sanitize_text_field($_GET['content']);
        $method = "display_dashboard_{$content_type}";

        if (method_exists('Coimne_Dashboard', $method)) {
            Coimne_Dashboard::$method();
        } else {
            wp_send_json_error(['message' => __('Método no encontrado.', 'coimne-custom-content')]);
        }

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

    public function set_user_account()
    {
        $api = new Coimne_API();
        $response = $api->set_user_account($_POST);

        wp_send_json($response);
        wp_die();
    }

    public function get_provinces()
    {
        if (!isset($_GET['country'])) {
            wp_send_json_error(['message' => __('Falta el parámetro de país.', 'coimne-custom-content')]);
            wp_die();
        }

        $api = new Coimne_API();
        $country = sanitize_text_field($_GET['country']);
        $response = $api->get_provinces($country);

        if (!$response || !is_array($response)) {
            wp_send_json_error([
                'message' => isset($response['message']) ? $response['message'] : __('Error al obtener provincias.', 'coimne-custom-content')
            ]);
            wp_die();
        }

        wp_send_json_success([$response['data']]);
        wp_die();
    }

    public function get_towns()
    {
        if (!isset($_GET['country']) || !isset($_GET['province'])) {
            wp_send_json_error(['message' => __('Faltan parámetros.', 'coimne-custom-content')]);
            wp_die();
        }

        $api = new Coimne_API();
        $country = sanitize_text_field($_GET['country']);
        $province = sanitize_text_field($_GET['province']);
        $response = $api->get_towns($country, $province);

        if (!$response || !is_array($response)) {
            wp_send_json_error([
                'message' => isset($response['message']) ? $response['message'] : __('Error al obtener población.', 'coimne-custom-content')
            ]);
            wp_die();
        }

        wp_send_json_success([$response['data']]);
        wp_die();
    }

    public function search_companies()
    {
        if (!isset($_GET['search'])) {
            wp_send_json_error(['message' => 'Falta el término de búsqueda']);
            wp_die();
        }

        $api = new Coimne_API();
        $search = sanitize_text_field($_GET['search']);
        $response = $api->get_empresas($search);

        if (!$response || !is_array($response)) {
            wp_send_json_error([
                'message' => isset($response['message']) ? $response['message'] : __('Error al obtener empresa.', 'coimne-custom-content')
            ]);
            wp_die();
        }

        wp_send_json_success([$response['data']]);
        wp_die();
    }

    public function submit_enrollment()
    {
        $api = new Coimne_API();
        $response = $api->submit_enrollment($_POST);

        wp_send_json($response);
        wp_die();
    }

    public function get_user_courses()
    {
        $api = new Coimne_API();
        $response = $api->get_user_courses($_POST);

        wp_send_json($response);
        wp_die();
    }
}

new Coimne_Ajax();
