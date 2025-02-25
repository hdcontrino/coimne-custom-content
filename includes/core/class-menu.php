<?php

if (!defined('ABSPATH')) {
    exit;
}

class Coimne_Menu
{
    public static function display_dashboard_menu()
    {
        $coimneAPI = new Coimne_API();
        $user_data = $coimneAPI->get_user_data();
        $template_path = plugin_dir_path(__FILE__) . '../../templates/dashboard-menu.php';
        
        if ($user_data && file_exists($template_path)) {
            $user_type = $user_data['tip'];

            $menu_location = null;
            $menu_avatar_url = Coimne_Helper::asset_url('img/default-avatar.png');
            $saludo = sprintf(__('¡Hola, %s!', 'coimne-custom-content'), $user_data['cttName']);

            if ($user_type === 'A') {
                $menu_location = 'students_dashboard';
            } elseif ($user_type === 'C' || $user_type === 'O') {
                $menu_location = 'collegiate_dashboard';
            }

            include $template_path;
        } else {
            echo '<p>' . __('', 'coimne-custom-content') . '</p>';
        }
    }
}
