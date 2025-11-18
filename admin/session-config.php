<?php
echo "<h2>PHP Session Configuration</h2>";

echo "<h3>Session Settings:</h3>";
echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr><td><strong>session.save_handler</strong></td><td>" . ini_get('session.save_handler') . "</td></tr>";
echo "<tr><td><strong>session.save_path</strong></td><td>" . ini_get('session.save_path') . "</td></tr>";
echo "<tr><td><strong>session.use_cookies</strong></td><td>" . (ini_get('session.use_cookies') ? 'Yes' : 'No') . "</td></tr>";
echo "<tr><td><strong>session.cookie_lifetime</strong></td><td>" . ini_get('session.cookie_lifetime') . "</td></tr>";
echo "<tr><td><strong>session.cookie_path</strong></td><td>" . ini_get('session.cookie_path') . "</td></tr>";
echo "<tr><td><strong>session.cookie_domain</strong></td><td>" . ini_get('session.cookie_domain') . "</td></tr>";
echo "<tr><td><strong>session.cookie_secure</strong></td><td>" . (ini_get('session.cookie_secure') ? 'Yes' : 'No') . "</td></tr>";
echo "<tr><td><strong>session.cookie_httponly</strong></td><td>" . (ini_get('session.cookie_httponly') ? 'Yes' : 'No') . "</td></tr>";
echo "<tr><td><strong>session.gc_maxlifetime</strong></td><td>" . ini_get('session.gc_maxlifetime') . "</td></tr>";
echo "</table>";

echo "<h3>Session Test:</h3>";
session_start();

if (!isset($_SESSION['test_counter'])) {
    $_SESSION['test_counter'] = 1;
} else {
    $_SESSION['test_counter']++;
}

echo "<p><strong>Session ID:</strong> " . session_id() . "</p>";
echo "<p><strong>Test Counter:</strong> " . $_SESSION['test_counter'] . " (should increment on refresh)</p>";

echo "<h3>Session Directory Check:</h3>";
$session_path = session_save_path();
if (empty($session_path)) {
    $session_path = sys_get_temp_dir();
}
echo "<p><strong>Session Path:</strong> $session_path</p>";
echo "<p><strong>Path Writable:</strong> " . (is_writable($session_path) ? 'Yes' : 'No') . "</p>";
echo "<p><strong>Path Exists:</strong> " . (is_dir($session_path) ? 'Yes' : 'No') . "</p>";

echo "<p><a href='index.php'>Back to Login</a> | <a href='debug-session.php'>Session Debug</a></p>";
?>