<?php

if (!defined('ABSPATH')) {
    exit;
}

$autoload_path = COIMNE_CUSTOM_CONTENT_DIR . 'vendor/autoload.php';

if (!file_exists($autoload_path)) {
    error_log('[Coimne Custom Content] ERROR: No se encontró vendor/autoload.php. El sistema de actualizaciones no se cargará.');
    return;
}

require_once $autoload_path;

use YahnisElsts\PluginUpdateChecker\v5p5\Plugin\UpdateChecker;

if (!class_exists(UpdateChecker::class)) {
    error_log('[Coimne Custom Content] ERROR CRÍTICO: La clase UpdateChecker sigue sin estar disponible después de cargar vendor/autoload.php.');
    return;
}

$updateChecker = new UpdateChecker(
    'https://raw.githubusercontent.com/hdcontrino/coimne-custom-content/main/updates.json',
    COIMNE_CUSTOM_CONTENT_DIR . 'coimne-custom-content.php'
);
