<?php
/**
 * Security Functions
 * Altaf Catering - Enhanced Security Layer
 */

// Start session securely
function startSecureSession() {
    if (session_status() === PHP_SESSION_NONE) {
        // Set secure session parameters
        ini_set('session.cookie_httponly', 1);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.cookie_samesite', 'Strict');
        
        // For HTTPS (uncomment in production)
        // ini_set('session.cookie_secure', 1);
        
        session_start();
        
        // Regenerate session ID periodically
        if (!isset($_SESSION['created'])) {
            $_SESSION['created'] = time();
        } else if (time() - $_SESSION['created'] > 1800) {
            // Regenerate after 30 minutes
            session_regenerate_id(true);
            $_SESSION['created'] = time();
        }
    }
}

/**
 * Generate CSRF Token
 */
function generateCSRFToken() {
    startSecureSession();
    
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF Token
 */
function verifyCSRFToken($token) {
    startSecureSession();
    
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Get CSRF Token Input Field
 */
function csrfTokenField() {
    $token = generateCSRFToken();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
}

/**
 * Hash Password Securely
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_ARGON2ID, [
        'memory_cost' => 65536,
        'time_cost' => 4,
        'threads' => 3
    ]);
}

/**
 * Verify Password
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Sanitize Input
 */
function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    
    return $data;
}

/**
 * Validate Email
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate Phone Number
 */
function validatePhone($phone) {
    // Remove all non-numeric characters
    $phone = preg_replace('/[^0-9+]/', '', $phone);
    
    // Check if valid Pakistani phone number
    return preg_match('/^(\+92|0)?[0-9]{10}$/', $phone);
}

/**
 * Rate Limiting
 */
function checkRateLimit($action, $maxAttempts = 5, $timeWindow = 300) {
    startSecureSession();
    
    $key = 'rate_limit_' . $action;
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = [];
    }
    
    // Clean old attempts
    $_SESSION[$key] = array_filter($_SESSION[$key], function($timestamp) use ($timeWindow) {
        return (time() - $timestamp) < $timeWindow;
    });
    
    // Check if limit exceeded
    if (count($_SESSION[$key]) >= $maxAttempts) {
        return false;
    }
    
    // Add current attempt
    $_SESSION[$key][] = time();
    
    return true;
}

/**
 * Get Rate Limit Remaining Time
 */
function getRateLimitWaitTime($action, $timeWindow = 300) {
    startSecureSession();
    
    $key = 'rate_limit_' . $action;
    
    if (!isset($_SESSION[$key]) || empty($_SESSION[$key])) {
        return 0;
    }
    
    $oldestAttempt = min($_SESSION[$key]);
    $waitTime = $timeWindow - (time() - $oldestAttempt);
    
    return max(0, $waitTime);
}

/**
 * Prevent SQL Injection (for future database use)
 */
function escapeSQLString($string) {
    return addslashes($string);
}

/**
 * Prevent XSS
 */
function preventXSS($data) {
    return htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

/**
 * Validate File Upload
 */
function validateFileUpload($file, $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'], $maxSize = 5242880) {
    $errors = [];
    
    // Check if file was uploaded
    if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
        $errors[] = 'No file uploaded';
        return ['valid' => false, 'errors' => $errors];
    }
    
    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'File upload error: ' . $file['error'];
        return ['valid' => false, 'errors' => $errors];
    }
    
    // Check file size
    if ($file['size'] > $maxSize) {
        $errors[] = 'File too large. Maximum size: ' . ($maxSize / 1024 / 1024) . 'MB';
        return ['valid' => false, 'errors' => $errors];
    }
    
    // Check file type
    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($fileExt, $allowedTypes)) {
        $errors[] = 'Invalid file type. Allowed: ' . implode(', ', $allowedTypes);
        return ['valid' => false, 'errors' => $errors];
    }
    
    // Check MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    $allowedMimes = [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/gif'
    ];
    
    if (!in_array($mimeType, $allowedMimes)) {
        $errors[] = 'Invalid file MIME type';
        return ['valid' => false, 'errors' => $errors];
    }
    
    return ['valid' => true, 'errors' => []];
}

/**
 * Generate Secure Random String
 */
function generateSecureToken($length = 32) {
    return bin2hex(random_bytes($length));
}

/**
 * Check if Request is AJAX
 */
function isAjaxRequest() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

/**
 * Prevent Directory Traversal
 */
function sanitizePath($path) {
    // Remove any ../ or ..\
    $path = str_replace(['../', '..\\'], '', $path);
    
    // Remove any null bytes
    $path = str_replace(chr(0), '', $path);
    
    return $path;
}

/**
 * Log Security Event
 */
function logSecurityEvent($event, $details = []) {
    $logFile = __DIR__ . '/../admin/data/security-log.json';
    
    $logs = [];
    if (file_exists($logFile)) {
        $logs = json_decode(file_get_contents($logFile), true) ?: [];
    }
    
    $logs[] = [
        'event' => $event,
        'details' => $details,
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
        'timestamp' => date('Y-m-d H:i:s')
    ];
    
    // Keep only last 1000 logs
    if (count($logs) > 1000) {
        $logs = array_slice($logs, -1000);
    }
    
    file_put_contents($logFile, json_encode($logs, JSON_PRETTY_PRINT));
}

/**
 * Check if IP is Blocked
 */
function isIPBlocked($ip = null) {
    if ($ip === null) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    }
    
    $blockedFile = __DIR__ . '/../admin/data/blocked-ips.json';
    
    if (!file_exists($blockedFile)) {
        return false;
    }
    
    $blocked = json_decode(file_get_contents($blockedFile), true) ?: [];
    
    return in_array($ip, $blocked);
}

/**
 * Block IP Address
 */
function blockIP($ip, $reason = '') {
    $blockedFile = __DIR__ . '/../admin/data/blocked-ips.json';
    
    $blocked = [];
    if (file_exists($blockedFile)) {
        $blocked = json_decode(file_get_contents($blockedFile), true) ?: [];
    }
    
    if (!in_array($ip, $blocked)) {
        $blocked[] = $ip;
        file_put_contents($blockedFile, json_encode($blocked, JSON_PRETTY_PRINT));
        
        logSecurityEvent('ip_blocked', [
            'ip' => $ip,
            'reason' => $reason
        ]);
    }
}

/**
 * Validate Admin Session
 */
function validateAdminSession() {
    startSecureSession();
    
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: index.php');
        exit;
    }
    
    // Check session timeout (2 hours)
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 7200)) {
        session_unset();
        session_destroy();
        header('Location: index.php?timeout=1');
        exit;
    }
    
    $_SESSION['last_activity'] = time();
}

/**
 * Clean Output (prevent information disclosure)
 */
function cleanOutput($output) {
    // Remove any PHP error messages
    $output = preg_replace('/Fatal error:.*$/m', '', $output);
    $output = preg_replace('/Warning:.*$/m', '', $output);
    $output = preg_replace('/Notice:.*$/m', '', $output);
    
    return $output;
}
