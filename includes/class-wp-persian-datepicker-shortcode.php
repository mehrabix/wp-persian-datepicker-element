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
        
        // Define default attributes
        $default_atts = array(
            'placeholder' => isset($options['placeholder']) ? $options['placeholder'] : 'انتخاب تاریخ',
            'format' => isset($options['format']) ? $options['format'] : 'YYYY/MM/DD',
            'show_holidays' => isset($options['show_holidays']) ? $options['show_holidays'] : true,
            'rtl' => isset($options['rtl']) ? $options['rtl'] : true,
            'dark_mode' => isset($options['dark_mode']) ? $options['dark_mode'] : false,
            'holiday_types' => isset($options['holiday_types']) ? $options['holiday_types'] : 'Iran,International',
            'range_mode' => isset($options['range_mode']) ? $options['range_mode'] : false,
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
        );
        
        // Parse attributes
        $atts = shortcode_atts($default_atts, $atts, 'persian_datepicker');
        
        // Convert boolean attributes from string to actual boolean
        $bool_attrs = array('show_holidays', 'rtl', 'dark_mode', 'range_mode', 
                           'show_month_selector', 'show_year_selector', 
                           'show_prev_button', 'show_next_button', 
                           'show_today_button', 'show_tomorrow_button');
        
        foreach ($bool_attrs as $attr) {
            if (is_string($atts[$attr])) {
                $atts[$attr] = filter_var($atts[$attr], FILTER_VALIDATE_BOOLEAN);
            }
        }
        
        // Convert attributes with dashes to attributes with underscore
        // e.g. 'show-holidays' to 'show_holidays'
        $attributes = array();
        foreach ($atts as $key => $value) {
            $key_dash = str_replace('_', '-', $key);
            
            // Skip empty attributes
            if ($value === '' && !in_array($key, $bool_attrs)) {
                continue;
            }
            
            // Convert boolean values to string representation
            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }
            
            $attributes[$key_dash] = $value;
        }
        
        // Special handling for class attribute
        $class = $attributes['class'] ?? '';
        unset($attributes['class']);
        
        // Build HTML attributes string
        $attr_str = '';
        foreach ($attributes as $key => $value) {
            $attr_str .= sprintf(' %s="%s"', esc_attr($key), esc_attr($value));
        }
        
        // Create the web component HTML
        $output = sprintf(
            '<persian-datepicker-element%s class="wp-persian-datepicker %s"></persian-datepicker-element>',
            $attr_str,
            esc_attr($class)
        );
        
        return $output;
    }
} 