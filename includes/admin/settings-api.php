<?php

if (!defined('ABSPATH')) {
    exit;
}

function coimne_register_api_settings()
{
    // Registrar el ajuste para activar/desactivar el modo desarrollo
    register_setting('coimne_settings_group_api', 'coimne_dev_mode');

    // Registrar el ajuste para la URL de la API
    register_setting('coimne_settings_group_api', 'coimne_api_url');

    // Sección API
    add_settings_section(
        'coimne_api_section',
        __('Configuración de API', 'coimne-custom-content'),
        null,
        'coimne-settings-api'
    );

    // Campo para el switch "Modo Desarrollo"
    add_settings_field(
        'coimne_dev_mode',
        __('Modo Desarrollo', 'coimne-custom-content'),
        'coimne_dev_mode_callback',
        'coimne-settings-api',
        'coimne_api_section'
    );

    // Campo para la URL de la API
    add_settings_field(
        'coimne_api_url',
        __('URL de la API', 'coimne-custom-content'),
        'coimne_api_url_callback',
        'coimne-settings-api',
        'coimne_api_section'
    );
}

// Callback para el switch "Modo Desarrollo"
function coimne_dev_mode_callback()
{
    $dev_mode = get_option('coimne_dev_mode', false);
?>
    <label class="switch">
        <input type="checkbox" name="coimne_dev_mode" value="1" <?php checked(1, $dev_mode, true); ?>>
        <span class="slider round"></span>
    </label>
    <p class="description"><?php _e('Activa el modo desarrollo para deshabilitar la verificación SSL en la API.', 'coimne-custom-content'); ?></p>
<?php
}

// Callback para el campo de la URL de la API
function coimne_api_url_callback()
{
    $api_url = get_option('coimne_api_url', '');
    echo "<input type='text' name='coimne_api_url' value='" . esc_attr($api_url) . "' class='regular-text'>";
}
