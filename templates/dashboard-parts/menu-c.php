<?php if (!defined('ABSPATH')) exit; ?>

<div class="coimne-dashboard-menu-container">
    <div id="coimne-dashboard-menu-header">
        <p class="coimne-dashboard-greeting"><?php echo $saludo; ?></p>
    </div>
    <nav class="coimne-profile-menu">
        <ul id="menu-profile" class="coimne-dashboard-menu-list">
            <?php foreach (COIMNE_MENU_C_ITEMS as $key => $label) : ?>
                <li id="menu-item-<?php echo esc_attr($key); ?>" class="menu-item">
                    <a href="javascript:void(0);" data-content="<?php echo esc_attr($key); ?>">
                        <?php echo esc_html__($label, 'coimne-custom-content'); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php
        if (!empty($menu_location) && has_nav_menu($menu_location)) {
            wp_nav_menu([
                'theme_location' => $menu_location,
                'container' => false,
                'container_class' => 'coimne-dashboard-menu',
                'menu_class' => 'coimne-dashboard-menu-list'
            ]);
        }
        ?>
        <!-- Botón de Cerrar Sesión -->
        <form id="coimne-logout-form" method="post" action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
            <input type="hidden" name="action" value="coimne_logout">
            <button type="submit" class="coimne-logout-button"><?php _e('Cerrar Sesión', 'coimne-custom-content'); ?></button>
        </form>
    </nav>
</div>