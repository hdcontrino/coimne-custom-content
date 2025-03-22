<?php

if (!defined('ABSPATH')) {
    exit;
}

class Coimne_Dashboard
{
    public static function display_coimne_dashboard()
    {
        $template = COIMNE_CUSTOM_TEMPLATES_DIR
            . '/dashboard.php';

        if (file_exists($template)) {
            return include $template;
        }

        echo Coimne_Shortcodes::template_not_found();
    }

    public static function display_dashboard_profile()
    {
        $api = new Coimne_API();

        $get_user_profile = $api->get_user_profile();
        $user_profile = $get_user_profile['data'];
        $user_type = strtolower($api->userType);
        
        $countries = $api->get_countries();
        $provinces = $api->get_provinces($user_profile['PAI']);
        $towns = $api->get_towns($user_profile['PAI'], $user_profile['PRO']);

        $template = COIMNE_CUSTOM_TEMPLATES_DIR 
            . "/dashboard-parts/profile/profile-$user_type.php";

        if (file_exists($template)) {
            return include $template;
        }

        echo Coimne_Shortcodes::template_not_found();
    }

    public static function display_dashboard_projects()
    {
        $api = new Coimne_API();

        $courses = $api->get_projects();

        $template = COIMNE_CUSTOM_TEMPLATES_DIR 
            . "/dashboard-parts/projects/projects.php";

        if (file_exists($template)) {
            return include $template;
        }

        echo Coimne_Shortcodes::template_not_found();
    }

    public static function display_dashboard_courses()
    {
        $template = COIMNE_CUSTOM_TEMPLATES_DIR
            . "/dashboard-parts/courses/courses.php";

        if (file_exists($template)) {
            return include $template;
        }

        echo Coimne_Shortcodes::template_not_found();
    }

    public static function display_dashboard_account()
    {
        $api = new Coimne_API();

        $user_type = strtolower($api->userType);

        $template = COIMNE_CUSTOM_TEMPLATES_DIR
            . "/dashboard-parts/account/account-$user_type.php";

        if (file_exists($template)) {
            return include $template;
        }

        echo Coimne_Shortcodes::template_not_found();
    }
}
