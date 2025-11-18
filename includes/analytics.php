<?php
/**
 * Google Analytics Integration
 * Altaf Catering - Analytics Tracking
 */

// Google Analytics Configuration
define('GA_MEASUREMENT_ID', 'G-XXXXXXXXXX'); // Replace with your actual GA4 Measurement ID
define('ENABLE_ANALYTICS', true); // Set to false to disable tracking

/**
 * Get Analytics Script
 */
function getAnalyticsScript() {
    if (!ENABLE_ANALYTICS) {
        return '';
    }
    
    $measurementId = GA_MEASUREMENT_ID;
    
    return <<<HTML
<!-- Google Analytics (GA4) -->
<script async src="https://www.googletagmanager.com/gtag/js?id={$measurementId}"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '{$measurementId}', {
    'send_page_view': true,
    'anonymize_ip': true
  });
</script>
HTML;
}

/**
 * Track Custom Event
 */
function trackEvent($category, $action, $label = '', $value = 0) {
    if (!ENABLE_ANALYTICS) {
        return '';
    }
    
    return <<<HTML
<script>
  gtag('event', '{$action}', {
    'event_category': '{$category}',
    'event_label': '{$label}',
    'value': {$value}
  });
</script>
HTML;
}

/**
 * Track Page View
 */
function trackPageView($pageTitle, $pagePath) {
    if (!ENABLE_ANALYTICS) {
        return '';
    }
    
    return <<<HTML
<script>
  gtag('event', 'page_view', {
    'page_title': '{$pageTitle}',
    'page_path': '{$pagePath}'
  });
</script>
HTML;
}

/**
 * Track Form Submission
 */
function trackFormSubmission($formName) {
    if (!ENABLE_ANALYTICS) {
        return '';
    }
    
    return <<<HTML
<script>
  gtag('event', 'form_submit', {
    'event_category': 'Form',
    'event_label': '{$formName}'
  });
</script>
HTML;
}

/**
 * Track Button Click
 */
function trackButtonClick($buttonName, $buttonLocation) {
    if (!ENABLE_ANALYTICS) {
        return '';
    }
    
    return <<<HTML
<script>
  gtag('event', 'click', {
    'event_category': 'Button',
    'event_label': '{$buttonName}',
    'button_location': '{$buttonLocation}'
  });
</script>
HTML;
}

/**
 * Track WhatsApp Click
 */
function trackWhatsAppClick() {
    return "onclick=\"gtag('event', 'click', {'event_category': 'WhatsApp', 'event_label': 'Chat Button'});\"";
}

/**
 * Track Phone Call Click
 */
function trackPhoneClick() {
    return "onclick=\"gtag('event', 'click', {'event_category': 'Phone', 'event_label': 'Call Button'});\"";
}

/**
 * Track Social Media Click
 */
function trackSocialClick($platform) {
    return "onclick=\"gtag('event', 'click', {'event_category': 'Social Media', 'event_label': '{$platform}'});\"";
}

/**
 * Track Download
 */
function trackDownload($fileName) {
    return "onclick=\"gtag('event', 'download', {'event_category': 'File', 'event_label': '{$fileName}'});\"";
}

/**
 * Track Booking Attempt
 */
function trackBookingAttempt() {
    return "onclick=\"gtag('event', 'begin_checkout', {'event_category': 'Booking', 'event_label': 'Booking Form Started'});\"";
}

/**
 * Track Menu View
 */
function trackMenuView($menuItem) {
    return "onclick=\"gtag('event', 'view_item', {'event_category': 'Menu', 'event_label': '{$menuItem}'});\"";
}
