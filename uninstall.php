<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'includes/constants.php';

foreach (COIMNE_OPTIONS as $option) {
    delete_option($option);
}

if (is_multisite()) {
    global $wpdb;
    $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");

    foreach ($blog_ids as $blog_id) {
        foreach (COIMNE_OPTIONS as $option) {
            delete_blog_option($blog_id, $option);
        }
    }
}
