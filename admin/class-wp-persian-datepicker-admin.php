<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 * @package    WP_Persian_Datepicker_Element
 * @subpackage WP_Persian_Datepicker_Element/admin
 */

class WP_Persian_Datepicker_Admin {

    /**
     * Add plugin menu to WordPress admin.
     *
     * @since    1.0.0
     */
    public function add_plugin_menu() {
        add_options_page(
            esc_html__('Persian Date Picker Settings', 'wp-persian-datepicker-element'),
            esc_html__('Persian Date Picker', 'wp-persian-datepicker-element'),
            'manage_options',
            'wp-persian-datepicker-settings',
            array($this, 'display_settings_page')
        );
    }

    /**
     * Register plugin settings.
     *
     * @since    1.0.0
     */
    public function register_settings() {
        // Register settings section
        add_settings_section(
            'wp_persian_datepicker_general',
            esc_html__('General Settings', 'wp-persian-datepicker-element'),
            array($this, 'render_settings_section'),
            'wp-persian-datepicker-settings'
        );
        
        // Register settings fields
        add_settings_field(
            'wp_persian_datepicker_placeholder',
            esc_html__('Default Placeholder', 'wp-persian-datepicker-element'),
            array($this, 'render_placeholder_field'),
            'wp-persian-datepicker-settings',
            'wp_persian_datepicker_general'
        );
        
        add_settings_field(
            'wp_persian_datepicker_format',
            esc_html__('Default Date Format', 'wp-persian-datepicker-element'),
            array($this, 'render_format_field'),
            'wp-persian-datepicker-settings',
            'wp_persian_datepicker_general'
        );
        
        add_settings_field(
            'wp_persian_datepicker_show_holidays',
            esc_html__('Show Holidays', 'wp-persian-datepicker-element'),
            array($this, 'render_show_holidays_field'),
            'wp-persian-datepicker-settings',
            'wp_persian_datepicker_general'
        );
        
        add_settings_field(
            'wp_persian_datepicker_rtl',
            esc_html__('Right-to-Left (RTL)', 'wp-persian-datepicker-element'),
            array($this, 'render_rtl_field'),
            'wp-persian-datepicker-settings',
            'wp_persian_datepicker_general'
        );
        
        add_settings_field(
            'wp_persian_datepicker_dark_mode',
            esc_html__('Dark Mode', 'wp-persian-datepicker-element'),
            array($this, 'render_dark_mode_field'),
            'wp-persian-datepicker-settings',
            'wp_persian_datepicker_general'
        );
        
        add_settings_field(
            'wp_persian_datepicker_holiday_types',
            esc_html__('Holiday Types', 'wp-persian-datepicker-element'),
            array($this, 'render_holiday_types_field'),
            'wp-persian-datepicker-settings',
            'wp_persian_datepicker_general'
        );
        
        add_settings_field(
            'wp_persian_datepicker_range_mode',
            esc_html__('Range Mode', 'wp-persian-datepicker-element'),
            array($this, 'render_range_mode_field'),
            'wp-persian-datepicker-settings',
            'wp_persian_datepicker_general'
        );
        
        // Register the settings
        register_setting(
            'wp_persian_datepicker_options',
            'wp_persian_datepicker_options',
            array($this, 'validate_options')
        );
    }
    
    /**
     * Render the settings section.
     *
     * @since    1.0.0
     */
    public function render_settings_section() {
        echo '<p>' . esc_html__('Configure the default settings for the Persian Date Picker component. These settings will be used as defaults for shortcodes and widgets unless overridden.', 'wp-persian-datepicker-element') . '</p>';
    }
    
    /**
     * Render the placeholder field.
     *
     * @since    1.0.0
     */
    public function render_placeholder_field() {
        $options = get_option('wp_persian_datepicker_options');
        $placeholder = isset($options['placeholder']) ? $options['placeholder'] : 'انتخاب تاریخ';
        
        echo '<input type="text" id="wp_persian_datepicker_placeholder" name="wp_persian_datepicker_options[placeholder]" value="' . esc_attr($placeholder) . '" class="regular-text" />';
        echo '<p class="description">' . esc_html__('The placeholder text to show in the input field.', 'wp-persian-datepicker-element') . '</p>';
    }
    
    /**
     * Render the format field.
     *
     * @since    1.0.0
     */
    public function render_format_field() {
        $options = get_option('wp_persian_datepicker_options');
        $format = isset($options['format']) ? $options['format'] : 'YYYY/MM/DD';
        
        echo '<input type="text" id="wp_persian_datepicker_format" name="wp_persian_datepicker_options[format]" value="' . esc_attr($format) . '" class="regular-text" />';
        echo '<p class="description">' . esc_html__('The date format pattern (e.g. YYYY/MM/DD, YYYY-MM-DD).', 'wp-persian-datepicker-element') . '</p>';
    }
    
    /**
     * Render the show holidays field.
     *
     * @since    1.0.0
     */
    public function render_show_holidays_field() {
        $options = get_option('wp_persian_datepicker_options');
        $show_holidays = isset($options['show_holidays']) ? $options['show_holidays'] : 1;
        
        echo '<input type="checkbox" id="wp_persian_datepicker_show_holidays" name="wp_persian_datepicker_options[show_holidays]" value="1" ' . checked(1, $show_holidays, false) . ' />';
        echo '<label for="wp_persian_datepicker_show_holidays">' . esc_html__('Show holidays in the calendar', 'wp-persian-datepicker-element') . '</label>';
    }
    
    /**
     * Render the RTL field.
     *
     * @since    1.0.0
     */
    public function render_rtl_field() {
        $options = get_option('wp_persian_datepicker_options');
        $rtl = isset($options['rtl']) ? $options['rtl'] : 1;
        
        echo '<input type="checkbox" id="wp_persian_datepicker_rtl" name="wp_persian_datepicker_options[rtl]" value="1" ' . checked(1, $rtl, false) . ' />';
        echo '<label for="wp_persian_datepicker_rtl">' . esc_html__('Enable right-to-left layout', 'wp-persian-datepicker-element') . '</label>';
    }
    
    /**
     * Render the dark mode field.
     *
     * @since    1.0.0
     */
    public function render_dark_mode_field() {
        $options = get_option('wp_persian_datepicker_options');
        $dark_mode = isset($options['dark_mode']) ? $options['dark_mode'] : 0;
        
        echo '<input type="checkbox" id="wp_persian_datepicker_dark_mode" name="wp_persian_datepicker_options[dark_mode]" value="1" ' . checked(1, $dark_mode, false) . ' />';
        echo '<label for="wp_persian_datepicker_dark_mode">' . esc_html__('Enable dark mode', 'wp-persian-datepicker-element') . '</label>';
    }
    
    /**
     * Render the holiday types field.
     *
     * @since    1.0.0
     */
    public function render_holiday_types_field() {
        $options = get_option('wp_persian_datepicker_options');
        $holiday_types = isset($options['holiday_types']) ? $options['holiday_types'] : 'Iran,International';
        
        echo '<input type="text" id="wp_persian_datepicker_holiday_types" name="wp_persian_datepicker_options[holiday_types]" value="' . esc_attr($holiday_types) . '" class="regular-text" />';
        echo '<p class="description">' . esc_html__('Comma-separated list of holiday types to display (Iran, Afghanistan, AncientIran, International). Use "all" to show all types.', 'wp-persian-datepicker-element') . '</p>';
    }
    
    /**
     * Render the range mode field.
     *
     * @since    1.0.0
     */
    public function render_range_mode_field() {
        $options = get_option('wp_persian_datepicker_options');
        // Convert to a boolean value for better compatibility
        $range_mode = isset($options['range_mode']) && ($options['range_mode'] == 1 || $options['range_mode'] === '1' || $options['range_mode'] === true);
        
        echo '<!-- Debug: Range Mode Value in DB: ' . (isset($options['range_mode']) ? var_export($options['range_mode'], true) : 'not set') . ' -->';
        echo '<!-- Debug: Range Mode Converted Value: ' . var_export($range_mode, true) . ' -->';
        
        echo '<input type="checkbox" id="wp_persian_datepicker_range_mode" name="wp_persian_datepicker_options[range_mode]" value="1" ' . checked(1, $range_mode, false) . ' />';
        echo '<label for="wp_persian_datepicker_range_mode">' . esc_html__('Enable date range selection mode', 'wp-persian-datepicker-element') . '</label>';
    }
    
    /**
     * Validate and sanitize options.
     *
     * @since    1.0.0
     * @param    array    $input    Array of input values.
     * @return   array    Sanitized input values.
     */
    public function validate_options($input) {
        $sanitized_input = array();
        
        // Debug options being saved
        error_log('WP Persian Datepicker: Validating options: ' . print_r($input, true));
        
        // Sanitize text fields
        if (isset($input['placeholder'])) {
            $sanitized_input['placeholder'] = sanitize_text_field($input['placeholder']);
        }
        
        if (isset($input['format'])) {
            $sanitized_input['format'] = sanitize_text_field($input['format']);
        }
        
        if (isset($input['holiday_types'])) {
            $sanitized_input['holiday_types'] = sanitize_text_field($input['holiday_types']);
        }
        
        // Sanitize checkboxes (boolean values)
        // For checkboxes, we need to explicitly check if they're set or not
        $sanitized_input['show_holidays'] = isset($input['show_holidays']) ? 1 : 0;
        $sanitized_input['rtl'] = isset($input['rtl']) ? 1 : 0;
        $sanitized_input['dark_mode'] = isset($input['dark_mode']) ? 1 : 0;
        $sanitized_input['range_mode'] = isset($input['range_mode']) ? 1 : 0;
        
        return $sanitized_input;
    }
    
    /**
     * Display the settings page content.
     *
     * @since    1.0.0
     */
    public function display_settings_page() {
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            return;
        }
        
        // Default options
        $default_options = array(
            'placeholder' => 'انتخاب تاریخ',
            'format' => 'YYYY/MM/DD',
            'show_holidays' => 1,
            'rtl' => 1,
            'dark_mode' => 0,
            'holiday_types' => 'Iran,International',
            'range_mode' => 0
        );
        
        // Get current options
        $options = get_option('wp_persian_datepicker_options', $default_options);
        
        // Handle form submissions and save settings
        if (isset($_POST['submit']) && isset($_POST['wp_persian_datepicker_options_nonce'])) {
            // Verify nonce
            if (check_admin_referer('wp_persian_datepicker_options_action', 'wp_persian_datepicker_options_nonce')) {
                // Debug: Check what's being submitted
                error_log('WP Persian Datepicker: Form submitted with data: ' . print_r($_POST['wp_persian_datepicker_options'], true));
                
                // Process form data
                $new_options = array();
                
                // Sanitize text fields
                $new_options['placeholder'] = isset($_POST['wp_persian_datepicker_options']['placeholder']) ? 
                    sanitize_text_field($_POST['wp_persian_datepicker_options']['placeholder']) : $default_options['placeholder'];
                    
                $new_options['format'] = isset($_POST['wp_persian_datepicker_options']['format']) ? 
                    sanitize_text_field($_POST['wp_persian_datepicker_options']['format']) : $default_options['format'];
                    
                $new_options['holiday_types'] = isset($_POST['wp_persian_datepicker_options']['holiday_types']) ? 
                    sanitize_text_field($_POST['wp_persian_datepicker_options']['holiday_types']) : $default_options['holiday_types'];
                
                // Process checkboxes (they'll only be in the POST array if checked)
                $new_options['show_holidays'] = isset($_POST['wp_persian_datepicker_options']['show_holidays']) ? 1 : 0;
                $new_options['rtl'] = isset($_POST['wp_persian_datepicker_options']['rtl']) ? 1 : 0;
                $new_options['dark_mode'] = isset($_POST['wp_persian_datepicker_options']['dark_mode']) ? 1 : 0;
                $new_options['range_mode'] = isset($_POST['wp_persian_datepicker_options']['range_mode']) ? 1 : 0;
                
                // Update the options
                update_option('wp_persian_datepicker_options', $new_options);
                
                // Update our local copy for display
                $options = $new_options;
                
                // Add success message
            add_settings_error(
                'wp_persian_datepicker_messages',
                'wp_persian_datepicker_message',
                esc_html__('Settings saved.', 'wp-persian-datepicker-element'),
                'updated'
            );
                
                // Debug: Check what's being saved
                error_log('WP Persian Datepicker: Saved options: ' . print_r($new_options, true));
            }
        }
        
        // Display settings page HTML
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <?php settings_errors('wp_persian_datepicker_messages'); ?>
            
            <h2 class="nav-tab-wrapper">
                <a href="#settings" class="nav-tab nav-tab-active"><?php esc_html_e('Settings', 'wp-persian-datepicker-element'); ?></a>
                <a href="#shortcode" class="nav-tab"><?php esc_html_e('Shortcode Usage', 'wp-persian-datepicker-element'); ?></a>
                <a href="#integration" class="nav-tab"><?php esc_html_e('Integration', 'wp-persian-datepicker-element'); ?></a>
            </h2>
            
            <div id="settings" class="tab-content">
                <form method="post" action="">
                    <?php wp_nonce_field('wp_persian_datepicker_options_action', 'wp_persian_datepicker_options_nonce'); ?>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="wp_persian_datepicker_placeholder"><?php esc_html_e('Default Placeholder', 'wp-persian-datepicker-element'); ?></label>
                            </th>
                            <td>
                                <input type="text" id="wp_persian_datepicker_placeholder" name="wp_persian_datepicker_options[placeholder]" value="<?php echo esc_attr($options['placeholder']); ?>" class="regular-text" />
                                <p class="description"><?php esc_html_e('The placeholder text to show in the input field.', 'wp-persian-datepicker-element'); ?></p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="wp_persian_datepicker_format"><?php esc_html_e('Default Date Format', 'wp-persian-datepicker-element'); ?></label>
                            </th>
                            <td>
                                <input type="text" id="wp_persian_datepicker_format" name="wp_persian_datepicker_options[format]" value="<?php echo esc_attr($options['format']); ?>" class="regular-text" />
                                <p class="description"><?php esc_html_e('The date format pattern (e.g. YYYY/MM/DD, YYYY-MM-DD).', 'wp-persian-datepicker-element'); ?></p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <?php esc_html_e('Show Holidays', 'wp-persian-datepicker-element'); ?>
                            </th>
                            <td>
                                <input type="checkbox" id="wp_persian_datepicker_show_holidays" name="wp_persian_datepicker_options[show_holidays]" value="1" <?php checked(1, $options['show_holidays']); ?> />
                                <label for="wp_persian_datepicker_show_holidays"><?php esc_html_e('Show holidays in the calendar', 'wp-persian-datepicker-element'); ?></label>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <?php esc_html_e('Right-to-Left (RTL)', 'wp-persian-datepicker-element'); ?>
                            </th>
                            <td>
                                <input type="checkbox" id="wp_persian_datepicker_rtl" name="wp_persian_datepicker_options[rtl]" value="1" <?php checked(1, $options['rtl']); ?> />
                                <label for="wp_persian_datepicker_rtl"><?php esc_html_e('Enable right-to-left layout', 'wp-persian-datepicker-element'); ?></label>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <?php esc_html_e('Dark Mode', 'wp-persian-datepicker-element'); ?>
                            </th>
                            <td>
                                <input type="checkbox" id="wp_persian_datepicker_dark_mode" name="wp_persian_datepicker_options[dark_mode]" value="1" <?php checked(1, $options['dark_mode']); ?> />
                                <label for="wp_persian_datepicker_dark_mode"><?php esc_html_e('Enable dark mode', 'wp-persian-datepicker-element'); ?></label>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="wp_persian_datepicker_holiday_types"><?php esc_html_e('Holiday Types', 'wp-persian-datepicker-element'); ?></label>
                            </th>
                            <td>
                                <input type="text" id="wp_persian_datepicker_holiday_types" name="wp_persian_datepicker_options[holiday_types]" value="<?php echo esc_attr($options['holiday_types']); ?>" class="regular-text" />
                                <p class="description"><?php esc_html_e('Comma-separated list of holiday types to display (Iran, Afghanistan, AncientIran, International). Use "all" to show all types.', 'wp-persian-datepicker-element'); ?></p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <?php esc_html_e('Range Mode', 'wp-persian-datepicker-element'); ?>
                            </th>
                            <td>
                                <input type="checkbox" id="wp_persian_datepicker_range_mode" name="wp_persian_datepicker_options[range_mode]" value="1" <?php checked(1, $options['range_mode']); ?> />
                                <label for="wp_persian_datepicker_range_mode"><?php esc_html_e('Enable date range selection mode', 'wp-persian-datepicker-element'); ?></label>
                            </td>
                        </tr>
                    </table>
                    
                    <?php submit_button(); ?>
                    
                <div class="persian-datepicker-preview">
                        <h3><?php esc_html_e('Preview', 'wp-persian-datepicker-element'); ?></h3>
                        <div class="preview-container">
                    <?php 
                            // Convert options to attributes
                            $attrs = array();
                            if (!empty($options['placeholder'])) $attrs[] = 'placeholder="' . esc_attr($options['placeholder']) . '"';
                            if (!empty($options['format'])) $attrs[] = 'format="' . esc_attr($options['format']) . '"';
                            if (isset($options['show_holidays'])) $attrs[] = 'show-holidays="' . (empty($options['show_holidays']) ? 'false' : 'true') . '"';
                            if (isset($options['rtl'])) $attrs[] = 'rtl="' . (empty($options['rtl']) ? 'false' : 'true') . '"';
                            if (isset($options['dark_mode'])) $attrs[] = 'darkmode="' . (empty($options['dark_mode']) ? 'false' : 'true') . '"';
                            if (!empty($options['holiday_types'])) $attrs[] = 'holiday-types="' . esc_attr($options['holiday_types']) . '"';

                            // Special handling for range_mode to ensure it's always explicitly set
                            // This is critical as web components need explicit boolean attributes
                            $attrs[] = 'range-mode="' . (isset($options['range_mode']) && !empty($options['range_mode']) ? 'true' : 'false') . '"';

                            // Debug output
                            error_log('WP Persian Datepicker Admin: Setting range-mode attribute to: ' . 
                                (isset($options['range_mode']) && !empty($options['range_mode']) ? 'true' : 'false') . 
                                ' (option value: ' . (isset($options['range_mode']) ? $options['range_mode'] : 'not set') . ')');

                            $dark_class = !empty($options['dark_mode']) ? ' dark-theme' : '';
                            ?>
                            <div class="preview-datepicker<?php echo esc_attr($dark_class); ?>">
                                <persian-datepicker-element id="preview-datepicker" <?php echo implode(' ', $attrs); ?>></persian-datepicker-element>
                </div>
                            <p class="description">
                                <?php esc_html_e('This preview reflects your current settings. Changes to settings will update this preview.', 'wp-persian-datepicker-element'); ?>
                            </p>
                        </div>
                    </div>
                </form>
            </div>
            
            <div id="shortcode" class="tab-content" style="display: none;">
                <?php $this->display_shortcode_help(); ?>
            </div>
            
            <div id="integration" class="tab-content" style="display: none;">
                <?php
                $locale = determine_locale();
                if ($locale == 'fa_IR') {
                    $this->display_integration_guide_fa();
                } else {
                    $this->display_integration_guide_en();
            }
            ?>
        </div>
        </div>
        <div id="wp-pd-debug" style="margin-top: 30px; padding: 15px; border: 1px solid #ccc; background: #f8f8f8; display: none;">
            <h3>Debug Information</h3>
            <pre id="wp-pd-debug-options"><?php echo esc_html(print_r($options, true)); ?></pre>
            <div id="wp-pd-debug-current"></div>
        </div>
        <p><a href="#" id="wp-pd-toggle-debug">Toggle Debug Information</a></p>
        <script>
        jQuery(document).ready(function($) {
            console.log('Settings page loaded');
            console.log('Current options:', <?php echo json_encode($options); ?>);
            
            // Log checkbox states for debugging
            console.log('Checkbox states:', {
                show_holidays: $('#wp_persian_datepicker_show_holidays').is(':checked'),
                rtl: $('#wp_persian_datepicker_rtl').is(':checked'),
                dark_mode: $('#wp_persian_datepicker_dark_mode').is(':checked'),
                range_mode: $('#wp_persian_datepicker_range_mode').is(':checked')
            });
            
            // Toggle debug info
            $('#wp-pd-toggle-debug').on('click', function(e) {
                e.preventDefault();
                $('#wp-pd-debug').toggle();
            });
            
            // Update debug info when form changes
            $('input[name^="wp_persian_datepicker_options"]').on('change input', function() {
                var debugInfo = '<h4>Current Form Values:</h4><ul>';
                debugInfo += '<li>Placeholder: ' + $('#wp_persian_datepicker_placeholder').val() + '</li>';
                debugInfo += '<li>Format: ' + $('#wp_persian_datepicker_format').val() + '</li>';
                debugInfo += '<li>Show Holidays: ' + $('#wp_persian_datepicker_show_holidays').is(':checked') + '</li>';
                debugInfo += '<li>RTL: ' + $('#wp_persian_datepicker_rtl').is(':checked') + '</li>';
                debugInfo += '<li>Dark Mode: ' + $('#wp_persian_datepicker_dark_mode').is(':checked') + '</li>';
                debugInfo += '<li>Holiday Types: ' + $('#wp_persian_datepicker_holiday_types').val() + '</li>';
                debugInfo += '<li>Range Mode: ' + $('#wp_persian_datepicker_range_mode').is(':checked') + '</li>';
                debugInfo += '</ul>';
                
                $('#wp-pd-debug-current').html(debugInfo);
            });
        });
        </script>
        <?php
    }
    
    /**
     * Display shortcode help section.
     *
     * @since    1.0.0
     */
    private function display_shortcode_help() {
        ?>
        <div class="wp-persian-datepicker-help">
            <h2><?php esc_html_e('Shortcode Usage', 'wp-persian-datepicker-element'); ?></h2>
            
            <p><?php esc_html_e('To display the Persian Date Picker in your content, use the following shortcode:', 'wp-persian-datepicker-element'); ?></p>
            
            <code>[persian_datepicker]</code>
            
            <p><?php esc_html_e('You can customize the datepicker with various attributes:', 'wp-persian-datepicker-element'); ?></p>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><?php esc_html_e('Basic Usage', 'wp-persian-datepicker-element'); ?></th>
                    <td><code>[persian_datepicker]</code></td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('With Custom Placeholder', 'wp-persian-datepicker-element'); ?></th>
                    <td><code>[persian_datepicker placeholder="تاریخ تولد"]</code></td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('With Custom Format', 'wp-persian-datepicker-element'); ?></th>
                    <td><code>[persian_datepicker format="YYYY-MM-DD"]</code></td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Show Holidays', 'wp-persian-datepicker-element'); ?></th>
                    <td><code>[persian_datepicker show_holidays="true"]</code></td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('RTL Direction', 'wp-persian-datepicker-element'); ?></th>
                    <td><code>[persian_datepicker rtl="true"]</code></td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Dark Mode', 'wp-persian-datepicker-element'); ?></th>
                    <td><code>[persian_datepicker dark_mode="true"]</code></td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Range Mode', 'wp-persian-datepicker-element'); ?></th>
                    <td><code>[persian_datepicker range_mode="true"]</code></td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Custom Holiday Types', 'wp-persian-datepicker-element'); ?></th>
                    <td><code>[persian_datepicker holiday_types="Iran,International"]</code></td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('With ID and Name Attributes', 'wp-persian-datepicker-element'); ?></th>
                    <td><code>[persian_datepicker id="custom-date" name="custom_date"]</code></td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('With Custom CSS Class', 'wp-persian-datepicker-element'); ?></th>
                    <td><code>[persian_datepicker class="my-custom-class"]</code></td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('With Date Range Constraints', 'wp-persian-datepicker-element'); ?></th>
                    <td><code>[persian_datepicker min_date="1402/01/01" max_date="1403/12/29"]</code></td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('With Disabled Dates', 'wp-persian-datepicker-element'); ?></th>
                    <td><code>[persian_datepicker disabled_dates="1402/01/13,1402/11/22"]</code></td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Customizing Selector Controls', 'wp-persian-datepicker-element'); ?></th>
                    <td><code>[persian_datepicker show_month_selector="true" show_year_selector="true"]</code></td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Customizing Navigation Buttons', 'wp-persian-datepicker-element'); ?></th>
                    <td><code>[persian_datepicker show_prev_button="true" show_next_button="true"]</code></td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Customizing Special Buttons', 'wp-persian-datepicker-element'); ?></th>
                    <td><code>[persian_datepicker show_today_button="true" today_button_text="امروز" show_tomorrow_button="true" tomorrow_button_text="فردا"]</code></td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Custom Events Path', 'wp-persian-datepicker-element'); ?></th>
                    <td><code>[persian_datepicker events_path="https://example.com/custom-events.json"]</code></td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Full Example', 'wp-persian-datepicker-element'); ?></th>
                    <td><code>[persian_datepicker placeholder="انتخاب تاریخ" format="YYYY/MM/DD" show_holidays="true" rtl="true" holiday_types="Iran,International" range_mode="false" dark_mode="false" id="my-datepicker" name="my_date" class="custom-datepicker" min_date="1400/01/01" max_date="1405/12/29"]</code></td>
                </tr>
            </table>
            
            <h3><?php esc_html_e('Gutenberg Block', 'wp-persian-datepicker-element'); ?></h3>
            <p><?php esc_html_e('You can also use the Persian Date Picker as a Gutenberg block. Simply search for "Persian Date Picker" in the block inserter.', 'wp-persian-datepicker-element'); ?></p>
        </div>
        <?php
    }
    
    /**
     * Display integration guide section.
     *
     * @since    1.0.0
     */
    private function display_integration_guide() {
        // اگر زبان فارسی باشد، از نسخه فارسی استفاده می‌کنیم
        if (determine_locale() === 'fa_IR') {
            $this->display_integration_guide_fa();
        } else {
            $this->display_integration_guide_en();
        }
    }
    
    /**
     * نمایش راهنمای ادغام به زبان فارسی
     */
    private function display_integration_guide_fa() {
        ?>
        <div class="wp-persian-datepicker-help">
            <h2>راهنمای ادغام</h2>
            
            <h3>ادغام با فرم تماس 7 (Contact Form 7)</h3>
            
            <p>برای استفاده از انتخابگر تاریخ شمسی با افزونه فرم تماس 7، مراحل زیر را دنبال کنید:</p>
            
            <ol>
                <li>یک فرم تماس 7 جدید ایجاد کنید یا فرم موجود را ویرایش کنید.</li>
                <li>یک فیلد متنی در جایی که می‌خواهید از انتخابگر تاریخ استفاده کنید، اضافه نمایید.</li>
                <li>مطمئن شوید که کلاس "persian-datepicker" را به فیلد اضافه کرده‌اید.</li>
                <li>می‌توانید ویژگی‌های دیگری را نیز برای شخصی‌سازی انتخابگر تاریخ اضافه کنید.</li>
            </ol>
            
            <div class="integration-example">
                <h4>مثال فیلد در فرم تماس 7</h4>
                <pre class="code-block">[text* birthday class:persian-datepicker data-placeholder="تاریخ تولد" data-format="YYYY/MM/DD" data-show-holidays="true" data-rtl="true"]</pre>
            </div>
            
            <p>ویژگی‌های قابل دسترس برای ادغام با فرم تماس 7:</p>
            
            <table class="form-table">
                <tr>
                    <th scope="row">data-placeholder</th>
                    <td>تنظیم متن راهنمای سفارشی</td>
                </tr>
                <tr>
                    <th scope="row">data-format</th>
                    <td>تنظیم قالب تاریخ (YYYY/MM/DD, YYYY-MM-DD و غیره)</td>
                </tr>
                <tr>
                    <th scope="row">data-show-holidays</th>
                    <td>نمایش تعطیلات در تقویم (true/false)</td>
                </tr>
                <tr>
                    <th scope="row">data-rtl</th>
                    <td>فعال‌سازی چیدمان راست به چپ (true/false)</td>
                </tr>
                <tr>
                    <th scope="row">data-dark-mode</th>
                    <td>فعال‌سازی حالت تاریک (true/false)</td>
                </tr>
                <tr>
                    <th scope="row">data-holiday-types</th>
                    <td>تنظیم انواع تعطیلات (جدا شده با کاما)</td>
                </tr>
                <tr>
                    <th scope="row">data-range-mode</th>
                    <td>فعال‌سازی حالت انتخاب بازه تاریخ (true/false)</td>
                </tr>
                <tr>
                    <th scope="row">data-min-date</th>
                    <td>تنظیم کمترین تاریخ قابل انتخاب</td>
                </tr>
                <tr>
                    <th scope="row">data-max-date</th>
                    <td>تنظیم بیشترین تاریخ قابل انتخاب</td>
                </tr>
            </table>
            
            <div class="integration-example">
                <h4>مثال کامل فرم تماس 7</h4>
                <pre class="code-block">[text* birthday class:persian-datepicker data-placeholder="تاریخ تولد" data-format="YYYY/MM/DD" data-show-holidays="true" data-rtl="true" data-holiday-types="Iran,International" data-min-date="1330/01/01" data-max-date="1402/12/29"]</pre>
            </div>
            
            <h3>ادغام با دبلیوپی‌فرمز (WPForms)</h3>
            
            <p>برای استفاده از انتخابگر تاریخ شمسی با افزونه دبلیوپی‌فرمز، کد جاوااسکریپت زیر را به پوسته یا فایل جاوااسکریپت سفارشی خود اضافه کنید:</p>
            
            <pre class="code-block">jQuery(document).ready(function($) {
    // اعمال انتخابگر تاریخ به فیلدهای متنی WPForms با کلاس 'persian-date'
    $('.wpforms-field-text input.persian-date').each(function() {
        var $input = $(this);
        var $wrapper = $('<persian-datepicker-element></persian-datepicker-element>');
        
        // کپی کردن ویژگی‌ها از ورودی به انتخابگر تاریخ
        if ($input.attr('placeholder')) {
            $wrapper.attr('placeholder', $input.attr('placeholder'));
        }
        
        // دریافت ویژگی‌های داده‌ای اضافی
        if ($input.data('format')) {
            $wrapper.attr('format', $input.data('format'));
        }
        
        if ($input.data('show-holidays') !== undefined) {
            $wrapper.attr('show-holidays', $input.data('show-holidays'));
        }
        
        if ($input.data('rtl') !== undefined) {
            $wrapper.attr('rtl', $input.data('rtl'));
        }
        
        // قرار دادن انتخابگر تاریخ و اتصال تغییرات مقدار
        $input.after($wrapper).hide();
        
        $wrapper[0].addEventListener('dateSelected', function(e) {
            $input.val(e.detail.formattedDate).trigger('change');
        });
    });
});</pre>
            
            <p>سپس کلاس "persian-date" را به هر فیلد متنی در فرم WPForms خود اضافه کنید.</p>
            
            <h3>ادغام با گراویتی فرمز (Gravity Forms)</h3>
            
            <p>برای استفاده از انتخابگر تاریخ شمسی با گراویتی فرمز، کد جاوااسکریپت مشابهی را مانند WPForms اضافه کنید، اما فیلدهای گراویتی فرمز را هدف قرار دهید:</p>
            
            <pre class="code-block">jQuery(document).ready(function($) {
    // اعمال انتخابگر تاریخ به فیلدهای متنی Gravity Forms با کلاس 'persian-date'
    $('.gfield input.persian-date').each(function() {
        // پیاده‌سازی مشابه مثال WPForms بالا
        var $input = $(this);
        var $wrapper = $('<persian-datepicker-element></persian-datepicker-element>');
        
        // اعمال ویژگی‌ها و تنظیمات...
        
        $input.after($wrapper).hide();
        
        $wrapper[0].addEventListener('dateSelected', function(e) {
            $input.val(e.detail.formattedDate).trigger('change');
        });
    });
});</pre>
            
            <h3>ادغام با ووکامرس (WooCommerce)</h3>
            
            <p>انتخابگر تاریخ شمسی به صورت خودکار با فرم‌های ووکامرس ادغام می‌شود. کافیست کلاس «persian-date» را به فیلدهای مورد نظر خود اضافه کنید:</p>
            
            <pre class="code-block">// برای سفارشی‌سازی فیلدهای سفارش در ووکامرس، از فیلتر زیر استفاده کنید
add_filter('woocommerce_checkout_fields', 'custom_woocommerce_checkout_fields');

function custom_woocommerce_checkout_fields($fields) {
    // اضافه کردن کلاس به فیلد تاریخ
    if (isset($fields['order']['order_date'])) {
        $fields['order']['order_date']['class'][] = 'persian-date';
    }
    
    return $fields;
}</pre>
            
            <h3>API جاوااسکریپت برای ادغام پیشرفته</h3>
            
            <p>برای شخصی‌سازی پیشرفته، می‌توانید مستقیماً از API جاوااسکریپت استفاده کنید:</p>
            
            <pre class="code-block">// ایجاد یک عنصر انتخابگر تاریخ
const datepicker = document.createElement('persian-datepicker-element');

// تنظیم ویژگی‌ها
datepicker.setAttribute('placeholder', 'انتخاب تاریخ');
datepicker.setAttribute('format', 'YYYY/MM/DD');
datepicker.setAttribute('show-holidays', 'true');
datepicker.setAttribute('rtl', 'true');

// گوش دادن به رویداد انتخاب تاریخ
datepicker.addEventListener('dateSelected', function(e) {
    console.log('تاریخ انتخاب شده:', e.detail.formattedDate);
    console.log('شیء تاریخ:', e.detail.date);
});

// افزودن به کانتینر
document.querySelector('#datepicker-container').appendChild(datepicker);</pre>
            
            <h3>شخصی‌سازی CSS</h3>
            
            <p>می‌توانید ظاهر انتخابگر تاریخ را با متغیرهای CSS سفارشی کنید. این‌ها را به برگه استایل پوسته یا سفارشی‌ساز خود اضافه کنید:</p>
            
            <pre class="code-block">/* اعمال به همه انتخابگرهای تاریخ یا انتخابگرهای خاص با کلاس‌ها */
persian-datepicker-element {
    /* رنگ‌های اصلی */
    --jdp-background: #ffffff;
    --jdp-foreground: #333333;
    
    /* استایل ورودی */
    --jdp-input-bg: #fafafa;
    --jdp-input-border: #cccccc;
    --jdp-input-padding: 8px 12px;
    --jdp-input-border-radius: 4px;
    
    /* استایل تقویم */
    --jdp-border-radius: 8px;
    --jdp-border-color: #e0e0e0;
    --jdp-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    
    /* استایل روز انتخاب شده */
    --jdp-selected-bg: #2271b1;
    --jdp-selected-fg: #ffffff;
    
    /* استایل تعطیلات */
    --jdp-holiday-color: #e53935;
    
    /* استایل امروز */
    --jdp-today-border-color: #2271b1;
    
    /* عرض سفارشی */
    width: 280px;
}</pre>
            
            <h3>استفاده در قالب‌ها و کدهای سفارشی</h3>
            
            <p>می‌توانید از تابع کوتاه PHP زیر در قالب‌های سفارشی خود استفاده کنید:</p>
            
            <pre class="code-block">// در فایل PHP قالب خود
if (function_exists('persian_datepicker_shortcode')) {
    echo persian_datepicker_shortcode([
        'placeholder' => 'تاریخ انتخاب کنید',
        'format' => 'YYYY/MM/DD',
        'show_holidays' => 'true',
        'rtl' => 'true',
        'holiday_types' => 'Iran,International'
    ]);
}</pre>
            
            <div class="integration-example">
                <h4>نکته مهم</h4>
                <p>برای بهترین عملکرد، همیشه اطمینان حاصل کنید که کلاس «persian-datepicker» را به فیلدهای متنی‌ای که می‌خواهید به انتخابگر تاریخ شمسی تبدیل شوند، اضافه کرده‌اید. این باعث می‌شود فرآیند تبدیل به طور خودکار انجام شود.</p>
            </div>
        </div>
        <?php
    }
    
    /**
     * Display integration guide section in English.
     */
    private function display_integration_guide_en() {
        ?>
        <div class="wp-persian-datepicker-help">
            <h2><?php esc_html_e('Integration Guide', 'wp-persian-datepicker-element'); ?></h2>
            
            <h3><?php esc_html_e('Integration with Contact Form 7', 'wp-persian-datepicker-element'); ?></h3>
            
            <p><?php esc_html_e('To use Persian Datepicker with Contact Form 7, follow these steps:', 'wp-persian-datepicker-element'); ?></p>
            
            <ol>
                <li><?php esc_html_e('Create a new Contact Form 7 form or edit an existing form.', 'wp-persian-datepicker-element'); ?></li>
                <li><?php esc_html_e('Add a text field where you want to use the datepicker.', 'wp-persian-datepicker-element'); ?></li>
                <li><?php esc_html_e('Make sure to add the class "persian-datepicker" to the field.', 'wp-persian-datepicker-element'); ?></li>
                <li><?php esc_html_e('You can also add other attributes to customize the datepicker.', 'wp-persian-datepicker-element'); ?></li>
            </ol>
            
            <div class="integration-example">
                <h4><?php esc_html_e('Example Contact Form 7 Field', 'wp-persian-datepicker-element'); ?></h4>
                <pre class="code-block">[text* birthday class:persian-datepicker data-placeholder="تاریخ تولد" data-format="YYYY/MM/DD" data-show-holidays="true" data-rtl="true"]</pre>
            </div>
            
            <p><?php esc_html_e('Available data attributes for Contact Form 7 integration:', 'wp-persian-datepicker-element'); ?></p>
            
            <table class="form-table">
                <tr>
                    <th scope="row">data-placeholder</th>
                    <td><?php esc_html_e('Set custom placeholder text', 'wp-persian-datepicker-element'); ?></td>
                </tr>
                <tr>
                    <th scope="row">data-format</th>
                    <td><?php esc_html_e('Set date format (YYYY/MM/DD, YYYY-MM-DD, etc.)', 'wp-persian-datepicker-element'); ?></td>
                </tr>
                <tr>
                    <th scope="row">data-show-holidays</th>
                    <td><?php esc_html_e('Show holidays in calendar (true/false)', 'wp-persian-datepicker-element'); ?></td>
                </tr>
                <tr>
                    <th scope="row">data-rtl</th>
                    <td><?php esc_html_e('Enable RTL layout (true/false)', 'wp-persian-datepicker-element'); ?></td>
                </tr>
                <tr>
                    <th scope="row">data-dark-mode</th>
                    <td><?php esc_html_e('Enable dark mode (true/false)', 'wp-persian-datepicker-element'); ?></td>
                </tr>
                <tr>
                    <th scope="row">data-holiday-types</th>
                    <td><?php esc_html_e('Set holiday types (comma-separated)', 'wp-persian-datepicker-element'); ?></td>
                </tr>
                <tr>
                    <th scope="row">data-range-mode</th>
                    <td><?php esc_html_e('Enable date range selection (true/false)', 'wp-persian-datepicker-element'); ?></td>
                </tr>
                <tr>
                    <th scope="row">data-min-date</th>
                    <td><?php esc_html_e('Set minimum selectable date', 'wp-persian-datepicker-element'); ?></td>
                </tr>
                <tr>
                    <th scope="row">data-max-date</th>
                    <td><?php esc_html_e('Set maximum selectable date', 'wp-persian-datepicker-element'); ?></td>
                </tr>
            </table>
            
            <div class="integration-example">
                <h4><?php esc_html_e('Contact Form 7 Full Example', 'wp-persian-datepicker-element'); ?></h4>
                <pre class="code-block">[text* birthday class:persian-datepicker data-placeholder="تاریخ تولد" data-format="YYYY/MM/DD" data-show-holidays="true" data-rtl="true" data-holiday-types="Iran,International" data-min-date="1330/01/01" data-max-date="1402/12/29"]</pre>
            </div>
            
            <h3><?php esc_html_e('Integration with WPForms', 'wp-persian-datepicker-element'); ?></h3>
            
            <p><?php esc_html_e('To use Persian Datepicker with WPForms, add the following JavaScript code to your theme or a custom JavaScript file:', 'wp-persian-datepicker-element'); ?></p>
            
            <pre class="code-block">jQuery(document).ready(function($) {
    // Apply datepicker to WPForms text fields with class 'persian-date'
    $('.wpforms-field-text input.persian-date').each(function() {
        var $input = $(this);
        var $wrapper = $('<persian-datepicker-element></persian-datepicker-element>');
        
        // Copy attributes from input to datepicker
        if ($input.attr('placeholder')) {
            $wrapper.attr('placeholder', $input.attr('placeholder'));
        }
        
        // Get additional data attributes
        if ($input.data('format')) {
            $wrapper.attr('format', $input.data('format'));
        }
        
        if ($input.data('show-holidays') !== undefined) {
            $wrapper.attr('show-holidays', $input.data('show-holidays'));
        }
        
        if ($input.data('rtl') !== undefined) {
            $wrapper.attr('rtl', $input.data('rtl'));
        }
        
        // Insert datepicker and hook up value changes
        $input.after($wrapper).hide();
        
        $wrapper[0].addEventListener('dateSelected', function(e) {
            $input.val(e.detail.formattedDate).trigger('change');
        });
    });
});</pre>
            
            <p><?php esc_html_e('Then add the class "persian-date" to any text field in your WPForms form.', 'wp-persian-datepicker-element'); ?></p>
            
            <h3><?php esc_html_e('Integration with Gravity Forms', 'wp-persian-datepicker-element'); ?></h3>
            
            <p><?php esc_html_e('To use Persian Datepicker with Gravity Forms, add similar JavaScript code as with WPForms, but target Gravity Forms fields:', 'wp-persian-datepicker-element'); ?></p>
            
            <pre class="code-block">jQuery(document).ready(function($) {
    // Apply datepicker to Gravity Forms text fields with class 'persian-date'
    $('.gfield input.persian-date').each(function() {
        // Similar implementation as WPForms example above
        var $input = $(this);
        var $wrapper = $('<persian-datepicker-element></persian-datepicker-element>');
        
        // Apply attributes and settings...
        
        $input.after($wrapper).hide();
        
        $wrapper[0].addEventListener('dateSelected', function(e) {
            $input.val(e.detail.formattedDate).trigger('change');
        });
    });
});</pre>
            
            <h3><?php esc_html_e('JavaScript API for Advanced Integration', 'wp-persian-datepicker-element'); ?></h3>
            
            <p><?php esc_html_e('For advanced customization, you can use the JavaScript API directly:', 'wp-persian-datepicker-element'); ?></p>
            
            <pre class="code-block">// Create a datepicker element
const datepicker = document.createElement('persian-datepicker-element');

// Set attributes
datepicker.setAttribute('placeholder', 'انتخاب تاریخ');
datepicker.setAttribute('format', 'YYYY/MM/DD');
datepicker.setAttribute('show-holidays', 'true');
datepicker.setAttribute('rtl', 'true');

// Listen for date selection
datepicker.addEventListener('dateSelected', function(e) {
    console.log('Selected date:', e.detail.formattedDate);
    console.log('Date object:', e.detail.date);
});

// Append to container
document.querySelector('#datepicker-container').appendChild(datepicker);</pre>
            
            <h3><?php esc_html_e('CSS Customization', 'wp-persian-datepicker-element'); ?></h3>
            
            <p><?php esc_html_e('You can customize the datepicker appearance with CSS variables. Add these to your theme\'s stylesheet or Customizer:', 'wp-persian-datepicker-element'); ?></p>
            
            <pre class="code-block">/* Apply to all datepickers or specific ones with classes */
persian-datepicker-element {
    /* Main colors */
    --jdp-background: #ffffff;
    --jdp-foreground: #333333;
    
    /* Input styling */
    --jdp-input-bg: #fafafa;
    --jdp-input-border: #cccccc;
    --jdp-input-padding: 8px 12px;
    --jdp-input-border-radius: 4px;
    
    /* Calendar styling */
    --jdp-border-radius: 8px;
    --jdp-border-color: #e0e0e0;
    --jdp-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    
    /* Selected day styling */
    --jdp-selected-bg: #2271b1;
    --jdp-selected-fg: #ffffff;
    
    /* Holiday styling */
    --jdp-holiday-color: #e53935;
    
    /* Today styling */
    --jdp-today-border-color: #2271b1;
    
    /* Custom width */
    width: 280px;
}</pre>
            
            <h3>استفاده در قالب‌ها و کدهای سفارشی</h3>
            
            <p>می‌توانید از تابع کوتاه PHP زیر در قالب‌های سفارشی خود استفاده کنید:</p>
            
            <pre class="code-block">// در فایل PHP قالب خود
if (function_exists('persian_datepicker_shortcode')) {
    echo persian_datepicker_shortcode([
        'placeholder' => 'تاریخ انتخاب کنید',
        'format' => 'YYYY/MM/DD',
        'show_holidays' => 'true',
        'rtl' => 'true',
        'holiday_types' => 'Iran,International'
    ]);
}</pre>
            
            <div class="integration-example">
                <h4>نکته مهم</h4>
                <p>برای بهترین عملکرد، همیشه اطمینان حاصل کنید که کلاس «persian-datepicker» را به فیلدهای متنی‌ای که می‌خواهید به انتخابگر تاریخ شمسی تبدیل شوند، اضافه کرده‌اید. این باعث می‌شود فرآیند تبدیل به طور خودکار انجام شود.</p>
            </div>
        </div>
        <?php
    }
} 