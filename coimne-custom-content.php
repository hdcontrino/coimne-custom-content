<?php

/**
 * Plugin Name: Coimne Custom Content
 * Plugin URI: https://coheda.com
 * Description: Plugin para mostrar contenido especial basado en sesiones de usuario externas.
 * Version: 1.0.1
 * Author: Daniel Contrino
 * Author URI: https://coheda.com
 * License: Propietaria
 */

if (!defined('ABSPATH')) {
    exit;
}

// Definir constantes
define('COIMNE_CUSTOM_CONTENT_VERSION', '1.0.1');
define('COIMNE_CUSTOM_CONTENT_DIR', plugin_dir_path(__FILE__));
define('COIMNE_CUSTOM_CONTENT_URL', plugin_dir_url(__FILE__));

// Cargar funciones principales
require_once COIMNE_CUSTOM_CONTENT_DIR . 'includes/init.php';
require_once COIMNE_CUSTOM_CONTENT_DIR . 'includes/update-checker.php';
