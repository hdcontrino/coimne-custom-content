<?php

/**
 * Plugin Name: Coimne Custom Content
 * Plugin URI: https://coheda.com
 * Description: Plugin para mostrar contenido especial basado en sesiones de usuario externas.
 * Version: 1.0.15
 * Author: Daniel Contrino
 * Author URI: https://coheda.com
 * License: Propietaria
 * Requires at least: 6.0
 * Tested up to: 6.7.2
 * Requires PHP: 7.4
 */

if (!defined('ABSPATH')) {
    exit;
}

// Definir constantes principales
define('COIMNE_CUSTOM_CONTENT_VERSION', '1.0.15');
define('COIMNE_CUSTOM_CONTENT_DIR', plugin_dir_path(__FILE__));
define('COIMNE_CUSTOM_CONTENT_URL', plugin_dir_url(__FILE__));

if (version_compare(get_bloginfo('version'), '5.0', '<')) {
    deactivate_plugins(plugin_basename(__FILE__));
    wp_die(
        __('Este plugin requiere WordPress 5.0 o superior.', 'coimne-custom-content'),
        __('Error de compatibilidad', 'coimne-custom-content'),
        ['back_link' => true]
    );
}

if (version_compare(PHP_VERSION, '7.4', '<')) {
    deactivate_plugins(plugin_basename(__FILE__));
    wp_die(
        __('Este plugin requiere PHP 7.4 o superior.', 'coimne-custom-content'),
        __('Error de compatibilidad', 'coimne-custom-content'),
        ['back_link' => true]
    );
}

// Cargar funciones principales
require_once COIMNE_CUSTOM_CONTENT_DIR . 'includes/init.php';
require_once COIMNE_CUSTOM_CONTENT_DIR . 'includes/update-checker.php';
