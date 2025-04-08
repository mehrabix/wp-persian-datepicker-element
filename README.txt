=== WP Persian Datepicker Element ===
Contributors: yourusername
Donate link: https://example.com/donate
Tags: persian calendar, jalali calendar, datepicker, persian datepicker, shamsi calendar, web component, gutenberg, rtl
Requires at least: 5.0
Tested up to: 6.4.3
Requires PHP: 7.0
Stable tag: 1.0.0
License: MIT
License URI: https://opensource.org/licenses/MIT

A modern, customizable Persian (Jalali) date picker web component for WordPress with full RTL support and easy integration.

== Description ==

WP Persian Datepicker Element is a powerful and versatile Persian (Jalali/Shamsi) date picker that can be easily added to any WordPress site. Built as a web component, it provides a modern, responsive, and intuitive date selection experience.

### Features

* 🎨 Fully customizable with CSS variables
* 🌙 Dark mode support
* 📱 Mobile-friendly with touch gestures
* 🎯 Multiple integration options (shortcode, widget, Gutenberg block)
* 📅 Holiday support with multiple event types (Iran, Afghanistan, Ancient Iran, International)
* 🔄 Right-to-left (RTL) support
* 🎨 Multiple theme options
* 📊 Date range selection mode
* 🚫 Disabled dates support
* 🎨 Customizable UI elements visibility

### Use Cases

* Contact forms
* Event registration
* Booking systems
* E-commerce (delivery date selection)
* Appointment scheduling
* Persian-friendly content management
* Any application requiring Persian/Jalali date input

### Integrations

* Works with Gutenberg editor
* Compatible with popular form plugins (Contact Form 7, Gravity Forms, etc.)
* Easily integrates with WooCommerce
* Works with custom themes and page builders

== Installation ==

1. Upload the `wp-persian-datepicker-element` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings > Persian Date Picker to configure default options
4. Use the shortcode `[persian_datepicker]` in your content, add the widget to a sidebar, or use the Gutenberg block

== Frequently Asked Questions ==

= How do I add the date picker to my content? =

You can add the date picker in several ways:

1. Use the shortcode: `[persian_datepicker]`
2. Add the Persian Date Picker widget to a widget area
3. Use the Persian Date Picker block in the Gutenberg editor

= Can I customize the appearance of the date picker? =

Yes, the date picker is fully customizable. You can:

1. Use the plugin settings to configure defaults
2. Pass custom attributes to the shortcode
3. Apply CSS variables to change colors, fonts, and other styles

= Does it support date range selection? =

Yes, you can enable range mode by setting the `range_mode` attribute to "true" in the shortcode:

`[persian_datepicker range_mode="true"]`

= How do I show holidays in the calendar? =

To show holidays, set the `show_holidays` attribute to "true":

`[persian_datepicker show_holidays="true"]`

You can also specify which holiday types to display:

`[persian_datepicker show_holidays="true" holiday_types="Iran,International"]`

= Can I use it with form plugins? =

Yes, the date picker works with most form plugins, including:

* Contact Form 7
* Gravity Forms
* WPForms
* Ninja Forms
* WooCommerce forms

= How do I retrieve the selected date value? =

The date picker creates a hidden input field that contains the selected date as a JSON array `[year, month, day]`. This field has the same name as the date picker, so it will be submitted with your form data.

== Screenshots ==

1. Persian Date Picker in default mode
2. Persian Date Picker with holidays shown
3. Persian Date Picker in range selection mode
4. Persian Date Picker in dark mode
5. Plugin settings page
6. Gutenberg block settings
7. Widget configuration

== Changelog ==

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.0.0 =
Initial release

== Additional Information ==

= Custom Styling =

You can customize the appearance of the date picker using CSS variables:

```css
.wp-persian-datepicker {
    --jdp-primary: #3b82f6; /* Main color */
    --jdp-primary-hover: #2563eb; /* Hover color */
    --jdp-primary-foreground: #ffffff; /* Text color on primary background */
    --jdp-font-family: 'Your Font', sans-serif; /* Custom font */
}
```

= Advanced Usage =

**Shortcode with all options:**

```
[persian_datepicker 
  placeholder="انتخاب تاریخ" 
  format="YYYY/MM/DD" 
  show_holidays="true" 
  rtl="true" 
  dark_mode="false" 
  holiday_types="Iran,International" 
  range_mode="false"
  show_month_selector="true"
  show_year_selector="true"
  show_prev_button="true" 
  show_next_button="true"
  show_today_button="true"
  show_tomorrow_button="false"
  today_button_text="امروز"
  tomorrow_button_text="فردا"
  class="custom-class"
  id="my-datepicker"
]
```

**Disabled Dates Function:**

If you need to disable specific dates (like weekends), you can use the `disabled_dates` attribute with a JavaScript function:

```html
<script>
function isWeekend(year, month, day) {
    var date = new Date(year, month - 1, day);
    var dayOfWeek = date.getDay();
    return dayOfWeek === 5 || dayOfWeek === 6; // Disable Friday and Saturday (Persian weekend)
}
</script>

[persian_datepicker disabled_dates="isWeekend"]
```

= Need Help? =

If you need help using this plugin, please see our [documentation](https://example.com/docs) or [contact us](https://example.com/contact). 