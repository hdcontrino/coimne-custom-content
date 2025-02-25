<?php

if (!defined('ABSPATH')) {
    exit;
}

// Cargar el dominio de texto para traducción
function coimne_load_textdomain()
{
    load_plugin_textdomain('coimne-custom-content', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'coimne_load_textdomain');

// Incluir helpers y constantes
require_once COIMNE_CUSTOM_CONTENT_DIR . 'includes/helpers.php';
require_once COIMNE_CUSTOM_CONTENT_DIR . 'includes/core/constants.php';

// Cargar funcionalidades principales
require_once COIMNE_CUSTOM_CONTENT_DIR . 'includes/core/class-session.php';
require_once COIMNE_CUSTOM_CONTENT_DIR . 'includes/core/class-api.php';
require_once COIMNE_CUSTOM_CONTENT_DIR . 'includes/core/class-menu.php';
require_once COIMNE_CUSTOM_CONTENT_DIR . 'includes/core/class-dashboard.php';
require_once COIMNE_CUSTOM_CONTENT_DIR . 'includes/core/class-user.php';

// Cargar elementos del frontend
require_once COIMNE_CUSTOM_CONTENT_DIR . 'includes/frontend/class-shortcodes.php';
require_once COIMNE_CUSTOM_CONTENT_DIR . 'includes/frontend/class-widgets.php';

// Cargar AJAX
require_once COIMNE_CUSTOM_CONTENT_DIR . 'includes/ajax/class-ajax.php';

// Cargar administración
require_once COIMNE_CUSTOM_CONTENT_DIR . 'includes/admin/admin-settings.php';
require_once COIMNE_CUSTOM_CONTENT_DIR . 'includes/admin/settings-general.php';
require_once COIMNE_CUSTOM_CONTENT_DIR . 'includes/admin/settings-api.php';
require_once COIMNE_CUSTOM_CONTENT_DIR . 'includes/admin/settings-recaptcha.php';
require_once COIMNE_CUSTOM_CONTENT_DIR . 'includes/admin/settings-tabs.php';

// Inicializar clases y registrar funcionalidades
function coimne_custom_content_init()
{
    new Coimne_Session();
    new Coimne_API();
    new Coimne_Shortcodes();
    new Coimne_Widgets();
}
add_action('plugins_loaded', 'coimne_custom_content_init');

// Registrar los ajustes de configuración
add_action('admin_init', 'coimne_register_settings');

// Registrar ubicación de menú
function coimne_register_menu_locations()
{
    register_nav_menus([
        'students_dashboard' => __('Dashboard Alumnos', 'coimne-custom-content'),
        'collegiate_dashboard' => __('Dashboard Colegiados', 'coimne-custom-content'),
    ]);
}
add_action('init', 'coimne_register_menu_locations');
