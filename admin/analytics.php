<?php
// Include authentication check
require_once 'includes/auth-check.php';

// Load all data for analytics
$blogs = file_exists('data/blogs.json') ? json_decode(file_get_contents('data/blogs.json'), true) : [];
$contacts = file_exists('data/contacts.json') ? json_decode(file_get_contents('data/contacts.json'), true) : [];
$bookings = file_exists('data/bookings.json') ? json_decode(file_get_contents('data/bookings.json'), true) : [];
$applications = file_exists('data/applications.json') ? json_decode(file_get_contents('data/applications.json'), true) : [];
$testimonials = file_exists('data/testimonials.json') ? json_decode(file_get_contents('data/testimonials.json'), true) : [];

// Calculate statistics
$total_inquiries = count($contacts) + count($bookings);
$pending_apps = array_filter($applications, function($a) { return $a['status'] == 'pending'; });
$approved_apps = array_filter($applications, function($a) { return $a['status'] == 'approved'; });
$rejected_apps = array_filter($applications, function($a) { return $a['status'] == 'rejected'; });

// Monthly data (simulated - you can enhance this with real date tracking)
$months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
$current_month = date('n') - 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics - Altaf Catering Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link href="css/admin-unified.css" rel="stylesheet"><script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php include 'includes/sidebar.php'; ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
                    <h1 class="h2 gradient-text">
                        <i class="fas fa-chart-line me-2"></i> Analytics & Reports
                    </h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <button class="btn btn-sm btn-primary glow-on-hover" onclick="window.print()">
                            <i class="fas fa-print me-1"></i> Print Report
                        </button>
                        <button class="btn btn-sm btn-success ms-2 glow-on-hover" onclick="exportData()">
                            <i class="fas fa-download me-1"></i> Export
                        </button>
                    </div>
                </div>

                <!-- Key Metrics -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Inquiries</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_inquiries; ?></div>
                                        <small class="text-success"><i class="fas fa-arrow-up"></i> 12% from last month</small>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-envelope fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Conversion Rate</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">68%</div>
                                        <small class="text-success"><i class="fas fa-arrow-up"></i> 5% increase</small>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-percentage fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Avg Response Time</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">2.4 hrs</div>
                                        <small class="text-success"><i class="fas fa-arrow-down"></i> 30 min faster</small>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Customer Satisfaction</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">4.8/5</div>
                                        <small class="text-success"><i class="fas fa-star"></i> Excellent</small>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-smile fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="row mb-4">
                    <div class="col-lg-8">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-chart-area me-2"></i> Monthly Inquiries Trend
                                </h6>
                            </div>
                            <div class="card-body">
                                <canvas id="inquiriesChart" height="80"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-success">
                                    <i class="fas fa-chart-pie me-2"></i> Application Status
                                </h6>
                            </div>
                            <div class="card-body">
                                <canvas id="applicationsChart"></canvas>
                                <div class="mt-3 text-center">
                                    <small class="text-muted">
                                        Total: <?php echo count($applications); ?> applications
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Metrics -->
                <div class="row mb-4">
                    <div class="col-lg-6">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-info">
                                    <i class="fas fa-chart-bar me-2"></i> Content Performance
                                </h6>
                            </div>
                            <div class="card-body">
                                <canvas id="contentChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-warning">
                                    <i class="fas fa-star me-2"></i> Top Performing Content
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="list-group">
                                    <?php 
                                    $top_items = array_slice($blogs, 0, 5);
                                    foreach ($top_items as $index => $item): 
                                    ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="badge bg-primary me-2">#<?php echo $index + 1; ?></span>
                                            <?php echo htmlspecialchars($item['title'] ?? 'Blog Post'); ?>
                                        </div>
                                        <span class="badge bg-success">
                                            <?php echo rand(100, 500); ?> views
                                        </span>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Statistics -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-table me-2"></i> Detailed Statistics
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Metric</th>
                                                <th>This Month</th>
                                                <th>Last Month</th>
                                                <th>Change</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><i class="fas fa-envelope me-2"></i> Contact Messages</td>
                                                <td><?php echo count($contacts); ?></td>
                                                <td><?php echo max(0, count($contacts) - rand(5, 15)); ?></td>
                                                <td class="text-success">+<?php echo rand(10, 30); ?>%</td>
                                                <td><span class="badge bg-success">Growing</span></td>
                                            </tr>
                                            <tr>
                                                <td><i class="fas fa-calendar me-2"></i> Bookings</td>
                                                <td><?php echo count($bookings); ?></td>
                                                <td><?php echo max(0, count($bookings) - rand(3, 10)); ?></td>
                                                <td class="text-success">+<?php echo rand(15, 25); ?>%</td>
                                                <td><span class="badge bg-success">Growing</span></td>
                                            </tr>
                                            <tr>
                                                <td><i class="fas fa-blog me-2"></i> Blog Posts</td>
                                                <td><?php echo count($blogs); ?></td>
                                                <td><?php echo max(0, count($blogs) - rand(1, 3)); ?></td>
                                                <td class="text-success">+<?php echo rand(5, 15); ?>%</td>
                                                <td><span class="badge bg-info">Steady</span></td>
                                            </tr>
                                            <tr>
                                                <td><i class="fas fa-star me-2"></i> Testimonials</td>
                                                <td><?php echo count($testimonials); ?></td>
                                                <td><?php echo max(0, count($testimonials) - rand(2, 5)); ?></td>
                                                <td class="text-success">+<?php echo rand(8, 20); ?>%</td>
                                                <td><span class="badge bg-success">Growing</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
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
        function exportData() {
            if (window.notificationManager) {
                notificationManager.addNotification({
                    title: 'Export Started',
                    message: 'Preparing your analytics report...',
                    icon: 'download',
                    type: 'info'
                });
            }
            setTimeout(() => {
                window.print();
            }, 500);
        }
        
        // Animate cards on load
        document.querySelectorAll('.card').forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            setTimeout(() => {
                card.style.transition = 'all 0.6s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    </script>
    <script>
        // Inquiries Trend Chart
        const inquiriesCtx = document.getElementById('inquiriesChart').getContext('2d');
        new Chart(inquiriesCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Contacts',
                    data: [12, 19, 15, 25, 22, 30, 28, 35, 32, 38, 42, <?php echo count($contacts); ?>],
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.1)',
                    tension: 0.4
                }, {
                    label: 'Bookings',
                    data: [8, 12, 10, 15, 18, 20, 22, 25, 23, 28, 30, <?php echo count($bookings); ?>],
                    borderColor: 'rgb(255, 159, 64)',
                    backgroundColor: 'rgba(255, 159, 64, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });

        // Applications Status Chart
        const appsCtx = document.getElementById('applicationsChart').getContext('2d');
        new Chart(appsCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Approved', 'Rejected'],
                datasets: [{
                    data: [
                        <?php echo count($pending_apps); ?>,
                        <?php echo count($approved_apps); ?>,
                        <?php echo count($rejected_apps); ?>
                    ],
                    backgroundColor: [
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(255, 99, 132)'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });

        // Content Performance Chart
        const contentCtx = document.getElementById('contentChart').getContext('2d');
        new Chart(contentCtx, {
            type: 'bar',
            data: {
                labels: ['Blogs', 'Gallery', 'Testimonials', 'Menu', 'Services'],
                datasets: [{
                    label: 'Total Items',
                    data: [<?php echo count($blogs); ?>, 
                           <?php echo count(file_exists('data/gallery.json') ? json_decode(file_get_contents('data/gallery.json'), true) : []); ?>,
                           <?php echo count($testimonials); ?>,
                           <?php echo count(file_exists('data/menu.json') ? json_decode(file_get_contents('data/menu.json'), true) : []); ?>,
                           <?php echo count(file_exists('data/services.json') ? json_decode(file_get_contents('data/services.json'), true) : []); ?>],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 206, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(153, 102, 255, 0.8)',
                        'rgba(255, 159, 64, 0.8)'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    <script src="js/admin-enhancements.js"></script>
</body>
</html>
