<?php

if (!defined('ABSPATH')) {
    exit;
}

class Coimne_Menu
{
    public static function display_dashboard_menu()
    {
        $api = new Coimne_API();
        $user_type = strtolower($api->userData['tip']);

        $template_path = COIMNE_CUSTOM_CONTENT_DIR . '/templates';
        $template_path .= "/dashboard-parts/menu-$user_type.php";

        if (!file_exists($template_path)) {
            echo self::menu_not_found();
        }
        
        $menu_avatar_url = Coimne_Helper::asset_url('img/default-avatar.png');
        $saludo = sprintf(__('¡Hola, %s!', 'coimne-custom-content'), $api->userData['cttName']);

        include $template_path;
    }

    private static function menu_not_found()
    {
        return '<p>' . __('Error: No se encontró el menú.', 'coimne-custom-content') . '</p>';
    }
}
