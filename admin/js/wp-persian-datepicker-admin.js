/**
 * Admin JavaScript for WP Persian Datepicker Element
 *
 * @package    WP_Persian_Datepicker_Element
 * @subpackage WP_Persian_Datepicker_Element/admin/js
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        
        // Handle tab navigation
        $('.nav-tab-wrapper a').on('click', function(e) {
            // Add native tab functionality if not using links
            if ($(this).attr('href').indexOf('#') === 0) {
                e.preventDefault();
                
                // Update active tab
                $('.nav-tab-wrapper a').removeClass('nav-tab-active');
                $(this).addClass('nav-tab-active');
                
                // Show/hide tab content
                var target = $(this).attr('href').substring(1);
                $('.tab-content').hide();
                $('#' + target).show();
            }
        });
        
        // Initialize preview datepicker if available
        updatePreviewDatepicker();
        
        // Update the preview datepicker when settings change
        $('input[name^="wp_persian_datepicker_options"]').on('change input', function() {
            updatePreviewDatepicker();
        });
        
        /**
         * Update the preview datepicker with current settings
         */
        function updatePreviewDatepicker() {
            var $preview = $('.persian-datepicker-preview persian-datepicker-element');
            
            if ($preview.length) {
                // Get current values from form
                var placeholder = $('#wp_persian_datepicker_placeholder').val();
                var format = $('#wp_persian_datepicker_format').val();
                var showHolidays = $('#wp_persian_datepicker_show_holidays').is(':checked');
                var rtl = $('#wp_persian_datepicker_rtl').is(':checked');
                var darkMode = $('#wp_persian_datepicker_dark_mode').is(':checked');
                var holidayTypes = $('#wp_persian_datepicker_holiday_types').val();
                var rangeMode = $('#wp_persian_datepicker_range_mode').is(':checked');
                
                // Update attributes
                if (placeholder) {
                    $preview.attr('placeholder', placeholder);
                }
                
                if (format) {
                    $preview.attr('format', format);
                }
                
                $preview.attr('show-holidays', showHolidays.toString());
                $preview.attr('rtl', rtl.toString());
                $preview.attr('dark-mode', darkMode.toString());
                
                if (holidayTypes) {
                    $preview.attr('holiday-types', holidayTypes);
                }
                
                $preview.attr('range-mode', rangeMode.toString());
                
                // Handle special styles based on settings
                if (darkMode) {
                    $preview.parent().addClass('dark-theme');
                } else {
                    $preview.parent().removeClass('dark-theme');
                }
            }
        }
        
        // Copy shortcode to clipboard when clicked
        $('.wp-persian-datepicker-help code').on('click', function() {
            var $this = $(this);
            var tempInput = $('<input>');
            $('body').append(tempInput);
            tempInput.val($this.text()).select();
            document.execCommand('copy');
            tempInput.remove();
            
            // Show copied message
            var $message = $('<span class="copied-message">Copied!</span>');
            $this.after($message);
            
            setTimeout(function() {
                $message.fadeOut(function() {
                    $(this).remove();
                });
            }, 1500);
        });
    });

})(jQuery); 