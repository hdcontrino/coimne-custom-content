<?php if (!defined('ABSPATH')) exit; ?>

<?php
$user_profile = (new Coimne_API)->get_user_profile();
?>

<div class="coimne-profile-container">
    <form id="profile-form" method="post">
        <input type="hidden" name="action" value="set_user_profile">

        <!-- Sección: Datos personales -->
        <fieldset>
            <legend>Datos Personales</legend>

            <div class="form-group">
                <label for="name">Nombre:</label>
                <input type="text" id="name" name="NAME" value="<?php echo esc_attr($user_profile['NAME']); ?>" required>
            </div>
            <div class="form-group">
                <label for="ape_1">Apellido 1:</label>
                <input type="text" id="ape_1" name="APE_1" value="<?php echo esc_attr($user_profile['APE_1']); ?>" required>
            </div>
            <div class="form-group">
                <label for="ape_2">Apellido 2:</label>
                <input type="text" id="ape_2" name="APE_2" value="<?php echo esc_attr($user_profile['APE_2']); ?>">
            </div>
            <div class="form-group">
                <label for="nif">NIF:</label>
                <input type="text" id="nif" name="NIF" value="<?php echo esc_attr($user_profile['NIF']); ?>">
            </div>
            <div class="form-group">
                <label for="nif">Fecha de nacimiento:</label>
                <input type="text" id="fch_nac" name="FCH_NAC" value="<?php echo esc_attr($user_profile['FCH_NAC']); ?>">
            </div>
        </fieldset>

        <!-- Sección: Datos de contacto -->
        <fieldset>
            <legend>Datos de Contacto</legend>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="EML" value="<?php echo esc_attr($user_profile['EML']); ?>">
            </div>
            <div class="form-group">
                <label for="phone">Teléfono:</label>
                <input type="text" id="phone" name="TFN" value="<?php echo esc_attr($user_profile['TFN']); ?>">
            </div>
            <div class="form-group">
                <label for="mobile">Móvil:</label>
                <input type="text" id="mobile" name="TFN_MOV" value="<?php echo esc_attr($user_profile['TFN_MOV']); ?>">
            </div>
        </fieldset>

        <!-- Sección: Información de empresa -->
        <fieldset>
            <legend>Información de Empresa</legend>
            <div class="form-group">
                <label for="empresa">Empresa:</label>
                <input type="text" id="empresa" name="EMP_NAME" value="<?php echo esc_attr($user_profile['EMP']['NAME']); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="empresa_nif">NIF Empresa:</label>
                <input type="text" id="empresa_nif" name="EMP_NIF" value="<?php echo esc_attr($user_profile['EMP']['NIF']); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="empresa_dir">Dirección:</label>
                <input type="text" id="empresa_dir" name="EMP_DIR" value="<?php echo esc_attr($user_profile['EMP']['DIR']); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="departamento">Departamento:</label>
                <select id="departamento" name="EMP_DEP">
                    <?php foreach ($user_profile['EMP']['EMP_DEP_COI_EMP_NOM_ON'] as $dep) : ?>
                        <option value="<?php echo esc_attr($dep['ID']); ?>" <?php selected($user_profile['EMP_DEP'], $dep['ID']); ?>>
                            <?php echo esc_html($dep['NAME']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </fieldset>

        <button type="submit">Guardar cambios</button>
    </form>
</div>