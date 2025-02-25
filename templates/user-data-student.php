<?php if (!defined('ABSPATH')) exit; ?>

<div class="coimne-user-data">
    <h3><?php _e('Datos del Alumno', 'coimne-custom-content'); ?></h3>
    
    <?php if (!empty($user_data['nombre'])): ?>
        <p><strong><?php _e('Nombre:', 'coimne-custom-content'); ?></strong> <?php echo esc_html($user_data['nombre']); ?></p>
    <?php endif; ?>

    <?php if (!empty($user_data['apellido1']) || !empty($user_data['apellido2'])): ?>
        <p><strong><?php _e('Apellidos:', 'coimne-custom-content'); ?></strong> 
            <?php echo esc_html(trim(($user_data['apellido1'] ?? '') . ' ' . ($user_data['apellido2'] ?? ''))); ?>
        </p>
    <?php endif; ?>

    <?php if (!empty($user_data['nif'])): ?>
        <p><strong><?php _e('NIF:', 'coimne-custom-content'); ?></strong> <?php echo esc_html($user_data['nif']); ?></p>
    <?php endif; ?>

    <h4><?php _e('Contacto', 'coimne-custom-content'); ?></h4>
    
    <?php if (!empty($user_data['telefono'])): ?>
        <p><strong><?php _e('Teléfono:', 'coimne-custom-content'); ?></strong> <?php echo esc_html($user_data['telefono']); ?></p>
    <?php endif; ?>

    <?php if (!empty($user_data['movil'])): ?>
        <p><strong><?php _e('Móvil:', 'coimne-custom-content'); ?></strong> <?php echo esc_html($user_data['movil']); ?></p>
    <?php endif; ?>

    <?php if (!empty($user_data['email'])): ?>
        <p><strong><?php _e('E-Mail:', 'coimne-custom-content'); ?></strong> <?php echo esc_html($user_data['email']); ?></p>
    <?php endif; ?>
</div>