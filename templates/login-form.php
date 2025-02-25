<?php if (!defined('ABSPATH')) exit; ?>

<?php
$recaptcha_site_key = get_option('coimne_recaptcha_site_key', '');
$recaptcha_enabled = !empty($recaptcha_site_key);
$redirect_url = get_option('coimne_redirect_url', site_url('/dashboard'));
?>

<div class="coimne-login-form-container">
    <form id="coimne-login-form">
        <label for="coimne-username"><?php _e('Usuario:', 'coimne-custom-content'); ?></label>
        <input type="text" id="coimne-username" name="username" required>

        <label for="coimne-password"><?php _e('Contraseña:', 'coimne-custom-content'); ?></label>
        <input type="password" id="coimne-password" name="password" required>

        <!-- reCAPTCHA -->
        <?php if ($recaptcha_enabled) : ?>
            <div class="g-recaptcha" data-sitekey="<?php echo esc_attr($recaptcha_site_key); ?>"></div>
        <?php else : ?>
            <p class="coimne-error-message">
                <?php _e('⚠ Error: El reCAPTCHA no está configurado correctamente. Contacta al administrador.', 'coimne-custom-content'); ?>
            </p>
        <?php endif; ?>

        <button id="coimne-login-submit" type="submit" <?php echo !$recaptcha_enabled ? 'disabled' : ''; ?>>
            <?php _e('Ingresar', 'coimne-custom-content'); ?>
        </button>
        <span id="coimne-login-loader" class="coimne-loader" style="display: none;"></span>

        <p id="coimne-login-message"></p>
    </form>
</div>