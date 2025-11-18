<?php
/**
 * AI Chat API Endpoint
 * Handles AI chat requests for the website widget
 */

// Load secure configuration
require_once '../config.php';

// Set JSON response header
header('Content-Type: application/json');

// Handle CORS if needed
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Get message from request
$input = json_decode(file_get_contents('php://input'), true);
$message = $input['message'] ?? $_POST['message'] ?? '';

if (empty($message)) {
    echo json_encode(['error' => 'No message provided']);
    exit;
}

// Check if API key is configured
if (empty(OPENROUTER_API_KEY)) {
    echo json_encode(['error' => 'AI service not configured']);
    exit;
}

// System prompt for widget
$system_prompt = "You are a helpful AI assistant for Altaf Catering Company, a premium catering service in Pakistan. 

Business Information:
- Phone: +92 303 9907296
- Email: altafcatering@gmail.com  
- Address: MM Farm House Sharif Medical Jati Umrah Road, Karachi, Pakistan

Your role:
1. Help with catering services, menu, packages, and booking information
2. Be friendly, professional, and concise (keep responses under 100 words)
3. Use emojis occasionally to be friendly
4. Guide users to contact for bookings: +92 303 9907296
5. Provide helpful information about Altaf Catering services

Always be helpful and encourage users to contact for bookings or detailed information.";

// Prepare API request
$data = [
    'model' => 'openai/gpt-3.5-turbo',
    'messages' => [
        ['role' => 'system', 'content' => $system_prompt],
        ['role' => 'user', 'content' => $message]
    ],
    'max_tokens' => 150,
    'temperature' => 0.7
];

// Make API call
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://openrouter.ai/api/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . OPENROUTER_API_KEY,
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Handle response
if ($response === false) {
    echo json_encode(['error' => 'Connection failed']);
} else {
    $result = json_decode($response, true);
    
    if ($http_code === 200 && isset($result['choices'][0]['message']['content'])) {
        echo json_encode([
            'success' => true, 
            'message' => $result['choices'][0]['message']['content']
        ]);
    } else {
        $error_message = 'API Error';
        if (isset($result['error']['message'])) {
            $error_message = $result['error']['message'];
        }
        echo json_encode(['error' => $error_message]);
    }
}
?>