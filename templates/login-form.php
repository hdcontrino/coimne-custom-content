<?php if (!defined('ABSPATH')) exit; ?>

<?php
$recaptcha_site_key = get_option(COIMNE_OPTION_RECAPTCHA_SITE_KEY, '');
?>

<div class="coimne-login-container">
    <!-- Lado del Login -->
    <div class="coimne-login-card front">
        <form id="coimne-login-form">
            <label for="coimne-username"><?php _e('Usuario:', 'coimne-custom-content'); ?></label>
            <input type="text" id="coimne-username" name="username" required>

            <label for="coimne-password"><?php _e('Contraseña:', 'coimne-custom-content'); ?></label>
            <input type="password" id="coimne-password" name="password" required>

            <!-- reCAPTCHA -->
            <?php if (!empty($recaptcha_site_key)) : ?>
                <div class="g-recaptcha" data-sitekey="<?php echo esc_attr($recaptcha_site_key); ?>"></div>
            <?php else : ?>
                <p class="coimne-error-message">
                    <?php _e('⚠ Error: El reCAPTCHA no está configurado correctamente. Contacta al administrador.', 'coimne-custom-content'); ?>
                </p>
            <?php endif; ?>

            <button id="coimne-login-submit" type="submit" <?php echo empty($recaptcha_site_key) ? 'disabled' : ''; ?>>
                <?php _e('Ingresar', 'coimne-custom-content'); ?>
            </button>
            <span id="coimne-login-loader" class="coimne-loader" style="display: none;"></span>

            <p id="coimne-login-message"></p>
        </form>

        <div class="coimne-login-forgot_password">
            <a href="javascript:void(0);" id="show-forgot-password"><?php _e('Olvidé mi contraseña', 'coimne-custom-content'); ?></a>
        </div>
    </div>

    <!-- Lado del Recuperar Contraseña -->
    <div class="coimne-login-card back">
        <form id="coimne-forgot-password-form">
            <label for="coimne-email"><?php _e('Ingresa tu email:', 'coimne-custom-content'); ?></label>
            <input type="email" id="coimne-email" name="email" required>

            <button id="coimne-forgot-submit" type="submit">
                <?php _e('Recuperar Contraseña', 'coimne-custom-content'); ?>
            </button>
            <span id="coimne-forgot-loader" class="coimne-loader" style="display: none;"></span>

            <p id="coimne-forgot-message"></p>
        </form>

        <div class="coimne-login-back">
            <a href="javascript:void(0);" id="show-login"><?php _e('Volver al login', 'coimne-custom-content'); ?></a>
        </div>
    </div>
</div>