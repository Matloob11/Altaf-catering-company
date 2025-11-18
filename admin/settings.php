<?php
// Include authentication check
require_once 'includes/auth-check.php';

// Load settings
$settings_file = 'data/settings.json';
$settings = file_exists($settings_file) ? json_decode(file_get_contents($settings_file), true) : [
    'site_name' => 'Altaf Catering',
    'site_email' => 'info@altafcatering.com',
    'site_phone' => '+923039907296',
    'site_address' => 'MM Farm House Sharif Medical Jati Umrah Road, Karachi',
    'facebook' => 'https://web.facebook.com/AltafCateringCompany',
    'instagram' => 'https://www.instagram.com/altafcateringcompany/',
    'youtube' => 'https://www.youtube.com/@Altafcateringcompanyy',
    'tiktok' => 'https://www.tiktok.com/@altafcateringcompany'
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_settings'])) {
    $settings = [
        'site_name' => $_POST['site_name'],
        'site_email' => $_POST['site_email'],
        'site_phone' => $_POST['site_phone'],
        'site_address' => $_POST['site_address'],
        'facebook' => $_POST['facebook'],
        'instagram' => $_POST['instagram'],
        'youtube' => $_POST['youtube'],
        'tiktok' => $_POST['tiktok']
    ];
    
    file_put_contents($settings_file, json_encode($settings, JSON_PRETTY_PRINT));
    $success = "Settings saved successfully!";
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Simple validation (in production, use proper password hashing)
    if ($current_password == 'altaf2025') {
        if ($new_password == $confirm_password) {
            // In production, update password in database
            $success = "Password changed successfully! (Note: Update in index.php manually)";
        } else {
            $error = "New passwords do not match!";
        }
    } else {
        $error = "Current password is incorrect!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Altaf Catering Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link href="css/admin-unified.css" rel="stylesheet"></head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php include 'includes/sidebar.php'; ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
                    <h1 class="h2 gradient-text">
                        <i class="fas fa-cog me-2"></i> General Settings
                    </h1>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-info" onclick="window.location.reload()">
                            <i class="fas fa-sync-alt me-1"></i> Refresh
                        </button>
                    </div>
                </div>
                
                <?php if (isset($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?php echo $success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?php echo $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <div class="row">
                    <div class="col-lg-8">
                        <!-- Site Settings -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-globe"></i> Site Information</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <div class="mb-3">
                                        <label class="form-label">Site Name</label>
                                        <input type="text" name="site_name" class="form-control" value="<?php echo $settings['site_name']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="site_email" class="form-control" value="<?php echo $settings['site_email']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Phone</label>
                                        <input type="text" name="site_phone" class="form-control" value="<?php echo $settings['site_phone']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Address</label>
                                        <textarea name="site_address" class="form-control" rows="3" required><?php echo $settings['site_address']; ?></textarea>
                                    </div>
                                    
                                    <h6 class="mt-4 mb-3">Social Media Links</h6>
                                    <div class="mb-3">
                                        <label class="form-label"><i class="fab fa-facebook"></i> Facebook</label>
                                        <input type="url" name="facebook" class="form-control" value="<?php echo $settings['facebook']; ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label"><i class="fab fa-instagram"></i> Instagram</label>
                                        <input type="url" name="instagram" class="form-control" value="<?php echo $settings['instagram']; ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label"><i class="fab fa-youtube"></i> YouTube</label>
                                        <input type="url" name="youtube" class="form-control" value="<?php echo $settings['youtube']; ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label"><i class="fab fa-tiktok"></i> TikTok</label>
                                        <input type="url" name="tiktok" class="form-control" value="<?php echo $settings['tiktok']; ?>">
                                    </div>
                                    
                                    <button type="submit" name="save_settings" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Save Settings
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Change Password -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-lock"></i> Change Password</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <div class="mb-3">
                                        <label class="form-label">Current Password</label>
                                        <input type="password" name="current_password" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">New Password</label>
                                        <input type="password" name="new_password" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Confirm New Password</label>
                                        <input type="password" name="confirm_password" class="form-control" required>
                                    </div>
                                    <button type="submit" name="change_password" class="btn btn-warning">
                                        <i class="fas fa-key"></i> Change Password
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <!-- Quick Stats -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Quick Stats</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <strong>Total Blogs:</strong>
                                    <span class="float-end badge bg-primary">
                                        <?php echo count(json_decode(file_get_contents('data/blogs.json'), true)); ?>
                                    </span>
                                </div>
                                <div class="mb-3">
                                    <strong>Team Members:</strong>
                                    <span class="float-end badge bg-success">
                                        <?php echo count(json_decode(file_get_contents('data/team.json'), true)); ?>
                                    </span>
                                </div>
                                <div class="mb-3">
                                    <strong>Gallery Items:</strong>
                                    <span class="float-end badge bg-info">
                                        <?php echo count(json_decode(file_get_contents('data/gallery.json'), true)); ?>
                                    </span>
                                </div>
                                <div class="mb-3">
                                    <strong>Testimonials:</strong>
                                    <span class="float-end badge bg-warning">
                                        <?php echo count(json_decode(file_get_contents('data/testimonials.json'), true)); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- System Info -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-info-circle"></i> System Info</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>PHP Version:</strong> <?php echo phpversion(); ?></p>
                                <p><strong>Server:</strong> <?php echo $_SERVER['SERVER_SOFTWARE']; ?></p>
                                <p><strong>Admin Panel Version:</strong> 1.0.0</p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/admin-enhancements.js"></script>
</body>
</html>
