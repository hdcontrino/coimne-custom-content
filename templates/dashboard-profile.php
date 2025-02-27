<?php if (!defined('ABSPATH')) exit; ?>

<?php
$api = new Coimne_API();
$user_profile = $api->get_user_profile();
$countries = $api->get_countries();
$provinces = $api->get_provinces($user_profile['PAI']);
$towns = $api->get_towns($user_profile['PAI'], $user_profile['PRO']);
$fch_nac = Coimne_Helper::format_date_to_input($user_profile['FCH_NAC'] ?? '');
$emp_dep = $user_profile['EMP']['EMP_DEP_COI_EMP_NOM_ON'];
?>

<div class="coimne-profile-container">
    <form id="profile-form" method="post">
        <input type="hidden" name="action" value="set_user_profile">

        <fieldset>
            <legend>Colegiado</legend>
            <div class="info-group">
                <span class="label">Número colegiado:</span>
                <span class="value"><?php echo esc_attr($user_profile['NUM_COL']); ?></span>
            </div>
            <div class="info-group">
                <span class="label">Nombre:</span>
                <span class="value"><?php echo esc_attr($user_profile['NAME']); ?></span>
            </div>
            <div class="info-group">
                <span class="label">Apellido 1:</span>
                <span class="value"><?php echo esc_attr($user_profile['APE_1']); ?></span>
                <span class="label">Apellido 2:</span>
                <span class="value"><?php echo esc_attr($user_profile['APE_2']); ?></span>
            </div>
            <div class="info-group">
                <span class="label">NIF:</span>
                <span class="value"><?php echo esc_attr($user_profile['NIF']); ?></span>
            </div>
        </fieldset>

        <fieldset>
            <legend>Dirección y Contacto</legend>
            <div class="coimne-form-group">
                <label for="countries">País:</label>
                <select id="countries" name="PAI" required>
                    <option value="">Seleccionar país</option>
                    <?php foreach ($countries as $country): ?>
                        <option value="<?php echo esc_attr($country['ID']); ?>"
                            <?php selected($country['ID'], $user_profile['PAI']); ?>>
                            <?php echo esc_html($country['NAME']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="coimne-form-group">
                <label for="dir">Dirección:</label>
                <input type="text" id="dir" name="DIR" value="<?php echo esc_attr($user_profile['DIR']); ?>" required>
            </div>
            <div class="coimne-form-group">
                <label for="cps">Código Postal:</label>
                <input type="text" id="cps" name="CPS" value="<?php echo esc_attr($user_profile['CPS']); ?>" required>
            </div>
            <div class="coimne-form-group">
                <label for="provinces">Provincia:</label>
                <select id="provinces" name="PRO" required>
                    <option value="">Seleccionar provincia</option>
                    <?php foreach ($provinces as $province): ?>
                        <option value="<?php echo esc_attr($province['ID']); ?>"
                            <?php selected($province['ID'], $user_profile['PRO']); ?>>
                            <?php echo esc_html($province['NAME']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="coimne-loader"></span>
            </div>
            <div class="coimne-form-group">
                <label for="towns">Población:</label>
                <select id="towns" name="POB" required>
                    <option value="">Seleccionar población</option>
                    <?php foreach ($towns as $town): ?>
                        <option value="<?php echo esc_attr($town['ID']); ?>"
                            <?php selected($town['ID'], $user_profile['POB']); ?>>
                            <?php echo esc_html($town['NAME']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="coimne-loader"></span>
            </div>
            <div class="coimne-form-group">
                <label for="phone">Teléfono:</label>
                <input type="text" id="phone" name="TFN" value="<?php echo esc_attr($user_profile['TFN']); ?>">
            </div>
            <div class="coimne-form-group">
                <label for="mobile">Móvil:</label>
                <input type="text" id="mobile" name="TFN_MOV"
                    value="<?php echo esc_attr($user_profile['TFN_MOV']); ?>" required>
            </div>
            <div class="coimne-form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="EML"
                    value="<?php echo esc_attr($user_profile['EML']); ?>" required>
            </div>
        </fieldset>

        <fieldset>
            <legend>Datos personales</legend>
            <div class="coimne-form-group">
                <label for="fch_nac">Fecha de Nacimiento:</label>
                <input type="date" id="fch_nac" name="FCH_NAC" value="<?php echo esc_attr($fch_nac); ?>" required>
            </div>
            <div class="coimne-form-group">
                <label for="lug_nac">Lugar de nacimiento:</label>
                <input type="text" id="lug_nac" name="LUG_NAC" value="<?php echo esc_attr($user_profile['LUG_NAC']); ?>">
            </div>
            <div class="coimne-form-group">
                <label for="nac_name">Nacionalidad:</label>
                <input type="text" id="nac_name" name="NAC_NAME" required readonly
                    value="<?php echo esc_attr($user_profile['NAC_NAME']); ?>">
            </div>
            <div class="coimne-form-group">
                <label for="sit_lab_name">Situación Laboral:</label>
                <input type="text" id="sit_lab_name" name="SIT_LAB_NAME" readonly
                    value="<?php echo esc_attr($user_profile['SIT_LAB_NAME']); ?>">
            </div>
            <div class="coimne-form-group">
                <label for="est_civ_name">Estado civil:</label>
                <input type="text" id="est_civ_name" name="EST_CIV_NAME" readonly
                    value="<?php echo esc_attr($user_profile['EST_CIV_NAME']); ?>">
            </div>
        </fieldset>

        <fieldset>
            <legend>Cónyuge</legend>
            <div class="coimne-form-group">
                <label for="con_nom">Nombre:</label>
                <input type="text" id="con_nom" name="CON_NOM" value="<?php echo esc_attr($user_profile['CON_NOM']); ?>">
            </div>
            <div class="coimne-form-group">
                <label for="con_ape_1">Apellido 1:</label>
                <input type="text" id="con_ape_1" name="CON_APE_1" value="<?php echo esc_attr($user_profile['CON_APE_1']); ?>">
            </div>
            <div class="coimne-form-group">
                <label for="con_ape_2">Apellido 2:</label>
                <input type="text" id="con_ape_2" name="CON_APE_2" value="<?php echo esc_attr($user_profile['CON_APE_2']); ?>">
            </div>
        </fieldset>

        <fieldset>
            <legend>Formación académica</legend>
            <div class="coimne-form-group">
                <label for="tit_name">Titulación:</label>
                <input type="text" id="tit_name" name="TIT_NAME" required
                    value="<?php echo esc_attr($user_profile['TIT_NAME']); ?>">
            </div>
            <div class="coimne-form-group">
                <label for="tit_fch">Fecha de titulación:</label>
                <input type="text" id="tit_fch" name="TIT_FCH" value="<?php echo esc_attr($user_profile['TIT_FCH']); ?>">
            </div>
            <div class="coimne-form-group">
                <label for="esc_min_name">Escuela de titulación:</label>
                <input type="text" id="esc_min_name" name="ESC_MIN_NAME" value="<?php echo esc_attr($user_profile['ESC_MIN_NAME']); ?>">
            </div>
            <div class="coimne-form-group">
                <label for="ts_prl_name">Tec. Sup. Prev. Riesgos Laborales:</label>
                <input type="text" id="ts_prl_name" name="TS_PRL_NAME" value="<?php echo esc_attr($user_profile['TS_PRL_NAME']); ?>">
            </div>
        </fieldset>

        <fieldset>
            <legend>Empresa</legend>
            <div class="coimne-form-group">
                <label for="empresa">Empresa:</label>
                <input type="text" id="empresa" name="EMP_NAME" value="<?php echo esc_attr($user_profile['EMP']['NAME']); ?>">
            </div>
            <div class="coimne-form-group">
                <label for="departamento">Departamento:</label>
                <select id="departamento" name="EMP_DEP">
                    <?php foreach ($emp_dep as $dep): ?>
                        <option value="<?php echo esc_attr($dep['ID']); ?>"
                            <?php selected($dep['ID'], $user_profile['EMP_DEP']); ?>>
                            <?php echo esc_html($dep['NAME']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="coimne-form-group">
                <label for="emp_cgo">Cargo:</label>
                <input type="text" id="emp_cgo" name="EMP_CGO" value="<?php echo esc_attr($user_profile['EMP_CGO']); ?>">
            </div>
            <div class="coimne-form-group">
                <label for="emp_pai">País:</label>
                <select id="emp_pai" name="EMP_PAI">
                    <option value="">Seleccionar país</option>
                    <?php foreach ($countries as $country): ?>
                        <option value="<?php echo esc_attr($country['ID']); ?>"
                            <?php selected($country['ID'], $user_profile['EMP']['PAI']); ?>>
                            <?php echo esc_html($country['NAME']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="coimne-form-group">
                <label for="emp_dir">Dirección:</label>
                <input type="text" id="emp_dir" name="EMP_DIR" value="<?php echo esc_attr($user_profile['EMP']['DIR']); ?>">
            </div>
            <div class="coimne-form-group">
                <label for="emp_cps">Código Postal:</label>
                <input type="text" id="emp_cps" name="EMP_CPS" value="<?php echo esc_attr($user_profile['EMP']['CPS']); ?>">
            </div>
            <div class="coimne-form-group">
                <label for="emp_pro">Provincia:</label>
                <select id="emp_pro" name="EMP_PRO">
                    <option value="">Seleccionar provincia</option>
                    <?php foreach ($provinces as $province): ?>
                        <option value="<?php echo esc_attr($province['ID']); ?>"
                            <?php selected($province['ID'], $user_profile['EMP']['PRO']); ?>>
                            <?php echo esc_html($province['NAME']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="coimne-loader"></span>
            </div>
            <div class="coimne-form-group">
                <label for="emp_pob">Población:</label>
                <select id="emp_pob" name="EMP_POB">
                    <option value="">Seleccionar población</option>
                    <?php foreach ($towns as $town): ?>
                        <option value="<?php echo esc_attr($town['ID']); ?>"
                            <?php selected($town['ID'], $user_profile['EMP']['POB']); ?>>
                            <?php echo esc_html($town['NAME']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="coimne-loader"></span>
            </div>
            <div class="coimne-form-group">
                <label for="emp_tfn">Teléfono:</label>
                <input type="text" id="emp_tfn" name="EMP_TFN" value="<?php echo esc_attr($user_profile['EMP']['TFN']); ?>">
            </div>
            <div class="coimne-form-group">
                <label for="emp_tfn_mov">Móvil:</label>
                <input type="text" id="emp_tfn_mov" name="EMP_TFN_MOV"
                    value="<?php echo esc_attr($user_profile['EMP']['TFN_MOV']); ?>">
            </div>
            <div class="coimne-form-group">
                <label for="emp_eml">E-Mail:</label>
                <input type="text" id="emp_eml" name="EMP_EML"
                    value="<?php echo esc_attr($user_profile['EMP']['EML']); ?>">
            </div>
        </fieldset>

        <fieldset>
            <legend>Datos de Pago</legend>
            <div class="coimne-form-group">
                <label for="iban">IBAN:</label>
                <input type="text" id="iban" name="IBAN"
                    value="<?php echo esc_attr($user_profile['IBAN']); ?>" required>
            </div>
            <div class="coimne-form-group">
                <label for="swift_bic">SWIFT/BIC:</label>
                <input type="text" id="swift_bic" name="SWIFT_BIC"
                    value="<?php echo esc_attr($user_profile['SWIFT_BIC']); ?>">
            </div>
        </fieldset>

        <button id="coimne-profile-submit" type="submit">Guardar cambios</button>
        <span id="coimne-profile-loader" class="coimne-loader" style="display: none;"></span>
        <span id="coimne-profile-message"></span>
    </form>
</div>