<?php if (!defined('ABSPATH')) exit; ?>

<?php
$emp_dep = $user_profile['EMP_COI']['EMP_DEP_COI_EMP_NOM_ON'];
$empresas = $api->get_empresas();
?>

<div class="coimne-dashboard-info">
    <div>
        <span><?php echo esc_attr($user_profile['COL_MIN']); ?>,</span>
        <span>Colegiado Nº<?php echo esc_attr($user_profile['NUM_COL']); ?></span>
        <br>
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
        <li class="tab-link" data-tab="tab2">Laboral</li>
    </ul>
</div>

<div class="coimne-profile-container">
    <form id="profile-form" method="post">
        <input type="hidden" name="action" value="set_user_profile">

        <fieldset id="tab1" class="tab-pane active">
            <div class="coimne-form-row">
                <div class="coimne-form-group">
                    <label for="dir">Dirección:</label>
                    <input type="text" id="dir" name="DIR" value="<?php echo esc_attr($user_profile['DIR']); ?>">
                </div>
                <div class="coimne-form-group w1/3">
                    <label for="cps">Código Postal:</label>
                    <input type="text" id="cps" name="CPS" value="<?php echo esc_attr($user_profile['CPS']); ?>">
                </div>
            </div>
            <div class="coimne-form-row">
                <div class="coimne-form-group">
                    <label for="countries">País:</label>
                    <select id="countries" name="PAI">
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
                    <select id="provinces" name="PRO">
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
                    <select id="towns" name="POB">
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
                        value="<?php echo esc_attr($user_profile['TFN_MOV']); ?>">
                </div>
            </div>
            <div class="coimne-form-group" style="display: none;">
                <label for="email">Email:</label>
                <input type="email" id="email" name="EML"
                    value="<?php echo esc_attr($user_profile['EML']); ?>">
            </div>
        </fieldset>

        <fieldset id="tab2" class="tab-pane">
            <div class="coimne-form-group">
                <label for="empresa">Empresa:</label>
                <select id="empresa" name="EMP">
                    <option value="<?php echo esc_attr($user_profile['EMP_COI']['ID']); ?>" selected>
                        <?php echo esc_html($user_profile['EMP_COI']['NAME']); ?>
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
        </fieldset>

        <button id="coimne-profile-submit" type="submit">Guardar cambios</button>
        <span id="coimne-profile-loader" class="coimne-loader" style="display: none;"></span>
        <span id="coimne-profile-message"></span>
    </form>
</div>