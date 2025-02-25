<?php

if (!defined('ABSPATH')) {
    exit;
}

class Coimne_User
{
    public static function get_user_data()
    {
        $coimneAPI = new Coimne_API();
        $user_data = $coimneAPI->get_user_data();
        $user_profile = $coimneAPI->get_user_profile();

        if (!$user_data) {
            return '<p>' . __('No hay usuario logueado.', 'coimne-custom-content') . '</p>';
        }

        $template_path = null;

        if ($user_data['tip'] === 'A') {
            $template_path = COIMNE_CUSTOM_CONTENT_DIR . 'templates/user-data-student.php';
        } elseif ($user_data['tip'] === 'C' || $user_data['tip'] === 'O') {
            $template_path = COIMNE_CUSTOM_CONTENT_DIR . 'templates/user-data-collegiate.php';
        }

        if ($template_path && file_exists($template_path)) {
            include $template_path;
        } else {
            echo '<p>' . __('Error: No se encontró la plantilla de usuario.', 'coimne-custom-content') . '</p>';
        }
    }
}
