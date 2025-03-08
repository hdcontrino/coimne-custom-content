<?php

if (!defined('ABSPATH')) {
    exit;
}

function coimne_register_general_settings()
{
    add_settings_section(
        'coimne_general_section',
        __('Configuración General', 'coimne-custom-content'),
        null,
        'coimne-settings-general'
    );

    coimne_login_url_setting();
    coimne_dashboard_url_setting();
}

function coimne_dashboard_url_setting()
{
    register_setting('coimne_settings_group_general', COIMNE_OPTION_DASHBOARD_URL);

    add_settings_field(
        COIMNE_OPTION_DASHBOARD_URL,
        __('URL del Dashboard', 'coimne-custom-content'),
        'coimne_dashboard_url_callback',
        'coimne-settings-general',
        'coimne_general_section'
    );
}

function coimne_dashboard_url_callback()
{
    $dashboard_url = get_option(COIMNE_OPTION_DASHBOARD_URL, site_url('/dashboard'));
    ?>
    <input type="text" name="<?php echo esc_attr(COIMNE_OPTION_DASHBOARD_URL); ?>"
        value="<?php echo esc_attr($dashboard_url); ?>" class="regular-text">
    <?php
}

function coimne_login_url_setting()
{
    register_setting('coimne_settings_group_general', COIMNE_OPTION_LOGIN_URL);

    add_settings_field(
        COIMNE_OPTION_LOGIN_URL,
        __('URL del Login', 'coimne-custom-content'),
        'coimne_login_url_callback',
        'coimne-settings-general',
        'coimne_general_section'
    );
}

function coimne_login_url_callback()
{
    $login_url = get_option(COIMNE_OPTION_LOGIN_URL, site_url('/login'));
    ?>
    <input type="text" name="<?php echo esc_attr(COIMNE_OPTION_LOGIN_URL); ?>"
        value="<?php echo esc_attr($login_url); ?>" class="regular-text">
    <?php
}

// Función para mostrar los shortcodes disponibles
function coimne_display_shortcodes()
{
?>
    <div class="coimne-shortcodes-section">
        <h2><?php _e('Shortcodes Disponibles', 'coimne-custom-content'); ?></h2>
        <p><?php _e('A continuación se listan los shortcodes que puedes utilizar en tu sitio:', 'coimne-custom-content'); ?></p>

        <ul>
            <li><code>[coimne_login_btn]</code> - <?php _e('Muestra el botón de Login/Mi cuenta.', 'coimne-custom-content'); ?></li>
            <li><code>[coimne_login_form]</code> - <?php _e('Muestra el formulario de inicio de sesión.', 'coimne-custom-content'); ?></li>
            <li></li>
            <li><code>[coimne_dashboard]</code> - <?php _e('Muestra el panel de control del usuario logueado.', 'coimne-custom-content'); ?></li>
            <li><code>[coimne_dashboard_menu]</code> - <?php _e('Muestra menú del panel de control.', 'coimne-custom-content'); ?></li>
            <li><code>[coimne_dashboard_profile]</code> - <?php _e('Muestra la sección de perfil del usuario logueado.', 'coimne-custom-content'); ?></li>
        </ul>
    </div>
<?php
}
