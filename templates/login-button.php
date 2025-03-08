<?php if (!defined('ABSPATH')) exit; ?>

<?php if ($user): ?>

    <a href="<?php echo get_option(COIMNE_OPTION_DASHBOARD_URL, ''); ?>">
        <?php _e('MI PERFIL', 'coimne-custom-content'); ?>
    </a>
    
<?php else: ?>

    <a href="<?php echo get_option(COIMNE_OPTION_LOGIN_URL, ''); ?>">
        <?php _e('ACCESO PRIVADO', 'coimne-custom-content'); ?>
    </a>

<?php endif; ?>