<?php

if (!defined('ABSPATH')) {
    exit;
}

class Coimne_Menu
{
    public static $menu_colegiados = [
        'profile'  => 'Perfil',
        'courses'  => 'Mis cursos',
        'projects' => 'Visados proyectos',
        'account'  => 'Mi cuenta',
    ];

    public static $menu_externos = [
        'profile'  => 'Perfil',
        'courses'  => 'Mis cursos',
        'projects' => 'Visados proyectos',
        'account'  => 'Mi cuenta',
    ];

    public static $menu_otros = [
        'profile'  => 'Perfil',
        'courses'  => 'Mis cursos',
        'account'  => 'Mi cuenta',
    ];

    public static function display_dashboard_menu()
    {
        $api = new Coimne_API();

        $user_type = strtolower($api->userType);
        
        $template = COIMNE_CUSTOM_TEMPLATES_DIR
            . "/dashboard-parts/menu-$user_type.php";

        if (file_exists($template)) {
            $saludo = sprintf(__('¡Hola, %s!', 'coimne-custom-content'), $api->userData['cttName']);

            return include $template;
        }
        
        echo Coimne_Shortcodes::template_not_found();
    }
}
