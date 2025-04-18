<?php
/**
 * Plugin Name: WP Persian Datepicker Element
 * Plugin URI: https://github.com/mehrabix/wp-persian-datepicker-element
 * Description: A modern, highly customizable Persian (Jalali) date picker web component for WordPress with Contact Form 7, WPForms, Gravity Forms, and WooCommerce integration
 * Version: 1.1.0
 * Author: Ahmad Mehrabi
 * Author URI: https://github.com/mehrabix
 * Text Domain: wp-persian-datepicker-element
 * Domain Path: /languages
 * License: MIT
 * Requires at least: 5.6
 * Requires PHP: 7.2
 * WC requires at least: 5.0
 * WC tested up to: 8.3
 */

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 */
define('PERSIAN_DATEPICKER_VERSION', '1.1.0');
define('PERSIAN_DATEPICKER_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PERSIAN_DATEPICKER_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Check if a plugin is active
 * 
 * @param string $plugin Plugin path/name (e.g. woocommerce/woocommerce.php)
 * @return bool True if plugin is active, false otherwise
 */
function wp_persian_datepicker_is_plugin_active($plugin) {
    // Check if get_plugins function exists. This is required on the front end
    if (!function_exists('is_plugin_active')) {
        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
    }
    
    return is_plugin_active($plugin);
}

/**
 * Check for active integrations and set global flag
 */
function wp_persian_datepicker_check_integrations() {
    $integrations = [
        'cf7' => wp_persian_datepicker_is_plugin_active('contact-form-7/wp-contact-form-7.php'),
        'woocommerce' => wp_persian_datepicker_is_plugin_active('woocommerce/woocommerce.php'),
        'gravity_forms' => wp_persian_datepicker_is_plugin_active('gravityforms/gravityforms.php'),
        'wpforms' => (
            wp_persian_datepicker_is_plugin_active('wpforms-lite/wpforms.php') || 
            wp_persian_datepicker_is_plugin_active('wpforms/wpforms.php')
        )
    ];
    
    // Set global flag for JavaScript to use
    wp_localize_script('wp-persian-datepicker-frontend', 'wpPersianDatepickerIntegrations', $integrations);
    
    return $integrations;
}

/**
 * Load plugin textdomain.
 */
function wp_persian_datepicker_load_textdomain() {
    // مسیر زبان پلاگین
    $language_dir = dirname(plugin_basename(__FILE__)) . '/languages/';
    
    // اگر زبان فارسی فعال است، مستقیماً ترجمه‌های فارسی را تنظیم کنیم
    $locale = determine_locale();
    if ($locale == 'fa_IR') {
        // تنظیم دستی ترجمه‌ها - این روش مستقیماً ترجمه‌ها را بدون نیاز به فایل MO تنظیم می‌کند
        
        // ساخت کلاس ساده برای ترجمه
        if (!class_exists('Simple_Translation_Entry')) {
            class Simple_Translation_Entry {
                public $singular;
                public $translations;
                
                public function __construct($args = array()) {
                    $this->singular = isset($args['singular']) ? $args['singular'] : '';
                    $this->translations = isset($args['translations']) ? $args['translations'] : array();
                }
            }
        }
        
        if (!class_exists('Simple_MO')) {
            class Simple_MO {
                public $entries = array();
                
                public function add_entry($entry) {
                    $this->entries[$entry->singular] = $entry;
                }
                
                public function translate($text) {
                    return isset($this->entries[$text]) && !empty($this->entries[$text]->translations) ? 
                           $this->entries[$text]->translations[0] : $text;
                }
            }
        }
        
        global $l10n;
        if (!isset($l10n['wp-persian-datepicker-element'])) {
            $l10n['wp-persian-datepicker-element'] = new Simple_MO();
        }
        
        // اینجا ما تمام رشته‌های متن مورد نیاز را ترجمه می‌کنیم
        $translations = array(
            'Persian Date Picker Settings' => 'تنظیمات انتخابگر تاریخ شمسی',
            'Persian Date Picker' => 'انتخابگر تاریخ شمسی',
            'General Settings' => 'تنظیمات عمومی',
            'Default Placeholder' => 'متن راهنمای پیش‌فرض',
            'Default Date Format' => 'قالب تاریخ پیش‌فرض',
            'Show Holidays' => 'نمایش تعطیلات',
            'Right-to-Left (RTL)' => 'راست به چپ (RTL)',
            'Dark Mode' => 'حالت تاریک',
            'Holiday Types' => 'انواع تعطیلات',
            'Range Mode' => 'حالت انتخاب بازه',
            'Configure the default settings for the Persian Date Picker component. These settings will be used as defaults for shortcodes and widgets unless overridden.' => 'تنظیمات پیش‌فرض کامپوننت انتخابگر تاریخ شمسی را پیکربندی کنید. این تنظیمات به عنوان پیش‌فرض برای شورت‌کدها و ابزارک‌ها استفاده می‌شوند مگر اینکه بازنویسی شوند.',
            'The placeholder text to show in the input field.' => 'متن راهنما که در فیلد ورودی نمایش داده می‌شود.',
            'The date format pattern (e.g. YYYY/MM/DD, YYYY-MM-DD).' => 'الگوی قالب تاریخ (مثلاً YYYY/MM/DD, YYYY-MM-DD).',
            'Show holidays in the calendar' => 'نمایش تعطیلات در تقویم',
            'Enable right-to-left layout' => 'فعال‌سازی چیدمان راست به چپ',
            'Enable dark mode' => 'فعال‌سازی حالت تاریک',
            'Comma-separated list of holiday types to display (Iran, Afghanistan, AncientIran, International). Use "all" to show all types.' => 'لیست انواع تعطیلات با کاما جدا شده (Iran, Afghanistan, AncientIran, International). از "all" برای نمایش همه انواع استفاده کنید.',
            'Enable date range selection mode' => 'فعال‌سازی حالت انتخاب بازه تاریخ',
            'Settings saved.' => 'تنظیمات ذخیره شد.',
            'Settings' => 'تنظیمات',
            'Shortcode Usage' => 'نحوه استفاده از شورت‌کد',
            'Preview' => 'پیش‌نمایش',
            'To display the Persian Date Picker in your content, use the following shortcode:' => 'برای نمایش انتخابگر تاریخ شمسی در محتوای خود، از شورت‌کد زیر استفاده کنید:',
            'You can customize the datepicker with various attributes:' => 'می‌توانید انتخابگر تاریخ را با ویژگی‌های مختلفی سفارشی کنید:',
            'Basic Usage' => 'استفاده پایه',
            'With Custom Placeholder' => 'با متن راهنمای سفارشی',
            'With Custom Format' => 'با قالب سفارشی',
            'RTL Direction' => 'جهت راست به چپ',
            'Range Mode' => 'حالت انتخاب بازه',
            'Custom Holiday Types' => 'انواع تعطیلات سفارشی',
            'Full Example' => 'مثال کامل',
            'Gutenberg Block' => 'بلوک گوتنبرگ',
            'You can also use the Persian Date Picker as a Gutenberg block. Simply search for "Persian Date Picker" in the block inserter.' => 'شما همچنین می‌توانید از انتخابگر تاریخ شمسی به عنوان یک بلوک گوتنبرگ استفاده کنید. کافیست در درج‌کننده بلوک، عبارت «انتخابگر تاریخ شمسی» را جستجو کنید.',
            'A Persian (Jalali) date picker widget.' => 'یک ابزارک انتخابگر تاریخ شمسی (جلالی).',
            'Title:' => 'عنوان:',
            'Placeholder:' => 'متن راهنما:',
            'Date Format:' => 'قالب تاریخ:',
            'e.g. YYYY/MM/DD or YYYY-MM-DD' => 'مثلاً YYYY/MM/DD یا YYYY-MM-DD',
            'Holiday Types:' => 'انواع تعطیلات:',
            'Comma-separated list: Iran,Afghanistan,AncientIran,International' => 'لیست جدا شده با کاما: Iran,Afghanistan,AncientIran,International',
            'Today' => 'امروز',
            'Tomorrow' => 'فردا',
        );
        
        // تنظیم ترجمه‌ها
        foreach ($translations as $original => $translation) {
            $l10n['wp-persian-datepicker-element']->add_entry(new Simple_Translation_Entry(array(
                'singular' => $original,
                'translations' => array($translation),
            )));
        }
        
        // اتصال تابع فیلتر برای تغییر متن‌های ترجمه نشده
        add_filter('gettext', 'wppdp_translate_strings', 10, 3);
        
    } else {
        // روش معمولی بارگذاری زبان برای سایر زبان‌ها
        load_plugin_textdomain(
            'wp-persian-datepicker-element',
            false,
            $language_dir
        );
    }
}
add_action('plugins_loaded', 'wp_persian_datepicker_load_textdomain', 1);

/**
 * فیلتر متن‌های ترجمه نشده 
 */
function wppdp_translate_strings($translation, $text, $domain) {
    if ($domain === 'wp-persian-datepicker-element') {
        global $l10n;
        if (isset($l10n['wp-persian-datepicker-element']) && method_exists($l10n['wp-persian-datepicker-element'], 'translate')) {
            $custom_translation = $l10n['wp-persian-datepicker-element']->translate($text);
            if ($custom_translation !== $text) {
                return $custom_translation;
            }
        }
    }
    return $translation;
}

/**
 * The core plugin class.
 */
class WP_Persian_Datepicker_Element {

    /**
     * Active plugin integrations
     *
     * @var array
     */
    private $active_integrations = array();

    /**
     * Constructor - set up the plugin.
     */
    public function __construct() {
        // Check for active integrations
        $this->active_integrations = $this->check_integrations();
        
        // Load dependencies
        $this->load_dependencies();
        
        // Define hooks
        $this->define_hooks();
    }

    /**
     * Check which plugins are active for integration
     */
    private function check_integrations() {
        // Set the active integrations
        return array(
            'cf7' => wp_persian_datepicker_is_plugin_active('contact-form-7/wp-contact-form-7.php'),
            'woocommerce' => wp_persian_datepicker_is_plugin_active('woocommerce/woocommerce.php'),
            'gravity_forms' => wp_persian_datepicker_is_plugin_active('gravityforms/gravityforms.php'),
            'wpforms' => (
                wp_persian_datepicker_is_plugin_active('wpforms-lite/wpforms.php') || 
                wp_persian_datepicker_is_plugin_active('wpforms/wpforms.php')
            )
        );
    }

    /**
     * Load the required dependencies for this plugin.
     */
    private function load_dependencies() {
        // Include the loader class
        require_once PERSIAN_DATEPICKER_PLUGIN_DIR . 'includes/class-wp-persian-datepicker-loader.php';
        
        // Include the admin class
        require_once PERSIAN_DATEPICKER_PLUGIN_DIR . 'admin/class-wp-persian-datepicker-admin.php';
        
        // Include scripts manager
        require_once PERSIAN_DATEPICKER_PLUGIN_DIR . 'includes/class-wp-persian-datepicker-scripts.php';
        
        // Include shortcode class
        require_once PERSIAN_DATEPICKER_PLUGIN_DIR . 'includes/class-wp-persian-datepicker-shortcode.php';
        
        // Include widget class
        require_once PERSIAN_DATEPICKER_PLUGIN_DIR . 'includes/class-wp-persian-datepicker-widget.php';
    }

    /**
     * Define the hooks.
     */
    private function define_hooks() {
        // Create a new loader
        $loader = new WP_Persian_Datepicker_Loader();
        
        // Debug - Check if we're properly loading
        error_log('WP Persian Datepicker Element: Plugin initializing');
        
        // Initialize the admin
        $admin = new WP_Persian_Datepicker_Admin();
        
        // Register scripts and styles
        $scripts = new WP_Persian_Datepicker_Scripts();
        $loader->add_action('wp_enqueue_scripts', $scripts, 'enqueue_scripts');
        $loader->add_action('admin_enqueue_scripts', $scripts, 'enqueue_admin_scripts');
        
        // Register shortcode
        $shortcode = new WP_Persian_Datepicker_Shortcode();
        $loader->add_shortcode('persian_datepicker', $shortcode, 'render_shortcode');
        
        // Register widget
        add_action('widgets_init', function() {
            register_widget('WP_Persian_Datepicker_Widget');
        });
        
        // Add admin menu items
        $loader->add_action('admin_menu', $admin, 'add_plugin_menu');
        $loader->add_action('admin_init', $admin, 'register_settings');
        
        // Add support for Gutenberg block
        if (function_exists('register_block_type')) {
            $loader->add_action('init', $this, 'register_block');
        }
        
        // Set up integrations with other plugins
        $this->setup_integrations($loader);
        
        // Run the loader to execute all hooks
        $loader->run();
    }
    
    /**
     * Set up integrations with other WordPress plugins
     * 
     * @param WP_Persian_Datepicker_Loader $loader The plugin loader
     */
    private function setup_integrations($loader) {
        // Pass active integrations to JavaScript
        add_action('wp_enqueue_scripts', function() {
            wp_localize_script('wp-persian-datepicker-frontend', 'wpPersianDatepickerIntegrations', $this->active_integrations);
        }, 20);
        
        // Contact Form 7 integration
        if ($this->active_integrations['cf7']) {
            // Add specific CF7 hooks if needed
            add_action('wpcf7_init', function() {
                // Any CF7-specific initialization can go here
            });
        }
        
        // WooCommerce integration
        if ($this->active_integrations['woocommerce']) {
            // Add WooCommerce hooks - only if the hook exists
            if (function_exists('WC') && has_filter('woocommerce_after_checkout_form')) {
                add_action('woocommerce_after_checkout_form', function() {
                    // Ensure datepicker scripts are loaded on checkout
                    $scripts = new WP_Persian_Datepicker_Scripts();
                    $scripts->enqueue_scripts();
                });
            }
        }
        
        // Gravity Forms integration
        if ($this->active_integrations['gravity_forms'] && class_exists('GFCommon')) {
            // Add Gravity Forms hooks
            add_action('gform_enqueue_scripts', function() {
                // Ensure datepicker scripts are loaded on GF forms
                $scripts = new WP_Persian_Datepicker_Scripts();
                $scripts->enqueue_scripts();
            });
        }
        
        // WPForms integration
        if ($this->active_integrations['wpforms']) {
            // Add WPForms hooks
            add_action('wpforms_frontend_load', function() {
                // Ensure datepicker scripts are loaded on WPForms
                $scripts = new WP_Persian_Datepicker_Scripts();
                $scripts->enqueue_scripts();
            });
        }
    }
    
    /**
     * Register Gutenberg block.
     */
    public function register_block() {
        // Register block script
        wp_register_script(
            'wp-persian-datepicker-block',
            PERSIAN_DATEPICKER_PLUGIN_URL . 'assets/js/blocks/build/index.js',
            array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components'),
            PERSIAN_DATEPICKER_VERSION
        );
        
        // Register block
        register_block_type('wp-persian-datepicker-element/datepicker', array(
            'editor_script' => 'wp-persian-datepicker-block',
            'render_callback' => array($this, 'render_block'),
            'attributes' => array(
                'placeholder' => array(
                    'type' => 'string',
                    'default' => 'انتخاب تاریخ'
                ),
                'format' => array(
                    'type' => 'string',
                    'default' => 'YYYY/MM/DD'
                ),
                'showHolidays' => array(
                    'type' => 'boolean',
                    'default' => true
                ),
                'rtl' => array(
                    'type' => 'boolean',
                    'default' => true
                ),
                'darkMode' => array(
                    'type' => 'boolean',
                    'default' => false
                ),
                'holidayTypes' => array(
                    'type' => 'string',
                    'default' => 'Iran,International'
                ),
                'rangeMode' => array(
                    'type' => 'boolean',
                    'default' => false
                ),
                'className' => array(
                    'type' => 'string'
                )
            )
        ));
    }
    
    /**
     * Render Gutenberg block.
     */
    public function render_block($attributes) {
        $shortcode = new WP_Persian_Datepicker_Shortcode();
        return $shortcode->render_shortcode($attributes);
    }

    /**
     * Activate the plugin.
     */
    public static function activate() {
        // Set default options if they don't exist
        if (!get_option('wp_persian_datepicker_options')) {
            $default_options = array(
                'placeholder' => 'انتخاب تاریخ',
                'format' => 'YYYY/MM/DD',
                'show_holidays' => 1,
                'rtl' => 1,
                'dark_mode' => 0,
                'holiday_types' => 'Iran,International',
                'range_mode' => 0,
            );
            update_option('wp_persian_datepicker_options', $default_options);
            
            // Log activation
            error_log('WP Persian Datepicker Element: Plugin activated with default options');
        } else {
            // If options already exist, ensure all keys are set with valid values
            $existing_options = get_option('wp_persian_datepicker_options');
            $updated = false;
            
            $required_keys = array(
                'placeholder' => 'انتخاب تاریخ',
                'format' => 'YYYY/MM/DD',
                'show_holidays' => 1,
                'rtl' => 1,
                'dark_mode' => 0,
                'holiday_types' => 'Iran,International',
                'range_mode' => 0,
            );
            
            foreach ($required_keys as $key => $default_value) {
                if (!isset($existing_options[$key])) {
                    $existing_options[$key] = $default_value;
                    $updated = true;
                }
            }
            
            if ($updated) {
                update_option('wp_persian_datepicker_options', $existing_options);
                error_log('WP Persian Datepicker Element: Plugin options updated during activation');
            }
        }
        
        // Create or update the MO file for Persian translations
        self::update_mo_file();
    }
    
    /**
     * Create or update the Persian language MO file.
     */
    private static function update_mo_file() {
        // Define the PO and MO file paths
        $langs_dir = plugin_dir_path(__FILE__) . 'languages/';
        $po_file = $langs_dir . 'wp-persian-datepicker-element-fa_IR.po';
        $mo_file = $langs_dir . 'wp-persian-datepicker-element-fa_IR.mo';
        
        // Create an empty MO file if PO file doesn't exist or can't be read
        if (!file_exists($po_file) || !is_readable($po_file)) {
            // Simple MO header with no translations
            $mo_data = "\x95\x04\x12\xde"; // Magic number
            $mo_data .= pack('V', 0); // Revision
            $mo_data .= pack('V', 0); // Number of strings
            $mo_data .= pack('V', 28); // Offset of original strings table
            $mo_data .= pack('V', 28); // Offset of translated strings table
            $mo_data .= pack('V', 0); // Size of hashing table
            $mo_data .= pack('V', 28); // Offset of hashing table
            
            file_put_contents($mo_file, $mo_data);
            return;
        }
        
        // Hard-coded translations to include in the MO file
        $translations = array(
            'Persian Date Picker Settings' => 'تنظیمات انتخابگر تاریخ شمسی',
            'Persian Date Picker' => 'انتخابگر تاریخ شمسی',
            'General Settings' => 'تنظیمات عمومی',
            'Default Placeholder' => 'متن راهنمای پیش‌فرض',
            'Default Date Format' => 'قالب تاریخ پیش‌فرض',
            'Show Holidays' => 'نمایش تعطیلات',
            'Integration Guide' => 'راهنمای ادغام',
            'Right-to-Left (RTL)' => 'راست به چپ (RTL)',
            'Dark Mode' => 'حالت تاریک',
            'Holiday Types' => 'انواع تعطیلات',
            'Range Mode' => 'حالت انتخاب بازه',
            'Settings' => 'تنظیمات',
            'Shortcode Usage' => 'نحوه استفاده از شورت‌کد',
            'Preview' => 'پیش‌نمایش',
        );
        
        // Count the number of translations
        $num_translations = count($translations);
        
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
        file_put_contents($mo_file, $mo_data);
    }

    /**
     * Deactivate the plugin.
     */
    public static function deactivate() {
        // Clean up if needed
    }

    /**
     * Uninstall the plugin.
     */
    public static function uninstall() {
        // Remove plugin options
        delete_option('wp_persian_datepicker_options');
    }
}

// Register activation and deactivation hooks
register_activation_hook(__FILE__, array('WP_Persian_Datepicker_Element', 'activate'));
register_deactivation_hook(__FILE__, array('WP_Persian_Datepicker_Element', 'deactivate'));
register_uninstall_hook(__FILE__, array('WP_Persian_Datepicker_Element', 'uninstall'));

// Initialize the plugin
$wp_persian_datepicker = new WP_Persian_Datepicker_Element();

/**
 * فارسی‌سازی خودکار عناوین رابط کاربری
 */
function wppdp_persian_admin_filter($translated_text, $text, $domain) {
    // فقط در دامنه این افزونه اعمال شود
    if ($domain !== 'wp-persian-datepicker-element') {
        return $translated_text;
    }
    
    // اگر زبان فارسی نیست، برگشت متن اصلی
    $locale = determine_locale();
    if ($locale !== 'fa_IR') {
        return $translated_text;
    }
    
    // ترجمه‌های دستی برای عناوین انگلیسی که به هر دلیلی از طریق روش اصلی ترجمه نشده‌اند
    $manual_translations = array(
        'Persian Date Picker Settings' => 'تنظیمات انتخابگر تاریخ شمسی',
        'Persian Date Picker' => 'انتخابگر تاریخ شمسی',
        'Default Placeholder' => 'متن راهنمای پیش‌فرض',
        'Default Date Format' => 'قالب تاریخ پیش‌فرض',
        'Save Changes' => 'ذخیره تغییرات',
        'Submit' => 'ارسال',
    );
    
    if (isset($manual_translations[$text])) {
        return $manual_translations[$text];
    }
    
    return $translated_text;
}
add_filter('gettext', 'wppdp_persian_admin_filter', 20, 3);

/**
 * فارسی‌سازی دکمه‌های وردپرس در بخش‌های مربوط به افزونه
 */
function wppdp_persian_admin_buttons() {
    // مطمئن شویم فقط در صفحه تنظیمات این افزونه اجرا شود
    global $pagenow;
    if ($pagenow === 'options-general.php' && isset($_GET['page']) && $_GET['page'] === 'wp-persian-datepicker-settings') {
        // اگر زبان فارسی است
        $locale = determine_locale();
        if ($locale === 'fa_IR') {
            ?>
            <script type="text/javascript">
            jQuery(document).ready(function($) {
                // تغییر متن دکمه ذخیره تغییرات
                $('input#submit').val('ذخیره تغییرات');
                
                // تنظیم استایل فارسی برای کل صفحه
                $('body').addClass('rtl');
                $('.wrap h1, .form-table th, .form-table td, .nav-tab-wrapper a, p.description').css('font-family', 'Tahoma, Arial');
            });
            </script>
            <?php
        }
    }
}
add_action('admin_footer', 'wppdp_persian_admin_buttons');

/**
 * Normalize option values for front-end use
 * Ensures all boolean options are properly converted to true/false values
 */
function wp_persian_datepicker_normalize_options() {
    $options = get_option('wp_persian_datepicker_options', array());
    $normalized = array();
    
    // Ensure default values
    $defaults = array(
        'placeholder' => 'انتخاب تاریخ',
        'format' => 'YYYY/MM/DD',
        'show_holidays' => 1,
        'rtl' => 1,
        'dark_mode' => 0,
        'holiday_types' => 'Iran,International',
        'range_mode' => 0,
    );
    
    // Merge with defaults
    $options = array_merge($defaults, $options);
    
    // Debug the options
    error_log('WP Persian Datepicker: Options before normalization = ' . print_r($options, true));
    
    // Convert numeric boolean values to actual booleans for JavaScript
    $boolean_keys = array('show_holidays', 'rtl', 'dark_mode', 'range_mode');
    
    foreach ($options as $key => $value) {
        if (in_array($key, $boolean_keys)) {
            // Explicitly handle range_mode with extra care
            if ($key === 'range_mode') {
                $normalized[$key] = !empty($value) ? true : false;
                // Add a string representation for debugging
                $normalized[$key . '_str'] = !empty($value) ? 'true' : 'false';
            } else {
                $normalized[$key] = (bool)$value;
            }
        } else {
            $normalized[$key] = $value;
        }
    }
    
    // Add plugin base URL to options
    $normalized['plugin_url'] = PERSIAN_DATEPICKER_PLUGIN_URL;
    
    // Debug the normalized options
    error_log('WP Persian Datepicker: Options after normalization = ' . print_r($normalized, true));
    
    return $normalized;
}

/**
 * Enqueue the front-end scripts and pass the correct options
 */
function wp_persian_datepicker_enqueue_frontend() {
    // Pass normalized options to script
    wp_localize_script(
        'wp-persian-datepicker-frontend',
        'wpPersianDatepickerOptions',
        wp_persian_datepicker_normalize_options()
    );
}
add_action('wp_enqueue_scripts', 'wp_persian_datepicker_enqueue_frontend', 25);

/**
 * Add a small script to ensure the range-mode attribute is properly set
 * This helps address issues with boolean attributes in web components
 */
function wp_persian_datepicker_add_attribute_fix() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Find all datepicker elements
        var datepickers = document.querySelectorAll('persian-datepicker-element');
        
        // Process each datepicker to ensure range-mode is properly set
        datepickers.forEach(function(picker) {
            // Get the current range-mode attribute
            var rangeMode = picker.getAttribute('range-mode');
            
            // Only proceed if the attribute exists
            if (rangeMode !== null) {
                console.log('Processing datepicker range-mode:', rangeMode);
                
                // Remove the attribute and then add it back explicitly
                // This helps ensure it's recognized properly by the web component
                picker.removeAttribute('range-mode');
                
                // Convert the value to a proper boolean string
                var rangeModeValue = (rangeMode === 'true' || rangeMode === '1' || rangeMode === true) ? 'true' : 'false';
                
                // Set the attribute with the explicit value
                picker.setAttribute('range-mode', rangeModeValue);
                console.log('Updated datepicker range-mode to:', rangeModeValue);
            }
            
            // Also handle dark mode more thoroughly
            var darkMode = picker.getAttribute('darkmode') || picker.getAttribute('dark-mode');
            if (darkMode === 'true' || darkMode === '1' || darkMode === true) {
                applyDarkModeStyles(picker, true);
            }
        });
        
        // Set up a function to apply dark mode styles to datepickers
        function applyDarkModeStyles(picker, isDark) {
            if (isDark) {
                picker.setAttribute('dark-mode', 'true');
                // Apply the dark mode CSS variables directly
                picker.style.setProperty('--jdp-background', '#1e1e2f');
                picker.style.setProperty('--jdp-foreground', '#e2e8f0');
                picker.style.setProperty('--jdp-muted', '#334155');
                picker.style.setProperty('--jdp-muted-foreground', '#94a3b8');
                picker.style.setProperty('--jdp-border', '#475569');
                picker.style.setProperty('--jdp-day-hover-bg', '#334155');
                picker.style.setProperty('--jdp-input-border-color', '#475569');
                picker.style.setProperty('--jdp-input-bg', '#1e1e2f');
                picker.style.setProperty('--jdp-calendar-bg', '#1e1e2f');
                picker.style.setProperty('--jdp-holiday-bg', '#3f1e2e');
                picker.style.setProperty('--jdp-border-color', '#475569');
                picker.style.setProperty('--jdp-nav-arrow-color', '#e2e8f0');
                picker.style.setProperty('--jdp-selected-bg', '#0891b2');
                picker.style.setProperty('--jdp-hover-bg', 'rgba(8, 145, 178, 0.2)');
                picker.style.setProperty('--jdp-today-border-color', '#0891b2');
                picker.style.setProperty('--jdp-input-focus-border', '#0891b2');
                picker.style.setProperty('--jdp-input-focus-shadow', '0 0 0 1px #0891b2');
            } else {
                picker.removeAttribute('dark-mode');
                // Instead of just removing properties, set light mode values explicitly
                picker.style.setProperty('--jdp-background', '#ffffff');
                picker.style.setProperty('--jdp-foreground', '#333333');
                picker.style.setProperty('--jdp-muted', '#f1f5f9');
                picker.style.setProperty('--jdp-muted-foreground', '#64748b');
                picker.style.setProperty('--jdp-border', '#e2e8f0');
                picker.style.setProperty('--jdp-day-hover-bg', '#f1f5f9');
                picker.style.setProperty('--jdp-input-border-color', '#ddd');
                picker.style.setProperty('--jdp-input-bg', '#ffffff');
                picker.style.setProperty('--jdp-calendar-bg', '#ffffff');
                picker.style.setProperty('--jdp-holiday-bg', '#fff1f2');
                picker.style.setProperty('--jdp-border-color', '#ddd');
                picker.style.setProperty('--jdp-nav-arrow-color', '#505050');
                picker.style.setProperty('--jdp-selected-bg', '#2271b1');
                picker.style.setProperty('--jdp-hover-bg', 'rgba(34, 113, 177, 0.1)');
                picker.style.setProperty('--jdp-today-border-color', '#2271b1');
                picker.style.setProperty('--jdp-input-focus-border', '#2271b1');
                picker.style.setProperty('--jdp-input-focus-shadow', '0 0 0 1px #2271b1');
            }
        }
        
        // Monitor DOM for dynamically added pickers
        try {
            var observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.addedNodes.length) {
                        mutation.addedNodes.forEach(function(node) {
                            // Check if added node is a datepicker element
                            if (node.tagName && node.tagName.toLowerCase() === 'persian-datepicker-element') {
                                // Process range mode
                                var rangeMode = node.getAttribute('range-mode');
                                if (rangeMode !== null) {
                                    var rangeModeValue = (rangeMode === 'true' || rangeMode === '1' || rangeMode === true) ? 'true' : 'false';
                                    node.setAttribute('range-mode', rangeModeValue);
                                }
                                
                                // Process dark mode
                                var darkMode = node.getAttribute('darkmode') || node.getAttribute('dark-mode');
                                if (darkMode === 'true' || darkMode === '1' || darkMode === true) {
                                    applyDarkModeStyles(node, true);
                                }
                            }
                            
                            // Also check for datepickers inside the added node
                            if (node.querySelectorAll) {
                                var nestedPickers = node.querySelectorAll('persian-datepicker-element');
                                if (nestedPickers.length > 0) {
                                    nestedPickers.forEach(function(nestedPicker) {
                                        // Process range mode for nested pickers
                                        var rangeMode = nestedPicker.getAttribute('range-mode');
                                        if (rangeMode !== null) {
                                            var rangeModeValue = (rangeMode === 'true' || rangeMode === '1' || rangeMode === true) ? 'true' : 'false';
                                            nestedPicker.setAttribute('range-mode', rangeModeValue);
                                        }
                                        
                                        // Process dark mode for nested pickers
                                        var darkMode = nestedPicker.getAttribute('darkmode') || nestedPicker.getAttribute('dark-mode');
                                        if (darkMode === 'true' || darkMode === '1' || darkMode === true) {
                                            applyDarkModeStyles(nestedPicker, true);
                                        }
                                    });
                                }
                            }
                        });
                    }
                });
            });
            
            // Start observing the document body for DOM changes
            observer.observe(document.body, { childList: true, subtree: true });
            console.log('MutationObserver set up for attribute and dark mode fixes');
            
        } catch (e) {
            console.error('Error setting up MutationObserver:', e);
        }
    });
    </script>
    <?php
}
add_action('wp_footer', 'wp_persian_datepicker_add_attribute_fix', 99);
add_action('admin_footer', 'wp_persian_datepicker_add_attribute_fix', 99);

/**
 * Add a direct JavaScript fix to ensure range mode works properly
 * This will manipulate the component property directly instead of just setting attributes
 */
function wp_persian_datepicker_direct_property_fix() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Define a function that will attempt to fix the datepicker elements
        function fixDatepickers() {
            console.log('Running direct Persian datepicker fixes');
            
            // Find all datepicker elements
            var datepickers = document.querySelectorAll('persian-datepicker-element');
         
            console.log('Found ' + datepickers.length + ' datepickers to process');
            
            // Process each datepicker to ensure properties are properly set
            datepickers.forEach(function(picker, index) {
                // Handle range mode
                var rangeMode = picker.getAttribute('range-mode');
                if (rangeMode !== null) {
                    console.log('Datepicker #' + index + ' range-mode attribute:', rangeMode);
                    
                    // Force a direct property assignment
                    try {
                        // Set the property directly - this bypasses the attribute system
                        var shouldEnableRangeMode = rangeMode === 'true' || rangeMode === '1' || rangeMode === true;
                        
                        // First try the direct property approach
                        if (typeof picker.rangeMode !== 'undefined') {
                            picker.rangeMode = shouldEnableRangeMode;
                            console.log('Set rangeMode property directly to:', shouldEnableRangeMode);
                        }
                        
                        // Also set via setAttribute for good measure
                        picker.setAttribute('range-mode', shouldEnableRangeMode ? 'true' : 'false');
                        
                        // Try to force a refresh of the component
                        if (typeof picker.refreshComponent === 'function') {
                            picker.refreshComponent();
                            console.log('Called refreshComponent method');
                        }
                        
                        // For web components, sometimes triggering an event can help refresh the component
                        var event = new CustomEvent('range-mode-changed', { 
                            detail: { rangeMode: shouldEnableRangeMode },
                            bubbles: true
                        });
                        picker.dispatchEvent(event);
                        
                        console.log('Datepicker #' + index + ' range mode set to:', shouldEnableRangeMode);
                    } catch (e) {
                        console.error('Error setting range mode directly:', e);
                    }
                }
                
                // Handle dark mode
                var darkMode = picker.getAttribute('darkmode') || picker.getAttribute('dark-mode');
                if (darkMode !== null) {
                    try {
                        var shouldEnableDarkMode = darkMode === 'true' || darkMode === '1' || darkMode === true;
                        console.log('Datepicker #' + index + ' dark mode attribute:', darkMode);
                        
                        // Set the dark-mode attribute properly
                        picker.setAttribute('dark-mode', shouldEnableDarkMode ? 'true' : 'false');
                        
                        // Apply appropriate mode styles
                        if (shouldEnableDarkMode) {
                            // Apply dark mode CSS variables from basic.html
                            picker.style.setProperty('--jdp-background', '#1e1e2f');
                            picker.style.setProperty('--jdp-foreground', '#e2e8f0');
                            picker.style.setProperty('--jdp-muted', '#334155');
                            picker.style.setProperty('--jdp-muted-foreground', '#94a3b8');
                            picker.style.setProperty('--jdp-border', '#475569');
                            picker.style.setProperty('--jdp-day-hover-bg', '#334155');
                            picker.style.setProperty('--jdp-input-border-color', '#475569');
                            picker.style.setProperty('--jdp-input-bg', '#1e1e2f');
                            picker.style.setProperty('--jdp-calendar-bg', '#1e1e2f');
                            picker.style.setProperty('--jdp-holiday-bg', '#3f1e2e');
                            picker.style.setProperty('--jdp-border-color', '#475569');
                            picker.style.setProperty('--jdp-nav-arrow-color', '#e2e8f0');
                            picker.style.setProperty('--jdp-selected-bg', '#0891b2');
                            picker.style.setProperty('--jdp-hover-bg', 'rgba(8, 145, 178, 0.2)');
                            picker.style.setProperty('--jdp-today-border-color', '#0891b2');
                            picker.style.setProperty('--jdp-input-focus-border', '#0891b2');
                            picker.style.setProperty('--jdp-input-focus-shadow', '0 0 0 1px #0891b2');
                            
                            console.log('Applied dark mode styles to datepicker #' + index);
                        } else {
                            // Apply light mode CSS variables 
                            picker.style.setProperty('--jdp-background', '#ffffff');
                            picker.style.setProperty('--jdp-foreground', '#333333');
                            picker.style.setProperty('--jdp-muted', '#f1f5f9');
                            picker.style.setProperty('--jdp-muted-foreground', '#64748b');
                            picker.style.setProperty('--jdp-border', '#e2e8f0');
                            picker.style.setProperty('--jdp-day-hover-bg', '#f1f5f9');
                            picker.style.setProperty('--jdp-input-border-color', '#ddd');
                            picker.style.setProperty('--jdp-input-bg', '#ffffff');
                            picker.style.setProperty('--jdp-calendar-bg', '#ffffff');
                            picker.style.setProperty('--jdp-holiday-bg', '#fff1f2');
                            picker.style.setProperty('--jdp-border-color', '#ddd');
                            picker.style.setProperty('--jdp-nav-arrow-color', '#505050');
                            picker.style.setProperty('--jdp-selected-bg', '#2271b1');
                            picker.style.setProperty('--jdp-hover-bg', 'rgba(34, 113, 177, 0.1)');
                            picker.style.setProperty('--jdp-today-border-color', '#2271b1');
                            picker.style.setProperty('--jdp-input-focus-border', '#2271b1');
                            picker.style.setProperty('--jdp-input-focus-shadow', '0 0 0 1px #2271b1');
                            
                            console.log('Applied light mode styles to datepicker #' + index);
                        }
                        
                        // Try to set the property directly if possible
                        if (typeof picker.darkMode !== 'undefined') {
                            picker.darkMode = shouldEnableDarkMode;
                        }
                        
                        // Also dispatch an event for good measure
                        var event = new CustomEvent('dark-mode-changed', { 
                            detail: { darkMode: shouldEnableDarkMode },
                            bubbles: true
                        });
                        picker.dispatchEvent(event);
                    } catch (e) {
                        console.error('Error setting dark mode directly:', e);
                    }
                }
            });
            
            // Set up a MutationObserver to handle dynamically added datepickers
            try {
                var observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.addedNodes.length) {
                            mutation.addedNodes.forEach(function(node) {
                                if (node.tagName && node.tagName.toLowerCase() === 'persian-datepicker-element') {
                                    // Handle range mode for new datepicker
                                    var rangeMode = node.getAttribute('range-mode');
                                    if (rangeMode !== null) {
                                        var shouldEnableRangeMode = rangeMode === 'true' || rangeMode === '1' || rangeMode === true;
                                        
                                        // Set the property directly if possible
                                        if (typeof node.rangeMode !== 'undefined') {
                                            node.rangeMode = shouldEnableRangeMode;
                                        }
                                        
                                        // Also set via setAttribute
                                        node.setAttribute('range-mode', shouldEnableRangeMode ? 'true' : 'false');
                                    }
                                    
                                    // Handle dark mode for new datepicker
                                    var darkMode = node.getAttribute('darkmode') || node.getAttribute('dark-mode');
                                    if (darkMode !== null) {
                                        var shouldEnableDarkMode = darkMode === 'true' || darkMode === '1' || darkMode === true;
                                        
                                        // Set the dark-mode attribute properly
                                        node.setAttribute('dark-mode', shouldEnableDarkMode ? 'true' : 'false');
                                        
                                        // Apply appropriate mode styles
                                        if (shouldEnableDarkMode) {
                                            // Apply the dark mode CSS variables
                                            node.style.setProperty('--jdp-background', '#1e1e2f');
                                            node.style.setProperty('--jdp-foreground', '#e2e8f0');
                                            node.style.setProperty('--jdp-muted', '#334155');
                                            node.style.setProperty('--jdp-muted-foreground', '#94a3b8');
                                            node.style.setProperty('--jdp-border', '#475569');
                                            node.style.setProperty('--jdp-day-hover-bg', '#334155');
                                            node.style.setProperty('--jdp-input-border-color', '#475569');
                                            node.style.setProperty('--jdp-input-bg', '#1e1e2f');
                                            node.style.setProperty('--jdp-calendar-bg', '#1e1e2f');
                                            node.style.setProperty('--jdp-holiday-bg', '#3f1e2e');
                                            node.style.setProperty('--jdp-border-color', '#475569');
                                            node.style.setProperty('--jdp-nav-arrow-color', '#e2e8f0');
                                            node.style.setProperty('--jdp-selected-bg', '#0891b2');
                                            node.style.setProperty('--jdp-hover-bg', 'rgba(8, 145, 178, 0.2)');
                                            node.style.setProperty('--jdp-today-border-color', '#0891b2');
                                            node.style.setProperty('--jdp-input-focus-border', '#0891b2');
                                            node.style.setProperty('--jdp-input-focus-shadow', '0 0 0 1px #0891b2');
                                        } else {
                                            // Apply light mode CSS variables
                                            node.style.setProperty('--jdp-background', '#ffffff');
                                            node.style.setProperty('--jdp-foreground', '#333333');
                                            node.style.setProperty('--jdp-muted', '#f1f5f9');
                                            node.style.setProperty('--jdp-muted-foreground', '#64748b');
                                            node.style.setProperty('--jdp-border', '#e2e8f0');
                                            node.style.setProperty('--jdp-day-hover-bg', '#f1f5f9');
                                            node.style.setProperty('--jdp-input-border-color', '#ddd');
                                            node.style.setProperty('--jdp-input-bg', '#ffffff');
                                            node.style.setProperty('--jdp-calendar-bg', '#ffffff');
                                            node.style.setProperty('--jdp-holiday-bg', '#fff1f2');
                                            node.style.setProperty('--jdp-border-color', '#ddd');
                                            node.style.setProperty('--jdp-nav-arrow-color', '#505050');
                                            node.style.setProperty('--jdp-selected-bg', '#2271b1');
                                            node.style.setProperty('--jdp-hover-bg', 'rgba(34, 113, 177, 0.1)');
                                            node.style.setProperty('--jdp-today-border-color', '#2271b1');
                                            node.style.setProperty('--jdp-input-focus-border', '#2271b1');
                                            node.style.setProperty('--jdp-input-focus-shadow', '0 0 0 1px #2271b1');
                                        }
                                    }
                                }
                            });
                        }
                    });
                });
                
                // Start observing the body for added nodes
                observer.observe(document.body, { childList: true, subtree: true });
                console.log('MutationObserver set up to watch for new datepickers');
                
            } catch (e) {
                console.error('Error setting up MutationObserver:', e);
            }
        }
        
        // Run the fix initially
        fixDatepickers();
        
        // Also run it again after a short delay to catch any lazily initialized components
        setTimeout(fixDatepickers, 1000);
    });
    </script>
    <?php
}
add_action('wp_footer', 'wp_persian_datepicker_direct_property_fix', 100);
add_action('admin_footer', 'wp_persian_datepicker_direct_property_fix', 100);

/**
 * Add a specific fix targeting the internal isRangeMode property
 * Based on the component's internal structure to ensure range mode works consistently
 */
function wp_persian_datepicker_israngemode_property_fix() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Wait a bit to ensure all components are fully initialized
        setTimeout(function() {
            console.log('Running internal property fixes for Persian datepicker');
            
            // Find all datepicker elements
            var datepickers = document.querySelectorAll('persian-datepicker-element');
            
            console.log('Found ' + datepickers.length + ' datepickers to apply property fixes');
            
            // Add a global safety wrapper for the render method to prevent the innerHTML error
            function wrapRenderMethodWithSafety(element) {
                if (!element || typeof element.render !== 'function') return false;
                
                var originalRender = element.render;
                element.render = function() {
                    try {
                        // Make sure the container exists before rendering
                        if (this.shadowRoot && this.shadowRoot.querySelector('.datepicker-container')) {
                            return originalRender.apply(this, arguments);
                        } else {
                            console.warn('Safety wrapper prevented render on incomplete component');
                            return false;
                        }
                    } catch (e) {
                        console.error('Error in wrapped render method:', e);
                        return false;
                    }
                };
                return true;
            }
            
            // Process each datepicker to set the internal properties
            datepickers.forEach(function(picker, index) {
                // First, apply the render safety wrapper
                if (wrapRenderMethodWithSafety(picker)) {
                    console.log('Added render safety wrapper to picker #' + index);
                }
                
                // Check if the component is fully initialized
                if (!picker.shadowRoot || !picker.shadowRoot.querySelector('.datepicker-container')) {
                    console.log('Picker #' + index + ' is not fully initialized, attempting to initialize...');
                    
                    // Try to initialize the component if possible
                    if (typeof picker.initializeComponent === 'function') {
                        try {
                            picker.initializeComponent();
                            console.log('Called initializeComponent on picker #' + index);
                        } catch (e) {
                            console.error('Error initializing component:', e);
                        }
                    }
                    
                    // Or try the connectedCallback as a fallback
                    if (!picker.shadowRoot && typeof picker.connectedCallback === 'function') {
                        try {
                            picker.connectedCallback();
                            console.log('Called connectedCallback on picker #' + index);
                        } catch (e) {
                            console.error('Error calling connectedCallback:', e);
                        }
                    }
                }
                
                // Handle range mode property
                var rangeMode = picker.getAttribute('range-mode');
                if (rangeMode !== null) {
                    // Convert to boolean
                    var shouldEnableRangeMode = rangeMode === 'true' || rangeMode === '1' || rangeMode === true;
                    
                    try {
                        // Try to access the internal isRangeMode property 
                        // We need to do this with care since it's an internal property
                        if (picker.__proto__ && picker.__proto__.isRangeMode !== undefined) {
                            // For prototype-based property
                            picker.__proto__.isRangeMode = shouldEnableRangeMode;
                            console.log('Set prototype isRangeMode property to:', shouldEnableRangeMode);
                        } else if (Object.getOwnPropertyDescriptor(picker, 'isRangeMode')) {
                            // For direct property
                            picker.isRangeMode = shouldEnableRangeMode;
                            console.log('Set direct isRangeMode property to:', shouldEnableRangeMode);
                        } else {
                            // Try defining the property if it doesn't exist yet
                            Object.defineProperty(picker, 'isRangeMode', {
                                value: shouldEnableRangeMode,
                                writable: true,
                                configurable: true
                            });
                            console.log('Defined new isRangeMode property as:', shouldEnableRangeMode);
                        }
                    } catch (e) {
                        console.error('Error setting isRangeMode property:', e);
                    }
                }
                
                // Handle dark mode property and styles
                var darkMode = picker.getAttribute('darkmode') || picker.getAttribute('dark-mode');
                if (darkMode !== null) {
                    var shouldEnableDarkMode = darkMode === 'true' || darkMode === '1' || darkMode === true;
                    
                    try {
                        // Set the attribute consistently
                        picker.setAttribute('dark-mode', shouldEnableDarkMode ? 'true' : 'false');
                        
                        // Apply appropriate mode styles
                        if (shouldEnableDarkMode) {
                            // Apply the dark mode CSS variables from basic.html demo
                            picker.style.setProperty('--jdp-background', '#1e1e2f');
                            picker.style.setProperty('--jdp-foreground', '#e2e8f0');
                            picker.style.setProperty('--jdp-muted', '#334155');
                            picker.style.setProperty('--jdp-muted-foreground', '#94a3b8');
                            picker.style.setProperty('--jdp-border', '#475569');
                            picker.style.setProperty('--jdp-day-hover-bg', '#334155');
                            picker.style.setProperty('--jdp-input-border-color', '#475569');
                            picker.style.setProperty('--jdp-input-bg', '#1e1e2f');
                            picker.style.setProperty('--jdp-calendar-bg', '#1e1e2f');
                            picker.style.setProperty('--jdp-holiday-bg', '#3f1e2e');
                            picker.style.setProperty('--jdp-border-color', '#475569');
                            picker.style.setProperty('--jdp-nav-arrow-color', '#e2e8f0');
                            picker.style.setProperty('--jdp-selected-bg', '#0891b2');
                            picker.style.setProperty('--jdp-hover-bg', 'rgba(8, 145, 178, 0.2)');
                            picker.style.setProperty('--jdp-today-border-color', '#0891b2');
                            picker.style.setProperty('--jdp-input-focus-border', '#0891b2');
                            picker.style.setProperty('--jdp-input-focus-shadow', '0 0 0 1px #0891b2');
                        } else {
                            // Apply light mode CSS variables
                            picker.style.setProperty('--jdp-background', '#ffffff');
                            picker.style.setProperty('--jdp-foreground', '#333333');
                            picker.style.setProperty('--jdp-muted', '#f1f5f9');
                            picker.style.setProperty('--jdp-muted-foreground', '#64748b');
                            picker.style.setProperty('--jdp-border', '#e2e8f0');
                            picker.style.setProperty('--jdp-day-hover-bg', '#f1f5f9');
                            picker.style.setProperty('--jdp-input-border-color', '#ddd');
                            picker.style.setProperty('--jdp-input-bg', '#ffffff');
                            picker.style.setProperty('--jdp-calendar-bg', '#ffffff');
                            picker.style.setProperty('--jdp-holiday-bg', '#fff1f2');
                            picker.style.setProperty('--jdp-border-color', '#ddd');
                            picker.style.setProperty('--jdp-nav-arrow-color', '#505050');
                            picker.style.setProperty('--jdp-selected-bg', '#2271b1');
                            picker.style.setProperty('--jdp-hover-bg', 'rgba(34, 113, 177, 0.1)');
                            picker.style.setProperty('--jdp-today-border-color', '#2271b1');
                            picker.style.setProperty('--jdp-input-focus-border', '#2271b1');
                            picker.style.setProperty('--jdp-input-focus-shadow', '0 0 0 1px #2271b1');
                        }

                        // Try to access the internal darkMode property
                        if (picker.__proto__ && picker.__proto__.darkMode !== undefined) {
                            // For prototype-based property
                            picker.__proto__.darkMode = shouldEnableDarkMode;
                            console.log('Set prototype darkMode property to:', shouldEnableDarkMode);
                        } else if (Object.getOwnPropertyDescriptor(picker, 'darkMode')) {
                            // For direct property
                            picker.darkMode = shouldEnableDarkMode;
                            console.log('Set direct darkMode property to:', shouldEnableDarkMode);
                        } else {
                            // Try defining the property if it doesn't exist yet
                            Object.defineProperty(picker, 'darkMode', {
                                value: shouldEnableDarkMode,
                                writable: true,
                                configurable: true
                            });
                            console.log('Defined new darkMode property as:', shouldEnableDarkMode);
                        }
                    } catch (e) {
                        console.error('Error setting darkMode property:', e);
                    }
                }
                
                // Also try to force component rendering, but only if it's fully initialized
                if (typeof picker.render === 'function' && picker.shadowRoot && picker.shadowRoot.querySelector('.datepicker-container')) {
                    try {
                        picker.render();
                        console.log('Called render method on datepicker #' + index);
                    } catch (e) {
                        console.error('Error rendering datepicker #' + index + ':', e);
                    }
                } else {
                    console.log('Skipped render call on picker #' + index + ' (not fully initialized)');
                }
                
                // Alternatively, try to force a property update event
                var event = new CustomEvent('property-changed', { 
                    detail: { properties: ['isRangeMode', 'darkMode'] },
                    bubbles: true
                });
                picker.dispatchEvent(event);
            });
            
            // Set up a MutationObserver to handle dynamically added datepickers
            try {
                var observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.addedNodes.length) {
                            mutation.addedNodes.forEach(function(node) {
                                // Check if it's a datepicker element or contains one
                                if (node.tagName && node.tagName.toLowerCase() === 'persian-datepicker-element') {
                                    console.log('New datepicker detected, applying property fixes');
                                    
                                    // Add safety wrapper to prevent render errors
                                    wrapRenderMethodWithSafety(node);
                                    
                                    // Apply range mode fix if needed
                                    var rangeMode = node.getAttribute('range-mode');
                                    if (rangeMode !== null) {
                                        var shouldEnableRangeMode = rangeMode === 'true' || rangeMode === '1' || rangeMode === true;
                                        
                                        // Log what we're doing
                                        if (shouldEnableRangeMode) {
                                            console.log('New datepicker detected with range mode enabled');
                                                } else {
                                            console.log('New datepicker detected with range mode disabled');
                                        }
                                        
                                        // Wait for component to initialize
                                        setTimeout(function() {
                                            try {
                                                // Fix for the "Cannot set properties of undefined (setting 'innerHTML')" error
                                                // Ensure the shadowRoot is initialized before trying to render
                                                if (!node.shadowRoot || !node.shadowRoot.querySelector('.datepicker-container')) {
                                                    console.log('New picker is not fully initialized, skipping render');
                                                    return;
                                                }
                                                
                                                // Apply range mode settings based on shouldEnableRangeMode
                                                if (node._datepicker || node.datepicker) {
                                                    var instance = node._datepicker || node.datepicker;
                                                    
                                                    if (typeof instance.setRangeMode === 'function') {
                                                        instance.setRangeMode(shouldEnableRangeMode);
                                                        console.log('Called setRangeMode(' + shouldEnableRangeMode + ') on new instance');
                                                    } else if (typeof instance.setOptions === 'function') {
                                                        instance.setOptions({ isRangeMode: shouldEnableRangeMode });
                                                        console.log('Called setOptions({isRangeMode:' + shouldEnableRangeMode + '}) on new instance');
                                                    } else if (instance.options) {
                                                        instance.options.isRangeMode = shouldEnableRangeMode;
                                                        if (instance.updateOptions) instance.updateOptions();
                                                        else if (instance.update) instance.update();
                                                        console.log('Updated instance.options.isRangeMode to ' + shouldEnableRangeMode);
                                                    }
                                                } else if (node.shadowRoot) {
                                                    var baseElement = node.shadowRoot.querySelector('.datepicker-container');
                                                    
                                                    if (baseElement && baseElement._datepicker) {
                                                        var instance = baseElement._datepicker;
                                                        
                                                        if (typeof instance.setRangeMode === 'function') {
                                                            instance.setRangeMode(shouldEnableRangeMode);
                                                            console.log('Called setRangeMode(' + shouldEnableRangeMode + ') via shadowRoot');
                                                        } else if (typeof instance.setOptions === 'function') {
                                                            instance.setOptions({ isRangeMode: shouldEnableRangeMode });
                                                            console.log('Called setOptions({isRangeMode:' + shouldEnableRangeMode + '}) via shadowRoot');
                                                        } else if (instance.options) {
                                                            instance.options.isRangeMode = true;
                                                            if (instance.updateOptions) instance.updateOptions();
                                                            else if (instance.update) instance.update();
                                                        }
                                                    }
                                                }
                                                
                                                // Only call render if the component is fully initialized
                                                if (typeof node.render === 'function' && node.shadowRoot && node.shadowRoot.querySelector('.datepicker-container')) {
                                                    node.render();
                                                }
                                            } catch (e) {
                                                console.error('Error applying direct API fix to new element:', e);
                                            }
                                        }, 200);
                                    } else {
                                        console.log('New datepicker detected with range mode disabled, skipping range mode API fix');
                                    }
                                } else if (node.querySelectorAll) {
                                    // Process nested datepicker elements
                                    var nestedPickers = node.querySelectorAll('persian-datepicker-element');
                                    if (nestedPickers.length > 0) {
                                        console.log('Found nested datepickers in new content:', nestedPickers.length);
                                        
                                        // Process each nested picker with a delay
                                        nestedPickers.forEach(function(nestedPicker) {
                                            var rangeMode = nestedPicker.getAttribute('range-mode');
                                                var shouldEnableRangeMode = rangeMode === 'true' || rangeMode === '1' || rangeMode === true;
                                                
                                            // Only enable range mode if explicitly requested
                                            if (shouldEnableRangeMode) {
                                                setTimeout(function() {
                                                    try {
                                                        // Check if component is fully initialized
                                                        if (!nestedPicker.shadowRoot || !nestedPicker.shadowRoot.querySelector('.datepicker-container')) {
                                                            console.log('Nested picker is not fully initialized, skipping render');
                                                            return;
                                                        }
                                                        
                                                        // Apply same fixes as above (condensed)
                                                        var instance = null;
                                                        
                                                        if (nestedPicker._datepicker || nestedPicker.datepicker) {
                                                            instance = nestedPicker._datepicker || nestedPicker.datepicker;
                                                        } else if (nestedPicker.shadowRoot) {
                                                            var baseElement = nestedPicker.shadowRoot.querySelector('.datepicker-container');
                                                            if (baseElement && baseElement._datepicker) {
                                                                instance = baseElement._datepicker;
                                                            }
                                                        }
                                                        
                                                        if (instance) {
                                                            if (typeof instance.setRangeMode === 'function') {
                                                                instance.setRangeMode(true);
                                                            } else if (typeof instance.setOptions === 'function') {
                                                                instance.setOptions({ isRangeMode: true });
                                                            } else if (instance.options) {
                                                                instance.options.isRangeMode = true;
                                                                if (instance.updateOptions) instance.updateOptions();
                                                                else if (instance.update) instance.update();
                                                            }
                                                        }
                                                        
                                                        // Only call render if component is fully initialized
                                                        if (typeof nestedPicker.render === 'function' && nestedPicker.shadowRoot && nestedPicker.shadowRoot.querySelector('.datepicker-container')) {
                                                            nestedPicker.render();
                                                        }
                                                    } catch (e) {
                                                        console.error('Error applying direct API fix to nested element:', e);
                                                    }
                                                }, 300); // Increased timeout for nested pickers
                                            } else {
                                                console.log('Nested picker has range mode disabled, skipping range mode API fix');
                                            }
                                        });
                                    }
                                }
                            });
                        }
                    });
                });
                
                // Start observing the body
                observer.observe(document.body, { childList: true, subtree: true });
                console.log('MutationObserver set up for direct API fix');
                
            } catch (e) {
                console.error('Error setting up MutationObserver for direct API fix:', e);
            }
        }, 1500); // Increased timeout to ensure components are fully initialized
    });
    </script>
    <?php
}
add_action('wp_footer', 'wp_persian_datepicker_israngemode_property_fix', 100);
add_action('admin_footer', 'wp_persian_datepicker_israngemode_property_fix', 100);

/**
 * Add a specific fix for direct API access to Persian datepicker components
 * Ensures consistent behavior when interacting with the datepicker API
 */
function wp_persian_datepicker_direct_api_fix() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Wait a bit to ensure all components are fully initialized
        setTimeout(function() {
            console.log('Running direct API fixes for Persian datepicker');
            
            // Find all datepicker elements
            var datepickers = document.querySelectorAll('persian-datepicker-element');
          
            console.log('Found ' + datepickers.length + ' datepickers to apply direct API fixes');
            
            // Process each datepicker to apply direct API fixes
            datepickers.forEach(function(picker, index) {
                try {
                    // Check if the component is fully initialized
                    if (!picker.shadowRoot || !picker.shadowRoot.querySelector('.datepicker-container')) {
                        console.log('Picker #' + index + ' is not fully initialized, skipping direct API fix');
                    return;
                }
                
                    // Apply direct API fixes depending on the internal structure
                    if (picker._datepicker || picker.datepicker) {
                        var instance = picker._datepicker || picker.datepicker;
                        
                        // Ensure the API methods are properly bound to the instance
                        if (instance && typeof instance.setDate === 'function') {
                            // Make sure the setDate method is properly bound
                            var originalSetDate = instance.setDate;
                            instance.setDate = function() {
                                try {
                                    return originalSetDate.apply(instance, arguments);
                            } catch (e) {
                                    console.error('Error in wrapped setDate method:', e);
                                return false;
                            }
                        };
                            console.log('Wrapped setDate method on picker #' + index);
                        }
                        
                        // Fix other API methods as needed
                        if (instance && typeof instance.getDate === 'function') {
                            var originalGetDate = instance.getDate;
                            instance.getDate = function() {
                                try {
                                    return originalGetDate.apply(instance, arguments);
                                } catch (e) {
                                    console.error('Error in wrapped getDate method:', e);
                                    return null;
                                }
                            };
                            console.log('Wrapped getDate method on picker #' + index);
                        }
                    }
                    
                    // Add direct access to instance via element
                    if (!picker.getAPI && (picker._datepicker || picker.datepicker)) {
                        picker.getAPI = function() {
                            return picker._datepicker || picker.datepicker;
                        };
                        console.log('Added getAPI method to picker #' + index);
                    }
                    
                } catch (e) {
                    console.error('Error applying direct API fix to picker #' + index + ':', e);
                }
            });
            
            // Also listen for any newly added datepickers
                var observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                            mutation.addedNodes.forEach(function(node) {
                        if (node.nodeType === 1) { // Element nodes only
                            // Check if it's a datepicker element
                                if (node.tagName && node.tagName.toLowerCase() === 'persian-datepicker-element') {
                                        console.log('New datepicker detected, applying direct API fix');
                                        setTimeout(function() {
                                            try {
                                        // Ensure the component is initialized
                                                if (!node.shadowRoot || !node.shadowRoot.querySelector('.datepicker-container')) {
                                            console.log('New picker is not fully initialized, skipping direct API fix');
                                                    return;
                                                }
                                                
                                                // Apply the same fixes as above
                                                if (node._datepicker || node.datepicker) {
                                                    var instance = node._datepicker || node.datepicker;
                                                    
                                            if (instance && typeof instance.setDate === 'function') {
                                                var originalSetDate = instance.setDate;
                                                instance.setDate = function() {
                                                    try {
                                                        return originalSetDate.apply(instance, arguments);
                                                    } catch (e) {
                                                        console.error('Error in wrapped setDate method:', e);
                                                        return false;
                                                    }
                                                };
                                            }
                                            
                                            if (instance && typeof instance.getDate === 'function') {
                                                var originalGetDate = instance.getDate;
                                                instance.getDate = function() {
                                                    try {
                                                        return originalGetDate.apply(instance, arguments);
                                                    } catch (e) {
                                                        console.error('Error in wrapped getDate method:', e);
                                                        return null;
                                                    }
                                                };
                                            }
                                            
                                            // Add direct access to instance via element
                                            if (!node.getAPI) {
                                                node.getAPI = function() {
                                                    return node._datepicker || node.datepicker;
                                                };
                                            }
                                                }
                                            } catch (e) {
                                                console.error('Error applying direct API fix to new element:', e);
                                            }
                                }, 300);
                                    }
                            
                            // Also check for nested datepickers
                            if (node.querySelectorAll) {
                                    var nestedPickers = node.querySelectorAll('persian-datepicker-element');
                                    if (nestedPickers.length > 0) {
                                        console.log('Found nested datepickers in new content:', nestedPickers.length);
                                        // Process each nested picker with a delay
                                        nestedPickers.forEach(function(nestedPicker) {
                                                setTimeout(function() {
                                                    try {
                                                // Same fixes as above for nested pickers
                                                        if (nestedPicker._datepicker || nestedPicker.datepicker) {
                                                    var instance = nestedPicker._datepicker || nestedPicker.datepicker;
                                                    
                                                    if (instance && typeof instance.setDate === 'function') {
                                                        var originalSetDate = instance.setDate;
                                                        instance.setDate = function() {
                                                            try {
                                                                return originalSetDate.apply(instance, arguments);
                                                            } catch (e) {
                                                                console.error('Error in wrapped setDate method:', e);
                                                                return false;
                                                            }
                                                        };
                                                    }
                                                    
                                                    if (instance && typeof instance.getDate === 'function') {
                                                        var originalGetDate = instance.getDate;
                                                        instance.getDate = function() {
                                                            try {
                                                                return originalGetDate.apply(instance, arguments);
                                                            } catch (e) {
                                                                console.error('Error in wrapped getDate method:', e);
                                                                return null;
                                                            }
                                                        };
                                                    }
                                                    
                                                    // Add direct access to instance via element
                                                    if (!nestedPicker.getAPI) {
                                                        nestedPicker.getAPI = function() {
                                                            return nestedPicker._datepicker || nestedPicker.datepicker;
                                                        };
                                                    }
                                                        }
                                                    } catch (e) {
                                                        console.error('Error applying direct API fix to nested element:', e);
                                                    }
                                        }, 400);
                                        });
                                    }
                                }
                        }
                    });
                    });
                });
                
            // Start observing the entire document
                observer.observe(document.body, { childList: true, subtree: true });
                console.log('MutationObserver set up for direct API fix');
                
        }, 2000); // Delay to ensure components are loaded
    });
    </script>
    <?php
}
add_action('wp_footer', 'wp_persian_datepicker_direct_api_fix', 102);
add_action('admin_footer', 'wp_persian_datepicker_direct_api_fix', 102); 
add_action('admin_footer', 'wp_persian_datepicker_direct_api_fix', 102); 