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
        $range_mode = isset($options['range_mode']) ? $options['range_mode'] : 0;
        
        echo '<input type="checkbox" id="wp_persian_datepicker_range_mode" name="wp_persian_datepicker_options[range_mode]" value="1" ' . checked(1, $range_mode, false) . ' />';
        echo '<label for="wp_persian_datepicker_range_mode">' . esc_html__('Enable date range selection mode', 'wp-persian-datepicker-element') . '</label>';
    }
    
    /**
     * Validate settings.
     *
     * @since    1.0.0
     * @param    array    $input    The input options.
     * @return   array              The validated options.
     */
    public function validate_options($input) {
        $validated = array();
        
        // Validate placeholder
        $validated['placeholder'] = sanitize_text_field($input['placeholder']);
        
        // Validate format
        $validated['format'] = sanitize_text_field($input['format']);
        
        // Validate show_holidays
        $validated['show_holidays'] = isset($input['show_holidays']) ? 1 : 0;
        
        // Validate rtl
        $validated['rtl'] = isset($input['rtl']) ? 1 : 0;
        
        // Validate dark_mode
        $validated['dark_mode'] = isset($input['dark_mode']) ? 1 : 0;
        
        // Validate holiday_types
        $validated['holiday_types'] = sanitize_text_field($input['holiday_types']);
        
        // Validate range_mode
        $validated['range_mode'] = isset($input['range_mode']) ? 1 : 0;
        
        return $validated;
    }
    
    /**
     * Display the settings page.
     *
     * @since    1.0.0
     */
    public function display_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        // Check if settings updated and show message
        if (isset($_GET['settings-updated']) && $_GET['settings-updated']) {
            add_settings_error(
                'wp_persian_datepicker_messages',
                'wp_persian_datepicker_message',
                esc_html__('Settings saved.', 'wp-persian-datepicker-element'),
                'updated'
            );
        }
        
        // Show settings errors
        settings_errors('wp_persian_datepicker_messages');
        
        // اضافه کردن کلاس RTL برای زبان فارسی
        $rtl_class = (determine_locale() === 'fa_IR') ? ' rtl' : '';
        
        // استایل های مخصوص فارسی
        if (determine_locale() === 'fa_IR') {
            ?>
            <style type="text/css">
                .rtl .form-table th {
                    text-align: right;
                    font-family: Tahoma, Arial;
                }
                .rtl h1, .rtl h2, .rtl h3, 
                .rtl .form-table td, 
                .rtl p, 
                .rtl .description, 
                .rtl label,
                .rtl .nav-tab {
                    font-family: Tahoma, Arial;
                }
                .rtl input[type="checkbox"] {
                    margin-left: 8px;
                    margin-right: 0;
                }
                .rtl code {
                    direction: ltr;
                    display: inline-block;
                }
                .rtl .wp-persian-datepicker-help .form-table th {
                    font-weight: normal;
                }
            </style>
            <?php
        }
        ?>
        
        <div class="wrap<?php echo esc_attr($rtl_class); ?>">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <div class="nav-tab-wrapper">
                <a href="?page=wp-persian-datepicker-settings" class="nav-tab nav-tab-active"><?php esc_html_e('Settings', 'wp-persian-datepicker-element'); ?></a>
                <a href="?page=wp-persian-datepicker-settings&tab=shortcode" class="nav-tab"><?php esc_html_e('Shortcode Usage', 'wp-persian-datepicker-element'); ?></a>
            </div>
            
            <?php
            $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'settings';
            
            if ($active_tab === 'shortcode') {
                $this->display_shortcode_help();
            } else {
                // Display the settings form
                ?>
                <form action="options.php" method="post">
                    <?php
                    settings_fields('wp_persian_datepicker_options');
                    do_settings_sections('wp-persian-datepicker-settings');
                    submit_button();
                    ?>
                </form>
                
                <h2><?php esc_html_e('Preview', 'wp-persian-datepicker-element'); ?></h2>
                <div class="persian-datepicker-preview">
                    <?php 
                    // Create an instance of the shortcode class
                    $shortcode = new WP_Persian_Datepicker_Shortcode();
                    
                    // Output the datepicker using the current settings
                    echo $shortcode->render_shortcode(array()); 
                    ?>
                </div>
                <?php
            }
            ?>
        </div>
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
                    <th scope="row"><?php esc_html_e('Full Example', 'wp-persian-datepicker-element'); ?></th>
                    <td><code>[persian_datepicker placeholder="انتخاب تاریخ" format="YYYY/MM/DD" show_holidays="true" rtl="true" holiday_types="Iran,International" range_mode="false" dark_mode="false"]</code></td>
                </tr>
            </table>
            
            <h3><?php esc_html_e('Gutenberg Block', 'wp-persian-datepicker-element'); ?></h3>
            <p><?php esc_html_e('You can also use the Persian Date Picker as a Gutenberg block. Simply search for "Persian Date Picker" in the block inserter.', 'wp-persian-datepicker-element'); ?></p>
        </div>
        <?php
    }
} 