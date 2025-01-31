<?php

if (!defined('ABSPATH')) {
    exit;
}

function coimne_register_recaptcha_settings()
{
    register_setting('coimne_settings_group_recaptcha', 'coimne_recaptcha_site_key');
    register_setting('coimne_settings_group_recaptcha', 'coimne_recaptcha_secret_key');

    add_settings_section(
        'coimne_recaptcha_section',
        __('Configuración de reCAPTCHA v2', 'coimne-custom-content'),
        null,
        'coimne-settings-recaptcha'
    );

    add_settings_field(
        'coimne_recaptcha_site_key',
        __('Clave del sitio', 'coimne-custom-content'),
        'coimne_recaptcha_site_key_callback',
        'coimne-settings-recaptcha',
        'coimne_recaptcha_section'
    );

    add_settings_field(
        'coimne_recaptcha_secret_key',
        __('Clave secreta', 'coimne-custom-content'),
        'coimne_recaptcha_secret_key_callback',
        'coimne-settings-recaptcha',
        'coimne_recaptcha_section'
    );
}

function coimne_recaptcha_site_key_callback()
{
    $recaptcha_site_key = get_option('coimne_recaptcha_site_key', '');
    echo "<input type='text' name='coimne_recaptcha_site_key' value='" . esc_attr($recaptcha_site_key) . "' class='regular-text'>";
}

function coimne_recaptcha_secret_key_callback()
{
    $recaptcha_secret_key = get_option('coimne_recaptcha_secret_key', '');
    echo "<input type='text' name='coimne_recaptcha_secret_key' value='" . esc_attr($recaptcha_secret_key) . "' class='regular-text'>";
}
