<?php
/**
 * Register all scripts and styles for the plugin.
 *
 * @since      1.0.0
 * @package    WP_Persian_Datepicker_Element
 * @subpackage WP_Persian_Datepicker_Element/includes
 */

class WP_Persian_Datepicker_Scripts {

    /**
     * Register the scripts and styles for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        // Only load if the shortcode or block is being used
        global $post;
        if (is_a($post, 'WP_Post') && 
            (has_shortcode($post->post_content, 'persian_datepicker') || 
             has_block('wp-persian-datepicker-element/datepicker'))) {
            
            $this->register_datepicker_assets();
        }
    }

    /**
     * Register the scripts and styles for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_admin_scripts() {
        // CSS for admin settings page
        wp_enqueue_style(
            'wp-persian-datepicker-admin',
            PERSIAN_DATEPICKER_PLUGIN_URL . 'admin/css/wp-persian-datepicker-admin.css',
            array(),
            PERSIAN_DATEPICKER_VERSION
        );

        // JS for admin settings page
        wp_enqueue_script(
            'wp-persian-datepicker-admin',
            PERSIAN_DATEPICKER_PLUGIN_URL . 'admin/js/wp-persian-datepicker-admin.js',
            array('jquery'),
            PERSIAN_DATEPICKER_VERSION,
            true
        );
        
        // Register the datepicker for the admin page preview
        $this->register_datepicker_assets();
    }
    
    /**
     * Register the datepicker web component and required assets.
     *
     * @since    1.0.0
     */
    private function register_datepicker_assets() {
        // Vazir Font for Persian text - Optional
        wp_enqueue_style(
            'vazir-font',
            'https://cdn.jsdelivr.net/gh/rastikerdar/vazir-font@v30.1.0/dist/font-face.css',
            array(),
            '30.1.0'
        );
        
        // Base CSS for the datepicker
        wp_enqueue_style(
            'wp-persian-datepicker-element',
            PERSIAN_DATEPICKER_PLUGIN_URL . 'assets/css/wp-persian-datepicker-element.css',
            array(),
            PERSIAN_DATEPICKER_VERSION
        );
        
        // The datepicker web component
        wp_enqueue_script(
            'persian-datepicker-element',
            PERSIAN_DATEPICKER_PLUGIN_URL . 'dist/persian-datepicker-element.min.js',
            array(),
            PERSIAN_DATEPICKER_VERSION,
            true
        );
        
        // Load the events path patch script
        wp_enqueue_script(
            'wp-persian-datepicker-events-path-patch',
            PERSIAN_DATEPICKER_PLUGIN_URL . 'assets/js/frontend/events-path-patch.js',
            array('persian-datepicker-element'),
            PERSIAN_DATEPICKER_VERSION,
            true
        );
        
        // Helper script for WordPress integration
        wp_enqueue_script(
            'wp-persian-datepicker-element-integration',
            PERSIAN_DATEPICKER_PLUGIN_URL . 'assets/js/frontend/wp-integration.js',
            array('persian-datepicker-element', 'wp-persian-datepicker-events-path-patch'),
            PERSIAN_DATEPICKER_VERSION,
            true
        );
        
        // Pass options to the script
        $options = get_option('wp_persian_datepicker_options', array());
        
        // Add plugin base URL to options
        $options['plugin_url'] = PERSIAN_DATEPICKER_PLUGIN_URL;
        
        wp_localize_script(
            'wp-persian-datepicker-element-integration',
            'wpPersianDatepickerOptions',
            $options
        );
    }
} 