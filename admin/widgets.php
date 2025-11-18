<?php
// Include authentication check
require_once 'includes/auth-check.php';

// Load data
$contacts = file_exists('data/contacts.json') ? json_decode(file_get_contents('data/contacts.json'), true) : [];
$bookings = file_exists('data/bookings.json') ? json_decode(file_get_contents('data/bookings.json'), true) : [];
$blogs = file_exists('data/blogs.json') ? json_decode(file_get_contents('data/blogs.json'), true) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Widgets - Altaf Catering Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link href="css/admin-unified.css" rel="stylesheet"><style>
        .widget {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            transition: transform 0.3s;
        }
        .widget:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .widget-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }
        .widget-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        .widget-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .widget-body {
            padding: 10px 0;
        }
        .widget-stat {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .widget-stat:last-child {
            border-bottom: none;
        }
        .stat-label {
            color: #666;
            font-size: 14px;
        }
        .stat-value {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }
        .mini-chart {
            height: 60px;
            background: linear-gradient(to top, rgba(102, 126, 234, 0.1), transparent);
            border-radius: 4px;
            position: relative;
            overflow: hidden;
        }
        .progress-ring {
            width: 100px;
            height: 100px;
            margin: 0 auto;
        }
        .calendar-widget {
            text-align: center;
        }
        .calendar-date {
            font-size: 48px;
            font-weight: bold;
            color: #667eea;
        }
        .calendar-month {
            font-size: 18px;
            color: #666;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php include 'includes/sidebar.php'; ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><i class="fas fa-th me-2"></i> Dashboard Widgets</h1>
                    <button class="btn btn-primary" onclick="customizeWidgets()">
                        <i class="fas fa-cog me-1"></i> Customize
                    </button>
                </div>

                <div class="row">
                    <!-- Today's Overview Widget -->
                    <div class="col-lg-4 col-md-6">
                        <div class="widget">
                            <div class="widget-header">
                                <div class="widget-title">Today's Overview</div>
                                <div class="widget-icon">
                                    <i class="fas fa-calendar-day"></i>
                                </div>
                            </div>
                            <div class="widget-body">
                                <div class="calendar-widget mb-3">
                                    <div class="calendar-date"><?php echo date('d'); ?></div>
                                    <div class="calendar-month"><?php echo date('F Y'); ?></div>
                                </div>
                                <div class="widget-stat">
                                    <span class="stat-label">New Messages</span>
                                    <span class="stat-value text-primary"><?php echo count(array_filter($contacts, function($c) { return isset($c['status']) && $c['status'] == 'new'; })); ?></span>
                                </div>
                                <div class="widget-stat">
                                    <span class="stat-label">Pending Bookings</span>
                                    <span class="stat-value text-warning"><?php echo count(array_filter($bookings, function($b) { return isset($b['status']) && $b['status'] == 'pending'; })); ?></span>
                                </div>
                                <div class="widget-stat">
                                    <span class="stat-label">Tasks Completed</span>
                                    <span class="stat-value text-success">8/12</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats Widget -->
                    <div class="col-lg-4 col-md-6">
                        <div class="widget">
                            <div class="widget-header">
                                <div class="widget-title">Quick Stats</div>
                                <div class="widget-icon">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                            </div>
                            <div class="widget-body">
                                <div class="widget-stat">
                                    <span class="stat-label"><i class="fas fa-envelope me-2 text-primary"></i> Total Contacts</span>
                                    <span class="stat-value"><?php echo count($contacts); ?></span>
                                </div>
                                <div class="widget-stat">
                                    <span class="stat-label"><i class="fas fa-calendar me-2 text-success"></i> Total Bookings</span>
                                    <span class="stat-value"><?php echo count($bookings); ?></span>
                                </div>
                                <div class="widget-stat">
                                    <span class="stat-label"><i class="fas fa-blog me-2 text-info"></i> Published Blogs</span>
                                    <span class="stat-value"><?php echo count($blogs); ?></span>
                                </div>
                                <div class="widget-stat">
                                    <span class="stat-label"><i class="fas fa-eye me-2 text-warning"></i> Page Views</span>
                                    <span class="stat-value">2,458</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Performance Widget -->
                    <div class="col-lg-4 col-md-6">
                        <div class="widget">
                            <div class="widget-header">
                                <div class="widget-title">Performance</div>
                                <div class="widget-icon">
                                    <i class="fas fa-tachometer-alt"></i>
                                </div>
                            </div>
                            <div class="widget-body text-center">
                                <div class="mb-3">
                                    <h3 class="text-success">98.5%</h3>
                                    <small class="text-muted">Overall Score</small>
                                </div>
                                <div class="progress mb-2" style="height: 8px;">
                                    <div class="progress-bar bg-success" style="width: 98.5%"></div>
                                </div>
                                <div class="row text-center mt-3">
                                    <div class="col-4">
                                        <div class="text-success"><i class="fas fa-arrow-up"></i> 12%</div>
                                        <small class="text-muted">Response</small>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-primary"><i class="fas fa-check"></i> 95%</div>
                                        <small class="text-muted">Uptime</small>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-warning"><i class="fas fa-star"></i> 4.8</div>
                                        <small class="text-muted">Rating</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity Widget -->
                    <div class="col-lg-6">
                        <div class="widget">
                            <div class="widget-header">
                                <div class="widget-title">Recent Activity</div>
                                <div class="widget-icon">
                                    <i class="fas fa-history"></i>
                                </div>
                            </div>
                            <div class="widget-body">
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item border-0 px-0">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <div class="bg-primary text-white rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-envelope"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">New contact message</h6>
                                                <small class="text-muted">2 minutes ago</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list-group-item border-0 px-0">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <div class="bg-success text-white rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-calendar"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">New booking request</h6>
                                                <small class="text-muted">15 minutes ago</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list-group-item border-0 px-0">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <div class="bg-info text-white rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-blog"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">Blog post published</h6>
                                                <small class="text-muted">1 hour ago</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Links Widget -->
                    <div class="col-lg-6">
                        <div class="widget">
                            <div class="widget-header">
                                <div class="widget-title">Quick Links</div>
                                <div class="widget-icon">
                                    <i class="fas fa-link"></i>
                                </div>
                            </div>
                            <div class="widget-body">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <a href="blogs.php" class="btn btn-outline-primary w-100">
                                            <i class="fas fa-blog d-block mb-2" style="font-size: 24px;"></i>
                                            Manage Blogs
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a href="gallery.php" class="btn btn-outline-success w-100">
                                            <i class="fas fa-images d-block mb-2" style="font-size: 24px;"></i>
                                            Gallery
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a href="contacts.php" class="btn btn-outline-info w-100">
                                            <i class="fas fa-envelope d-block mb-2" style="font-size: 24px;"></i>
                                            Messages
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a href="analytics.php" class="btn btn-outline-warning w-100">
                                            <i class="fas fa-chart-line d-block mb-2" style="font-size: 24px;"></i>
                                            Analytics
                                        </a>
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
    <script src="js/admin-common.js"></script>
    <script src="js/notifications.js"></script>
    <script>
        function customizeWidgets() {
            alert('Widget customization feature coming soon!');
        }

        // Animate widgets on load
        document.querySelectorAll('.widget').forEach((widget, index) => {
            widget.style.opacity = '0';
            widget.style.transform = 'translateY(20px)';
            setTimeout(() => {
                widget.style.transition = 'all 0.5s ease';
                widget.style.opacity = '1';
                widget.style.transform = 'translateY(0)';
            }, index * 100);
        });
    </script>
    <script src="js/admin-enhancements.js"></script>
</body>
</html>
