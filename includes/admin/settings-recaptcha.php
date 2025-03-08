<?php

if (!defined('ABSPATH')) {
    exit;
}

function coimne_register_recaptcha_settings()
{
    register_setting('coimne_settings_group_recaptcha', COIMNE_OPTION_RECAPTCHA_SITE_KEY);
    register_setting('coimne_settings_group_recaptcha', COIMNE_OPTION_RECAPTCHA_SECRET_KEY);

    add_settings_section(
        'coimne_recaptcha_section',
        __('Configuración de reCAPTCHA v2', 'coimne-custom-content'),
        null,
        'coimne-settings-recaptcha'
    );

    add_settings_field(
        COIMNE_OPTION_RECAPTCHA_SITE_KEY,
        __('Clave del sitio', 'coimne-custom-content'),
        'coimne_recaptcha_site_key_callback',
        'coimne-settings-recaptcha',
        'coimne_recaptcha_section'
    );

    add_settings_field(
        COIMNE_OPTION_RECAPTCHA_SECRET_KEY,
        __('Clave secreta', 'coimne-custom-content'),
        'coimne_recaptcha_secret_key_callback',
        'coimne-settings-recaptcha',
        'coimne_recaptcha_section'
    );
}

function coimne_recaptcha_site_key_callback()
{
    $recaptcha_site_key = get_option(COIMNE_OPTION_RECAPTCHA_SITE_KEY, '');
?> 
    <input type="text" name="<?php echo COIMNE_OPTION_RECAPTCHA_SITE_KEY; ?>" 
        value="<?php echo esc_attr($recaptcha_site_key); ?>" class="regular-text">
<?php
}

function coimne_recaptcha_secret_key_callback()
{
    $recaptcha_secret_key = get_option(COIMNE_OPTION_RECAPTCHA_SECRET_KEY, '');
?> 
    <input type="text" name="<?php echo COIMNE_OPTION_RECAPTCHA_SECRET_KEY; ?>" 
        value="<?php esc_attr($recaptcha_secret_key); ?>" class="regular-text">
<?php
}
