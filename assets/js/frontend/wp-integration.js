/**
 * Frontend integration JavaScript for WP Persian Datepicker Element
 *
 * @package    WP_Persian_Datepicker_Element
 * @subpackage WP_Persian_Datepicker_Element/assets/js/frontend
 */

(function() {
    'use strict';

    // When the DOM is fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        initializeAllDatepickers();

        // Handle dynamically added datepickers
        observeNewDatepickers();
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