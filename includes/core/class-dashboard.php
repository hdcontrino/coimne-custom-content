<?php

if (!defined('ABSPATH')) {
    exit;
}

class Coimne_Dashboard
{
    public static function display_coimne_dashboard()
    {
        $template_path = COIMNE_CUSTOM_CONTENT_DIR . '/templates/dashboard.php';

        if (!file_exists($template_path)) {
            echo self::dashboard_not_found();
        }

        include $template_path;
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
        $emp_dep = $user_profile['EMP']['EMP_DEP_COI_EMP_NOM_ON'];

        $user_type = strtolower($api->userData['tip']);
        $template_path = COIMNE_CUSTOM_CONTENT_DIR . '/templates';
        $template_path .= "/dashboard-parts/profile-$user_type.php";

        if (!file_exists($template_path)) {
            echo self::dashboard_not_found();
        }

        include $template_path;
    }

    public static function display_dashboard_projects()
    {
        $template_path = COIMNE_CUSTOM_CONTENT_DIR . '/templates';
        $template_path .= "/dashboard-parts/projects.php";

        if (!file_exists($template_path)) {
            echo self::dashboard_not_found();
        }

        include $template_path;
    }

    public static function display_dashboard_courses()
    {
        $template_path = COIMNE_CUSTOM_CONTENT_DIR . '/templates';
        $template_path .= "/dashboard-parts/courses.php";

        if (!file_exists($template_path)) {
            echo self::dashboard_not_found();
        }

        include $template_path;
    }

    public static function display_dashboard_jobs()
    {
        $template_path = COIMNE_CUSTOM_CONTENT_DIR . '/templates';
        $template_path .= "/dashboard-parts/jobs.php";

        if (!file_exists($template_path)) {
            echo self::dashboard_not_found();
        }

        include $template_path;
    }

    public static function display_dashboard_account()
    {
        $api = new Coimne_API();

        $user_type = strtolower($api->userData['tip']);
        $template_path = COIMNE_CUSTOM_CONTENT_DIR . '/templates';
        $template_path .= "/dashboard-parts/account-$user_type.php";

        if (!file_exists($template_path)) {
            echo self::dashboard_not_found();
        }

        include $template_path;
    }

    private static function dashboard_not_found()
    {
        return '<p>' . __('Error: No se encontró la plantilla de dashboard.', 'coimne-custom-content') . '</p>';
    }
}
