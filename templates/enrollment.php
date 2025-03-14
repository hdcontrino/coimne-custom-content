<?php if (!defined('ABSPATH')) exit; ?>

<?php
$nif = isset($user_profile['NIF']) ? $user_profile['NIF'] : '';
$name = isset($user_profile['NAME']) ? $user_profile['NAME'] : '';

$ape_1 = isset($user_profile['APE_1']) ? $user_profile['APE_1'] : '';
$ape_2 = isset($user_profile['APE_2']) ? $user_profile['APE_2'] : '';
$email = isset($user_profile['EML']) ? $user_profile['EML'] : '';
$phone = isset($user_profile['TFN']) ? $user_profile['TFN'] : '';

$course_id = ''; // Por si en el futuro quieren autocompletar el select desde el frontend

$courses = $api->get_cursos();
?>

<div class="coimne-enrollment-container">
    <form id="coimne-enrollment-form">
        <div class="coimne-form-row">
            <div class="coimne-form-group">
                <label for="nif">NIF: <span class="coimne-required-field">*</span></label>
                <input type="text" id="nif" name="NIF" value="<?php echo esc_attr($nif); ?>" required>
            </div>
            <div class="coimne-form-group">
                <label for="name">Nombre: <span class="coimne-required-field">*</span></label>
                <input type="text" id="name" name="NAME" value="<?php echo esc_attr($name); ?>" required>
            </div>
        </div>

        <div class="coimne-form-row">
            <div class="coimne-form-group">
                <label for="ape_1">Apellido 1: <span class="coimne-required-field">*</span></label>
                <input type="text" id="ape_1" name="APE_1" value="<?php echo esc_attr($ape_1); ?>" required>
            </div>
            <div class="coimne-form-group">
                <label for="ape_2">Apellido 2: <span class="coimne-required-field">*</span></label>
                <input type="text" id="ape_2" name="APE_2" value="<?php echo esc_attr($ape_2); ?>" required>
            </div>
        </div>

        <div class="coimne-form-row">
            <div class="coimne-form-group">
                <label for="email">E-mail: <span class="coimne-required-field">*</span></label>
                <input type="email" id="email" name="EML" value="<?php echo esc_attr($email); ?>" required>
            </div>
            <div class="coimne-form-group">
                <label for="phone">Teléfono: <span class="coimne-required-field">*</span></label>
                <input type="text" id="phone" name="TFN" value="<?php echo esc_attr($phone); ?>" required>
            </div>
        </div>

        <div class="coimne-form-group">
            <label for="courses">Título curso: <span class="coimne-required-field">*</span></label>
            <select id="courses" name="CUR_ID" required>
                <option value="">Elija un curso... </option>
                <?php foreach ($courses['data'] as $course): ?>
                    <option value="<?php echo esc_attr($course['ID']); ?>"
                        <?php selected($course['ID'], $course_id); ?>
                        <?php disabled($course['COM'], true); ?>>
                        <?php if ($course['COM']): ?>
                            ** Completo **
                        <?php endif; ?>
                        <?php echo esc_html($course['NAME']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="coimne-form-group coimne-checkbox-group">
            <input type="checkbox" id="terms" name="TERMS" required>
            <label for="terms">
                He completado y enviado la 
                <a href="<?php echo get_option(COIMNE_OPTION_PRIVACY_URL, ''); ?>" target="_blank">Cláusula Informativa</a> 
                en la Inscripción
            </label>
        </div>

        <div class="coimne-form-group coimne-checkbox-group">
            <input type="checkbox" id="privacy" name="PRIVACY" required>
            <label for="privacy">
                He leído y acepto las 
                <a href="<?php echo get_option(COIMNE_OPTION_TERMS_URL, ''); ?>" target="_blank">Políticas de Privacidad</a>
            </label>
        </div>

        <button id="coimne-enrollment-submit" type="submit">
            <?php _e('Enviar', 'coimne-custom-content'); ?>
        </button>
        <span id="coimne-enrollment-loader" class="coimne-loader" style="display: none;"></span>
        <span id="coimne-enrollment-message"></span>
    </form>
</div>