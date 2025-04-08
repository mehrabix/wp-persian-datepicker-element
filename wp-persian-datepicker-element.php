<?php
/**
 * Plugin Name: WP Persian Datepicker Element
 * Plugin URI: https://github.com/yourusername/wp-persian-datepicker-element
 * Description: A modern, highly customizable Persian (Jalali) date picker web component for WordPress with Contact Form 7, WPForms, Gravity Forms, and WooCommerce integration
 * Version: 1.1.0
 * Author: Ahmad Mehrabi
 * Author URI: https://github.com/yourusername
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
     * Initialize the plugin.
     */
    public function __construct() {
        $this->load_dependencies();
        $this->define_hooks();
    }

    /**
     * Load required dependencies.
     */
    private function load_dependencies() {
        // Include files
        require_once PERSIAN_DATEPICKER_PLUGIN_DIR . 'includes/class-wp-persian-datepicker-loader.php';
        require_once PERSIAN_DATEPICKER_PLUGIN_DIR . 'includes/class-wp-persian-datepicker-scripts.php';
        require_once PERSIAN_DATEPICKER_PLUGIN_DIR . 'includes/class-wp-persian-datepicker-shortcode.php';
        require_once PERSIAN_DATEPICKER_PLUGIN_DIR . 'includes/class-wp-persian-datepicker-widget.php';
        require_once PERSIAN_DATEPICKER_PLUGIN_DIR . 'admin/class-wp-persian-datepicker-admin.php';
    }

    /**
     * Define hooks and filters.
     */
    private function define_hooks() {
        // Initialize the loader
        $loader = new WP_Persian_Datepicker_Loader();
        
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
        
        // Run the loader to execute all hooks
        $loader->run();
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
        // Set default options
        if (!get_option('wp_persian_datepicker_options')) {
            $default_options = array(
                'placeholder' => 'انتخاب تاریخ',
                'show_holidays' => 1,
                'rtl' => 1,
                'format' => 'YYYY/MM/DD',
                'holiday_types' => 'Iran,International',
                'dark_mode' => 0,
                'range_mode' => 0,
            );
            update_option('wp_persian_datepicker_options', $default_options);
        }
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