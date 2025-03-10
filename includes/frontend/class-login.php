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

        $template = COIMNE_CUSTOM_TEMPLATES_DIR
            . "/login-button.php";

        if (file_exists($template)) {
            return include $template;
        }

        echo Coimne_Shortcodes::template_not_found();
    }
    public static function display_login_form()
    {

        $template = COIMNE_CUSTOM_TEMPLATES_DIR
            . "/login-form.php";

        if (file_exists($template)) {
            return include $template;
        }

        echo Coimne_Shortcodes::template_not_found();
    }
}