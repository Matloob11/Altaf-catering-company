<?php
// Include authentication check
require_once 'includes/auth-check.php';

// Get saved emails
$emailsDir = 'data/test-emails/';
$emails = [];

if (file_exists($emailsDir)) {
    $files = glob($emailsDir . '*.html');
    rsort($files); // Latest first
    
    foreach ($files as $file) {
        $emails[] = [
            'filename' => basename($file),
            'filepath' => $file,
            'date' => filemtime($file),
            'size' => filesize($file)
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saved Emails - Altaf Catering Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link href="css/admin-unified.css" rel="stylesheet"><style>
        .email-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        .email-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            transform: translateY(-2px);
        }
        .email-preview {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            background: #f9f9f9;
            max-height: 400px;
            overflow: auto;
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
                        <i class="fas fa-envelope-open"></i> Saved Emails (Localhost Mode)
                    </h1>
                    <div>
                        <a href="email-settings.php" class="btn btn-outline-primary">
                            <i class="fas fa-cog"></i> Email Settings
                        </a>
                    </div>
                </div>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Localhost Mode:</strong> These emails were saved to files because you're running on localhost.
                    In production, they will be sent to actual email addresses.
                </div>
                
                <?php if (empty($emails)): ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        No saved emails yet. Send a test email from the 
                        <a href="email-settings.php">Email Settings</a> page.
                    </div>
                <?php else: ?>
                    <div class="mb-3">
                        <strong>Total Saved Emails:</strong> <?php echo count($emails); ?>
                    </div>
                    
                    <?php foreach ($emails as $email): ?>
                    <div class="email-card">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5>
                                    <i class="fas fa-envelope"></i>
                                    <?php echo htmlspecialchars($email['filename']); ?>
                                </h5>
                                <p class="mb-0 text-muted">
                                    <i class="fas fa-clock"></i>
                                    <?php echo date('F j, Y g:i A', $email['date']); ?>
                                    |
                                    <i class="fas fa-file"></i>
                                    <?php echo number_format($email['size'] / 1024, 2); ?> KB
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <a href="<?php echo $email['filepath']; ?>" target="_blank" 
                                   class="btn btn-primary">
                                    <i class="fas fa-eye"></i> View Email
                                </a>
                                <button onclick="deleteEmail('<?php echo $email['filename']; ?>')" 
                                        class="btn btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    
                    <div class="mt-4">
                        <button onclick="deleteAllEmails()" class="btn btn-danger">
                            <i class="fas fa-trash-alt"></i> Delete All Saved Emails
                        </button>
                    </div>
                <?php endif; ?>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function deleteEmail(filename) {
            if (confirm('Delete this email?')) {
                // TODO: Implement delete functionality
                alert('Delete functionality coming soon!');
            }
        }
        
        function deleteAllEmails() {
            if (confirm('Delete all saved emails? This cannot be undone!')) {
                // TODO: Implement delete all functionality
                alert('Delete all functionality coming soon!');
            }
        }
    </script>
</body>
</html>
