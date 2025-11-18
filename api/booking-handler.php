<?php
/**
 * Booking Form Handler with Email Notifications
 * Altaf Catering
 */

header('Content-Type: application/json');

// Load required files
require_once '../config.php';
require_once '../includes/security.php';

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Check if IP is blocked (skip in dev mode)
if (!DEV_MODE && isIPBlocked()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Access denied']);
    exit;
}

// Check rate limiting (relaxed in dev mode)
$maxAttempts = DEV_MODE ? 100 : 5; // 100 attempts in dev, 5 in production
$timeWindow = DEV_MODE ? 60 : 600; // 1 minute in dev, 10 minutes in production

if (!checkRateLimit('booking_form', $maxAttempts, $timeWindow)) {
    $waitTime = getRateLimitWaitTime('booking_form', $timeWindow);
    http_response_code(429);
    echo json_encode([
        'success' => false,
        'message' => 'Too many booking attempts. Please try again in ' . ceil($waitTime / 60) . ' minutes.'
    ]);
    exit;
}

// Get form data
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$city = trim($_POST['city'] ?? '');
$address = trim($_POST['address'] ?? '');
$event_type = trim($_POST['eventType'] ?? '');
$event_date = trim($_POST['eventDate'] ?? '');
$guests = trim($_POST['guestCount'] ?? '');
$menu_type = trim($_POST['menuType'] ?? '');

// Debug log
error_log("Booking Form Data: " . json_encode($_POST));

// Validate required fields
if (empty($name) || empty($email) || empty($phone) || empty($city) || empty($address) || empty($event_type) || empty($event_date) || empty($guests) || empty($menu_type)) {
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

// Validate date (must be in future or today)
$eventDateTime = strtotime($event_date);
$today = strtotime(date('Y-m-d'));
if ($eventDateTime < $today) {
    echo json_encode([
        'success' => false,
        'message' => 'Event date must be today or in the future'
    ]);
    exit;
}

// Sanitize data
$data = [
    'name' => htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
    'email' => filter_var($email, FILTER_SANITIZE_EMAIL),
    'phone' => htmlspecialchars($phone, ENT_QUOTES, 'UTF-8'),
    'city' => htmlspecialchars($city, ENT_QUOTES, 'UTF-8'),
    'address' => htmlspecialchars($address, ENT_QUOTES, 'UTF-8'),
    'event_type' => htmlspecialchars($event_type, ENT_QUOTES, 'UTF-8'),
    'event_date' => htmlspecialchars($event_date, ENT_QUOTES, 'UTF-8'),
    'guests' => intval($guests),
    'menu_type' => htmlspecialchars($menu_type, ENT_QUOTES, 'UTF-8'),
    'date' => date('Y-m-d H:i:s'),
    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
    'status' => 'pending'
];

// Save to JSON file
$bookingsFile = '../admin/data/bookings.json';
$bookings = [];

if (file_exists($bookingsFile)) {
    $bookings = json_decode(file_get_contents($bookingsFile), true) ?: [];
}

// Add ID
$data['id'] = count($bookings) + 1;

// Add to array
$bookings[] = $data;

// Save to file
if (file_put_contents($bookingsFile, json_encode($bookings, JSON_PRETTY_PRINT))) {
    
    // Create WhatsApp message with original (non-sanitized) data
    $whatsappMessage = "🎉 *New Booking Request*\n\n";
    $whatsappMessage .= "👤 *Name:* $name\n";
    $whatsappMessage .= "📧 *Email:* $email\n";
    $whatsappMessage .= "📱 *Phone:* $phone\n";
    $whatsappMessage .= "🏙️ *City:* $city\n";
    $whatsappMessage .= "📍 *Address:* $address\n";
    $whatsappMessage .= "🎊 *Event Type:* $event_type\n";
    $whatsappMessage .= "📅 *Event Date:* $event_date\n";
    $whatsappMessage .= "👥 *Guests:* $guests\n";
    $whatsappMessage .= "🍽️ *Menu Type:* $menu_type\n\n";
    $whatsappMessage .= "Please confirm my booking details. Thank you!";
    
    // WhatsApp business number (without + sign for wa.me)
    $whatsappNumber = '923039907296';
    $whatsappUrl = 'https://wa.me/' . $whatsappNumber . '?text=' . urlencode($whatsappMessage);
    
    // Debug: Log WhatsApp URL
    error_log("WhatsApp URL: " . $whatsappUrl);
    
    echo json_encode([
        'success' => true,
        'message' => 'Booking saved! Opening WhatsApp...',
        'whatsapp_url' => $whatsappUrl,
        'debug_message' => $whatsappMessage // For debugging
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Error saving booking. Please try again.'
    ]);
}
