<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Página de ajustes del plugin.
 */
function coimne_settings_page()
{
    $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'general';
?>
    <div class="wrap">
        <h1><?php _e('Ajustes de Coimne Custom Content', 'coimne-custom-content'); ?></h1>

        <?php coimne_render_tabs($active_tab); ?>

        <div class="coimne-settings-content">
            <?php coimne_render_settings_form($active_tab); ?>
        </div>
    </div>
<?php
}

/**
 * Renderiza las pestañas de navegación.
 */
function coimne_render_tabs($active_tab)
{
    $tabs = [
        'general'   => __('General', 'coimne-custom-content'),
        'api'       => __('API', 'coimne-custom-content'),
        'recaptcha' => __('reCAPTCHA v2', 'coimne-custom-content'),
    ];
?>
    <h2 class="nav-tab-wrapper">
        <?php foreach ($tabs as $tab => $label) : ?>
            <a href="?page=coimne-settings&tab=<?php echo esc_attr($tab); ?>"
                class="nav-tab <?php echo $active_tab === $tab ? 'nav-tab-active' : ''; ?>">
                <?php echo esc_html($label); ?>
            </a>
        <?php endforeach; ?>
    </h2>
<?php
}

/**
 * Renderiza el formulario de configuración según la pestaña activa.
 */
function coimne_render_settings_form($active_tab)
{
    $settings_groups = [
        'general'   => 'coimne_settings_group_general',
        'api'       => 'coimne_settings_group_api',
        'recaptcha' => 'coimne_settings_group_recaptcha',
    ];

    if (isset($settings_groups[$active_tab])) {
        echo '<form method="post" action="options.php">';
        settings_fields($settings_groups[$active_tab]);
        do_settings_sections('coimne-settings-' . $active_tab);
        submit_button();
        echo '</form>';

        // Mostrar los shortcodes solo en la pestaña "General"
        if ($active_tab === 'general') {
            coimne_display_shortcodes();
        }
    }
}

/**
 * Encola los estilos CSS del panel de ajustes.
 */
function coimne_admin_enqueue_styles($hook)
{
    if ($hook === 'toplevel_page_coimne-settings') {
        wp_enqueue_style('coimne-admin-styles', Coimne_Helper::asset_url('css/coimne-admin.css'));
    }
}
add_action('admin_enqueue_scripts', 'coimne_admin_enqueue_styles');

?>