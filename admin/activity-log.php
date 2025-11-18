<?php
// Include authentication check
require_once 'includes/auth-check.php';

// Load or create activity log
$log_file = 'data/activity_log.json';
if (!file_exists($log_file)) {
    file_put_contents($log_file, json_encode([]));
}
$activities = json_decode(file_get_contents($log_file), true) ?: [];

// Reverse to show latest first
$activities = array_reverse($activities);

// Pagination
$per_page = 20;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$total = count($activities);
$total_pages = ceil($total / $per_page);
$offset = ($page - 1) * $per_page;
$activities_page = array_slice($activities, $offset, $per_page);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Log - Altaf Catering Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link href="css/admin-unified.css" rel="stylesheet"></head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php include 'includes/sidebar.php'; ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><i class="fas fa-history me-2"></i> Activity Log</h1>
                    <div class="btn-toolbar">
                        <button class="btn btn-sm btn-outline-danger" onclick="clearLog()">
                            <i class="fas fa-trash me-1"></i> Clear Log
                        </button>
                    </div>
                </div>

                <!-- Filter Options -->
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <select class="form-select" id="filterType">
                                    <option value="">All Activities</option>
                                    <option value="create">Create</option>
                                    <option value="update">Update</option>
                                    <option value="delete">Delete</option>
                                    <option value="login">Login</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="date" class="form-control" id="filterDate">
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="searchLog" placeholder="Search activities...">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary w-100" onclick="applyFilters()">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activity Timeline -->
                <div class="card shadow">
                    <div class="card-body">
                        <?php if (empty($activities_page)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
                            <p class="text-muted">No activities recorded yet</p>
                        </div>
                        <?php else: ?>
                        <div class="activity-timeline">
                            <?php foreach ($activities_page as $activity): ?>
                            <div class="activity-item">
                                <div class="activity-icon bg-<?php echo $activity['type'] == 'create' ? 'success' : ($activity['type'] == 'delete' ? 'danger' : 'primary'); ?>">
                                    <i class="fas fa-<?php echo $activity['icon'] ?? 'circle'; ?>"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-1"><?php echo htmlspecialchars($activity['action']); ?></h6>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            <?php echo $activity['timestamp']; ?>
                                        </small>
                                    </div>
                                    <p class="text-muted mb-1"><?php echo htmlspecialchars($activity['description'] ?? ''); ?></p>
                                    <small class="text-muted">
                                        <i class="fas fa-user me-1"></i> <?php echo $activity['user'] ?? 'Admin'; ?>
                                    </small>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Pagination -->
                        <?php if ($total_pages > 1): ?>
                        <nav class="mt-4">
                            <ul class="pagination justify-content-center">
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                                <?php endfor; ?>
                            </ul>
                        </nav>
                        <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/admin-common.js"></script>
    <script>
        function clearLog() {
            if (confirm('Are you sure you want to clear all activity logs?')) {
                window.location.href = '?clear=1';
            }
        }

        function applyFilters() {
            // Implement filter logic
            alert('Filter functionality will be implemented');
        }
    </script>
    <script src="js/admin-enhancements.js"></script>
</body>
</html>
