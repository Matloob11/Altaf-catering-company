<?php
// Include authentication check
require_once 'includes/auth-check.php';

// Load applications data
$applications_file = 'data/applications.json';
$applications = file_exists($applications_file) ? json_decode(file_get_contents($applications_file), true) : [];

// Handle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $id = (int)$_POST['id'];
    foreach ($applications as &$app) {
        if ($app['id'] == $id) {
            $app['status'] = $_POST['status'];
            break;
        }
    }
    file_put_contents($applications_file, json_encode($applications, JSON_PRETTY_PRINT));
    $success = "Application status updated successfully!";
}

// Handle delete
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_application'])) {
    $id = (int)$_POST['id'];
    $applications = array_filter($applications, function($app) use ($id) {
        return $app['id'] != $id;
    });
    $applications = array_values($applications);
    file_put_contents($applications_file, json_encode($applications, JSON_PRETTY_PRINT));
    $success = "Application deleted successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Applications - Altaf Catering Admin</title>
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
                        <i class="fas fa-file-alt me-2"></i> Job Applications
                    </h1>
                    <div class="d-flex gap-2">
                        <span class="badge bg-primary fs-6">Total: <?php echo count($applications); ?></span>
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
                
                <?php if (empty($applications)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No applications received yet.
                    </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Position</th>
                                <th>Status</th>
                                <th>Applied Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($applications as $app): ?>
                            <tr>
                                <td><?php echo $app['id']; ?></td>
                                <td><?php echo htmlspecialchars($app['name']); ?></td>
                                <td><?php echo htmlspecialchars($app['email']); ?></td>
                                <td><?php echo htmlspecialchars($app['phone']); ?></td>
                                <td><?php echo htmlspecialchars($app['position']); ?></td>
                                <td>
                                    <span class="badge bg-<?php 
                                        echo $app['status'] == 'pending' ? 'warning' : 
                                            ($app['status'] == 'reviewed' ? 'info' : 
                                            ($app['status'] == 'shortlisted' ? 'success' : 'danger')); 
                                    ?>">
                                        <?php echo ucfirst($app['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo $app['applied_date']; ?></td>
                                <td>
                                    <button class="btn btn-sm btn-info" onclick='viewApplication(<?php echo json_encode($app); ?>)'>
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="id" value="<?php echo $app['id']; ?>">
                                        <button type="submit" name="delete_application" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </main>
        </div>
    </div>
    
    <!-- View Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Application Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Name:</strong>
                            <p id="view_name"></p>
                        </div>
                        <div class="col-md-6">
                            <strong>Email:</strong>
                            <p id="view_email"></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Phone:</strong>
                            <p id="view_phone"></p>
                        </div>
                        <div class="col-md-6">
                            <strong>Position Applied:</strong>
                            <p id="view_position"></p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <strong>Cover Letter:</strong>
                        <p id="view_cover_letter"></p>
                    </div>
                    <div class="mb-3">
                        <strong>Resume:</strong>
                        <p><a id="view_resume" href="#" target="_blank">Download Resume</a></p>
                    </div>
                    <form method="POST">
                        <input type="hidden" name="id" id="status_id">
                        <div class="mb-3">
                            <label class="form-label"><strong>Update Status:</strong></label>
                            <select name="status" id="status_select" class="form-select">
                                <option value="pending">Pending</option>
                                <option value="reviewed">Reviewed</option>
                                <option value="shortlisted">Shortlisted</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <button type="submit" name="update_status" class="btn btn-primary">Update Status</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/vip-admin.js"></script>
    <script>
        function viewApplication(app) {
            document.getElementById('view_name').textContent = app.name;
            document.getElementById('view_email').textContent = app.email;
            document.getElementById('view_phone').textContent = app.phone;
            document.getElementById('view_position').textContent = app.position;
            document.getElementById('view_cover_letter').textContent = app.cover_letter || 'N/A';
            document.getElementById('view_resume').href = app.resume || '#';
            document.getElementById('status_id').value = app.id;
            document.getElementById('status_select').value = app.status;
            new bootstrap.Modal(document.getElementById('viewModal')).show();
        }
        
        // VIP Applications Page Enhancements
        document.addEventListener('DOMContentLoaded', function() {
            // Show notification for successful actions
            <?php if (isset($success)): ?>
            VIP.notify('<?php echo addslashes($success); ?>', 'success');
            <?php endif; ?>
            
            // Add confirmation for delete actions
            document.querySelectorAll('button[name="delete_application"]').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    if (!confirm('Are you sure you want to delete this application?')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</body>
</html>
