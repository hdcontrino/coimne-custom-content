<?php

if (!defined('ABSPATH')) {
    exit;
}

define('COIMNE_OPTION_API_URL', 'coimne_api_url');
define('COIMNE_OPTION_DEV_MODE', 'coimne_dev_mode');
define('COIMNE_OPTION_REDIRECT_URL', 'coimne_redirect_url');
define('COIMNE_OPTION_RECAPTCHA_SITE_KEY', 'coimne_recaptcha_site_key');
define('COIMNE_OPTION_RECAPTCHA_SECRET_KEY', 'coimne_recaptcha_secret_key');

define('COIMNE_OPTIONS', [
    COIMNE_OPTION_API_URL,
    COIMNE_OPTION_DEV_MODE,
    COIMNE_OPTION_REDIRECT_URL,
    COIMNE_OPTION_RECAPTCHA_SITE_KEY,
    COIMNE_OPTION_RECAPTCHA_SECRET_KEY,
]);

define('COIMNE_MENU_ITEMS', [
    'profile'  => 'Perfil',
    'projects' => 'Visados proyectos',
    'courses'  => 'Cursos',
    'jobs'     => 'Bolsa empleo'
]);
