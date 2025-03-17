<?php

if (!defined('ABSPATH')) {
    exit;
}

class Coimne_Menu
{
    public static function display_dashboard_menu()
    {
        $api = new Coimne_API();

        $user_type = strtolower($api->userType);
        
        $template = COIMNE_CUSTOM_TEMPLATES_DIR
            . "/dashboard-parts/menu-$user_type.php";

        if (file_exists($template)) {
            $menu_avatar_url = Coimne_Helper::asset_url('img/default-avatar.png');
            $saludo = sprintf(__('¡Hola, %s!', 'coimne-custom-content'), $api->userData['cttName']);

            return include $template;
        }
        
        echo Coimne_Shortcodes::template_not_found();
    }
}
