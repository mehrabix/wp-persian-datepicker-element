/**
 * Frontend integration JavaScript for WP Persian Datepicker Element
 *
 * @package    WP_Persian_Datepicker_Element
 * @subpackage WP_Persian_Datepicker_Element/assets/js/frontend
 */

(function() {
    'use strict';

    // تنظیم مسیر فایل events.json
    window.persianDatepickerEventsPath = wpPersianDatepickerOptions.plugin_url + 'assets/data/events.json';

    // تعریف متغیرهای برای ادغام با افزونه‌های دیگر
    const integrations = {
        cf7: false,
        woocommerce: false,
        gravity_forms: false,
        wpforms: false
    };

    // بررسی وضعیت ادغام با افزونه‌های دیگر
    if (typeof wpPersianDatepickerIntegrations !== 'undefined') {
        console.log('Persian Datepicker Active Integrations:', wpPersianDatepickerIntegrations);
        integrations.cf7 = wpPersianDatepickerIntegrations.cf7 || false;
        integrations.woocommerce = wpPersianDatepickerIntegrations.woocommerce || false;
        integrations.gravity_forms = wpPersianDatepickerIntegrations.gravity_forms || false;
        integrations.wpforms = wpPersianDatepickerIntegrations.wpforms || false;
    }

    // When the DOM is fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize built-in datepickers
        initializeAllDatepickers();

        // Transform regular inputs into datepickers if they have the class
        transformInputsToDatepickers();

        // Handle dynamically added datepickers
        observeNewDatepickers();

        // وقتی صفحه بارگذاری شد
        const datepickers = document.querySelectorAll('persian-datepicker-element');
        
        // پردازش تنظیمات از وردپرس
        if (datepickers.length > 0 && typeof wpPersianDatepickerOptions !== 'undefined') {
            // تنظیم مسیر فایل تعطیلات برای همه کامپوننت‌ها
            datepickers.forEach(function(datepicker) {
                // کمک به کامپوننت برای یافتن مسیر فایل events.json
                datepicker.addEventListener('persianDatepickerEventsPath', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    datepicker.setAttribute('events-path', window.persianDatepickerEventsPath);
                });
                
                // یک رویداد مصنوعی برای درخواست مسیر فایل events.json تولید کنیم
                datepicker.dispatchEvent(new CustomEvent('persianDatepickerEventsPathRequest'));
            });
        }
    });

    /**
     * Initialize all datepickers on the page
     */
    function initializeAllDatepickers() {
        // Get all datepickers
        var datepickers = document.querySelectorAll('persian-datepicker-element.wp-persian-datepicker');
        
        datepickers.forEach(function(datepicker) {
            setupDatepickerIntegration(datepicker);
        });
    }

    /**
     * Transform regular inputs with persian-datepicker class into datepicker elements
     */
    function transformInputsToDatepickers() {
        // Find all inputs with the persian-datepicker class
        var inputs = document.querySelectorAll('input.persian-datepicker');
        
        inputs.forEach(function(input) {
            // Don't transform if already transformed
            if (input.dataset.datepickerTransformed === 'true') {
                return;
            }
            
            // Create the datepicker element
            var datepicker = document.createElement('persian-datepicker-element');
            datepicker.className = 'wp-persian-datepicker';
            
            // Generate a unique ID if none exists
            var inputId = input.id || 'pdp-input-' + generateUniqueId();
            input.id = inputId;
            
            // Set ID for the datepicker
            datepicker.id = 'pdp-' + inputId;
            
            // Copy attributes from input to datepicker
            copyDataAttributesToElement(input, datepicker);
            
            // Preserve the original input for form submission but hide it
            input.style.display = 'none';
            input.dataset.datepickerTransformed = 'true';
            
            // Insert the datepicker after the input
            input.parentNode.insertBefore(datepicker, input.nextSibling);
            
            // Setup event handling to sync values
            datepicker.addEventListener('dateSelected', function(e) {
                if (e.detail && e.detail.formattedDate) {
                    input.value = e.detail.formattedDate;
                    triggerChangeEvent(input);
                }
            });
            
            // If input has value, set it in the datepicker
            if (input.value) {
                datepicker.setAttribute('value', input.value);
            }
            
            // Setup integration
            setupDatepickerIntegration(datepicker);
        });
        
        // Special handling for Contact Form 7 - only if active
        if (integrations.cf7) {
            setupContactForm7Integration();
        }
        
        // Special handling for WPForms - only if active
        if (integrations.wpforms) {
            setupWPFormsIntegration();
        }
        
        // Special handling for Gravity Forms - only if active
        if (integrations.gravity_forms) {
            setupGravityFormsIntegration();
        }
        
        // Special handling for WooCommerce - only if active
        if (integrations.woocommerce) {
            setupWooCommerceIntegration();
        }
    }

    /**
     * Copy data attributes from input to datepicker element
     */
    function copyDataAttributesToElement(input, element) {
        // Copy standard attributes
        var attributes = [
            'placeholder', 'name', 'required', 'aria-required', 
            'aria-invalid', 'aria-describedby'
        ];
        
        attributes.forEach(function(attr) {
            if (input.hasAttribute(attr)) {
                element.setAttribute(attr, input.getAttribute(attr));
            }
        });
        
        // Copy all data attributes with the specific prefix
        Array.from(input.attributes).forEach(function(attr) {
            if (attr.name.startsWith('data-')) {
                // Convert data-attribute to attribute format 
                // e.g., data-show-holidays to show-holidays
                var newAttrName = attr.name.replace('data-', '');
                element.setAttribute(newAttrName, attr.value);
            }
        });
        
        // Set up form-specific attributes
        if (input.form) {
            element.setAttribute('form', input.form.id);
        }
    }

    /**
     * Setup Contact Form 7 integration
     */
    function setupContactForm7Integration() {
        if (!integrations.cf7) {
            return;
        }
        
        // Check if Contact Form 7 is active by looking for its elements
        var cf7Forms = document.querySelectorAll('.wpcf7-form');
        
        // Only proceed if Contact Form 7 forms exist
        if (cf7Forms.length === 0) {
            return;
        }
        
        console.log('Setting up Contact Form 7 integration');
        
        cf7Forms.forEach(function(form) {
            // Find all date inputs in CF7 forms
            var dateInputs = form.querySelectorAll('.wpcf7-date, input.persian-datepicker');
            
            dateInputs.forEach(function(input) {
                // Skip if already transformed
                if (input.dataset.datepickerTransformed === 'true') {
                    return;
                }
                
                // Add the persian-datepicker class to ensure it's processed
                input.classList.add('persian-datepicker');
                
                // Mark for re-processing
                input.dataset.datepickerTransforming = 'true';
            });
            
            // Re-process any marked inputs
            var transformingInputs = form.querySelectorAll('input[data-datepicker-transforming="true"]');
            transformingInputs.forEach(function(input) {
                input.dataset.datepickerTransforming = 'false';
                transformInputToDatepicker(input);
            });
            
            // Handle CF7 AJAX form submission and reset
            if (typeof window.wpcf7 !== 'undefined') {
                document.addEventListener('wpcf7submit', function(e) {
                    if (e.detail.contactFormId && form.querySelector('[data-contactform-id="' + e.detail.contactFormId + '"]')) {
                        // Reset datepickers in this form
                        var datepickers = form.querySelectorAll('persian-datepicker-element');
                        datepickers.forEach(function(datepicker) {
                            if (typeof datepicker.reset === 'function') {
                                datepicker.reset();
                            }
                        });
                    }
                });
            }
        });
    }

    /**
     * Transform a single input into a datepicker
     */
    function transformInputToDatepicker(input) {
        // Create the datepicker element
        var datepicker = document.createElement('persian-datepicker-element');
        datepicker.className = 'wp-persian-datepicker';
        
        // Generate a unique ID if none exists
        var inputId = input.id || 'pdp-input-' + generateUniqueId();
        input.id = inputId;
        
        // Set ID for the datepicker
        datepicker.id = 'pdp-' + inputId;
        
        // Copy attributes from input to datepicker
        copyDataAttributesToElement(input, datepicker);
        
        // Preserve the original input for form submission but hide it
        input.style.display = 'none';
        input.dataset.datepickerTransformed = 'true';
        
        // Insert the datepicker after the input
        input.parentNode.insertBefore(datepicker, input.nextSibling);
        
        // Setup event handling to sync values
        datepicker.addEventListener('dateSelected', function(e) {
            if (e.detail && e.detail.formattedDate) {
                input.value = e.detail.formattedDate;
                triggerChangeEvent(input);
            }
        });
        
        // If input has value, set it in the datepicker
        if (input.value) {
            datepicker.setAttribute('value', input.value);
        }
        
        // Setup integration
        setupDatepickerIntegration(datepicker);
        
        return datepicker;
    }

    /**
     * Setup WPForms integration
     */
    function setupWPFormsIntegration() {
        if (!integrations.wpforms) {
            return;
        }
        
        // Check if WPForms is active by looking for its forms
        var wpformsForms = document.querySelectorAll('.wpforms-form');
        
        // Only proceed if WPForms forms exist
        if (wpformsForms.length === 0) {
            return;
        }
        
        console.log('Setting up WPForms integration');
        
        wpformsForms.forEach(function(form) {
            // Find date inputs
            var dateInputs = form.querySelectorAll('input.wpforms-field-date-time-date, input.persian-date');
            
            dateInputs.forEach(function(input) {
                // Skip if already transformed
                if (input.dataset.datepickerTransformed === 'true') {
                    return;
                }
                
                // Add the persian-datepicker class to ensure it's processed
                input.classList.add('persian-datepicker');
                
                // Mark for re-processing
                input.dataset.datepickerTransforming = 'true';
            });
            
            // Re-process any marked inputs
            var transformingInputs = form.querySelectorAll('input[data-datepicker-transforming="true"]');
            transformingInputs.forEach(function(input) {
                input.dataset.datepickerTransforming = 'false';
                transformInputToDatepicker(input);
            });
        });
        
        // Handle WPForms form submission events if the WPForms JavaScript is available
        if (typeof window.wpforms !== 'undefined') {
            document.addEventListener('wpformsBeforeFormSubmit', function(e) {
                // Reset datepickers after form submission
                if (e.detail && e.detail.formID) {
                    var form = document.querySelector('#wpforms-form-' + e.detail.formID);
                    if (form) {
                        // Wait for form submission to complete
                        setTimeout(function() {
                            var datepickers = form.querySelectorAll('persian-datepicker-element');
                            datepickers.forEach(function(datepicker) {
                                if (typeof datepicker.reset === 'function') {
                                    datepicker.reset();
                                }
                            });
                        }, 500);
                    }
                }
            });
        }
    }

    /**
     * Setup Gravity Forms integration
     */
    function setupGravityFormsIntegration() {
        if (!integrations.gravity_forms) {
            return;
        }
        
        // Check if Gravity Forms is active by looking for its wrappers
        var gravityForms = document.querySelectorAll('.gform_wrapper');
        
        // Only proceed if Gravity Forms exist
        if (gravityForms.length === 0) {
            return;
        }
        
        console.log('Setting up Gravity Forms integration');
        
        gravityForms.forEach(function(formWrapper) {
            // Find date inputs
            var dateInputs = formWrapper.querySelectorAll('input.datepicker, input.persian-date');
            
            dateInputs.forEach(function(input) {
                // Skip if already transformed
                if (input.dataset.datepickerTransformed === 'true') {
                    return;
                }
                
                // Add the persian-datepicker class to ensure it's processed
                input.classList.add('persian-datepicker');
                
                // Mark for re-processing
                input.dataset.datepickerTransforming = 'true';
            });
            
            // Re-process any marked inputs
            var transformingInputs = formWrapper.querySelectorAll('input[data-datepicker-transforming="true"]');
            transformingInputs.forEach(function(input) {
                input.dataset.datepickerTransforming = 'false';
                transformInputToDatepicker(input);
            });
        });
        
        // Handle Gravity Forms AJAX submissions if GF JavaScript is available
        if (typeof window.gform !== 'undefined') {
            // Listen for form render event
            document.addEventListener('gform_post_render', function() {
                // Re-run the transformation for the rendered form
                setupGravityFormsIntegration();
            });
        }
    }

    /**
     * Setup WooCommerce integration
     */
    function setupWooCommerceIntegration() {
        if (!integrations.woocommerce) {
            return;
        }
        
        // Check if WooCommerce is active by looking for its elements
        var wooCheckout = document.querySelector('.woocommerce-checkout');
        var wooAccount = document.querySelector('.woocommerce-account');
        
        // Only proceed if WooCommerce elements exist
        if (!wooCheckout && !wooAccount) {
            return;
        }
        
        console.log('Setting up WooCommerce integration');
        
        // Find date inputs in WooCommerce checkout
        if (wooCheckout) {
            var checkoutDateInputs = wooCheckout.querySelectorAll('input[type="date"], input.date-picker, input.persian-date');
            
            checkoutDateInputs.forEach(function(input) {
                // Skip if already transformed
                if (input.dataset.datepickerTransformed === 'true') {
                    return;
                }
                
                // Add the persian-datepicker class and transform
                input.classList.add('persian-datepicker');
                transformInputToDatepicker(input);
            });
            
            // Handle checkout form updates
            if (typeof window.jQuery !== 'undefined' && typeof window.jQuery.fn.woocommerce_checkout_form === 'function') {
                jQuery(document.body).on('updated_checkout', function() {
                    // Re-run WooCommerce integration after checkout is updated
                    setupWooCommerceIntegration();
                });
            }
        }
        
        // Find date inputs in WooCommerce account pages
        if (wooAccount) {
            var accountDateInputs = wooAccount.querySelectorAll('input[type="date"], input.date-picker, input.persian-date');
            
            accountDateInputs.forEach(function(input) {
                // Skip if already transformed
                if (input.dataset.datepickerTransformed === 'true') {
                    return;
                }
                
                // Add the persian-datepicker class and transform
                input.classList.add('persian-datepicker');
                transformInputToDatepicker(input);
            });
        }
    }

    /**
     * Setup event handlers and integration for a datepicker
     */
    function setupDatepickerIntegration(datepicker) {
        // Add event listeners
        datepicker.addEventListener('change', function(event) {
            handleDatepickerChange(event, datepicker);
        });

        // Initialize with default values
        initializeWithDefaultValues(datepicker);
    }

    /**
     * Initialize datepicker with default values based on attributes
     */
    function initializeWithDefaultValues(datepicker) {
        // Check for hidden input field to populate with initial value
        var hiddenInput = getAssociatedHiddenInput(datepicker);
        if (hiddenInput && hiddenInput.value) {
            try {
                var initialDate = JSON.parse(hiddenInput.value);
                if (Array.isArray(initialDate) && initialDate.length === 3) {
                    // Set the value programmatically
                    if (typeof datepicker.setValue === 'function') {
                        datepicker.setValue(initialDate[0], initialDate[1], initialDate[2]);
                    }
                }
            } catch (e) {
                console.error('Error parsing initial date value:', e);
            }
        }

        // Apply WordPress specific behaviors
        applyWordPressIntegration(datepicker);
    }

    /**
     * Handle datepicker change event
     */
    function handleDatepickerChange(event, datepicker) {
        // Get the associated hidden input
        var hiddenInput = getAssociatedHiddenInput(datepicker);
        
        if (hiddenInput) {
            // Range mode handling
            if (event.detail && event.detail.isRange) {
                // Store range as stringified JSON
                var rangeValue = {
                    start: event.detail.range.start,
                    end: event.detail.range.end
                };
                hiddenInput.value = JSON.stringify(rangeValue);
            } 
            // Single date handling
            else if (event.detail && event.detail.jalali) {
                // Store as stringified JSON array [year, month, day]
                hiddenInput.value = JSON.stringify(event.detail.jalali);
                
                // Update any associated display elements
                updateDisplayElements(datepicker, event.detail);
            }
            
            // Trigger change event for form validation
            triggerChangeEvent(hiddenInput);
        }
        
        // Dispatch a custom event that WordPress plugins can listen for
        dispatchWPEvent(datepicker, 'wp-persian-datepicker:change', event.detail);
    }

    /**
     * Get or create the associated hidden input field
     */
    function getAssociatedHiddenInput(datepicker) {
        var id = datepicker.getAttribute('id') || 'pdp-' + generateUniqueId();
        var hiddenInputId = id + '-hidden';
        
        // Check if hidden input already exists
        var hiddenInput = document.getElementById(hiddenInputId);
        
        // If not, create it
        if (!hiddenInput) {
            hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.id = hiddenInputId;
            hiddenInput.name = datepicker.getAttribute('name') || id;
            hiddenInput.className = 'wp-persian-datepicker-hidden';
            
            // Insert after the datepicker
            datepicker.parentNode.insertBefore(hiddenInput, datepicker.nextSibling);
        }
        
        return hiddenInput;
    }

    /**
     * Update any display elements associated with the datepicker
     */
    function updateDisplayElements(datepicker, detail) {
        var displaySelector = datepicker.getAttribute('data-display');
        
        if (displaySelector) {
            var displayElements = document.querySelectorAll(displaySelector);
            
            displayElements.forEach(function(element) {
                // Update with formatted date
                if (detail.formattedDate) {
                    element.textContent = detail.formattedDate;
                }
            });
        }
    }

    /**
     * Apply WordPress specific integrations
     */
    function applyWordPressIntegration(datepicker) {
        // Special integration with common WordPress form plugins - only if they're active
        
        // Gravity Forms integration
        if (integrations.gravity_forms && datepicker.closest('.gform_wrapper')) {
            datepicker.classList.add('gform-datepicker');
        }
        
        // Contact Form 7 integration
        if (integrations.cf7 && datepicker.closest('.wpcf7-form')) {
            datepicker.classList.add('wpcf7-datepicker');
        }
        
        // WooCommerce integration
        if (integrations.woocommerce && 
            (datepicker.closest('.woocommerce-checkout') || datepicker.closest('.woocommerce-account'))) {
            datepicker.classList.add('woocommerce-datepicker');
        }
        
        // Apply default options from WordPress settings
        if (typeof wpPersianDatepickerOptions !== 'undefined') {
            console.log('WP Persian Datepicker Options:', wpPersianDatepickerOptions);
            
            // Only apply if attribute is not already set
            if (!datepicker.hasAttribute('placeholder') && wpPersianDatepickerOptions.placeholder) {
                datepicker.setAttribute('placeholder', wpPersianDatepickerOptions.placeholder);
            }
            
            if (!datepicker.hasAttribute('format') && wpPersianDatepickerOptions.format) {
                datepicker.setAttribute('format', wpPersianDatepickerOptions.format);
            }
            
            if (!datepicker.hasAttribute('rtl') && typeof wpPersianDatepickerOptions.rtl !== 'undefined') {
                const rtlValue = wpPersianDatepickerOptions.rtl === '1' || wpPersianDatepickerOptions.rtl === 1 || wpPersianDatepickerOptions.rtl === true;
                datepicker.setAttribute('rtl', rtlValue ? 'true' : 'false');
            }
            
            if (!datepicker.hasAttribute('show-holidays') && typeof wpPersianDatepickerOptions.show_holidays !== 'undefined') {
                const showHolidaysValue = wpPersianDatepickerOptions.show_holidays === '1' || wpPersianDatepickerOptions.show_holidays === 1 || wpPersianDatepickerOptions.show_holidays === true;
                datepicker.setAttribute('show-holidays', showHolidaysValue ? 'true' : 'false');
            }
            
            if (!datepicker.hasAttribute('holiday-types') && wpPersianDatepickerOptions.holiday_types) {
                datepicker.setAttribute('holiday-types', wpPersianDatepickerOptions.holiday_types);
            }
            
            if (!datepicker.hasAttribute('dark-mode') && typeof wpPersianDatepickerOptions.dark_mode !== 'undefined') {
                const darkModeValue = wpPersianDatepickerOptions.dark_mode === '1' || wpPersianDatepickerOptions.dark_mode === 1 || wpPersianDatepickerOptions.dark_mode === true;
                datepicker.setAttribute('dark-mode', darkModeValue ? 'true' : 'false');
            }
            
            if (!datepicker.hasAttribute('range-mode') && typeof wpPersianDatepickerOptions.range_mode !== 'undefined') {
                // Log the raw value for debugging
                console.log('Range Mode Raw Value:', wpPersianDatepickerOptions.range_mode, 'Type:', typeof wpPersianDatepickerOptions.range_mode);
                
                // Explicitly convert to boolean using strict comparison
                const rangeModeValue = wpPersianDatepickerOptions.range_mode === '1' || wpPersianDatepickerOptions.range_mode === 1 || wpPersianDatepickerOptions.range_mode === true;
                console.log('Range Mode Converted Value:', rangeModeValue);
                
                // Set the attribute as string 'true' or 'false'
                datepicker.setAttribute('range-mode', rangeModeValue ? 'true' : 'false');
                console.log('Range Mode Attribute Set:', datepicker.getAttribute('range-mode'));
            }
        }
    }

    /**
     * Generate a unique ID for elements
     */
    function generateUniqueId() {
        return 'pdp-' + Math.random().toString(36).substring(2, 11);
    }

    /**
     * Trigger a change event on an element
     */
    function triggerChangeEvent(element) {
        // Create and dispatch the event
        var event = new Event('change', {
            bubbles: true,
            cancelable: true
        });
        
        element.dispatchEvent(event);
    }

    /**
     * Dispatch a custom WordPress event
     */
    function dispatchWPEvent(element, eventName, detail) {
        var event = new CustomEvent(eventName, {
            bubbles: true,
            cancelable: true,
            detail: detail
        });
        
        element.dispatchEvent(event);
    }

    /**
     * Observe for dynamically added datepickers
     */
    function observeNewDatepickers() {
        // Only run if MutationObserver is supported
        if (typeof MutationObserver !== 'function') {
            return;
        }
        
        var observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList' && mutation.addedNodes.length) {
                    mutation.addedNodes.forEach(function(node) {
                        // If the node is an element
                        if (node.nodeType === 1) {
                            // Check if it's a datepicker
                            if (node.tagName.toLowerCase() === 'persian-datepicker-element' && 
                                node.classList.contains('wp-persian-datepicker')) {
                                setupDatepickerIntegration(node);
                            }
                            
                            // Check for datepickers inside the added node
                            var childDatepickers = node.querySelectorAll('persian-datepicker-element.wp-persian-datepicker');
                            if (childDatepickers.length) {
                                childDatepickers.forEach(function(datepicker) {
                                    setupDatepickerIntegration(datepicker);
                                });
                            }
                            
                            // Check for inputs that need transformation
                            var datepickerInputs = node.querySelectorAll('input.persian-datepicker');
                            if (datepickerInputs.length) {
                                datepickerInputs.forEach(function(input) {
                                    if (!input.dataset.datepickerTransformed) {
                                        transformInputToDatepicker(input);
                                    }
                                });
                            }
                        }
                    });
                }
            });
        });
        
        // Start observing the document body
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }
})(); 