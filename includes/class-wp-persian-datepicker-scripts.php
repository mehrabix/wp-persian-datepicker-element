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
        // Check if we need to load scripts based on active plugins
        $load_for_plugins = $this->should_load_for_active_plugins();
        
        // Load if the shortcode or block is being used, or if compatible plugins are active on the page
        global $post;
        if (($load_for_plugins) || 
            (is_a($post, 'WP_Post') && 
            (has_shortcode($post->post_content, 'persian_datepicker') || 
             has_block('wp-persian-datepicker-element/datepicker')))) {
            
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
        
        // Frontend integration script
        wp_register_script(
            'wp-persian-datepicker-frontend',
            PERSIAN_DATEPICKER_PLUGIN_URL . 'assets/js/frontend/wp-integration.js',
            array('persian-datepicker-element', 'wp-persian-datepicker-events-path-patch'),
            PERSIAN_DATEPICKER_VERSION,
            true
        );
        
        // Pass options to the script
        $options = get_option('wp_persian_datepicker_options', array());
        
        // Add plugin base URL to options
        $options['plugin_url'] = PERSIAN_DATEPICKER_PLUGIN_URL;
        
        // Pass options to script
        wp_localize_script(
            'wp-persian-datepicker-frontend',
            'wpPersianDatepickerOptions',
            $options
        );
        
        // Pass active plugin integrations to script
        $integrations = array(
            'cf7' => function_exists('wpcf7_contact_form_tag_func'),
            'woocommerce' => function_exists('WC'),
            'gravity_forms' => class_exists('GFCommon'),
            'wpforms' => function_exists('wpforms') || class_exists('WPForms\WPForms')
        );
        
        wp_localize_script(
            'wp-persian-datepicker-frontend',
            'wpPersianDatepickerIntegrations',
            $integrations
        );
        
        // Enqueue the frontend script
        wp_enqueue_script('wp-persian-datepicker-frontend');
    }

    /**
     * Check if scripts should be loaded for active plugins
     * 
     * @return bool Whether scripts should be loaded
     */
    private function should_load_for_active_plugins() {
        // Only check specific pages where forms might be present
        $is_singular_or_page = is_singular() || is_page();
        $is_woo_checkout = function_exists('is_checkout') ? is_checkout() : false;
        $is_woo_account = function_exists('is_account_page') ? is_account_page() : false;
        
        if (!$is_singular_or_page && !$is_woo_checkout && !$is_woo_account) {
            return false;
        }
        
        // Check for active plugins that we integrate with
        $load_scripts = false;
        
        // Contact Form 7
        if (function_exists('wpcf7_contact_form_tag_func') && 
            $is_singular_or_page) {
            $load_scripts = true;
        }
        
        // WooCommerce
        if (function_exists('WC') && 
            ($is_woo_checkout || $is_woo_account)) {
            $load_scripts = true;
        }
        
        // Gravity Forms
        if (class_exists('GFCommon') && 
            $is_singular_or_page) {
            $load_scripts = true;
        }
        
        // WPForms
        if ((function_exists('wpforms') || class_exists('WPForms\WPForms')) && 
            $is_singular_or_page) {
            $load_scripts = true;
        }
        
        return $load_scripts;
    }
} 