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
        $countries = $api->get_countries();
        $provinces = $api->get_provinces($user_profile['PAI']);
        $locs = $api->get_locs($user_profile['PAI'], $user_profile['PRO']);
        $towns = $api->get_towns($user_profile['PAI'], $user_profile['PRO']);

        $template = COIMNE_CUSTOM_TEMPLATES_DIR 
            . "/dashboard-parts/profile-$api->userType.php";

        if (file_exists($template)) {
            return include $template;
        }

        echo Coimne_Shortcodes::template_not_found();
    }

    public static function display_dashboard_projects()
    {
        $template = COIMNE_CUSTOM_TEMPLATES_DIR 
            . "/dashboard-parts/projects.php";

        if (file_exists($template)) {
            return include $template;
        }

        echo Coimne_Shortcodes::template_not_found();
    }

    public static function display_dashboard_courses()
    {
        $template = COIMNE_CUSTOM_TEMPLATES_DIR
            . "/dashboard-parts/courses.php";

        if (file_exists($template)) {
            return include $template;
        }

        echo Coimne_Shortcodes::template_not_found();
    }

    public static function display_dashboard_jobs()
    {
        $template = COIMNE_CUSTOM_TEMPLATES_DIR
            . "/dashboard-parts/jobs.php";

        if (file_exists($template)) {
            return include $template;
        }

        echo Coimne_Shortcodes::template_not_found();
    }

    public static function display_dashboard_account()
    {
        $api = new Coimne_API();

        $template = COIMNE_CUSTOM_TEMPLATES_DIR
            . "/dashboard-parts/account-$api->userType.php";

        if (file_exists($template)) {
            return include $template;
        }

        echo Coimne_Shortcodes::template_not_found();
    }
}
