<?php

if (!defined('ABSPATH')) {
    exit;
}

class Coimne_Dashboard
{
    public static function display_coimne_dashboard()
    {
        $template_path = plugin_dir_path(__FILE__) . '../../templates/dashboard.php';

        if (file_exists($template_path)) {
            include $template_path;
        } else {
            echo '<p>' . __('Error: No se encontró la plantilla de dashboard.', 'coimne-custom-content') . '</p>';
        }
    }
}
