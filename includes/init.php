<?php

if (!defined('ABSPATH')) {
    exit;
}

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

if (!file_exists(plugin_dir_path(__DIR__) . '/vendor/autoload.php') || coimne_necesita_actualizar_vendor()) {
    add_action('admin_notices', function () {
        echo '<div class="notice notice-warning"><p>' . __('Actualizando dependencias del plugin... Por favor, espere.', 'coimne-custom-content') . '</p></div>';
    });

    coimne_instalar_o_actualizar_composer();
}

// Verifica si las dependencias necesitan actualización comparando el hash del composer.lock
function coimne_necesita_actualizar_vendor()
{
    $plugin_path = plugin_dir_path(__DIR__);
    $composer_lock = $plugin_path . 'composer.lock';
    $hash_file = $plugin_path . 'vendor/.installed_hash';

    if (!file_exists($composer_lock)) {
        return false;
    }

    $actual_hash = hash_file('sha256', $composer_lock);

    if (!file_exists($hash_file) || file_get_contents($hash_file) !== $actual_hash) {
        return true;
    }

    return false;
}

// Instala o actualiza dependencias según sea necesario
function coimne_instalar_o_actualizar_composer()
{
    $plugin_path = plugin_dir_path(__DIR__);

    $composer_bin = 'composer';
    if (!is_executable($composer_bin)) {
        $composer_bin = '/usr/local/bin/composer';
    }

    if (!is_executable($composer_bin)) {
        coimne_descargar_vendor();
        return;
    }

    if (coimne_necesita_actualizar_vendor()) {
        $command = escapeshellcmd("$composer_bin install --no-dev --optimize-autoloader -d $plugin_path");
        shell_exec($command);

        file_put_contents($plugin_path . 'vendor/.installed_hash', hash_file('sha256', $plugin_path . 'composer.lock'));
    }
}

// Descarga y extrae `vendor.zip` si no se puede ejecutar Composer
function coimne_descargar_vendor()
{
    $plugin_path = plugin_dir_path(__DIR__);
    $vendor_url = 'https://github.com/hdcontrino/coimne-custom-content/releases/latest/download/vendor.zip';
    $vendor_zip = $plugin_path . 'vendor.zip';

    file_put_contents($vendor_zip, file_get_contents($vendor_url));

    $zip = new ZipArchive;
    if ($zip->open($vendor_zip) === TRUE) {
        $zip->extractTo($plugin_path);
        $zip->close();
        unlink($vendor_zip);
    } else {
        add_action('admin_notices', function () {
            echo '<div class="notice notice-error"><p>' . __('Error: No se pudo descargar las dependencias del plugin.', 'coimne-custom-content') . '</p></div>';
        });
    }
}

// Cargar el dominio de texto para traducción
function coimne_load_textdomain()
{
    load_plugin_textdomain('coimne-custom-content', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'coimne_load_textdomain');

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
