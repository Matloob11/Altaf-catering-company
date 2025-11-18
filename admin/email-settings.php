<?php
// Include authentication check
require_once 'includes/auth-check.php';

require_once '../config.php';
require_once '../includes/email.php';

$message = '';
$messageType = '';

// Handle test email
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['test_email'])) {
    $testEmail = $_POST['test_email_address'] ?? ADMIN_EMAIL;
    
    $subject = '✅ Test Email - Altaf Catering';
    $body = wrapEmailHTML('
        <h2>Email System Test Successful!</h2>
        <p>Congratulations! Your email system is working correctly.</p>
        <p><strong>Configuration:</strong></p>
        <ul>
            <li>SMTP Enabled: ' . (SMTP_ENABLED ? 'Yes' : 'No') . '</li>
            <li>SMTP Host: ' . SMTP_HOST . '</li>
            <li>SMTP Port: ' . SMTP_PORT . '</li>
            <li>From: ' . FROM_NAME . ' &lt;' . FROM_EMAIL . '&gt;</li>
        </ul>
        <p>Test Date: ' . date('F j, Y g:i A') . '</p>
    ');
    
    $result = sendEmail($testEmail, $subject, $body);
    
    if ($result) {
        $message = 'Test email sent successfully to ' . htmlspecialchars($testEmail);
        $messageType = 'success';
    } else {
        $message = 'Failed to send test email. Please check your SMTP configuration.';
        $messageType = 'danger';
    }
}

// Load email logs
$emailLogs = [];
$logFile = '../admin/data/email-log.json';
if (file_exists($logFile)) {
    $emailLogs = json_decode(file_get_contents($logFile), true) ?: [];
    $emailLogs = array_reverse($emailLogs);
    $emailLogs = array_slice($emailLogs, 0, 50); // Last 50 emails
}

// Calculate stats
$totalEmails = count($emailLogs);
$sentEmails = count(array_filter($emailLogs, fn($log) => $log['status'] === 'sent' || $log['status'] === 'saved_to_file'));
$failedEmails = count(array_filter($emailLogs, fn($log) => $log['status'] === 'failed' || strpos($log['status'], 'error') !== false));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Settings - Altaf Catering Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link href="css/admin-unified.css" rel="stylesheet"><style>
        .config-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .status-enabled {
            color: #28a745;
            font-weight: bold;
        }
        .status-disabled {
            color: #dc3545;
            font-weight: bold;
        }
        .config-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        .config-table td:first-child {
            font-weight: bold;
            width: 200px;
        }
        .log-table {
            font-size: 14px;
        }
        .badge-sent {
            background: #28a745;
        }
        .badge-failed {
            background: #dc3545;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php include 'includes/sidebar.php'; ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
                    <h1 class="h2">
                        <i class="fas fa-envelope"></i> Email Settings
                    </h1>
                </div>
                
                <!-- Localhost Mode Banner -->
                <?php
                $isLocalhost = ($_SERVER['SERVER_NAME'] === 'localhost' || strpos($_SERVER['HTTP_HOST'], 'localhost') !== false);
                if ($isLocalhost):
                ?>
                <div class="alert alert-info alert-dismissible fade show">
                    <h5><i class="fas fa-info-circle"></i> Localhost Mode Active</h5>
                    <p class="mb-0">
                        <strong>Emails are being saved to files</strong> instead of sending because you're running on localhost.
                        <br>
                        <a href="../admin/data/test-emails/" target="_blank" class="alert-link">
                            <i class="fas fa-folder-open"></i> View Saved Emails
                        </a>
                        | 
                        <a href="../EMAIL_LOCALHOST_FIX.md" target="_blank" class="alert-link">
                            <i class="fas fa-book"></i> Read Guide
                        </a>
                    </p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show">
                    <?php echo $message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <!-- Email Stats -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="config-card">
                            <h5><i class="fas fa-paper-plane text-primary"></i> Total Emails</h5>
                            <h2><?php echo $totalEmails; ?></h2>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="config-card">
                            <h5><i class="fas fa-check-circle text-success"></i> Sent Successfully</h5>
                            <h2><?php echo $sentEmails; ?></h2>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="config-card">
                            <h5><i class="fas fa-times-circle text-danger"></i> Failed</h5>
                            <h2><?php echo $failedEmails; ?></h2>
                        </div>
                    </div>
                </div>
                
                <!-- Current Configuration -->
                <div class="config-card">
                    <h4><i class="fas fa-cog"></i> Current Configuration</h4>
                    <table class="config-table w-100">
                        <tr>
                            <td>Email Service:</td>
                            <td>
                                <?php 
                                $emailService = defined('EMAIL_SERVICE') ? EMAIL_SERVICE : 'php_mail';
                                if ($emailService === 'resend'): 
                                ?>
                                    <span class="status-enabled">
                                        <i class="fas fa-check-circle"></i> Resend API (Active)
                                    </span>
                                <?php elseif (SMTP_ENABLED): ?>
                                    <span class="status-enabled">
                                        <i class="fas fa-check-circle"></i> SMTP Enabled
                                    </span>
                                <?php else: ?>
                                    <span class="status-disabled">
                                        <i class="fas fa-times-circle"></i> PHP mail()
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php if ($emailService === 'resend'): ?>
                        <tr>
                            <td>Resend API Key:</td>
                            <td><?php echo substr(RESEND_API_KEY, 0, 15); ?>...</td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <td>SMTP Host:</td>
                            <td><?php echo htmlspecialchars(SMTP_HOST); ?></td>
                        </tr>
                        <tr>
                            <td>SMTP Port:</td>
                            <td><?php echo htmlspecialchars(SMTP_PORT); ?></td>
                        </tr>
                        <tr>
                            <td>SMTP Username:</td>
                            <td><?php echo htmlspecialchars(SMTP_USERNAME); ?></td>
                        </tr>
                        <tr>
                            <td>SMTP Encryption:</td>
                            <td><?php echo strtoupper(SMTP_ENCRYPTION); ?></td>
                        </tr>
                        <tr>
                            <td>From Email:</td>
                            <td><?php echo htmlspecialchars(FROM_EMAIL); ?></td>
                        </tr>
                        <tr>
                            <td>From Name:</td>
                            <td><?php echo htmlspecialchars(FROM_NAME); ?></td>
                        </tr>
                    </table>
                </div>
                
                <!-- Test Email -->
                <div class="config-card">
                    <h4><i class="fas fa-paper-plane"></i> Send Test Email</h4>
                    <?php if (defined('EMAIL_SERVICE') && EMAIL_SERVICE === 'resend'): ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Resend Testing Mode:</strong> You can only send emails to your registered email: 
                        <strong><?php echo defined('RESEND_TEST_EMAIL') ? RESEND_TEST_EMAIL : 'matloobulhassnain11@gmail.com'; ?></strong>
                        <br>
                        <small>To send to other emails, verify a domain at <a href="https://resend.com/domains" target="_blank">resend.com/domains</a></small>
                    </div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-8">
                                <input type="email" name="test_email_address" class="form-control" 
                                       placeholder="Enter email address" 
                                       value="<?php echo defined('RESEND_TEST_EMAIL') ? RESEND_TEST_EMAIL : ADMIN_EMAIL; ?>" 
                                       required>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" name="test_email" class="btn btn-primary w-100">
                                    <i class="fas fa-paper-plane"></i> Send Test Email
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                
                <!-- Setup Instructions -->
                <div class="config-card">
                    <h4><i class="fas fa-info-circle"></i> How to Configure Email</h4>
                    <div class="alert alert-info">
                        <h5>For Gmail (Recommended):</h5>
                        <ol>
                            <li>Enable 2-Factor Authentication on your Google Account</li>
                            <li>Visit: <a href="https://myaccount.google.com/apppasswords" target="_blank">myaccount.google.com/apppasswords</a></li>
                            <li>Generate an App Password (16 characters)</li>
                            <li>Open <code>config.php</code> and update:
                                <pre class="mt-2 p-2 bg-light">define('SMTP_ENABLED', true);
define('SMTP_USERNAME', 'your-email@gmail.com');
define('SMTP_PASSWORD', 'your-app-password');</pre>
                            </li>
                            <li>Save and test using the form above</li>
                        </ol>
                        <p class="mb-0">
                            <strong>Need help?</strong> Read the 
                            <a href="../EMAIL_SETUP_GUIDE.md" target="_blank">Email Setup Guide</a>
                        </p>
                    </div>
                </div>
                
                <!-- Saved Emails (Localhost) -->
                <?php
                $testEmailsDir = '../admin/data/test-emails/';
                $savedEmails = [];
                if (file_exists($testEmailsDir)) {
                    $files = glob($testEmailsDir . '*.html');
                    rsort($files); // Latest first
                    $savedEmails = array_slice($files, 0, 10); // Last 10
                }
                
                if (!empty($savedEmails)):
                ?>
                <div class="config-card">
                    <h4><i class="fas fa-folder-open"></i> Saved Emails (Localhost Mode)</h4>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Localhost Mode:</strong> Emails are being saved to files instead of sending. 
                        Click on any email below to view it.
                    </div>
                    <div class="list-group">
                        <?php foreach ($savedEmails as $file): ?>
                        <?php
                        $filename = basename($file);
                        $filedate = filemtime($file);
                        ?>
                        <a href="../admin/data/test-emails/<?php echo $filename; ?>" target="_blank" 
                           class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">
                                    <i class="fas fa-envelope"></i> 
                                    <?php echo substr($filename, 0, 50); ?>...
                                </h6>
                                <small><?php echo date('M j, Y g:i A', $filedate); ?></small>
                            </div>
                            <small class="text-muted">Click to view email</small>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Email Logs -->
                <div class="config-card">
                    <h4><i class="fas fa-history"></i> Recent Email Activity</h4>
                    <?php if (empty($emailLogs)): ?>
                        <p class="text-muted">No email activity yet.</p>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped log-table">
                            <thead>
                                <tr>
                                    <th>To</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>IP</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($emailLogs as $log): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($log['to']); ?></td>
                                    <td><?php echo htmlspecialchars($log['subject']); ?></td>
                                    <td>
                                        <?php if ($log['status'] === 'sent' || $log['status'] === 'saved_to_file'): ?>
                                            <span class="badge badge-sent">
                                                <?php echo $log['status'] === 'saved_to_file' ? 'Saved' : 'Sent'; ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-failed">Failed</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($log['date']); ?></td>
                                    <td><?php echo htmlspecialchars($log['ip']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
