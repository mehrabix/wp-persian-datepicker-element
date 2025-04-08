<?php
/**
 * The shortcode functionality of the plugin.
 *
 * @since      1.0.0
 * @package    WP_Persian_Datepicker_Element
 * @subpackage WP_Persian_Datepicker_Element/includes
 */

class WP_Persian_Datepicker_Shortcode {

    /**
     * Render the shortcode.
     *
     * @since    1.0.0
     * @param    array    $atts    Shortcode attributes.
     * @return   string             The shortcode output.
     */
    public function render_shortcode($atts) {
        // Ensure scripts and styles are loaded
        $scripts = new WP_Persian_Datepicker_Scripts();
        $scripts->enqueue_scripts();
        
        // Get global plugin options
        $options = get_option('wp_persian_datepicker_options', array());
        
        // Log options for debugging
        error_log('WP Persian Datepicker Shortcode: Options = ' . print_r($options, true));
        
        // Define default attributes, using settings as defaults
        $default_atts = array(
            'placeholder' => isset($options['placeholder']) ? $options['placeholder'] : 'انتخاب تاریخ',
            'format' => isset($options['format']) ? $options['format'] : 'YYYY/MM/DD',
            'show_holidays' => isset($options['show_holidays']) ? (bool)$options['show_holidays'] : true,
            'rtl' => isset($options['rtl']) ? (bool)$options['rtl'] : true,
            'dark_mode' => isset($options['dark_mode']) ? (bool)$options['dark_mode'] : false,
            'holiday_types' => isset($options['holiday_types']) ? $options['holiday_types'] : 'Iran,International',
            'range_mode' => isset($options['range_mode']) ? (bool)$options['range_mode'] : false,
            'class' => '',
            'id' => 'pdp-' . uniqid(),
            'name' => '',
            'value' => '',
            'min_date' => '',
            'max_date' => '',
            'disabled_dates' => '',
            'show_month_selector' => true,
            'show_year_selector' => true,
            'show_prev_button' => true,
            'show_next_button' => true,
            'show_today_button' => true,
            'show_tomorrow_button' => false,
            'today_button_text' => 'امروز',
            'tomorrow_button_text' => 'فردا',
            'today_button_class' => '',
            'tomorrow_button_class' => '',
            'events_path' => PERSIAN_DATEPICKER_PLUGIN_URL . 'assets/data/events.json',
            // Enhanced customization options
            'auto_close' => true,
            'default_view' => 'day', // day, month, year
            'position' => 'bottom', // bottom, top, auto
            'custom_class' => '',
            'input_class' => '',
            'calendar_class' => '',
            'input_style' => '',
            'calendar_style' => '',
            'use_current_on_init' => false,
            'clear_button' => false,
            'clear_button_text' => 'پاک کردن',
            'close_on_select' => true,
            'inline_mode' => false,
            'readable_format' => '',
            'highlight_today' => true,
            'highlight_holidays' => true,
            'initial_value' => '',
            'calendar_height' => '',
            'calendar_width' => '',
            'header_size' => 'default', // small, default, large
            'day_names_type' => 'default', // default, short, min
            'theme' => '', // bootstrap, material, custom
            'on_init_js' => '',
            'on_select_js' => '',
            'on_open_js' => '',
            'on_close_js' => '',
        );
        
        // Parse attributes
        $atts = shortcode_atts($default_atts, $atts, 'persian_datepicker');
        
        // Convert boolean attributes from string to actual boolean
        $bool_attrs = array(
            'show_holidays', 'rtl', 'dark_mode', 'range_mode', 
            'show_month_selector', 'show_year_selector', 
            'show_prev_button', 'show_next_button', 
            'show_today_button', 'show_tomorrow_button',
            'auto_close', 'use_current_on_init', 'clear_button',
            'close_on_select', 'inline_mode', 'highlight_today',
            'highlight_holidays'
        );
        
        foreach ($bool_attrs as $attr) {
            if (is_string($atts[$attr])) {
                $atts[$attr] = filter_var($atts[$attr], FILTER_VALIDATE_BOOLEAN);
            }
        }
        
        // Initialize the attribute string
        $attr_str = '';
        
        // Convert attributes with dashes to attributes with underscore
        // e.g. 'show-holidays' to 'show_holidays'
        $attributes = array();
        foreach ($atts as $key => $value) {
            // Skip reserved attributes that should be handled separately
            if (in_array($key, array('class', 'custom_class', 'input_class', 'calendar_class', 
                                     'input_style', 'calendar_style', 'on_init_js', 'on_select_js',
                                     'on_open_js', 'on_close_js'))) {
                continue;
            }
            
            // Convert underscore to dash for HTML attribute
            $key_dash = str_replace('_', '-', $key);
            
            // Skip empty attributes
            if ($value === '' && !in_array($key, $bool_attrs)) {
                continue;
            }
            
            // Special handling for range_mode attribute to ensure it works correctly
            if ($key === 'range_mode') {
                // Make sure it's converted to a proper boolean for output purposes
                $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                
                // Always add the attribute with an explicit string value 
                // to ensure it's correctly recognized by the web component
                $attr_str .= ' range-mode="' . ($value ? 'true' : 'false') . '"';
                
                // Log for debugging
                error_log('WP Persian Datepicker: Setting range-mode attribute to: ' . ($value ? 'true' : 'false'));
                
                // Skip the normal attribute processing
                continue;
            }
            
            // Handle boolean attributes properly for HTML5 custom elements
            if (is_bool($value)) {
                if ($value === true) {
                    // For true values, just add the attribute without a value (HTML5 boolean attribute)
                    $attributes[$key_dash] = true;
                } else {
                    // Skip false boolean attributes entirely
                    continue;
                }
            } else {
                // Regular attributes
                $attributes[$key_dash] = $value;
            }
        }
        
        // Special handling for class attribute
        $class = array('wp-persian-datepicker');
        
        // Add custom classes if provided
        if (!empty($atts['class'])) {
            $class[] = $atts['class'];
        }
        
        if (!empty($atts['custom_class'])) {
            $class[] = $atts['custom_class'];
        }
        
        // Build additional custom styles/classes
        $custom_styles = '';
        $input_class = !empty($atts['input_class']) ? ' input-class="' . esc_attr($atts['input_class']) . '"' : '';
        $calendar_class = !empty($atts['calendar_class']) ? ' calendar-class="' . esc_attr($atts['calendar_class']) . '"' : '';
        
        if (!empty($atts['input_style'])) {
            $custom_styles .= ' input-style="' . esc_attr($atts['input_style']) . '"';
        }
        
        if (!empty($atts['calendar_style'])) {
            $custom_styles .= ' calendar-style="' . esc_attr($atts['calendar_style']) . '"';
        }
        
        // Event handlers for JavaScript
        $js_handlers = '';
        if (!empty($atts['on_init_js'])) {
            $js_handlers .= ' data-on-init="' . esc_attr($atts['on_init_js']) . '"';
        }
        
        if (!empty($atts['on_select_js'])) {
            $js_handlers .= ' data-on-select="' . esc_attr($atts['on_select_js']) . '"';
        }
        
        if (!empty($atts['on_open_js'])) {
            $js_handlers .= ' data-on-open="' . esc_attr($atts['on_open_js']) . '"';
        }
        
        if (!empty($atts['on_close_js'])) {
            $js_handlers .= ' data-on-close="' . esc_attr($atts['on_close_js']) . '"';
        }
        
        // Build HTML attributes string
        foreach ($attributes as $key => $value) {
            if ($value === true) {
                // HTML5 boolean attribute (just the name, no value)
                $attr_str .= ' ' . esc_attr($key);
            } else if (in_array(str_replace('-', '_', $key), $bool_attrs)) {
                // For other boolean attributes also ensure explicit value
                $attr_str .= sprintf(' %s="%s"', esc_attr($key), $value === true || $value === 'true' ? 'true' : 'false');
            } else {
                // Regular attribute with value
                $attr_str .= sprintf(' %s="%s"', esc_attr($key), esc_attr($value));
            }
        }
        
        // Create the web component HTML
        $output = sprintf(
            '<persian-datepicker-element%s class="%s"%s%s%s%s></persian-datepicker-element>',
            $attr_str,
            esc_attr(implode(' ', $class)),
            $input_class,
            $calendar_class,
            $custom_styles,
            $js_handlers
        );
        
        // Add script for event handling if any JS handlers were specified
        if (!empty($atts['on_init_js']) || !empty($atts['on_select_js']) || 
            !empty($atts['on_open_js']) || !empty($atts['on_close_js'])) {
            $output .= '<script>
            document.addEventListener("DOMContentLoaded", function() {
                const datepicker = document.getElementById("' . esc_attr($atts['id']) . '");
                if (!datepicker) return;
                
                // Init handler
                if (datepicker.hasAttribute("data-on-init")) {
                    const initFn = new Function("event", datepicker.getAttribute("data-on-init"));
                    datepicker.addEventListener("initialized", initFn);
                }
                
                // Select handler
                if (datepicker.hasAttribute("data-on-select")) {
                    const selectFn = new Function("event", datepicker.getAttribute("data-on-select"));
                    datepicker.addEventListener("dateSelected", selectFn);
                }
                
                // Open handler
                if (datepicker.hasAttribute("data-on-open")) {
                    const openFn = new Function("event", datepicker.getAttribute("data-on-open"));
                    datepicker.addEventListener("open", openFn);
                }
                
                // Close handler
                if (datepicker.hasAttribute("data-on-close")) {
                    const closeFn = new Function("event", datepicker.getAttribute("data-on-close"));
                    datepicker.addEventListener("close", closeFn);
                }
            });
            </script>';
        }
        
        return $output;
    }
} 