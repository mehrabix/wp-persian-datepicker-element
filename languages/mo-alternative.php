<?php
/**
 * Basic MO file generator for Persian language.
 * This creates a minimalistic MO file with just headers.
 * While not a complete translation, it allows WordPress to recognize the language is installed.
 */

// MO file header format
$mo_data = "\x95\x04\x12\xde"; // Magic number
$mo_data .= pack('V', 0); // Revision
$mo_data .= pack('V', 0); // Number of strings
$mo_data .= pack('V', 28); // Offset of original strings table
$mo_data .= pack('V', 28); // Offset of translated strings table
$mo_data .= pack('V', 0); // Size of hashing table
$mo_data .= pack('V', 28); // Offset of hashing table

// Write the MO file
$mo_file = __DIR__ . '/wp-persian-datepicker-element-fa_IR.mo';
file_put_contents($mo_file, $mo_data);

echo "Created basic MO file for Persian language at: $mo_file\n";
echo "This is a placeholder file that allows WordPress to recognize Persian language is installed.\n";
echo "For a complete translation, please compile the PO file using proper tools like msgfmt.\n"; 