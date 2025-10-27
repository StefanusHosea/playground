<?php
/**
 * Plugin Name: Dynamic Invert Cursor
 * Plugin URI: https://github.com/StefanusHosea/playground
 * Description: A custom cursor with invert effect that changes size dynamically based on element interactions
 * Version: 1.0.0
 * Author: StefanusHosea
 * Author URI: https://github.com/StefanusHosea
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: dynamic-invert-cursor
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Dynamic_Invert_Cursor {
    
    /**
     * Plugin version
     */
    const VERSION = '1.0.0';
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
    }
    
    /**
     * Enqueue plugin assets
     */
    public function enqueue_assets() {
        // Don't load on mobile devices
        if (wp_is_mobile()) {
            return;
        }
        
        // Enqueue CSS
        wp_enqueue_style(
            'dynamic-invert-cursor-style',
            plugin_dir_url(__FILE__) . 'assets/css/cursor.css',
            array(),
            self::VERSION,
            'all'
        );
        
        // Enqueue JavaScript
        wp_enqueue_script(
            'dynamic-invert-cursor-script',
            plugin_dir_url(__FILE__) . 'assets/js/cursor.js',
            array(),
            self::VERSION,
            true
        );
    }
}

// Initialize the plugin
new Dynamic_Invert_Cursor();
