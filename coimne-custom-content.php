<?php

/**
 * Plugin Name: Coimne Custom Content
 * Plugin URI: https://github.com/hdcontrino/coimne-custom-content
 * Description: Plugin para mostrar contenido especial basado en sesiones de usuario externas.
 * Version: 1.0.0
 * Author: Daniel Contrino
 * Author URI: https://coheda.com
 * License: Propietaria
 */

if (!defined('ABSPATH')) {
    exit;
}

// Constants
define('COIMNE_CUSTOM_CONTENT_VERSION', '1.0.0');
define('COIMNE_CUSTOM_CONTENT_DIR', plugin_dir_path(__FILE__));
define('COIMNE_CUSTOM_CONTENT_URL', plugin_dir_url(__FILE__));

// Includes
require_once COIMNE_CUSTOM_CONTENT_DIR . 'includes/class-session.php';
require_once COIMNE_CUSTOM_CONTENT_DIR . 'includes/class-api.php';
require_once COIMNE_CUSTOM_CONTENT_DIR . 'includes/class-shortcodes.php';
require_once COIMNE_CUSTOM_CONTENT_DIR . 'includes/class-widgets.php';

// Register shortcodes y widgets
function coimne_custom_content_init()
{
    new Coimne_Session();
    new Coimne_API();
    new Coimne_Shortcodes();
    new Coimne_Widgets();
}
add_action('plugins_loaded', 'coimne_custom_content_init');
