<?php
/**
 * Basic MO file generator for Persian language.
 * This reads the PO file and creates a simple MO file for the translations.
 */

// Function to read the PO file and extract translations
function parse_po_file($file_path) {
    if (!file_exists($file_path)) {
        die("PO file not found: $file_path\n");
    }
    
    $content = file_get_contents($file_path);
    $lines = explode("\n", $content);
    
    $translations = [];
    $current_msgid = '';
    $current_msgstr = '';
    
    foreach ($lines as $line) {
        $line = trim($line);
        
        // Skip empty lines and comments
        if (empty($line) || $line[0] == '#') {
            continue;
        }
        
        // Handle msgid lines
        if (strpos($line, 'msgid "') === 0) {
            // Save previous translation
            if (!empty($current_msgid)) {
                $translations[$current_msgid] = $current_msgstr;
            }
            
            // Start new translation
            $current_msgid = substr($line, 7, -1);
            $current_msgstr = '';
        }
        // Handle msgstr lines
        elseif (strpos($line, 'msgstr "') === 0) {
            $current_msgstr = substr($line, 8, -1);
        }
        // Handle continuation lines
        elseif ($line[0] == '"' && $line[strlen($line) - 1] == '"') {
            if (strpos($lines[array_search($line, $lines) - 1], 'msgid') === 0) {
                $current_msgid .= substr($line, 1, -1);
            } elseif (strpos($lines[array_search($line, $lines) - 1], 'msgstr') === 0 || $lines[array_search($line, $lines) - 1][0] == '"') {
                $current_msgstr .= substr($line, 1, -1);
            }
        }
    }
    
    // Save the last translation
    if (!empty($current_msgid)) {
        $translations[$current_msgid] = $current_msgstr;
    }
    
    return $translations;
}

// Read the translations from the PO file
$po_file = __DIR__ . '/wp-persian-datepicker-element-fa_IR.po';
$translations = parse_po_file($po_file);

// Remove the header entry (empty msgid)
if (isset($translations[''])) {
    unset($translations['']);
}

// Count the number of translations
$num_translations = count($translations);
echo "Found $num_translations translations in PO file.\n";

// Prepare the MO file data
$mo_data = "\x95\x04\x12\xde"; // Magic number (little endian)
$mo_data .= pack('V', 0); // Revision
$mo_data .= pack('V', $num_translations); // Number of strings

// Calculate offsets
$header_size = 28; // 7 x 4 bytes
$o_offset = $header_size; // Original strings table offset
$t_offset = $header_size + 8 * $num_translations; // Translated strings table offset

$mo_data .= pack('V', $o_offset); // Offset of original strings table
$mo_data .= pack('V', $t_offset); // Offset of translated strings table
$mo_data .= pack('V', 0); // Size of hashing table
$mo_data .= pack('V', 0); // Offset of hashing table

// Prepare string tables
$o_table = ''; // Original strings table
$t_table = ''; // Translated strings table
$o_lengths = []; // Original string lengths
$t_lengths = []; // Translated string lengths
$o_offsets = []; // Original string offsets
$t_offsets = []; // Translated string offsets

// Current offsets
$o_current_offset = 0;
$t_current_offset = 0;

// Sort translations by original string
ksort($translations);

// Build string tables
foreach ($translations as $original => $translated) {
    // Original string
    $o_lengths[] = strlen($original);
    $o_offsets[] = $o_current_offset;
    $o_table .= $original . "\0";
    $o_current_offset += strlen($original) + 1; // +1 for null terminator
    
    // Translated string
    $t_lengths[] = strlen($translated);
    $t_offsets[] = $t_current_offset;
    $t_table .= $translated . "\0";
    $t_current_offset += strlen($translated) + 1; // +1 for null terminator
}

// Build original strings index table
for ($i = 0; $i < $num_translations; $i++) {
    $mo_data .= pack('VV', $o_lengths[$i], $o_offsets[$i]);
}

// Build translated strings index table
for ($i = 0; $i < $num_translations; $i++) {
    $mo_data .= pack('VV', $t_lengths[$i], $t_offsets[$i]);
}

// Add string tables
$mo_data .= $o_table . $t_table;

// Write the MO file
$mo_file = __DIR__ . '/wp-persian-datepicker-element-fa_IR.mo';
file_put_contents($mo_file, $mo_data);

echo "Created MO file for Persian language at: $mo_file\n"; 