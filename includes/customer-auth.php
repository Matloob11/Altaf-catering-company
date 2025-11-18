<?php
/**
 * Customer Authentication System
 * Altaf Catering - Customer Portal
 */

require_once __DIR__ . '/security.php';

/**
 * Register Customer
 */
function registerCustomer($data) {
    $customersFile = __DIR__ . '/../admin/data/customers.json';
    
    // Load existing customers
    $customers = [];
    if (file_exists($customersFile)) {
        $customers = json_decode(file_get_contents($customersFile), true) ?: [];
    }
    
    // Check if email already exists
    foreach ($customers as $customer) {
        if ($customer['email'] === $data['email']) {
            return ['success' => false, 'message' => 'Email already registered'];
        }
    }
    
    // Create new customer
    $newCustomer = [
        'id' => count($customers) + 1,
        'name' => sanitizeInput($data['name']),
        'email' => filter_var($data['email'], FILTER_SANITIZE_EMAIL),
        'phone' => sanitizeInput($data['phone']),
        'password' => hashPassword($data['password']),
        'created_at' => date('Y-m-d H:i:s'),
        'status' => 'active',
        'verified' => false
    ];
    
    $customers[] = $newCustomer;
    
    // Save to file
    if (file_put_contents($customersFile, json_encode($customers, JSON_PRETTY_PRINT))) {
        // Log registration
        logSecurityEvent('customer_registered', ['email' => $newCustomer['email']]);
        
        return [
            'success' => true,
            'message' => 'Registration successful! Please login.',
            'customer_id' => $newCustomer['id']
        ];
    }
    
    return ['success' => false, 'message' => 'Registration failed'];
}

/**
 * Login Customer
 */
function loginCustomer($email, $password) {
    startSecureSession();
    
    // Check rate limiting
    if (!checkRateLimit('customer_login', 5, 900)) {
        return [
            'success' => false,
            'message' => 'Too many login attempts. Please try again later.'
        ];
    }
    
    $customersFile = __DIR__ . '/../admin/data/customers.json';
    
    if (!file_exists($customersFile)) {
        return ['success' => false, 'message' => 'Invalid credentials'];
    }
    
    $customers = json_decode(file_get_contents($customersFile), true);
    
    foreach ($customers as &$customer) {
        if ($customer['email'] === $email) {
            if (verifyPassword($password, $customer['password'])) {
                // Login successful
                $_SESSION['customer_logged_in'] = true;
                $_SESSION['customer_id'] = $customer['id'];
                $_SESSION['customer_name'] = $customer['name'];
                $_SESSION['customer_email'] = $customer['email'];
                
                // Update last login
                $customer['last_login'] = date('Y-m-d H:i:s');
                file_put_contents($customersFile, json_encode($customers, JSON_PRETTY_PRINT));
                
                logSecurityEvent('customer_login_success', ['email' => $email]);
                
                return [
                    'success' => true,
                    'message' => 'Login successful',
                    'customer' => [
                        'id' => $customer['id'],
                        'name' => $customer['name'],
                        'email' => $customer['email']
                    ]
                ];
            }
            break;
        }
    }
    
    logSecurityEvent('customer_login_failed', ['email' => $email]);
    
    return ['success' => false, 'message' => 'Invalid email or password'];
}

/**
 * Logout Customer
 */
function logoutCustomer() {
    startSecureSession();
    
    unset($_SESSION['customer_logged_in']);
    unset($_SESSION['customer_id']);
    unset($_SESSION['customer_name']);
    unset($_SESSION['customer_email']);
    
    session_destroy();
    
    return ['success' => true, 'message' => 'Logged out successfully'];
}

/**
 * Check if Customer is Logged In
 */
function isCustomerLoggedIn() {
    startSecureSession();
    return isset($_SESSION['customer_logged_in']) && $_SESSION['customer_logged_in'] === true;
}

/**
 * Get Current Customer
 */
function getCurrentCustomer() {
    if (!isCustomerLoggedIn()) {
        return null;
    }
    
    $customersFile = __DIR__ . '/../admin/data/customers.json';
    
    if (!file_exists($customersFile)) {
        return null;
    }
    
    $customers = json_decode(file_get_contents($customersFile), true);
    
    foreach ($customers as $customer) {
        if ($customer['id'] == $_SESSION['customer_id']) {
            // Remove password from returned data
            unset($customer['password']);
            return $customer;
        }
    }
    
    return null;
}

/**
 * Get Customer Bookings
 */
function getCustomerBookings($customerId = null) {
    if ($customerId === null) {
        $customerId = $_SESSION['customer_id'] ?? null;
    }
    
    if (!$customerId) {
        return [];
    }
    
    $bookingsFile = __DIR__ . '/../admin/data/bookings.json';
    
    if (!file_exists($bookingsFile)) {
        return [];
    }
    
    $allBookings = json_decode(file_get_contents($bookingsFile), true);
    
    // Get customer email
    $customer = getCurrentCustomer();
    if (!$customer) {
        return [];
    }
    
    // Filter bookings by customer email
    $customerBookings = array_filter($allBookings, function($booking) use ($customer) {
        return isset($booking['email']) && $booking['email'] === $customer['email'];
    });
    
    // Sort by date (newest first)
    usort($customerBookings, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });
    
    return array_values($customerBookings);
}

/**
 * Update Customer Profile
 */
function updateCustomerProfile($data) {
    if (!isCustomerLoggedIn()) {
        return ['success' => false, 'message' => 'Not logged in'];
    }
    
    $customersFile = __DIR__ . '/../admin/data/customers.json';
    $customers = json_decode(file_get_contents($customersFile), true);
    
    foreach ($customers as &$customer) {
        if ($customer['id'] == $_SESSION['customer_id']) {
            // Update allowed fields
            if (isset($data['name'])) {
                $customer['name'] = sanitizeInput($data['name']);
                $_SESSION['customer_name'] = $customer['name'];
            }
            
            if (isset($data['phone'])) {
                $customer['phone'] = sanitizeInput($data['phone']);
            }
            
            $customer['updated_at'] = date('Y-m-d H:i:s');
            
            file_put_contents($customersFile, json_encode($customers, JSON_PRETTY_PRINT));
            
            return ['success' => true, 'message' => 'Profile updated successfully'];
        }
    }
    
    return ['success' => false, 'message' => 'Customer not found'];
}

/**
 * Change Customer Password
 */
function changeCustomerPassword($currentPassword, $newPassword) {
    if (!isCustomerLoggedIn()) {
        return ['success' => false, 'message' => 'Not logged in'];
    }
    
    $customersFile = __DIR__ . '/../admin/data/customers.json';
    $customers = json_decode(file_get_contents($customersFile), true);
    
    foreach ($customers as &$customer) {
        if ($customer['id'] == $_SESSION['customer_id']) {
            // Verify current password
            if (!verifyPassword($currentPassword, $customer['password'])) {
                return ['success' => false, 'message' => 'Current password is incorrect'];
            }
            
            // Update password
            $customer['password'] = hashPassword($newPassword);
            $customer['password_changed_at'] = date('Y-m-d H:i:s');
            
            file_put_contents($customersFile, json_encode($customers, JSON_PRETTY_PRINT));
            
            logSecurityEvent('customer_password_changed', ['customer_id' => $customer['id']]);
            
            return ['success' => true, 'message' => 'Password changed successfully'];
        }
    }
    
    return ['success' => false, 'message' => 'Customer not found'];
}

/**
 * Request Password Reset
 */
function requestPasswordReset($email) {
    $customersFile = __DIR__ . '/../admin/data/customers.json';
    
    if (!file_exists($customersFile)) {
        // Don't reveal if email exists
        return ['success' => true, 'message' => 'If email exists, reset link will be sent'];
    }
    
    $customers = json_decode(file_get_contents($customersFile), true);
    
    foreach ($customers as &$customer) {
        if ($customer['email'] === $email) {
            // Generate reset token
            $token = generateSecureToken(32);
            $customer['reset_token'] = $token;
            $customer['reset_token_expires'] = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            file_put_contents($customersFile, json_encode($customers, JSON_PRETTY_PRINT));
            
            // TODO: Send email with reset link
            // For now, just log it
            logSecurityEvent('password_reset_requested', ['email' => $email]);
            
            break;
        }
    }
    
    // Always return success to prevent email enumeration
    return ['success' => true, 'message' => 'If email exists, reset link will be sent'];
}

/**
 * Validate Customer Session
 */
function validateCustomerSession() {
    startSecureSession();
    
    if (!isCustomerLoggedIn()) {
        header('Location: customer-login.php');
        exit;
    }
    
    // Check session timeout (2 hours)
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 7200)) {
        logoutCustomer();
        header('Location: customer-login.php?timeout=1');
        exit;
    }
    
    $_SESSION['last_activity'] = time();
}
