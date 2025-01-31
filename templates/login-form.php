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

        <button type="submit" <?php echo !$recaptcha_enabled ? 'disabled' : ''; ?>><?php _e('Ingresar', 'coimne-custom-content'); ?></button>
        
        <p id="coimne-login-message"></p>
    </form>
</div>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let form = document.getElementById('coimne-login-form');
    let recaptchaEnabled = <?php echo json_encode($recaptcha_enabled); ?>;
    let redirectUrl = "<?php echo esc_url($redirect_url); ?>";

    if (!recaptchaEnabled) {
        document.getElementById('coimne-login-message').textContent = "<?php _e('Error: No se puede iniciar sesión porque el reCAPTCHA no está configurado.', 'coimne-custom-content'); ?>";
        document.getElementById('coimne-login-message').style.color = 'red';
        return;
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        let formData = new FormData();
        formData.append('action', 'coimne_login');
        formData.append('username', document.getElementById('coimne-username').value);
        formData.append('password', document.getElementById('coimne-password').value);
        formData.append('g-recaptcha-response', grecaptcha.getResponse());

        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            let message = document.getElementById('coimne-login-message');
            if (data.success) {
                window.location.href = data.redirect ? data.redirect : redirectUrl;
            } else {
                message.textContent = data.message;
                message.style.color = 'red';
                grecaptcha.reset();
            }
        })
        .catch(error => console.error('Error:', error));
    });
});
</script>

<style>
.coimne-error-message {
    color: red;
    font-weight: bold;
    margin-top: 10px;
}
</style>