<?php if (!defined('ABSPATH')) exit; ?>

<div class="coimne-account-container">
    <form id="account-form" method="post">
        <input type="hidden" name="action" value="set_user_account">
        
        <fieldset>
            <legend>Usuario y contraseña</legend>
            <div class="coimne-form-group">
                <label for="username">E-Mail:</label>
                <input type="text" id="username" name="USERNAME" required
                    value="<?php echo esc_attr($api->userData['userWeb']); ?>">
            </div>
            <div class="coimne-form-group">
                <label for="password">Contraseña actual:</label>
                <input type="text" id="password" name="PASS">
            </div>
            <div class="coimne-form-group">
                <label for="new_password">Nueva contraseña:</label>
                <input type="text" id="new_password" name="NEWPASS">
            </div>
        </fieldset>

        <p class="coimne-account-disclaimer">
            Cambiar el email aquí significará que, en adelante, deba ingresar con el nuevo email.
            <br>
            Para cambiar la contraseña, deberá ingresar tanto la contraseña actual como la nueva.
        </p>

        <button id="coimne-account-submit" type="submit">Confirmar</button>
        <span id="coimne-account-loader" class="coimne-loader" style="display: none;"></span>
        <span id="coimne-account-message"></span>
    </form>
</div>
