<?php
/**
 * Plugin Name: Dynamic Invert Cursor
 * Plugin URI: https://github.com/StefanusHosea/playground
 * Description: A custom cursor with invert effect that changes size dynamically based on element interactions
 * Version: 27.10.2025
 * Author: hello@stefanushosea.com
 * Author URI: https://github.com/StefanusHosea
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
    const VERSION = '27.10.2025';
    
    /**
     * Option name
     */
    const OPTION_NAME = 'dynamic_invert_cursor_settings';
    
    /**
     * Default settings
     */
    private $defaults = array(
        'enabled' => true,
        'cursor_size' => 15,
        'link_size' => 11.25,
        'button_size' => 9.75,
        'input_size' => 7.5,
        'smooth_enabled' => true,
        'smooth_speed' => 0.5,
    );
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('admin_menu', array($this, 'add_settings_page'));
        add_action('admin_init', array($this, 'register_settings'));
    }
    
    /**
     * Get plugin settings
     */
    private function get_settings() {
        $settings = get_option(self::OPTION_NAME, $this->defaults);
        return wp_parse_args($settings, $this->defaults);
    }
    
    /**
     * Enqueue plugin assets
     */
    public function enqueue_assets() {
        $settings = $this->get_settings();
        
        // Check if enabled
        if (!$settings['enabled']) {
            return;
        }
        
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
        
        // Add inline CSS for custom sizes
        $custom_css = "
            .custom-cursor {
                width: {$settings['cursor_size']}px;
                height: {$settings['cursor_size']}px;
            }
            .custom-cursor.hover-link {
                width: {$settings['link_size']}px;
                height: {$settings['link_size']}px;
            }
            .custom-cursor.hover-button {
                width: {$settings['button_size']}px;
                height: {$settings['button_size']}px;
            }
            .custom-cursor.hover-input {
                width: {$settings['input_size']}px;
                height: {$settings['input_size']}px;
            }
        ";
        wp_add_inline_style('dynamic-invert-cursor-style', $custom_css);
        
        // Enqueue JavaScript
        wp_enqueue_script(
            'dynamic-invert-cursor-script',
            plugin_dir_url(__FILE__) . 'assets/js/cursor.js',
            array(),
            self::VERSION,
            true
        );
        
        // Pass settings to JavaScript
        wp_localize_script('dynamic-invert-cursor-script', 'dicSettings', array(
            'cursorSize' => floatval($settings['cursor_size']),
            'linkSize' => floatval($settings['link_size']),
            'buttonSize' => floatval($settings['button_size']),
            'inputSize' => floatval($settings['input_size']),
            'smoothEnabled' => (bool)$settings['smooth_enabled'],
            'smoothSpeed' => floatval($settings['smooth_speed']),
        ));
    }
    
    /**
     * Add settings page to admin menu
     */
    public function add_settings_page() {
        add_options_page(
            'Dynamic Invert Cursor Settings',
            'Invert Cursor',
            'manage_options',
            'dynamic-invert-cursor',
            array($this, 'render_settings_page')
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        register_setting(
            'dynamic_invert_cursor_group',
            self::OPTION_NAME,
            array($this, 'sanitize_settings')
        );
    }
    
    /**
     * Sanitize settings
     */
    public function sanitize_settings($input) {
        $sanitized = array();
        
        $sanitized['enabled'] = isset($input['enabled']) ? true : false;
        $sanitized['cursor_size'] = isset($input['cursor_size']) ? absint($input['cursor_size']) : $this->defaults['cursor_size'];
        $sanitized['link_size'] = isset($input['link_size']) ? absint($input['link_size']) : $this->defaults['link_size'];
        $sanitized['button_size'] = isset($input['button_size']) ? absint($input['button_size']) : $this->defaults['button_size'];
        $sanitized['input_size'] = isset($input['input_size']) ? absint($input['input_size']) : $this->defaults['input_size'];
        $sanitized['smooth_enabled'] = isset($input['smooth_enabled']) ? true : false;
        $sanitized['smooth_speed'] = isset($input['smooth_speed']) ? floatval($input['smooth_speed']) : $this->defaults['smooth_speed'];
        
        // Ensure smooth speed is within reasonable range
        if ($sanitized['smooth_speed'] < 0.01) {
            $sanitized['smooth_speed'] = 0.01;
        } elseif ($sanitized['smooth_speed'] > 1) {
            $sanitized['smooth_speed'] = 1;
        }
        
        return $sanitized;
    }
    
    /**
     * Render settings page
     */
    public function render_settings_page() {
        $settings = $this->get_settings();
        ?>
        <div class="wrap">
            <h1>Dynamic Invert Cursor Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('dynamic_invert_cursor_group');
                ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">Enable Cursor</th>
                        <td>
                            <label>
                                <input type="checkbox" name="<?php echo self::OPTION_NAME; ?>[enabled]" value="1" <?php checked($settings['enabled'], true); ?>>
                                Enable custom cursor effect
                            </label>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row" colspan="2">
                            <h2>Cursor Sizes (pixels)</h2>
                        </th>
                    </tr>
                    
                    <tr>
                        <th scope="row">Idle Cursor Size</th>
                        <td>
                            <input type="number" name="<?php echo self::OPTION_NAME; ?>[cursor_size]" value="<?php echo esc_attr($settings['cursor_size']); ?>" min="5" max="100" step="1">
                            <p class="description">Default cursor size when not hovering any element (default: 15px)</p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">Link Hover Size</th>
                        <td>
                            <input type="number" name="<?php echo self::OPTION_NAME; ?>[link_size]" value="<?php echo esc_attr($settings['link_size']); ?>" min="5" max="100" step="0.5">
                            <p class="description">Cursor size when hovering links (default: 11.25px - 25% smaller)</p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">Button Hover Size</th>
                        <td>
                            <input type="number" name="<?php echo self::OPTION_NAME; ?>[button_size]" value="<?php echo esc_attr($settings['button_size']); ?>" min="5" max="100" step="0.5">
                            <p class="description">Cursor size when hovering buttons (default: 9.75px - 35% smaller)</p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">Input Hover Size</th>
                        <td>
                            <input type="number" name="<?php echo self::OPTION_NAME; ?>[input_size]" value="<?php echo esc_attr($settings['input_size']); ?>" min="5" max="100" step="0.5">
                            <p class="description">Cursor size when hovering input fields (default: 7.5px - 50% smaller)</p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row" colspan="2">
                            <h2>Smooth Tracking</h2>
                        </th>
                    </tr>
                    
                    <tr>
                        <th scope="row">Enable Smooth Tracking</th>
                        <td>
                            <label>
                                <input type="checkbox" name="<?php echo self::OPTION_NAME; ?>[smooth_enabled]" value="1" <?php checked($settings['smooth_enabled'], true); ?>>
                                Enable smooth following effect
                            </label>
                            <p class="description">When disabled, cursor will follow mouse position instantly</p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">Smooth Tracking Speed</th>
                        <td>
                            <input type="number" name="<?php echo self::OPTION_NAME; ?>[smooth_speed]" value="<?php echo esc_attr($settings['smooth_speed']); ?>" min="0.01" max="1" step="0.01">
                            <p class="description">Higher value = faster tracking. Range: 0.01 to 1 (default: 0.5)</p>
                            <p class="description"><strong>Recommended values:</strong> Slow: 0.08 | Medium: 0.15 | Fast: 0.3 | Instant: 1</p>
                        </td>
                    </tr>
                </table>
                
                <?php submit_button(); ?>
            </form>
        </div>
        
        <style>
            .form-table h2 {
                margin: 0;
                padding: 0;
                font-size: 1.1em;
            }
        </style>
        <?php
    }
}

// Initialize the plugin
new Dynamic_Invert_Cursor();
