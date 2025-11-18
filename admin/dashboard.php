<?php
// Debug mode for login issues
if (isset($_GET['login_success'])) {
    // Don't start session here as auth-check.php will handle it
    echo "<div style='background: #d4edda; padding: 20px; margin: 20px; border: 2px solid #28a745; border-radius: 10px;'>";
    echo "<h3>✅ Login Successful!</h3>";
    echo "<p>You have been successfully logged in to the admin panel.</p>";
    echo "<p><a href='dashboard.php' class='btn btn-success'>Continue to Dashboard</a></p>";
    echo "<script>setTimeout(() => { window.location.href = 'dashboard.php'; }, 2000);</script>";
    echo "</div>";
    exit;
}

// Debug: Show session before auth check
if (isset($_GET['debug'])) {
    session_start();
    echo "<div style='background: #f0f8ff; padding: 20px; margin: 20px; border: 2px solid #007bff;'>";
    echo "<h3>🔍 Dashboard Debug</h3>";
    echo "<p><strong>Session ID:</strong> " . session_id() . "</p>";
    echo "<p><strong>admin_logged_in:</strong> " . (isset($_SESSION['admin_logged_in']) ? var_export($_SESSION['admin_logged_in'], true) : 'NOT SET') . "</p>";
    echo "<pre>" . print_r($_SESSION, true) . "</pre>";
    echo "<p><a href='dashboard.php'>Continue without debug</a></p>";
    echo "</div>";
    exit;
}

// Include authentication check
require_once 'includes/auth-check.php';

// Load data for statistics
$blogs = file_exists('data/blogs.json') ? json_decode(file_get_contents('data/blogs.json'), true) : [];
$blog_details = file_exists('data/blog-details.json') ? json_decode(file_get_contents('data/blog-details.json'), true) : [];
$team = file_exists('data/team.json') ? json_decode(file_get_contents('data/team.json'), true) : [];
$gallery = file_exists('data/gallery.json') ? json_decode(file_get_contents('data/gallery.json'), true) : [];
$testimonials = file_exists('data/testimonials.json') ? json_decode(file_get_contents('data/testimonials.json'), true) : [];
$applications = file_exists('data/applications.json') ? json_decode(file_get_contents('data/applications.json'), true) : [];
$jobs = file_exists('data/jobs.json') ? json_decode(file_get_contents('data/jobs.json'), true) : [];
$menu = file_exists('data/menu.json') ? json_decode(file_get_contents('data/menu.json'), true) : [];
$contacts = file_exists('data/contacts.json') ? json_decode(file_get_contents('data/contacts.json'), true) : [];
$bookings = file_exists('data/bookings.json') ? json_decode(file_get_contents('data/bookings.json'), true) : [];
$packages = file_exists('data/packages.json') ? json_decode(file_get_contents('data/packages.json'), true) : [];
$services = file_exists('data/services.json') ? json_decode(file_get_contents('data/services.json'), true) : [];

// Calculate additional statistics
$total_inquiries = count($contacts) + count($bookings);
$new_contacts_today = 0;
$new_bookings_today = 0;
$today = date('Y-m-d');

foreach ($contacts as $contact) {
    if (isset($contact['date']) && strpos($contact['date'], $today) !== false) {
        $new_contacts_today++;
    }
}

foreach ($bookings as $booking) {
    if (isset($booking['date']) && strpos($booking['date'], $today) !== false) {
        $new_bookings_today++;
    }
}

// Get recent blogs (last 3)
$recent_blogs = array_slice(array_reverse($blogs), 0, 3);

// Get pending applications
$pending_applications = array_filter($applications, function($app) {
    return $app['status'] == 'pending';
});
$pending_applications = array_slice($pending_applications, 0, 3);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Altaf Catering Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link href="css/admin-unified.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php include 'includes/sidebar.php'; ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
                    <h1 class="h2 gradient-text">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </h1>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-primary" onclick="refreshDashboard()">
                            <i class="fas fa-sync-alt me-1"></i> Refresh
                        </button>
                        <button class="btn btn-sm btn-success" onclick="window.location.href='analytics.php'">
                            <i class="fas fa-chart-line me-1"></i> Analytics
                        </button>
                    </div>
                </div>
                
                <!-- Quick Actions Bar -->
                <div class="row mb-4">
                    <div class="col-lg-12">
                        <div class="card glow-on-hover" style="animation-delay: 0.1s;">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i> Quick Actions</h5>
                            </div>
                            <div class="card-body">
                                <div class="row text-center g-3">
                                    <div class="col-md-3 col-6">
                                        <a href="analytics.php" class="btn btn-primary w-100 py-3 glow-on-hover" style="animation: fadeIn 0.5s ease 0.1s both;">
                                            <i class="fas fa-chart-line fa-2x mb-2 d-block"></i>
                                            <span class="d-block">Analytics</span>
                                        </a>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <a href="backup.php" class="btn btn-success w-100 py-3 glow-on-hover" style="animation: fadeIn 0.5s ease 0.2s both;">
                                            <i class="fas fa-database fa-2x mb-2 d-block"></i>
                                            <span class="d-block">Backup</span>
                                        </a>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <a href="activity-log.php" class="btn btn-info w-100 py-3 glow-on-hover" style="animation: fadeIn 0.5s ease 0.3s both;">
                                            <i class="fas fa-history fa-2x mb-2 d-block"></i>
                                            <span class="d-block">Activity</span>
                                        </a>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <a href="system-check.php" class="btn btn-warning w-100 py-3 glow-on-hover" style="animation: fadeIn 0.5s ease 0.4s both;">
                                            <i class="fas fa-check-circle fa-2x mb-2 d-block"></i>
                                            <span class="d-block">System</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards Row 1 -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2 card-hover stat-card">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Blogs</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" data-count="<?php echo count($blogs); ?>"><?php echo count($blogs); ?></div>
                                        <small class="text-muted"><i class="fas fa-blog"></i> Published Articles</small>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-blog fa-2x text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-secondary shadow h-100 py-2 card-hover stat-card">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Blog Details</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" data-count="<?php echo count($blog_details); ?>"><?php echo count($blog_details); ?></div>
                                        <small class="text-muted"><i class="fas fa-file-alt"></i> Detailed Posts</small>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-file-alt fa-2x text-secondary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2 card-hover stat-card">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Team Members</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" data-count="<?php echo count($team); ?>"><?php echo count($team); ?></div>
                                        <small class="text-muted"><i class="fas fa-users"></i> Active Staff</small>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-users fa-2x text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2 card-hover stat-card">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Gallery Photos</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" data-count="<?php echo count($gallery); ?>"><?php echo count($gallery); ?></div>
                                        <small class="text-muted"><i class="fas fa-images"></i> Photo Collection</small>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-images fa-2x text-info"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2 card-hover stat-card">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Testimonials</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" data-count="<?php echo count($testimonials); ?>"><?php echo count($testimonials); ?></div>
                                        <small class="text-muted"><i class="fas fa-star"></i> Customer Reviews</small>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-star fa-2x text-warning"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Stats Cards Row 2 -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-danger shadow h-100 py-2 card-hover stat-card">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Menu Items</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" data-count="<?php echo count($menu); ?>"><?php echo count($menu); ?></div>
                                        <small class="text-muted"><i class="fas fa-utensils"></i> Food Items</small>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-utensils fa-2x text-danger"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2 card-hover stat-card">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Packages</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" data-count="<?php echo count($packages); ?>"><?php echo count($packages); ?></div>
                                        <small class="text-muted"><i class="fas fa-box"></i> Catering Packages</small>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-box fa-2x text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2 card-hover stat-card">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Services</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" data-count="<?php echo count($services); ?>"><?php echo count($services); ?></div>
                                        <small class="text-muted"><i class="fas fa-concierge-bell"></i> Available Services</small>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-concierge-bell fa-2x text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2 card-hover stat-card">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Job Listings</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" data-count="<?php echo count($jobs); ?>"><?php echo count($jobs); ?></div>
                                        <small class="text-muted"><i class="fas fa-clipboard-list"></i> Open Positions</small>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clipboard-list fa-2x text-info"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Stats Cards Row 3 - Inquiries & Applications -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2 card-hover stat-card">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Contacts</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" data-count="<?php echo count($contacts); ?>"><?php echo count($contacts); ?></div>
                                        <small class="text-muted"><i class="fas fa-envelope"></i> Messages Received</small>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-envelope fa-2x text-warning"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2 card-hover stat-card">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Bookings</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" data-count="<?php echo count($bookings); ?>"><?php echo count($bookings); ?></div>
                                        <small class="text-muted"><i class="fas fa-calendar-check"></i> Event Bookings</small>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar-check fa-2x text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-danger shadow h-100 py-2 card-hover stat-card">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Job Applications</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" data-count="<?php echo count($applications); ?>"><?php echo count($applications); ?></div>
                                        <small class="text-muted"><i class="fas fa-briefcase"></i> Total Applications</small>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-briefcase fa-2x text-danger"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2 card-hover stat-card">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Review</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" data-count="<?php echo count($pending_applications); ?>"><?php echo count($pending_applications); ?></div>
                                        <small class="text-muted"><i class="fas fa-clock"></i> Awaiting Action</small>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clock fa-2x text-warning"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <a href="blogs.php" class="btn btn-primary btn-block w-100">
                                            <i class="fas fa-plus"></i> Add New Blog
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <a href="team.php" class="btn btn-success btn-block w-100">
                                            <i class="fas fa-user-plus"></i> Add Team Member
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <a href="gallery.php" class="btn btn-info btn-block w-100">
                                            <i class="fas fa-image"></i> Upload Photos
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <a href="settings.php" class="btn btn-warning btn-block w-100">
                                            <i class="fas fa-cog"></i> Settings
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- System Status & Analytics -->
                <div class="row mb-4">
                    <div class="col-lg-12">
                        <div class="card shadow">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-chart-line me-2"></i> System Overview
                                </h6>
                                <span class="badge bg-success">
                                    <i class="fas fa-circle pulse-animation"></i> All Systems Operational
                                </span>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-md-3 mb-3">
                                        <div class="p-3 border-end">
                                            <i class="fas fa-server fa-2x text-primary mb-2"></i>
                                            <h5 class="mb-0">99.9%</h5>
                                            <small class="text-muted">Uptime</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="p-3 border-end">
                                            <i class="fas fa-users fa-2x text-success mb-2"></i>
                                            <h5 class="mb-0"><?php echo $total_inquiries; ?></h5>
                                            <small class="text-muted">Total Inquiries</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="p-3 border-end">
                                            <i class="fas fa-eye fa-2x text-info mb-2"></i>
                                            <h5 class="mb-0">2.4K</h5>
                                            <small class="text-muted">Page Views</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="p-3">
                                            <i class="fas fa-star fa-2x text-warning mb-2"></i>
                                            <h5 class="mb-0">4.8/5</h5>
                                            <small class="text-muted">Avg Rating</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Recent Blogs</h6>
                            </div>
                            <div class="card-body">
                                <?php if (empty($recent_blogs)): ?>
                                    <p class="text-muted">No blogs yet. <a href="blogs.php">Add your first blog</a></p>
                                <?php else: ?>
                                <div class="list-group">
                                    <?php foreach ($recent_blogs as $blog): ?>
                                    <a href="blogs.php" class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1"><?php echo htmlspecialchars($blog['title']); ?></h6>
                                            <small><?php echo $blog['date']; ?></small>
                                        </div>
                                        <small class="badge bg-<?php echo $blog['status'] == 'published' ? 'success' : 'warning'; ?>">
                                            <?php echo ucfirst($blog['status']); ?>
                                        </small>
                                    </a>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-success">
                                    <i class="fas fa-file-alt me-2"></i> Pending Applications
                                </h6>
                            </div>
                            <div class="card-body">
                                <?php if (empty($pending_applications)): ?>
                                    <p class="text-muted">No pending applications.</p>
                                <?php else: ?>
                                <div class="list-group">
                                    <?php foreach ($pending_applications as $app): ?>
                                    <a href="applications.php" class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1"><?php echo htmlspecialchars($app['name']); ?> - <?php echo htmlspecialchars($app['position']); ?></h6>
                                            <small><?php echo $app['applied_date']; ?></small>
                                        </div>
                                        <small class="text-warning"><i class="fas fa-clock"></i> Pending Review</small>
                                    </a>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activity Timeline -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-info">
                                    <i class="fas fa-history me-2"></i> Recent Activity Timeline
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="timeline">
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-success"></div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1">New Blog Published</h6>
                                            <p class="text-muted mb-0">
                                                <i class="fas fa-blog me-2"></i> 
                                                <?php echo !empty($recent_blogs) ? htmlspecialchars($recent_blogs[0]['title']) : 'Latest blog post'; ?>
                                            </p>
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i> 
                                                <?php echo !empty($recent_blogs) ? $recent_blogs[0]['date'] : 'Today'; ?>
                                            </small>
                                        </div>
                                    </div>
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-primary"></div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1">New Contact Message</h6>
                                            <p class="text-muted mb-0">
                                                <i class="fas fa-envelope me-2"></i> 
                                                <?php echo count($contacts); ?> total messages received
                                            </p>
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i> Recent
                                            </small>
                                        </div>
                                    </div>
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-warning"></div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1">Job Application Received</h6>
                                            <p class="text-muted mb-0">
                                                <i class="fas fa-briefcase me-2"></i> 
                                                <?php echo count($pending_applications); ?> applications pending review
                                            </p>
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i> Recent
                                            </small>
                                        </div>
                                    </div>
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-info"></div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1">Gallery Updated</h6>
                                            <p class="text-muted mb-0">
                                                <i class="fas fa-images me-2"></i> 
                                                <?php echo count($gallery); ?> photos in gallery
                                            </p>
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i> This week
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/layout-init.js"></script>
    <script src="js/layout-manager.js"></script>
    <script src="js/admin-common.js"></script>
    <script src="js/notifications.js"></script>
    <script src="js/animations.js"></script>
    <script src="js/admin-enhancements.js"></script>
    <script>
        // Welcome notification
        setTimeout(() => {
            if (window.notificationManager) {
                notificationManager.addNotification({
                    title: 'Welcome Back! 👋',
                    message: 'Dashboard loaded successfully',
                    icon: 'check-circle',
                    type: 'success'
                });
            }
        }, 1000);

        // Animate stat cards with stagger effect
        document.querySelectorAll('.stat-card').forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px) scale(0.9)';
            setTimeout(() => {
                card.style.transition = 'all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0) scale(1)';
            }, index * 150);
        });

        // Animate numbers counting up
        function animateValue(element, start, end, duration) {
            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                element.textContent = Math.floor(progress * (end - start) + start);
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        }

        // Count up animation for stat numbers
        setTimeout(() => {
            document.querySelectorAll('.h5[data-count]').forEach(el => {
                const finalValue = parseInt(el.getAttribute('data-count'));
                if (!isNaN(finalValue)) {
                    el.textContent = '0';
                    animateValue(el, 0, finalValue, 1500);
                }
            });
        }, 500);

        // Refresh dashboard function
        function refreshDashboard() {
            const btn = event.target.closest('button');
            const icon = btn.querySelector('i');
            icon.style.animation = 'rotate 1s linear';
            
            setTimeout(() => {
                location.reload();
            }, 1000);
        }

        // Add floating animation to icons
        document.querySelectorAll('.fa-2x').forEach((icon, index) => {
            icon.style.animation = `float 3s ease-in-out ${index * 0.2}s infinite`;
        });

        // Parallax effect on scroll
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            document.querySelectorAll('.card').forEach((card, index) => {
                const speed = 0.5 + (index * 0.1);
                card.style.transform = `translateY(${scrolled * speed * 0.1}px)`;
            });
        });

        // Add ripple effect to buttons
        document.querySelectorAll('.btn').forEach(button => {
            button.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.classList.add('ripple');
                
                this.appendChild(ripple);
                
                setTimeout(() => ripple.remove(), 600);
            });
        });
    </script>
    <style>
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.6);
            transform: scale(0);
            animation: ripple-animation 0.6s ease-out;
            pointer-events: none;
        }
        
        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    </style>
</body>
</html>
