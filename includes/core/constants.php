<?php

if (!defined('ABSPATH')) {
    exit;
}

define('COIMNE_OPTION_API_URL', 'coimne_api_url');
define('COIMNE_OPTION_DEV_MODE', 'coimne_dev_mode');
define('COIMNE_OPTION_LOGIN_URL', 'coimne_login_url');
define('COIMNE_OPTION_DASHBOARD_URL', 'coimne_dashboard_url');
define('COIMNE_OPTION_RECAPTCHA_SITE_KEY', 'coimne_recaptcha_site_key');
define('COIMNE_OPTION_RECAPTCHA_SECRET_KEY', 'coimne_recaptcha_secret_key');

define('COIMNE_OPTIONS', [
    COIMNE_OPTION_API_URL,
    COIMNE_OPTION_DEV_MODE,
    COIMNE_OPTION_LOGIN_URL,
    COIMNE_OPTION_DASHBOARD_URL,
    COIMNE_OPTION_RECAPTCHA_SITE_KEY,
    COIMNE_OPTION_RECAPTCHA_SECRET_KEY,
]);

define('COIMNE_MENU_C_ITEMS', [
    'profile'  => 'Perfil',
    'projects' => 'Visados proyectos',
    'courses'  => 'Mis cursos',
    'account'  => 'Mi cuenta',
]);

define('COIMNE_MENU_E_ITEMS', [
    'profile'  => 'Perfil',
    'courses'  => 'Mis cursos',
    'account'  => 'Mi cuenta',
]);

define('COIMNE_MENU_O_ITEMS', [
    'profile'  => 'Perfil',
    'account'  => 'Mi cuenta',
]);

define('EST_CIV', [
    '1'  => 'Soltero/a',
    '2'  => 'Casado/a',
    '3'  => 'Viudo/a',
    '4'  => 'Divorciado/a',
    '9'  => 'Otros',
]);

define('TIT', [
    '1'  => 'Ingeniero/a de Minas',
    '2'  => 'Master Ingeniero de Minas',
]);

define('SIT_LAB', [
    '1'  => 'Desempleado/a',
    '2'  => 'Becario/a',
    '3'  => 'Contrato en prácticas',
    '4'  => 'Activo/a',
    '5'  => 'Jubilado/a',
    '6'  => 'Desconocida',
]);

define('TS_PRL', [
    '0'  => 'Sin cursar',
    '1'  => 'Especialidad Seguridad',
    '2'  => 'Especialidad Higiene',
    '3'  => 'Especialidad Ergonomía',
    '4'  => 'Especialidad Seguridad e Higiene',
    '5'  => 'Especialidad Seguridad y Ergonomía',
    '6'  => 'Especialidad Higien y Ergonomía',
    '7'  => 'Especialidad Seguridad, Higiene y Ergonomía',
]);
