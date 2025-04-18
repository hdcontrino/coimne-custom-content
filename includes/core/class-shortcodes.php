<?php

if (!defined('ABSPATH')) {
    exit;
}

class Coimne_Shortcodes
{
    public function __construct()
    {
        add_shortcode('coimne_login_btn', [$this, 'login_btn_shortcode']);
        add_shortcode('coimne_login_form', [$this, 'login_form_shortcode']);

        add_shortcode('coimne_dashboard', [$this, 'dashboard_shortcode']);
        add_shortcode('coimne_dashboard_menu', [$this, 'dashboard_menu_shortcode']);
        add_shortcode('coimne_dashboard_profile', [$this, 'dashboard_profile_shortcode']);

        add_shortcode('coimne_enrollment_form', [$this, 'enrollment_form_shortcode']);
    }

    public function login_btn_shortcode()
    {
        ob_start();
        Coimne_Login::display_login_button();
        return ob_get_clean();
    }

    public function login_form_shortcode()
    {
        $recaptcha_site_key = get_option(COIMNE_OPTION_RECAPTCHA_SITE_KEY, '');
        $dashboard_url = get_option(COIMNE_OPTION_DASHBOARD_URL, site_url('/dashboard'));

        wp_enqueue_style('coimne-login-styles', Coimne_Helper::asset_url('css/coimne-login.css'));
        wp_enqueue_script('coimne-login-script', Coimne_Helper::asset_url('js/coimne-login.js'), [], false, true);
        wp_enqueue_script('coimne-recaptcha', 'https://www.google.com/recaptcha/api.js', [], false, true);

        wp_localize_script('coimne-login-script', 'coimneLoginData', [
            'ajaxUrl' => esc_url(admin_url('admin-ajax.php')),
            'recaptchaEnabled' => !empty($recaptcha_site_key),
            'recaptchaSiteKey' => esc_attr($recaptcha_site_key),
            'dashboardUrl' => esc_url($dashboard_url),
            'errorMessage' => __('Error: No se puede iniciar sesión porque el reCAPTCHA no está configurado.', 'coimne-custom-content')
        ]);

        ob_start();
        Coimne_Login::display_login_form();
        return ob_get_clean();
    }

    public function dashboard_shortcode()
    {
        if (Coimne_Helper::in_the_builder()) {
            return Coimne_Helper::hidden_shortcode_notice('Dashboard');
        }

        wp_enqueue_script('jquery');

        if (!wp_script_is('select2', 'registered')) {
            wp_enqueue_style('select2-css', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css');
            wp_enqueue_script('select2-js', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', ['jquery'], null, true);
        }
        
        wp_enqueue_style('coimne-dashboard-styles', Coimne_Helper::asset_url('css/coimne-dashboard.css'));
        wp_enqueue_script('coimne-dashboard-script', Coimne_Helper::asset_url('js/coimne-dashboard.js'), [], false, true);

        wp_localize_script('coimne-dashboard-script', 'coimneDashboardData', [
            'ajaxUrl' => esc_url(admin_url('admin-ajax.php')),
        ]);

        ob_start();
        Coimne_Dashboard::display_coimne_dashboard();
        return ob_get_clean();
    }

    public function dashboard_menu_shortcode()
    {
        if (Coimne_Helper::in_the_builder()) {
            return Coimne_Helper::hidden_shortcode_notice('Dashboard Menu');
        }

        wp_enqueue_style('coimne-menu-styles', Coimne_Helper::asset_url('css/coimne-menu.css'));
        wp_enqueue_script('coimne-menu-script', Coimne_Helper::asset_url('js/coimne-menu.js'), [], false, true);

        wp_localize_script('coimne-menu-script', 'coimneMenuData', [
            'ajaxUrl' => esc_url(admin_url('admin-ajax.php')),
        ]);

        ob_start();
        Coimne_Menu::display_dashboard_menu();
        return ob_get_clean();
    }

    public function dashboard_profile_shortcode()
    {
        if (Coimne_Helper::in_the_builder()) {
            return Coimne_Helper::hidden_shortcode_notice('Dashboard Profile');
        }
        
        ob_start();
        Coimne_Dashboard::display_dashboard_profile();
        return ob_get_clean();
    }

    public function enrollment_form_shortcode()
    {
        wp_enqueue_style('coimne-enroll-styles', Coimne_Helper::asset_url('css/coimne-enrollment.css'));
        wp_enqueue_script('coimne-enroll-script', Coimne_Helper::asset_url('js/coimne-enrollment.js'), [], false, true);

        wp_localize_script('coimne-enroll-script', 'coimneEnrollmentData', [
            'ajaxUrl' => esc_url(admin_url('admin-ajax.php')),
        ]);

        ob_start();
        Coimne_Enrollment::display_coimne_enrollment();
        return ob_get_clean();
    }

    public static function template_not_found()
    {
        return '<p>' . __('Error: No se encontró la plantilla del formulario.', 'coimne-custom-content') . '</p>';
    }
}
