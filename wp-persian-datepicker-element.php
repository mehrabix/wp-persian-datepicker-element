<?php
/**
 * Plugin Name: WP Persian Datepicker Element
 * Plugin URI: https://github.com/yourusername/wp-persian-datepicker-element
 * Description: A modern, customizable Persian (Jalali) date picker web component for WordPress
 * Version: 1.0.0
 * Author: Ahmad Mehrabi
 * Author URI: https://github.com/yourusername
 * Text Domain: wp-persian-datepicker-element
 * Domain Path: /languages
 * License: MIT
 */

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 */
define('PERSIAN_DATEPICKER_VERSION', '1.0.0');
define('PERSIAN_DATEPICKER_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PERSIAN_DATEPICKER_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Load plugin textdomain.
 */
function wp_persian_datepicker_load_textdomain() {
    load_plugin_textdomain(
        'wp-persian-datepicker-element',
        false,
        dirname(plugin_basename(__FILE__)) . '/languages/'
    );
}
add_action('plugins_loaded', 'wp_persian_datepicker_load_textdomain');

/**
 * The core plugin class.
 */
class WP_Persian_Datepicker_Element {

    /**
     * Initialize the plugin.
     */
    public function __construct() {
        $this->load_dependencies();
        $this->define_hooks();
    }

    /**
     * Load required dependencies.
     */
    private function load_dependencies() {
        // Include files
        require_once PERSIAN_DATEPICKER_PLUGIN_DIR . 'includes/class-wp-persian-datepicker-loader.php';
        require_once PERSIAN_DATEPICKER_PLUGIN_DIR . 'includes/class-wp-persian-datepicker-scripts.php';
        require_once PERSIAN_DATEPICKER_PLUGIN_DIR . 'includes/class-wp-persian-datepicker-shortcode.php';
        require_once PERSIAN_DATEPICKER_PLUGIN_DIR . 'includes/class-wp-persian-datepicker-widget.php';
        require_once PERSIAN_DATEPICKER_PLUGIN_DIR . 'admin/class-wp-persian-datepicker-admin.php';
    }

    /**
     * Define hooks and filters.
     */
    private function define_hooks() {
        // Initialize the loader
        $loader = new WP_Persian_Datepicker_Loader();
        
        // Initialize the admin
        $admin = new WP_Persian_Datepicker_Admin();
        
        // Register scripts and styles
        $scripts = new WP_Persian_Datepicker_Scripts();
        $loader->add_action('wp_enqueue_scripts', $scripts, 'enqueue_scripts');
        $loader->add_action('admin_enqueue_scripts', $scripts, 'enqueue_admin_scripts');
        
        // Register shortcode
        $shortcode = new WP_Persian_Datepicker_Shortcode();
        $loader->add_shortcode('persian_datepicker', $shortcode, 'render_shortcode');
        
        // Register widget
        add_action('widgets_init', function() {
            register_widget('WP_Persian_Datepicker_Widget');
        });
        
        // Add admin menu items
        $loader->add_action('admin_menu', $admin, 'add_plugin_menu');
        $loader->add_action('admin_init', $admin, 'register_settings');
        
        // Add support for Gutenberg block
        if (function_exists('register_block_type')) {
            $loader->add_action('init', $this, 'register_block');
        }
        
        // Run the loader to execute all hooks
        $loader->run();
    }
    
    /**
     * Register Gutenberg block.
     */
    public function register_block() {
        // Register block script
        wp_register_script(
            'wp-persian-datepicker-block',
            PERSIAN_DATEPICKER_PLUGIN_URL . 'assets/js/blocks/build/index.js',
            array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components'),
            PERSIAN_DATEPICKER_VERSION
        );
        
        // Register block
        register_block_type('wp-persian-datepicker-element/datepicker', array(
            'editor_script' => 'wp-persian-datepicker-block',
            'render_callback' => array($this, 'render_block'),
            'attributes' => array(
                'placeholder' => array(
                    'type' => 'string',
                    'default' => 'انتخاب تاریخ'
                ),
                'format' => array(
                    'type' => 'string',
                    'default' => 'YYYY/MM/DD'
                ),
                'showHolidays' => array(
                    'type' => 'boolean',
                    'default' => true
                ),
                'rtl' => array(
                    'type' => 'boolean',
                    'default' => true
                ),
                'darkMode' => array(
                    'type' => 'boolean',
                    'default' => false
                ),
                'holidayTypes' => array(
                    'type' => 'string',
                    'default' => 'Iran,International'
                ),
                'rangeMode' => array(
                    'type' => 'boolean',
                    'default' => false
                ),
                'className' => array(
                    'type' => 'string'
                )
            )
        ));
    }
    
    /**
     * Render Gutenberg block.
     */
    public function render_block($attributes) {
        $shortcode = new WP_Persian_Datepicker_Shortcode();
        return $shortcode->render_shortcode($attributes);
    }

    /**
     * Activate the plugin.
     */
    public static function activate() {
        // Set default options
        if (!get_option('wp_persian_datepicker_options')) {
            $default_options = array(
                'placeholder' => 'انتخاب تاریخ',
                'show_holidays' => 1,
                'rtl' => 1,
                'format' => 'YYYY/MM/DD',
                'holiday_types' => 'Iran,International',
                'dark_mode' => 0,
                'range_mode' => 0,
            );
            update_option('wp_persian_datepicker_options', $default_options);
        }
    }

    /**
     * Deactivate the plugin.
     */
    public static function deactivate() {
        // Clean up if needed
    }

    /**
     * Uninstall the plugin.
     */
    public static function uninstall() {
        // Remove plugin options
        delete_option('wp_persian_datepicker_options');
    }
}

// Register activation and deactivation hooks
register_activation_hook(__FILE__, array('WP_Persian_Datepicker_Element', 'activate'));
register_deactivation_hook(__FILE__, array('WP_Persian_Datepicker_Element', 'deactivate'));
register_uninstall_hook(__FILE__, array('WP_Persian_Datepicker_Element', 'uninstall'));

// Initialize the plugin
$wp_persian_datepicker = new WP_Persian_Datepicker_Element(); 