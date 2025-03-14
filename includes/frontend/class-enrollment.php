<?php

if (!defined('ABSPATH')) {
    exit;
}

class Coimne_Enrollment
{
    public static function display_coimne_enrollment()
    {
        $api = new Coimne_API();
        $user_profile = null;
        
        if ($api->userData) {
            $get_user_profile = $api->get_user_profile();
            $user_profile = $get_user_profile['data'];
        }

        $template = COIMNE_CUSTOM_TEMPLATES_DIR
            . '/enrollment.php';

        if (file_exists($template)) {
            return include $template;
        }
            
        echo Coimne_Shortcodes::template_not_found();
    }
}