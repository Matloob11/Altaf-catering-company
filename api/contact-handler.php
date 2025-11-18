<?php
/**
 * Contact Form Handler with Email Notifications
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

// Check rate limiting (3 submissions per 5 minutes)
if (!checkRateLimit('contact_form', 3, 300)) {
    $waitTime = getRateLimitWaitTime('contact_form', 300);
    http_response_code(429);
    echo json_encode([
        'success' => false,
        'message' => 'Too many submissions. Please try again in ' . ceil($waitTime / 60) . ' minutes.'
    ]);
    exit;
}

// Get form data
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

// Validate required fields
if (empty($name) || empty($email) || empty($phone) || empty($message)) {
    echo json_encode([
        'success' => false,
        'message' => 'Please fill in all required fields'
    ]);
    exit;
}

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'success' => false,
        'message' => 'Please enter a valid email address'
    ]);
    exit;
}

// Sanitize data
$data = [
    'name' => htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
    'email' => filter_var($email, FILTER_SANITIZE_EMAIL),
    'phone' => htmlspecialchars($phone, ENT_QUOTES, 'UTF-8'),
    'subject' => htmlspecialchars($subject, ENT_QUOTES, 'UTF-8'),
    'message' => htmlspecialchars($message, ENT_QUOTES, 'UTF-8'),
    'date' => date('Y-m-d H:i:s'),
    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
    'status' => 'new'
];

// Save to JSON file
$contactsFile = '../admin/data/contacts.json';
$contacts = [];

if (file_exists($contactsFile)) {
    $contacts = json_decode(file_get_contents($contactsFile), true) ?: [];
}

// Add ID
$data['id'] = count($contacts) + 1;

// Add to array
$contacts[] = $data;

// Save to file
if (file_put_contents($contactsFile, json_encode($contacts, JSON_PRETTY_PRINT))) {
    
    // Send email notifications
    $emailSent = false;
    $customerEmailSent = false;
    
    try {
        // Send notification to admin
        $emailSent = sendContactNotification($data);
        
        // Send confirmation to customer
        $customerEmailSent = sendContactConfirmation($data);
    } catch (Exception $e) {
        // Log error but don't fail the submission
        error_log('Email error: ' . $e->getMessage());
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Thank you! Your message has been sent successfully. We will get back to you within 24 hours.',
        'email_sent' => $emailSent,
        'confirmation_sent' => $customerEmailSent
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Sorry, there was an error saving your message. Please try again or contact us directly.'
    ]);
}
