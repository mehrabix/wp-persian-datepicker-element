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
                .integration-example {
                    background: #f8f8f8;
                    padding: 15px;
                    border-left: 4px solid #2271b1;
                    margin: 10px 0;
                }
                .code-block {
                    background: #f0f0f0;
                    padding: 10px;
                    border: 1px solid #ddd;
                    margin: 10px 0;
                    direction: ltr;
                    display: block;
                    overflow-x: auto;
                }
            </style>
            <?php
        }
        ?>
        
        <div class="wrap<?php echo esc_attr($rtl_class); ?>">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <div class="nav-tab-wrapper">
                <a href="?page=wp-persian-datepicker-settings" class="nav-tab <?php echo (empty($_GET['tab']) || $_GET['tab'] === 'settings') ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Settings', 'wp-persian-datepicker-element'); ?></a>
                <a href="?page=wp-persian-datepicker-settings&tab=shortcode" class="nav-tab <?php echo (isset($_GET['tab']) && $_GET['tab'] === 'shortcode') ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Shortcode Usage', 'wp-persian-datepicker-element'); ?></a>
                <a href="?page=wp-persian-datepicker-settings&tab=integration" class="nav-tab <?php echo (isset($_GET['tab']) && $_GET['tab'] === 'integration') ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Integration Guide', 'wp-persian-datepicker-element'); ?></a>
            </div>
            
            <?php
            $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'settings';
            
            if ($active_tab === 'shortcode') {
                $this->display_shortcode_help();
            } elseif ($active_tab === 'integration') {
                $this->display_integration_guide();
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
        </div>
        <?php
    }
} 