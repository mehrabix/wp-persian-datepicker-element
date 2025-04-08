/**
 * Admin JavaScript for WP Persian Datepicker Element
 *
 * @package    WP_Persian_Datepicker_Element
 * @subpackage WP_Persian_Datepicker_Element/admin/js
 */

(function($) {
    'use strict';

    // Wait for document and custom elements to be ready
    $(document).ready(function() {
        // Wait a moment to ensure Web Components are registered
        setTimeout(function() {
            initAdmin();
        }, 200);
    });
    
    function initAdmin() {
        console.log('WP Persian Datepicker admin initialized');
        
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
        
        // Make sure the tab content is properly initialized
        // Hide all tabs except the active one
        var activeTab = $('.nav-tab-active').attr('href');
        if (activeTab && activeTab.indexOf('#') === 0) {
            var activeTabId = activeTab.substring(1);
            $('.tab-content').hide();
            $('#' + activeTabId).show();
        }
        
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
                console.log('Updating preview datepicker');
                
                // Get current values from form
                var placeholder = $('#wp_persian_datepicker_placeholder').val();
                var format = $('#wp_persian_datepicker_format').val();
                var showHolidays = $('#wp_persian_datepicker_show_holidays').is(':checked');
                var rtl = $('#wp_persian_datepicker_rtl').is(':checked');
                var darkMode = $('#wp_persian_datepicker_dark_mode').is(':checked');
                var holidayTypes = $('#wp_persian_datepicker_holiday_types').val();
                var rangeMode = $('#wp_persian_datepicker_range_mode').is(':checked');
                
                console.log('Preview settings:', { 
                    placeholder: placeholder, 
                    format: format, 
                    showHolidays: showHolidays, 
                    rtl: rtl,
                    darkMode: darkMode,
                    holidayTypes: holidayTypes,
                    rangeMode: rangeMode
                });
                
                // Create a new datepicker element to replace the old one
                var newElement = document.createElement('persian-datepicker-element');
                newElement.id = 'preview-datepicker';
                
                // Update attributes
                if (placeholder) {
                    newElement.setAttribute('placeholder', placeholder);
                }
                
                if (format) {
                    newElement.setAttribute('format', format);
                }
                
                newElement.setAttribute('show-holidays', showHolidays ? 'true' : 'false');
                newElement.setAttribute('rtl', rtl ? 'true' : 'false');
                newElement.setAttribute('darkmode', darkMode ? 'true' : 'false');
                
                if (holidayTypes) {
                    newElement.setAttribute('holiday-types', holidayTypes);
                }
                
                newElement.setAttribute('range-mode', rangeMode ? 'true' : 'false');
                
                // Replace the old element with the new one
                $preview.replaceWith(newElement);
                
                // Handle special styles based on settings
                var $previewContainer = $('.preview-datepicker');
                if (darkMode) {
                    $previewContainer.addClass('dark-theme');
                } else {
                    $previewContainer.removeClass('dark-theme');
                }
            } else {
                console.log('Preview datepicker not found in the DOM');
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
    }

})(jQuery); 