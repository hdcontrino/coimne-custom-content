<?php if (!defined('ABSPATH')) exit; ?>

<?php
$fch_nac = Coimne_Helper::format_date_to_input($user_profile['FCH_NAC'] ?? '');
$fch_tit = Coimne_Helper::format_date_to_input($user_profile['TIT_FCH'] ?? '');
$emp_dep = $user_profile['EMP']['EMP_DEP_COI_EMP_NOM_ON'];
$escuelas = $api->get_escuelas();
?>

<div class="coimne-dashboard-info">
    <div>
        <span>Colegiado Nº<?php echo esc_attr($user_profile['NUM_COL']); ?></span>
        <span>&nbsp;<?php echo esc_attr($user_profile['NAME']); ?></span>
        <span>&nbsp;<?php echo esc_attr($user_profile['APE_1']); ?></span>
        <span>&nbsp;<?php echo esc_attr($user_profile['APE_2']); ?></span>
    </div>
    <div>
        <span>NIF:</span>
        <span><?php echo esc_attr($user_profile['NIF']); ?></span>
    </div>
</div>

<div class="coimne-tabs">
    <ul class="tab-menu">
        <li class="tab-link active" data-tab="tab1">Dirección y Contacto</li>
        <li class="tab-link" data-tab="tab2">Datos Personales</li>
        <li class="tab-link" data-tab="tab3">Formación Académica</li>
        <li class="tab-link" data-tab="tab4">Empresa</li>
        <li class="tab-link" data-tab="tab5">Datos de Pago</li>
    </ul>
</div>

<div class="coimne-profile-container">
    <form id="profile-form" method="post">
        <input type="hidden" name="action" value="set_user_profile">

        <fieldset id="tab1" class="tab-pane active">
            <div class="coimne-form-row">
                <div class="coimne-form-group grown">
                    <label for="dir">Dirección:</label>
                    <input type="text" id="dir" name="DIR" value="<?php echo esc_attr($user_profile['DIR']); ?>" required>
                </div>
                <div class="coimne-form-group w1/3">
                    <label for="cps">Código Postal:</label>
                    <input type="text" id="cps" name="CPS" value="<?php echo esc_attr($user_profile['CPS']); ?>" required>
                </div>
            </div>
            <div class="coimne-form-row">
                <div class="coimne-form-group">
                    <label for="countries">País:</label>
                    <select id="countries" name="PAI" required>
                        <option value="">Seleccionar país</option>
                        <?php foreach ($countries['data'] as $country): ?>
                            <option value="<?php echo esc_attr($country['ID']); ?>"
                                <?php selected($country['ID'], $user_profile['PAI'] ?? 0); ?>>
                                <?php echo esc_html($country['NAME']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="coimne-form-group">
                    <label for="provinces">Provincia:</label>
                    <select id="provinces" name="PRO" required>
                        <option value="">Seleccionar provincia</option>
                        <?php foreach ($provinces['data'] as $province): ?>
                            <option value="<?php echo esc_attr($province['ID']); ?>"
                                <?php selected($province['ID'], $user_profile['PRO'] ?? 0); ?>>
                                <?php echo esc_html($province['NAME']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="coimne-loader"></span>
                </div>
                <div class="coimne-form-group">
                    <label for="towns">Municipio:</label>
                    <select id="towns" name="POB" required>
                        <option value="">Seleccionar municipio</option>
                        <?php foreach ($towns['data'] as $town): ?>
                            <option value="<?php echo esc_attr($town['ID']); ?>"
                                <?php selected($town['ID'], $user_profile['POB'] ?? 0); ?>>
                                <?php echo esc_html($town['NAME']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="coimne-loader"></span>
                </div>
            </div>
            <div class="coimne-form-row">
                <div class="coimne-form-group">
                    <label for="loc">Localidad:</label>
                    <input type="text" id="loc" name="LOC" value="<?php echo esc_attr($user_profile['LOC']); ?>">
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
            </div>
            <div class="coimne-form-group" style="display: none;">
                <label for="email">Email:</label>
                <input type="email" id="email" name="EML"
                    value="<?php echo esc_attr($user_profile['EML']); ?>">
            </div>
        </fieldset>

        <fieldset id="tab2" class="tab-pane">
            <div class="coimne-form-row">
                <div class="coimne-form-group">
                    <label for="fch_nac">Fecha de Nacimiento:</label>
                    <input type="date" id="fch_nac" name="FCH_NAC" value="<?php echo esc_attr($fch_nac); ?>" required>
                </div>
                <div class="coimne-form-group">
                    <label for="lug_nac">Lugar de nacimiento:</label>
                    <input type="text" id="lug_nac" name="LUG_NAC" value="<?php echo esc_attr($user_profile['LUG_NAC']); ?>">
                </div>
                <div class="coimne-form-group">
                    <label for="nac">Nacionalidad:</label>
                    <select id="nac" name="NAC" required>
                        <option value="">Seleccionar nacionalidad</option>
                        <?php foreach ($countries['data'] as $country): ?>
                            <option value="<?php echo esc_attr($country['ID']); ?>"
                                <?php selected($country['ID'], $user_profile['NAC'] ?? 0); ?>>
                                <?php echo esc_html($country['NAC']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="coimne-form-row">
                <div class="coimne-form-group">
                    <label for="sit_lab">Situación Laboral:</label>
                    <select id="sit_lab" name="SIT_LAB" required>
                        <option value="">Seleccionar ...</option>
                        <?php foreach (SIT_LAB as $key => $val): ?>
                            <option value="<?php echo esc_attr($key); ?>"
                                <?php selected($key, $user_profile['SIT_LAB'] ?? 0); ?>>
                                <?php echo esc_html($val); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="coimne-form-group">
                    <label for="est_civ">Estado civil:</label>
                    <select id="est_civ" name="EST_CIV" required>
                        <option value="">Seleccionar ...</option>
                        <?php foreach (EST_CIV as $key => $val): ?>
                            <option value="<?php echo esc_attr($key); ?>"
                                <?php selected($key, $user_profile['EST_CIV'] ?? 0); ?>>
                                <?php echo esc_html($val); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="coimne-subtitle">Cónyuge</div>
            <div class="coimne-form-row">
                <div class="coimne-form-group">
                    <label for="con_nom">Nombre:</label>
                    <input type="text" id="con_nom" name="CON_NOM" value="<?php echo esc_attr($user_profile['CON_NOM']); ?>">
                </div>
            </div>
            <div class="coimne-form-row">
                <div class="coimne-form-group">
                    <label for="con_ape_1">Apellido 1:</label>
                    <input type="text" id="con_ape_1" name="CON_APE_1" value="<?php echo esc_attr($user_profile['CON_APE_1']); ?>">
                </div>
                <div class="coimne-form-group">
                    <label for="con_ape_2">Apellido 2:</label>
                    <input type="text" id="con_ape_2" name="CON_APE_2" value="<?php echo esc_attr($user_profile['CON_APE_2']); ?>">
                </div>
            </div>
        </fieldset>

        <fieldset id="tab3" class="tab-pane">
            <div class="coimne-form-group">
                <label for="tit">Titulación:</label>
                <select id="tit" name="TIT" required>
                    <option value="">Seleccionar ...</option>
                    <?php foreach (TIT as $key => $val): ?>
                        <option value="<?php echo esc_attr($key); ?>"
                            <?php selected($key, $user_profile['TIT'] ?? 0); ?>>
                            <?php echo esc_html($val); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="coimne-form-group">
                <label for="tit_fch">Fecha de titulación:</label>
                <input type="date" id="tit_fch" name="TIT_FCH" value="<?php echo esc_attr($fch_tit); ?>">
            </div>
            <div class="coimne-form-group">
                <label for="esc_min">Escuela de titulación:</label>
                <select id="esc_min" name="ESC_MIN">
                    <option value="">Seleccionar ...</option>
                    <?php foreach ($escuelas['data'] as $escuela): ?>
                        <option value="<?php echo esc_attr($escuela['ID']); ?>"
                            <?php selected($escuela['ID'], $user_profile['ESC_MIN'] ?? 0); ?>>
                            <?php echo esc_html($escuela['NAME']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="coimne-form-group">
                <label for="ts_prl">Tec. Sup. Prev. Riesgos Laborales:</label>
                <select id="ts_prl" name="TS_PRL">
                    <option value="">Seleccionar ...</option>
                    <?php foreach (TS_PRL as $key => $val): ?>
                        <option value="<?php echo esc_attr($key); ?>"
                            <?php selected($key, $user_profile['TS_PRL'] ?? 0); ?>>
                            <?php echo esc_html($val); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </fieldset>

        <fieldset id="tab4" class="tab-pane">
            <div class="coimne-form-group">
                <label for="empresa">Empresa:</label>
                <select id="empresa" name="EMP">
                    <option value="<?php echo esc_attr($user_profile['EMP']['ID']); ?>" selected>
                        <?php echo esc_html($user_profile['EMP']['NAME']); ?>
                    </option>
                </select>
            </div>
            <div class="coimne-form-group">
                <label for="departamento">Departamento:</label>
                <select id="departamento" name="EMP_DEP">
                    <option value="">Seleccionar ...</option>
                    <?php foreach ($emp_dep as $dep): ?>
                        <option value="<?php echo esc_attr($dep['ID']); ?>"
                            <?php selected($dep['ID'], $user_profile['EMP_DEP'] ?? 0); ?>>
                            <?php echo esc_html($dep['NAME']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="coimne-form-group">
                <label for="emp_cgo">Cargo:</label>
                <input type="text" id="emp_cgo" name="EMP_CGO" value="<?php echo esc_attr($user_profile['EMP_CGO']); ?>">
            </div>
            <div class="coimne-form-row">
                <div class="coimne-form-group">
                    <label for="emp_dir">Dirección:</label>
                    <input type="text" id="emp_dir" name="EMP_DIR" readonly
                        value="<?php echo esc_attr($user_profile['EMP']['DIR']); ?>">
                </div>
                <div class="coimne-form-group w1/3">
                    <label for="emp_cps">Código Postal:</label>
                    <input type="text" id="emp_cps" name="EMP_CPS" readonly
                        value="<?php echo esc_attr($user_profile['EMP']['CPS']); ?>">
                </div>
            </div>
            <div class="coimne-form-row">
                <div class="coimne-form-group">
                    <label for="emp_pai">País:</label>
                    <select id="emp_pai" name="EMP_PAI" disabled>
                        <option value="">Seleccionar país</option>
                        <?php foreach ($countries['data'] as $country): ?>
                            <option value="<?php echo esc_attr($country['ID']); ?>"
                                <?php selected($country['ID'], $user_profile['EMP']['PAI'] ?? 0); ?>>
                                <?php echo esc_html($country['NAME']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="coimne-form-group">
                    <label for="emp_pro">Provincia:</label>
                    <select id="emp_pro" name="EMP_PRO" disabled>
                        <option value="">Seleccionar provincia</option>
                        <?php foreach ($provinces['data'] as $province): ?>
                            <option value="<?php echo esc_attr($province['ID']); ?>"
                                <?php selected($province['ID'], $user_profile['EMP']['PRO'] ?? 0); ?>>
                                <?php echo esc_html($province['NAME']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="coimne-loader"></span>
                </div>
                <div class="coimne-form-group">
                    <label for="emp_pob">Municipio:</label>
                    <select id="emp_pob" name="EMP_POB" disabled>
                        <option value="">Seleccionar municipio</option>
                        <?php foreach ($towns['data'] as $town): ?>
                            <option value="<?php echo esc_attr($town['ID']); ?>"
                                <?php selected($town['ID'], $user_profile['EMP']['POB'] ?? 0); ?>>
                                <?php echo esc_html($town['NAME']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="coimne-loader"></span>
                </div>
            </div>
            <div class="coimne-form-row">
                <div class="coimne-form-group">
                    <label for="emp_loc">Localidad:</label>
                    <input type="text" id="emp_loc" name="EMP_LOC" readonly
                        value="<?php echo esc_attr($user_profile['EMP']['LOC']); ?>">
                </div>
                <div class="coimne-form-group">
                    <label for="emp_tfn">Teléfono:</label>
                    <input type="text" id="emp_tfn" name="EMP_TFN" readonly
                        value="<?php echo esc_attr($user_profile['EMP']['TFN']); ?>">
                </div>
                <div class="coimne-form-group">
                    <label for="emp_tfn_mov">Móvil:</label>
                    <input type="text" id="emp_tfn_mov" name="EMP_TFN_MOV" readonly
                        value="<?php echo esc_attr($user_profile['EMP']['TFN_MOV']); ?>">
                </div>
            </div>
            <div class="coimne-form-group">
                <label for="emp_eml">E-Mail:</label>
                <input type="text" id="emp_eml" name="EMP_EML" readonly
                    value="<?php echo esc_attr($user_profile['EMP']['EML']); ?>">
            </div>
        </fieldset>

        <fieldset id="tab5" class="tab-pane">
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
</div>