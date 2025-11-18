<?php
// Include authentication check
require_once 'includes/auth-check.php';

require_once '../includes/visitor-tracking.php';

// Get analytics data
$summary = getVisitorAnalyticsSummary();
$trafficSources = getVisitorTrafficSources();
$dailyStats = getVisitorDailyStats(30);
$deviceStats = getVisitorDeviceStats();

// Handle CSV export
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    $csv = exportVisitorAnalyticsCSV();
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="analytics-' . date('Y-m-d') . '.csv"');
    echo $csv;
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Analytics - Altaf Catering Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link href="css/admin-unified.css" rel="stylesheet"><script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
        }
        .stat-number {
            font-size: 36px;
            font-weight: bold;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .page-item {
            display: flex;
            justify-content: space-between;
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        .page-item:hover {
            background: #f8f9fa;
        }
        .progress-bar-animated {
            animation: progress-animation 2s ease;
        }
        @keyframes progress-animation {
            from { width: 0; }
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
                        <i class="fas fa-chart-line me-2"></i> Advanced Analytics
                    </h1>
                    <div class="btn-toolbar">
                        <button class="btn btn-sm btn-primary me-2" onclick="window.location.reload()">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                        <a href="?export=csv" class="btn btn-sm btn-success">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stat-card">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1">Total Page Views</p>
                                    <div class="stat-number"><?php echo number_format($summary['total_page_views']); ?></div>
                                </div>
                                <div class="text-primary" style="font-size: 40px;">
                                    <i class="fas fa-eye"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stat-card">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1">Total Visitors</p>
                                    <div class="stat-number"><?php echo number_format($summary['total_visitors']); ?></div>
                                </div>
                                <div class="text-success" style="font-size: 40px;">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stat-card">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1">Today's Views</p>
                                    <div class="stat-number"><?php echo number_format($summary['today_views']); ?></div>
                                </div>
                                <div class="text-info" style="font-size: 40px;">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stat-card">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1">Today's Visitors</p>
                                    <div class="stat-number"><?php echo number_format($summary['today_visitors']); ?></div>
                                </div>
                                <div class="text-warning" style="font-size: 40px;">
                                    <i class="fas fa-user-friends"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="row mb-4">
                    <div class="col-lg-8">
                        <div class="chart-container">
                            <h5 class="mb-4"><i class="fas fa-chart-area me-2"></i> Traffic Trend (Last 30 Days)</h5>
                            <canvas id="trafficChart" height="80"></canvas>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="chart-container">
                            <h5 class="mb-4"><i class="fas fa-mobile-alt me-2"></i> Device Breakdown</h5>
                            <canvas id="deviceChart"></canvas>
                            <div class="mt-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span><i class="fas fa-desktop text-primary"></i> Desktop</span>
                                    <strong><?php echo $deviceStats['desktop']; ?></strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span><i class="fas fa-mobile text-success"></i> Mobile</span>
                                    <strong><?php echo $deviceStats['mobile']; ?></strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span><i class="fas fa-tablet text-info"></i> Tablet</span>
                                    <strong><?php echo $deviceStats['tablet']; ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Popular Pages & Traffic Sources -->
                <div class="row mb-4">
                    <div class="col-lg-6">
                        <div class="chart-container">
                            <h5 class="mb-4"><i class="fas fa-fire me-2"></i> Popular Pages</h5>
                            <?php if (empty($summary['popular_pages'])): ?>
                                <p class="text-muted">No data yet. Visit your website to start tracking!</p>
                            <?php else: ?>
                                <?php 
                                $maxViews = max($summary['popular_pages']);
                                foreach ($summary['popular_pages'] as $page => $views): 
                                    $percentage = ($views / $maxViews) * 100;
                                ?>
                                <div class="page-item">
                                    <div class="flex-grow-1">
                                        <strong><?php echo htmlspecialchars($page); ?></strong>
                                        <div class="progress mt-2" style="height: 8px;">
                                            <div class="progress-bar progress-bar-animated bg-primary" 
                                                 style="width: <?php echo $percentage; ?>%"></div>
                                        </div>
                                    </div>
                                    <div class="ms-3">
                                        <span class="badge bg-primary"><?php echo number_format($views); ?> views</span>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        <div class="chart-container">
                            <h5 class="mb-4"><i class="fas fa-share-alt me-2"></i> Traffic Sources</h5>
                            <canvas id="sourcesChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Real-time Info -->
                <div class="row">
                    <div class="col-12">
                        <div class="chart-container">
                            <h5 class="mb-4"><i class="fas fa-info-circle me-2"></i> Analytics Information</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="alert alert-info">
                                        <strong><i class="fas fa-clock"></i> Real-time Tracking</strong>
                                        <p class="mb-0 mt-2">All visitor data is tracked in real-time and stored locally.</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="alert alert-success">
                                        <strong><i class="fas fa-shield-alt"></i> Privacy Compliant</strong>
                                        <p class="mb-0 mt-2">No personal data is collected. Only anonymous statistics.</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="alert alert-warning">
                                        <strong><i class="fas fa-database"></i> Data Storage</strong>
                                        <p class="mb-0 mt-2">Last 10,000 page views and 90 days of daily stats are kept.</p>
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
    <script>
        // Traffic Trend Chart
        const trafficCtx = document.getElementById('trafficChart').getContext('2d');
        const dailyData = <?php echo json_encode(array_values($dailyStats)); ?>;
        const labels = dailyData.map(d => d.date);
        const pageViews = dailyData.map(d => d.page_views);
        
        new Chart(trafficCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Page Views',
                    data: pageViews,
                    borderColor: 'rgb(102, 126, 234)',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4,
                    fill: true
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

        // Device Chart
        const deviceCtx = document.getElementById('deviceChart').getContext('2d');
        new Chart(deviceCtx, {
            type: 'doughnut',
            data: {
                labels: ['Desktop', 'Mobile', 'Tablet'],
                datasets: [{
                    data: [
                        <?php echo $deviceStats['desktop']; ?>,
                        <?php echo $deviceStats['mobile']; ?>,
                        <?php echo $deviceStats['tablet']; ?>
                    ],
                    backgroundColor: [
                        'rgb(102, 126, 234)',
                        'rgb(75, 192, 192)',
                        'rgb(255, 205, 86)'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Traffic Sources Chart
        const sourcesCtx = document.getElementById('sourcesChart').getContext('2d');
        const sources = <?php echo json_encode($trafficSources); ?>;
        
        new Chart(sourcesCtx, {
            type: 'bar',
            data: {
                labels: Object.keys(sources),
                datasets: [{
                    label: 'Visitors',
                    data: Object.values(sources),
                    backgroundColor: [
                        'rgba(102, 126, 234, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(255, 205, 86, 0.8)',
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(153, 102, 255, 0.8)'
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
</body>
</html>
