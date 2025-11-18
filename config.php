<?php
/**
 * Configuration File
 * Altaf Catering - Central Configuration
 * 
 * IMPORTANT: Update these values for production!
 */

// Site Configuration
define('SITE_NAME', 'Altaf Catering');
define('SITE_URL', 'https://altafcatering.com');
define('SITE_EMAIL', 'altafcatering@gmail.com');
define('ADMIN_EMAIL', 'altafcatering@gmail.com');

// Contact Information
define('CONTACT_PHONE', '+923039907296');
define('CONTACT_PHONE_2', '+923008859633');
define('CONTACT_ADDRESS', 'MM Farm House Sharif Medical Jati Umrah Road, Karachi');
define('CONTACT_ADDRESS_2', 'Bahria Town Lahore — Umer Block (Gate No.2), near Bahria Grand Station');

// Social Media Links
define('FACEBOOK_URL', 'https://web.facebook.com/AltafCateringCompany');
define('INSTAGRAM_URL', 'https://www.instagram.com/altafcateringcompany/');
define('YOUTUBE_URL', 'https://www.youtube.com/@Altafcateringcompanyy');
define('TIKTOK_URL', 'https://www.tiktok.com/@altafcateringcompany');

// Email Configuration - Resend.com API
// Get your API key from: https://resend.com/api-keys
define('EMAIL_SERVICE', 'resend'); // 'resend', 'smtp', or 'php_mail'
define('RESEND_API_KEY', 're_U3TCNs8d_NRC7Dgup5sf1LSAzCXh9MgWL'); // Your Resend API Key
define('FROM_EMAIL', 'onboarding@resend.dev'); // Use your verified domain or resend.dev for testing
define('FROM_NAME', 'Altaf Catering');
define('RESEND_TEST_EMAIL', 'matloobulhassnain11@gmail.com'); // Your registered Resend email for testing

// SMTP Configuration (Alternative - if not using Resend)
define('SMTP_ENABLED', false);
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'altafcatering@gmail.com');
define('SMTP_PASSWORD', 'your-app-password');
define('SMTP_ENCRYPTION', 'tls');

// WhatsApp Business Configuration
define('WHATSAPP_NUMBER', '+923039907296');
define('WHATSAPP_BUSINESS_NUMBER', '+923008859633');
define('WHATSAPP_MESSAGE_TEMPLATE', 'Hello! I am interested in your catering services.');

// Google Analytics
define('GOOGLE_ANALYTICS_ID', 'G-XXXXXXXXXX'); // UPDATE THIS

// OpenRouter AI (for AI Chat)
// Get your API key from: https://openrouter.ai/keys
define('OPENROUTER_API_KEY', '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');

// Security Settings
define('SESSION_TIMEOUT', 7200); // 2 hours in seconds
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_TIMEOUT', 900); // 15 minutes in seconds
define('CSRF_TOKEN_EXPIRY', 3600); // 1 hour in seconds

// File Upload Settings
define('MAX_UPLOAD_SIZE', 5242880); // 5MB in bytes
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
define('UPLOAD_PATH', __DIR__ . '/img/uploads/');

// JSON Data Path
define('DATA_PATH', __DIR__ . '/admin/data/');

// Timezone
date_default_timezone_set('Asia/Karachi');

// Development Mode (Set to false in production)
define('DEV_MODE', true);

// Error Reporting (Disable in production)
if (DEV_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Helper Functions
function getDataFile($filename) {
    return DATA_PATH . $filename;
}

function loadJSONData($filename) {
    $file = getDataFile($filename);
    if (file_exists($file)) {
        $content = file_get_contents($file);
        return json_decode($content, true) ?: [];
    }
    return [];
}

function saveJSONData($filename, $data) {
    $file = getDataFile($filename);
    return file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// Auto-create data directory if not exists
if (!file_exists(DATA_PATH)) {
    mkdir(DATA_PATH, 0755, true);
}

// Auto-create upload directory if not exists
if (!file_exists(UPLOAD_PATH)) {
    mkdir(UPLOAD_PATH, 0755, true);
}
