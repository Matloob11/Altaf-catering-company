<?php
/**
 * Authentication Check for Admin Pages
 * Include this file at the top of every admin page to ensure user is logged in
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    // Ensure session configuration matches index.php
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS
    ini_set('session.gc_maxlifetime', 3600); // 1 hour
    ini_set('session.cookie_lifetime', 0); // Until browser closes
    
    session_start();
}

// Debug session data (remove in production)
if (isset($_GET['debug_session'])) {
    echo "<pre>Session Debug:\n";
    echo "Session ID: " . session_id() . "\n";
    echo "Session Data: " . print_r($_SESSION, true) . "\n";
    echo "admin_logged_in isset: " . (isset($_SESSION['admin_logged_in']) ? 'YES' : 'NO') . "\n";
    echo "admin_logged_in value: " . (isset($_SESSION['admin_logged_in']) ? var_export($_SESSION['admin_logged_in'], true) : 'NOT SET') . "\n";
    echo "</pre>";
    exit;
}

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Debug: Show what's happening
    if (isset($_GET['auth_debug'])) {
        echo "<div style='background: #f8d7da; padding: 20px; margin: 20px; border: 2px solid #dc3545;'>";
        echo "<h3>❌ Authentication Failed</h3>";
        echo "<p><strong>Session ID:</strong> " . session_id() . "</p>";
        echo "<p><strong>admin_logged_in isset:</strong> " . (isset($_SESSION['admin_logged_in']) ? 'YES' : 'NO') . "</p>";
        echo "<p><strong>admin_logged_in value:</strong> " . (isset($_SESSION['admin_logged_in']) ? var_export($_SESSION['admin_logged_in'], true) : 'NOT SET') . "</p>";
        echo "<p><strong>All Session Data:</strong></p>";
        echo "<pre>" . print_r($_SESSION, true) . "</pre>";
        echo "<p><a href='index.php'>Go to Login</a> | <a href='fix-login.php'>Quick Fix</a></p>";
        echo "</div>";
        exit;
    }
    
    // Store the current page URL for redirect after login
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    
    // Redirect to login page
    header('Location: index.php');
    exit();
}

// Optional: Check session timeout (30 minutes of inactivity)
$timeout_duration = 1800; // 30 minutes in seconds

if (isset($_SESSION['last_activity'])) {
    if ((time() - $_SESSION['last_activity']) > $timeout_duration) {
        // Session expired
        session_unset();
        session_destroy();
        header('Location: index.php?timeout=1');
        exit();
    }
}

// Update last activity time
$_SESSION['last_activity'] = time();

// Optional: Regenerate session ID periodically for security
if (!isset($_SESSION['session_regenerated'])) {
    $_SESSION['session_regenerated'] = time();
} else {
    // Regenerate session ID every 15 minutes
    if ((time() - $_SESSION['session_regenerated']) > 900) {
        session_regenerate_id(true);
        $_SESSION['session_regenerated'] = time();
    }
}
?>