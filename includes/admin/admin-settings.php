<?php

if (!defined('ABSPATH')) {
    exit;
}

// Agregar menú en el administrador
function coimne_add_admin_menu()
{
    add_menu_page(
        __('Coimne Custom Content', 'coimne-custom-content'),
        __('Coimne Content', 'coimne-custom-content'),
        'manage_options',
        'coimne-settings',
        'coimne_settings_page',
        'dashicons-admin-generic',
        25
    );
}
add_action('admin_menu', 'coimne_add_admin_menu');

// Agregar enlace "Ajustes" en la lista de plugins
function coimne_add_settings_link($links)
{
    $settings_link = '<a href="admin.php?page=coimne-settings">' . __('Ajustes', 'coimne-custom-content') . '</a>';
    array_push($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(COIMNE_CUSTOM_CONTENT_DIR . 'coimne-custom-content.php'), 'coimne_add_settings_link');

// Registrar ajustes desde los archivos específicos
function coimne_register_settings()
{
    coimne_register_general_settings();
    coimne_register_api_settings();
    coimne_register_recaptcha_settings();
}
