<?php

if (!defined('ABSPATH')) {
    exit;
}

function coimne_register_general_settings()
{
    register_setting('coimne_settings_group_general', 'coimne_redirect_url');

    add_settings_section(
        'coimne_general_section',
        __('Configuración General', 'coimne-custom-content'),
        null,
        'coimne-settings-general'
    );

    add_settings_field(
        'coimne_redirect_url',
        __('URL de Redirección después del Login', 'coimne-custom-content'),
        'coimne_redirect_url_callback',
        'coimne-settings-general',
        'coimne_general_section'
    );
}

// Callback para la URL de redirección
function coimne_redirect_url_callback()
{
    $redirect_url = get_option('coimne_redirect_url', site_url('/dashboard'));
    echo "<input type='text' name='coimne_redirect_url' value='" . esc_attr($redirect_url) . "' class='regular-text'>";
}

// Función para mostrar los shortcodes disponibles
function coimne_display_shortcodes()
{
?>
    <div class="coimne-shortcodes-section">
        <h2><?php _e('Shortcodes Disponibles', 'coimne-custom-content'); ?></h2>
        <p><?php _e('A continuación se listan los shortcodes que puedes utilizar en tu sitio:', 'coimne-custom-content'); ?></p>

        <ul>
            <li><code>[coimne_login]</code> - <?php _e('Muestra el formulario de inicio de sesión.', 'coimne-custom-content'); ?></li>
            <li><code>[coimne_dashboard]</code> - <?php _e('Muestra el panel de control del usuario logueado.', 'coimne-custom-content'); ?></li>
            <!-- Agrega más shortcodes aquí si es necesario -->
        </ul>
    </div>
<?php
}
