<?php
/**
 * The widget functionality of the plugin.
 *
 * @since      1.0.0
 * @package    WP_Persian_Datepicker_Element
 * @subpackage WP_Persian_Datepicker_Element/includes
 */

class WP_Persian_Datepicker_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'wp_persian_datepicker_widget', // Base ID
            esc_html__('Persian Date Picker', 'wp-persian-datepicker-element'), // Name
            array('description' => esc_html__('A Persian (Jalali) date picker widget.', 'wp-persian-datepicker-element')) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        
        // Create shortcode attributes array
        $shortcode_atts = array(
            'placeholder' => !empty($instance['placeholder']) ? $instance['placeholder'] : '',
            'format' => !empty($instance['format']) ? $instance['format'] : '',
            'show_holidays' => !empty($instance['show_holidays']) ? 'true' : 'false',
            'rtl' => !empty($instance['rtl']) ? 'true' : 'false',
            'dark_mode' => !empty($instance['dark_mode']) ? 'true' : 'false',
            'holiday_types' => !empty($instance['holiday_types']) ? $instance['holiday_types'] : '',
            'range_mode' => !empty($instance['range_mode']) ? 'true' : 'false',
        );
        
        // Render the shortcode
        $shortcode = new WP_Persian_Datepicker_Shortcode();
        echo $shortcode->render_shortcode($shortcode_atts);
        
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Persian Date Picker', 'wp-persian-datepicker-element');
        $placeholder = !empty($instance['placeholder']) ? $instance['placeholder'] : esc_html__('انتخاب تاریخ', 'wp-persian-datepicker-element');
        $format = !empty($instance['format']) ? $instance['format'] : 'YYYY/MM/DD';
        $show_holidays = !empty($instance['show_holidays']) ? $instance['show_holidays'] : false;
        $rtl = !empty($instance['rtl']) ? $instance['rtl'] : true;
        $dark_mode = !empty($instance['dark_mode']) ? $instance['dark_mode'] : false;
        $holiday_types = !empty($instance['holiday_types']) ? $instance['holiday_types'] : 'Iran,International';
        $range_mode = !empty($instance['range_mode']) ? $instance['range_mode'] : false;
        ?>
        
        <!-- Title -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
                <?php esc_html_e('Title:', 'wp-persian-datepicker-element'); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('title')); ?>" 
                   type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        
        <!-- Placeholder -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('placeholder')); ?>">
                <?php esc_html_e('Placeholder:', 'wp-persian-datepicker-element'); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('placeholder')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('placeholder')); ?>" 
                   type="text" value="<?php echo esc_attr($placeholder); ?>">
        </p>
        
        <!-- Format -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('format')); ?>">
                <?php esc_html_e('Date Format:', 'wp-persian-datepicker-element'); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('format')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('format')); ?>" 
                   type="text" value="<?php echo esc_attr($format); ?>">
            <small><?php esc_html_e('e.g. YYYY/MM/DD or YYYY-MM-DD', 'wp-persian-datepicker-element'); ?></small>
        </p>
        
        <!-- Show Holidays -->
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_holidays); ?> 
                   id="<?php echo esc_attr($this->get_field_id('show_holidays')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('show_holidays')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_holidays')); ?>">
                <?php esc_html_e('Show Holidays', 'wp-persian-datepicker-element'); ?>
            </label>
        </p>
        
        <!-- RTL -->
        <p>
            <input class="checkbox" type="checkbox" <?php checked($rtl); ?> 
                   id="<?php echo esc_attr($this->get_field_id('rtl')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('rtl')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('rtl')); ?>">
                <?php esc_html_e('Right-to-Left (RTL)', 'wp-persian-datepicker-element'); ?>
            </label>
        </p>
        
        <!-- Dark Mode -->
        <p>
            <input class="checkbox" type="checkbox" <?php checked($dark_mode); ?> 
                   id="<?php echo esc_attr($this->get_field_id('dark_mode')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('dark_mode')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('dark_mode')); ?>">
                <?php esc_html_e('Dark Mode', 'wp-persian-datepicker-element'); ?>
            </label>
        </p>
        
        <!-- Holiday Types -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('holiday_types')); ?>">
                <?php esc_html_e('Holiday Types:', 'wp-persian-datepicker-element'); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('holiday_types')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('holiday_types')); ?>" 
                   type="text" value="<?php echo esc_attr($holiday_types); ?>">
            <small><?php esc_html_e('Comma-separated list: Iran,Afghanistan,AncientIran,International', 'wp-persian-datepicker-element'); ?></small>
        </p>
        
        <!-- Range Mode -->
        <p>
            <input class="checkbox" type="checkbox" <?php checked($range_mode); ?> 
                   id="<?php echo esc_attr($this->get_field_id('range_mode')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('range_mode')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('range_mode')); ?>">
                <?php esc_html_e('Range Mode', 'wp-persian-datepicker-element'); ?>
            </label>
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['placeholder'] = (!empty($new_instance['placeholder'])) ? sanitize_text_field($new_instance['placeholder']) : '';
        $instance['format'] = (!empty($new_instance['format'])) ? sanitize_text_field($new_instance['format']) : '';
        $instance['show_holidays'] = !empty($new_instance['show_holidays']);
        $instance['rtl'] = !empty($new_instance['rtl']);
        $instance['dark_mode'] = !empty($new_instance['dark_mode']);
        $instance['holiday_types'] = (!empty($new_instance['holiday_types'])) ? sanitize_text_field($new_instance['holiday_types']) : '';
        $instance['range_mode'] = !empty($new_instance['range_mode']);

        return $instance;
    }
} 