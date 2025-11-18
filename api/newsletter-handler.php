<?php
/**
 * Newsletter Subscription Handler with Email Confirmation
 * Altaf Catering
 */

header('Content-Type: application/json');

// Load required files
require_once '../includes/email.php';
require_once '../includes/security.php';

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Check if IP is blocked
if (isIPBlocked()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Access denied']);
    exit;
}

// Check rate limiting (5 subscriptions per hour)
if (!checkRateLimit('newsletter', 5, 3600)) {
    http_response_code(429);
    echo json_encode([
        'success' => false,
        'message' => 'Too many subscription attempts. Please try again later.'
    ]);
    exit;
}

// Get form data
$email = trim($_POST['email'] ?? '');
$name = trim($_POST['name'] ?? '');

// Validate email
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'success' => false,
        'message' => 'Please enter a valid email address'
    ]);
    exit;
}

// Sanitize data
$data = [
    'email' => filter_var($email, FILTER_SANITIZE_EMAIL),
    'name' => htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
    'date' => date('Y-m-d H:i:s'),
    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
    'status' => 'active'
];

// Save to JSON file
$subscribersFile = '../admin/data/newsletter-subscribers.json';
$subscribers = [];

if (file_exists($subscribersFile)) {
    $subscribers = json_decode(file_get_contents($subscribersFile), true) ?: [];
}

// Check if email already exists
foreach ($subscribers as $subscriber) {
    if ($subscriber['email'] === $data['email']) {
        echo json_encode([
            'success' => false,
            'message' => 'This email is already subscribed to our newsletter'
        ]);
        exit;
    }
}

// Add ID
$data['id'] = count($subscribers) + 1;

// Add to array
$subscribers[] = $data;

// Save to file
if (file_put_contents($subscribersFile, json_encode($subscribers, JSON_PRETTY_PRINT))) {
    
    // Send confirmation email
    $emailSent = false;
    
    try {
        $emailSent = sendNewsletterConfirmation($data['email'], $data['name']);
    } catch (Exception $e) {
        error_log('Email error: ' . $e->getMessage());
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Thank you for subscribing! Check your email for confirmation.',
        'email_sent' => $emailSent
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Sorry, there was an error. Please try again later.'
    ]);
}
