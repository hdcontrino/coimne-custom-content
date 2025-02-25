<?php if (!defined('ABSPATH')) exit; ?>

<div class="coimne-user-data">
    <h3><?php _e('Datos del Colegiado', 'coimne-custom-content'); ?></h3>
    
    <?php if (!empty($user_profile['NUM_COL'])): ?>
        <p><strong><?php _e('Número de Colegiado:', 'coimne-custom-content'); ?></strong> <?php echo esc_html($user_profile['NUM_COL']); ?></p>
    <?php endif; ?>

    <?php if (!empty($user_profile['NAME'])): ?>
        <p><strong><?php _e('Nombre:', 'coimne-custom-content'); ?></strong> <?php echo esc_html($user_profile['NAME']); ?></p>
    <?php endif; ?>

    <?php if (!empty($user_profile['APE_1']) || !empty($user_profile['APE_2'])): ?>
        <p><strong><?php _e('Apellidos:', 'coimne-custom-content'); ?></strong> 
            <?php echo esc_html(trim(($user_profile['APE_1'] ?? '') . ' ' . ($user_profile['APE_2'] ?? ''))); ?>
        </p>
    <?php endif; ?>

    <?php if (!empty($user_profile['NIF'])): ?>
        <p><strong><?php _e('NIF:', 'coimne-custom-content'); ?></strong> <?php echo esc_html($user_profile['NIF']); ?></p>
    <?php endif; ?>
</div>