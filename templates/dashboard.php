<?php if (!defined('ABSPATH')) exit; ?>

<div class="coimne-dashboard-container">
    <div class="coimne-dashboard-menu">
        <?php echo do_shortcode('[coimne_dashboard_menu]'); ?>
    </div>

    <div class="coimne-dashboard-content">
        <div id="coimne-dynamic-content">
            <?php include 'dashboard-profile.php'; ?>
        </div>
    </div>
</div>