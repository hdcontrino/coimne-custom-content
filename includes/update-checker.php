<?php
if (!class_exists('YahnisElsts\PluginUpdateChecker\v4p11\PluginUpdateChecker')) {
    $autoload_path = plugin_dir_path(__DIR__) . '../vendor/autoload.php';

    if (file_exists($autoload_path)) {
        require_once $autoload_path;
    } else {
        error_log('[Coimne Custom Content] Error: No se encontró vendor/autoload.php. El sistema de actualizaciones no se cargará.');
        return;
    }
}

if (!class_exists('YahnisElsts\PluginUpdateChecker\v4p11\PluginUpdateChecker')) {
    error_log('[Coimne Custom Content] Error crítico: La clase PluginUpdateChecker no se encuentra disponible.');
    return;
}

use YahnisElsts\PluginUpdateChecker\v4p11\PluginUpdateChecker;

try {
    $updateChecker = PluginUpdateChecker::buildUpdateChecker(
        'https://github.com/hdcontrino/coimne-custom-content/',
        __FILE__,
        'coimne-custom-content'
    );

    $updateChecker->setBranch('main');
    $updateChecker->getVcsApi()->enableReleaseAssets();
} catch (Exception $e) {
    error_log('[Coimne Custom Content] Error al inicializar el actualizador: ' . $e->getMessage());
}
