<?php
if (!class_exists('Puc_v4p11_Autoloader')) {
    require_once plugin_dir_path(__FILE__) . 'plugin-update-checker/plugin-update-checker.php';
}

use YahnisElsts\PluginUpdateChecker\v4p11\PluginUpdateChecker;

$updateChecker = PluginUpdateChecker::buildUpdateChecker(
    'https://github.com/hdcontrino/coimne-custom-content/',
    __FILE__,
    'coimne-custom-content'
);

$updateChecker->setBranch('main');
$updateChecker->getVcsApi()->enableReleaseAssets();
