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
        
        // Special handling for Contact Form 7
        setupContactForm7Integration();
        
        // Special handling for WPForms
        setupWPFormsIntegration();
        
        // Special handling for Gravity Forms
        setupGravityFormsIntegration();
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
        // Handle CF7 forms with date fields
        var cf7Forms = document.querySelectorAll('.wpcf7-form');
        
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
            if (window.wpcf7) {
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
        // Handle WPForms with date fields
        var wpformsForms = document.querySelectorAll('.wpforms-form');
        
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
    }

    /**
     * Setup Gravity Forms integration
     */
    function setupGravityFormsIntegration() {
        // Handle Gravity Forms with date fields
        var gravityForms = document.querySelectorAll('.gform_wrapper');
        
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
            
            // Handle Gravity Forms AJAX submissions
            if (window.gform) {
                // Listen for form render event
                document.addEventListener('gform_post_render', function() {
                    // Re-run the transformation for the rendered form
                    setupGravityFormsIntegration();
                });
            }
        });
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
        // Special integration with common WordPress form plugins
        
        // Gravity Forms integration
        if (datepicker.closest('.gform_wrapper')) {
            datepicker.classList.add('gform-datepicker');
        }
        
        // Contact Form 7 integration
        if (datepicker.closest('.wpcf7-form')) {
            datepicker.classList.add('wpcf7-datepicker');
        }
        
        // WooCommerce integration
        if (datepicker.closest('.woocommerce-checkout') || datepicker.closest('.woocommerce-account')) {
            datepicker.classList.add('woocommerce-datepicker');
        }
        
        // Apply default options from WordPress settings
        if (typeof wpPersianDatepickerOptions !== 'undefined') {
            // Only apply if attribute is not already set
            if (!datepicker.hasAttribute('placeholder') && wpPersianDatepickerOptions.placeholder) {
                datepicker.setAttribute('placeholder', wpPersianDatepickerOptions.placeholder);
            }
            
            if (!datepicker.hasAttribute('format') && wpPersianDatepickerOptions.format) {
                datepicker.setAttribute('format', wpPersianDatepickerOptions.format);
            }
            
            if (!datepicker.hasAttribute('rtl') && typeof wpPersianDatepickerOptions.rtl !== 'undefined') {
                datepicker.setAttribute('rtl', wpPersianDatepickerOptions.rtl ? 'true' : 'false');
            }
            
            if (!datepicker.hasAttribute('show-holidays') && typeof wpPersianDatepickerOptions.show_holidays !== 'undefined') {
                datepicker.setAttribute('show-holidays', wpPersianDatepickerOptions.show_holidays ? 'true' : 'false');
            }
            
            if (!datepicker.hasAttribute('holiday-types') && wpPersianDatepickerOptions.holiday_types) {
                datepicker.setAttribute('holiday-types', wpPersianDatepickerOptions.holiday_types);
            }
            
            if (!datepicker.hasAttribute('dark-mode') && typeof wpPersianDatepickerOptions.dark_mode !== 'undefined') {
                datepicker.setAttribute('dark-mode', wpPersianDatepickerOptions.dark_mode ? 'true' : 'false');
            }
            
            if (!datepicker.hasAttribute('range-mode') && typeof wpPersianDatepickerOptions.range_mode !== 'undefined') {
                datepicker.setAttribute('range-mode', wpPersianDatepickerOptions.range_mode ? 'true' : 'false');
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