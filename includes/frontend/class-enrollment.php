<?php

if (!defined('ABSPATH')) {
    exit;
}

class Coimne_Enrollment
{
    public static function display_coimne_enrollment()
    {
        $template = COIMNE_CUSTOM_TEMPLATES_DIR
            . 'templates/enrollment.php';

        if (file_exists($template)) {
            return include $template;
        }
            
        echo Coimne_Shortcodes::template_not_found();
    }
}