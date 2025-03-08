<?php

if (!defined('ABSPATH')) {
    exit;
}

class Coimne_Login
{
    public static function display_login_button()
    {
        $api = new Coimne_API();
        $user = $api->userData;

        $template_path = COIMNE_CUSTOM_CONTENT_DIR . '/templates';
        $template_path .= "/login-button.php";

        if (!file_exists($template_path)) {
            echo self::menu_not_found();
        }

        include $template_path;
    }
    public static function display_login_form()
    {

        $template_path = COIMNE_CUSTOM_CONTENT_DIR . '/templates';
        $template_path .= "/login-form.php";

        if (!file_exists($template_path)) {
            echo self::menu_not_found();
        }

        include $template_path;
    }

    private static function menu_not_found()
    {
        return '<p>' . __('Error: No se encontró el elemento.', 'coimne-custom-content') . '</p>';
    }
}