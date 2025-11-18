<?php
/**
 * Email Notification System
 * Altaf Catering - Email Handler with SMTP Support
 */

// Load configuration
if (file_exists(__DIR__ . '/../config.php')) {
    require_once __DIR__ . '/../config.php';
}

// Email Configuration (fallback if config.php not loaded)
if (!defined('SMTP_ENABLED')) {
    define('SMTP_ENABLED', false);
    define('SMTP_HOST', 'smtp.gmail.com');
    define('SMTP_PORT', 587);
    define('SMTP_USERNAME', 'altafcatering@gmail.com');
    define('SMTP_PASSWORD', 'your-app-password');
    define('SMTP_ENCRYPTION', 'tls');
    define('FROM_EMAIL', 'altafcatering@gmail.com');
    define('FROM_NAME', 'Altaf Catering');
}

if (!defined('ADMIN_EMAIL')) {
    define('ADMIN_EMAIL', 'altafcatering@gmail.com');
    define('ADMIN_NAME', 'Altaf Catering Admin');
}

/**
 * Send Email using configured service (Resend, SMTP, or PHP mail)
 */
function sendEmail($to, $subject, $body, $isHTML = true) {
    // Check which email service to use
    if (defined('EMAIL_SERVICE') && EMAIL_SERVICE === 'resend') {
        return sendResendEmail($to, $subject, $body);
    } elseif (SMTP_ENABLED) {
        return sendSMTPEmail($to, $subject, $body, $isHTML);
    } else {
        return sendPHPMail($to, $subject, $body, $isHTML);
    }
}

/**
 * Send Email using Resend API
 */
function sendResendEmail($to, $subject, $body) {
    require_once __DIR__ . '/ResendMailer.php';
    
    try {
        $mailer = new ResendMailer(
            RESEND_API_KEY,
            FROM_EMAIL,
            FROM_NAME
        );
        
        $result = $mailer->send($to, $subject, $body);
        
        // Log email
        logEmail($to, $subject, $result ? 'sent_via_resend' : 'failed: ' . $mailer->getError());
        
        return $result;
        
    } catch (Exception $e) {
        logEmail($to, $subject, 'error: ' . $e->getMessage());
        return false;
    }
}

/**
 * Send Email using PHP mail() function
 * For localhost: Saves email to file for testing
 */
function sendPHPMail($to, $subject, $body, $isHTML = true) {
    // Check if running on localhost
    $isLocalhost = (
        $_SERVER['SERVER_NAME'] === 'localhost' || 
        $_SERVER['SERVER_ADDR'] === '127.0.0.1' ||
        strpos($_SERVER['HTTP_HOST'], 'localhost') !== false
    );
    
    if ($isLocalhost) {
        // Save email to file for testing on localhost
        return saveEmailToFile($to, $subject, $body);
    }
    
    // Production: Use PHP mail()
    $headers = [];
    $headers[] = 'From: ' . FROM_NAME . ' <' . FROM_EMAIL . '>';
    $headers[] = 'Reply-To: ' . FROM_EMAIL;
    $headers[] = 'X-Mailer: PHP/' . phpversion();
    
    if ($isHTML) {
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=UTF-8';
    }
    
    $headerString = implode("\r\n", $headers);
    
    try {
        $result = @mail($to, $subject, $body, $headerString);
        
        // Log email
        logEmail($to, $subject, $result ? 'sent' : 'failed');
        
        return $result;
    } catch (Exception $e) {
        logEmail($to, $subject, 'error: ' . $e->getMessage());
        return false;
    }
}

/**
 * Save Email to File (for localhost testing)
 */
function saveEmailToFile($to, $subject, $body) {
    $emailDir = __DIR__ . '/../admin/data/test-emails/';
    
    // Create directory if not exists
    if (!file_exists($emailDir)) {
        mkdir($emailDir, 0755, true);
    }
    
    // Create filename
    $filename = date('Y-m-d_H-i-s') . '_' . md5($to . $subject) . '.html';
    $filepath = $emailDir . $filename;
    
    // Create email content
    $emailContent = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Email Preview</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .email-info { background: #fff; padding: 20px; margin-bottom: 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .email-info h3 { margin-top: 0; color: #FE7E00; }
        .email-info p { margin: 5px 0; }
        .email-body { background: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .label { font-weight: bold; color: #666; }
    </style>
</head>
<body>
    <div class="email-info">
        <h3>📧 Email Preview (Localhost Mode)</h3>
        <p><span class="label">To:</span> {$to}</p>
        <p><span class="label">Subject:</span> {$subject}</p>
        <p><span class="label">Date:</span> {date('F j, Y g:i A')}</p>
        <p><span class="label">Status:</span> <span style="color: green;">✅ Saved Successfully</span></p>
        <p style="color: #666; font-size: 12px; margin-top: 10px;">
            <strong>Note:</strong> This email was saved to file because you're running on localhost. 
            In production, it will be sent via email.
        </p>
    </div>
    <div class="email-body">
        {$body}
    </div>
</body>
</html>
HTML;
    
    // Save to file
    $result = file_put_contents($filepath, $emailContent);
    
    // Log email
    logEmail($to, $subject, $result ? 'saved_to_file' : 'failed');
    
    return $result !== false;
}

/**
 * Send Email using SMTP
 */
function sendSMTPEmail($to, $subject, $body, $isHTML = true) {
    require_once __DIR__ . '/PHPMailer.php';
    
    try {
        $mailer = new SimpleMailer([
            'host' => SMTP_HOST,
            'port' => SMTP_PORT,
            'username' => SMTP_USERNAME,
            'password' => SMTP_PASSWORD,
            'encryption' => SMTP_ENCRYPTION,
            'from_email' => FROM_EMAIL,
            'from_name' => FROM_NAME
        ]);
        
        $result = $mailer->send($to, $subject, $body, $isHTML);
        
        // Log email
        logEmail($to, $subject, $result ? 'sent' : 'failed: ' . $mailer->getError());
        
        return $result;
        
    } catch (Exception $e) {
        logEmail($to, $subject, 'error: ' . $e->getMessage());
        return false;
    }
}

/**
 * Send Contact Form Notification to Admin
 */
function sendContactNotification($data) {
    $subject = '🔔 New Contact Form Submission - Altaf Catering';
    
    $body = getEmailTemplate('contact-notification', [
        'name' => $data['name'],
        'email' => $data['email'],
        'phone' => $data['phone'] ?? 'Not provided',
        'message' => $data['message'],
        'date' => date('F j, Y, g:i a')
    ]);
    
    return sendEmail(ADMIN_EMAIL, $subject, $body);
}

/**
 * Send Contact Form Confirmation to Customer
 */
function sendContactConfirmation($data) {
    $subject = '✅ Thank You for Contacting Altaf Catering';
    
    $body = getEmailTemplate('contact-confirmation', [
        'name' => $data['name']
    ]);
    
    return sendEmail($data['email'], $subject, $body);
}

/**
 * Send Booking Notification to Admin
 */
function sendBookingNotification($data) {
    $subject = '🎉 New Event Booking - Altaf Catering';
    
    $body = getEmailTemplate('booking-notification', [
        'name' => $data['name'],
        'email' => $data['email'],
        'phone' => $data['phone'],
        'event_type' => $data['event_type'],
        'event_date' => $data['event_date'],
        'guests' => $data['guests'],
        'message' => $data['message'] ?? 'No additional message',
        'date' => date('F j, Y, g:i a')
    ]);
    
    return sendEmail(ADMIN_EMAIL, $subject, $body);
}

/**
 * Send Booking Confirmation to Customer
 */
function sendBookingConfirmation($data) {
    $subject = '🎊 Booking Confirmed - Altaf Catering';
    
    $body = getEmailTemplate('booking-confirmation', [
        'name' => $data['name'],
        'event_type' => $data['event_type'],
        'event_date' => $data['event_date'],
        'guests' => $data['guests']
    ]);
    
    return sendEmail($data['email'], $subject, $body);
}

/**
 * Send Job Application Notification to Admin
 */
function sendApplicationNotification($data) {
    $subject = '💼 New Job Application - ' . $data['position'];
    
    $body = getEmailTemplate('application-notification', [
        'name' => $data['name'],
        'email' => $data['email'],
        'phone' => $data['phone'],
        'position' => $data['position'],
        'experience' => $data['experience'],
        'message' => $data['message'] ?? 'No cover letter',
        'date' => date('F j, Y, g:i a')
    ]);
    
    return sendEmail(ADMIN_EMAIL, $subject, $body);
}

/**
 * Send Job Application Confirmation to Applicant
 */
function sendApplicationConfirmation($data) {
    $subject = '✅ Application Received - Altaf Catering';
    
    $body = getEmailTemplate('application-confirmation', [
        'name' => $data['name'],
        'position' => $data['position']
    ]);
    
    return sendEmail($data['email'], $subject, $body);
}

/**
 * Send Newsletter Subscription Confirmation
 */
function sendNewsletterConfirmation($email, $name = '') {
    $subject = '📧 Welcome to Altaf Catering Newsletter';
    
    $body = getEmailTemplate('newsletter-confirmation', [
        'name' => $name ?: 'Valued Customer'
    ]);
    
    return sendEmail($email, $subject, $body);
}

/**
 * Get Email Template
 */
function getEmailTemplate($template, $data = []) {
    $templateFile = __DIR__ . '/email-templates/' . $template . '.php';
    
    if (file_exists($templateFile)) {
        ob_start();
        extract($data);
        include $templateFile;
        return ob_get_clean();
    }
    
    // Fallback to basic template
    return getBasicEmailTemplate($template, $data);
}

/**
 * Basic Email Template (Fallback)
 */
function getBasicEmailTemplate($type, $data) {
    $templates = [
        'contact-notification' => "
            <h2>New Contact Form Submission</h2>
            <p><strong>Name:</strong> {$data['name']}</p>
            <p><strong>Email:</strong> {$data['email']}</p>
            <p><strong>Phone:</strong> {$data['phone']}</p>
            <p><strong>Message:</strong><br>{$data['message']}</p>
            <p><strong>Date:</strong> {$data['date']}</p>
        ",
        'contact-confirmation' => "
            <h2>Thank You for Contacting Us!</h2>
            <p>Dear {$data['name']},</p>
            <p>Thank you for reaching out to Altaf Catering. We have received your message and will get back to you within 24 hours.</p>
            <p>Best regards,<br>Altaf Catering Team</p>
        ",
        'booking-notification' => "
            <h2>New Event Booking</h2>
            <p><strong>Name:</strong> {$data['name']}</p>
            <p><strong>Email:</strong> {$data['email']}</p>
            <p><strong>Phone:</strong> {$data['phone']}</p>
            <p><strong>Event Type:</strong> {$data['event_type']}</p>
            <p><strong>Event Date:</strong> {$data['event_date']}</p>
            <p><strong>Guests:</strong> {$data['guests']}</p>
            <p><strong>Message:</strong><br>{$data['message']}</p>
            <p><strong>Submitted:</strong> {$data['date']}</p>
        ",
        'booking-confirmation' => "
            <h2>Booking Confirmed!</h2>
            <p>Dear {$data['name']},</p>
            <p>Your event booking has been confirmed!</p>
            <p><strong>Event Type:</strong> {$data['event_type']}</p>
            <p><strong>Event Date:</strong> {$data['event_date']}</p>
            <p><strong>Number of Guests:</strong> {$data['guests']}</p>
            <p>Our team will contact you shortly to discuss the details.</p>
            <p>Best regards,<br>Altaf Catering Team</p>
        ",
        'application-notification' => "
            <h2>New Job Application</h2>
            <p><strong>Position:</strong> {$data['position']}</p>
            <p><strong>Name:</strong> {$data['name']}</p>
            <p><strong>Email:</strong> {$data['email']}</p>
            <p><strong>Phone:</strong> {$data['phone']}</p>
            <p><strong>Experience:</strong> {$data['experience']}</p>
            <p><strong>Cover Letter:</strong><br>{$data['message']}</p>
            <p><strong>Date:</strong> {$data['date']}</p>
        ",
        'application-confirmation' => "
            <h2>Application Received</h2>
            <p>Dear {$data['name']},</p>
            <p>Thank you for applying for the <strong>{$data['position']}</strong> position at Altaf Catering.</p>
            <p>We have received your application and will review it carefully. If your qualifications match our requirements, we will contact you for an interview.</p>
            <p>Best regards,<br>Altaf Catering HR Team</p>
        ",
        'newsletter-confirmation' => "
            <h2>Welcome to Our Newsletter!</h2>
            <p>Dear {$data['name']},</p>
            <p>Thank you for subscribing to Altaf Catering newsletter!</p>
            <p>You'll receive updates about our latest menus, special offers, and catering tips.</p>
            <p>Best regards,<br>Altaf Catering Team</p>
        "
    ];
    
    $content = $templates[$type] ?? '<p>Email notification</p>';
    
    return wrapEmailHTML($content);
}

/**
 * Wrap content in HTML email template
 */
function wrapEmailHTML($content) {
    return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #FE7E00 0%, #FF6B00 100%); color: white; padding: 20px; text-align: center; }
        .content { background: #f9f9f9; padding: 30px; }
        .footer { background: #333; color: white; padding: 20px; text-align: center; font-size: 12px; }
        h2 { color: #FE7E00; }
        a { color: #FE7E00; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Altaf Catering</h1>
        </div>
        <div class="content">
            {$content}
        </div>
        <div class="footer">
            <p>Altaf Catering Company</p>
            <p>MM Farm House Sharif Medical Jati Umrah Road, Karachi, Pakistan</p>
            <p>Phone: +923039907296 | Email: altafcatering@gmail.com</p>
            <p>&copy; 2025 Altaf Catering. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
HTML;
}

/**
 * Log Email Activity
 */
function logEmail($to, $subject, $status) {
    $logFile = __DIR__ . '/../admin/data/email-log.json';
    
    $logs = [];
    if (file_exists($logFile)) {
        $logs = json_decode(file_get_contents($logFile), true) ?: [];
    }
    
    $logs[] = [
        'to' => $to,
        'subject' => $subject,
        'status' => $status,
        'date' => date('Y-m-d H:i:s'),
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
    ];
    
    // Keep only last 500 logs
    if (count($logs) > 500) {
        $logs = array_slice($logs, -500);
    }
    
    file_put_contents($logFile, json_encode($logs, JSON_PRETTY_PRINT));
}

/**
 * Test Email Configuration
 */
function testEmailConfig() {
    $testEmail = ADMIN_EMAIL;
    $subject = '✅ Email Configuration Test - Altaf Catering';
    $body = wrapEmailHTML('
        <h2>Email Test Successful!</h2>
        <p>Your email configuration is working correctly.</p>
        <p>Date: ' . date('F j, Y, g:i a') . '</p>
    ');
    
    return sendEmail($testEmail, $subject, $body);
}

/**
 * Validate Email Address
 */
function isValidEmailAddress($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Sanitize Email Content
 */
function sanitizeEmailContent($content) {
    return htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
}
