<?php
/**
 * Uninstall script for Dynamic Invert Cursor
 * 
 * This file is executed when the plugin is uninstalled via WordPress admin
 */

// Exit if uninstall not called from WordPress
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Clean up any plugin options (if we add settings in the future)
delete_option('dynamic_invert_cursor_settings');
delete_option('dynamic_invert_cursor_version');

// Clean up any transients
delete_transient('dynamic_invert_cursor_cache');

// For multisite installations
if (is_multisite()) {
    global $wpdb;
    $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
    
    foreach ($blog_ids as $blog_id) {
        switch_to_blog($blog_id);
        delete_option('dynamic_invert_cursor_settings');
        delete_option('dynamic_invert_cursor_version');
        delete_transient('dynamic_invert_cursor_cache');
        restore_current_blog();
    }
}