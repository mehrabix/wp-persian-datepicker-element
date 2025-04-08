<?php
/**
 * Simple script to compile PO file to MO format
 * Run this script from the command line in the plugin directory:
 * php languages/compile-mo.php
 */

if (!class_exists('MO')) {
    // Try to load the WordPress MO class
    $wp_load_paths = [
        '../../../wp-includes/pomo/mo.php',  // From plugin languages directory
        '../../../../wp-includes/pomo/mo.php', // Alternative path
        'wp-includes/pomo/mo.php',           // Direct path if run from WordPress root
    ];
    
    $loaded = false;
    foreach ($wp_load_paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            $loaded = true;
            break;
        }
    }
    
    if (!$loaded) {
        die("Could not load WordPress MO class. Please run this script from WordPress environment.\n");
    }
}

// Set the file paths
$po_file = __DIR__ . '/wp-persian-datepicker-element-fa_IR.po';
$mo_file = __DIR__ . '/wp-persian-datepicker-element-fa_IR.mo';

if (!file_exists($po_file)) {
    die("PO file not found: $po_file\n");
}

// Create a new MO object
$mo = new MO();

// Import from PO file
if (!$mo->import_from_file($po_file)) {
    die("Error importing from PO file\n");
}

// Export to MO file
if (!$mo->export_to_file($mo_file)) {
    die("Error exporting to MO file\n");
} else {
    echo "Successfully compiled MO file: $mo_file\n";
} 