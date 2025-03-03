<?php if (!defined('ABSPATH')) exit; ?>

<div class="coimne-dashboard-info">
    <div>
        <span><?php echo esc_attr($user_profile['NAME']); ?></span>
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
            <div class="coimne-form-group">
                <label for="countries">País:</label>
                <select id="countries" name="PAI">
                    <option value="">Seleccionar país</option>
                    <?php foreach ($countries['data'] as $country): ?>
                        <option value="<?php echo esc_attr($country['ID']); ?>"
                            <?php selected($country['ID'], $user_profile['PAI']); ?>>
                            <?php echo esc_html($country['NAME']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="coimne-form-group">
                <label for="dir">Dirección:</label>
                <input type="text" id="dir" name="DIR" value="<?php echo esc_attr($user_profile['DIR']); ?>">
            </div>
            <div class="coimne-form-group">
                <label for="cps">Código Postal:</label>
                <input type="text" id="cps" name="CPS" value="<?php echo esc_attr($user_profile['CPS']); ?>">
            </div>
            <div class="coimne-form-group">
                <label for="provinces">Provincia:</label>
                <select id="provinces" name="PRO">
                    <option value="">Seleccionar provincia</option>
                    <?php foreach ($provinces['data'] as $province): ?>
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
                <select id="towns" name="POB">
                    <option value="">Seleccionar población</option>
                    <?php foreach ($towns['data'] as $town): ?>
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
                    value="<?php echo esc_attr($user_profile['TFN_MOV']); ?>">
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
                <input type="hidden" id="emp" name="EMP" value="<?php echo esc_attr($user_profile['EMP']['ID']); ?>">
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
        </fieldset>
    </form>
</div>