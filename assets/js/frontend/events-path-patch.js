/**
 * Patches the Persian Datepicker Element to use the correct path for events.json
 */
(function() {
    // یک متد اصلی fetchWithRetry را از کامپوننت پیدا و ذخیره کنیم
    const originalFetch = window.fetch;
    
    // جایگزینی متد fetch با یک متد سفارشی برای تغییر مسیر درخواست
    window.fetch = function(url, options) {
        // اگر درخواست برای events.json است و مسیر سفارشی تعریف شده‌است
        if (url === 'data/events.json' && window.persianDatepickerEventsPath) {
            // از مسیر سفارشی استفاده کنیم
            return originalFetch(window.persianDatepickerEventsPath, options);
        }
        
        // در غیر این صورت، از متد اصلی fetch استفاده کنیم
        return originalFetch(url, options);
    };
    
    // وقتی کامپوننت ثبت شد، به آن آموزش دهیم که چگونه مسیر را تغییر دهد
    document.addEventListener('DOMContentLoaded', function() {
        // روی همه کامپوننت‌های موجود اعمال کنیم
        const patchDatepicker = function(datepicker) {
            // اگر کامپوننت مقدار events-path را دارد
            if (datepicker.hasAttribute('events-path')) {
                const eventsPath = datepicker.getAttribute('events-path');
                if (eventsPath) {
                    window.persianDatepickerEventsPath = eventsPath;
                }
            }
            
            // یک observer برای تغییرات attr ایجاد کنیم
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'events-path') {
                        const newPath = datepicker.getAttribute('events-path');
                        if (newPath) {
                            window.persianDatepickerEventsPath = newPath;
                        }
                    }
                });
            });
            
            // شروع مشاهده تغییرات
            observer.observe(datepicker, { attributes: true });
        };
        
        // اعمال پچ به همه کامپوننت‌های موجود
        document.querySelectorAll('persian-datepicker-element').forEach(patchDatepicker);
        
        // یک observer برای کامپوننت‌های جدید ایجاد کنیم
        const bodyObserver = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList') {
                    mutation.addedNodes.forEach(function(node) {
                        if (node.nodeType === 1) { // ELEMENT_NODE
                            if (node.tagName && node.tagName.toLowerCase() === 'persian-datepicker-element') {
                                patchDatepicker(node);
                            } else {
                                node.querySelectorAll('persian-datepicker-element').forEach(patchDatepicker);
                            }
                        }
                    });
                }
            });
        });
        
        // شروع مشاهده تغییرات در body
        bodyObserver.observe(document.body, { childList: true, subtree: true });
    });
})(); 