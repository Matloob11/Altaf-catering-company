<?php
// Include authentication check
require_once 'includes/auth-check.php';

// Your PHP logic here
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Page - Altaf Catering Admin</title>
    
    <!-- UNIFIED CSS SETUP - Only these 3 lines needed for all admin pages -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link href="css/admin-unified.css" rel="stylesheet">
    <!-- END UNIFIED CSS SETUP -->
    
</head>
<body>
    <!-- Include Header -->
    <?php include 'includes/header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <!-- Include Sidebar -->
            <?php include 'includes/sidebar.php'; ?>
            
            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!-- Page Header -->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                    <h1 class="h2 gradient-text">
                        <i class="fas fa-star me-2"></i> New Page Title
                    </h1>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-vip">
                            <i class="fas fa-plus me-1"></i> Add New
                        </button>
                        <button class="btn btn-sm btn-primary" onclick="window.location.reload()">
                            <i class="fas fa-sync-alt me-1"></i> Refresh
                        </button>
                        <button class="btn btn-sm btn-glass">
                            <i class="fas fa-download me-1"></i> Export
                        </button>
                    </div>
                </div>
                
                <!-- Success/Error Messages -->
                <?php if (isset($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php echo $success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <?php echo $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <!-- VIP Stats Cards Row -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2 card-hover stat-card glow-on-hover">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Items</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" data-count="25">25</div>
                                        <small class="text-muted"><i class="fas fa-box me-1"></i> Active Items</small>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-box fa-2x text-primary float-animation"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2 card-hover stat-card glow-on-hover">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" data-count="18">18</div>
                                        <small class="text-muted"><i class="fas fa-check me-1"></i> Published</small>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-check-circle fa-2x text-success pulse-animation"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2 card-hover stat-card glow-on-hover">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" data-count="5">5</div>
                                        <small class="text-muted"><i class="fas fa-clock me-1"></i> Review Needed</small>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clock fa-2x text-warning pulse-animation"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-danger shadow h-100 py-2 card-hover stat-card glow-on-hover">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Inactive</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" data-count="2">2</div>
                                        <small class="text-muted"><i class="fas fa-times me-1"></i> Disabled</small>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-times-circle fa-2x text-danger"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Main Content Card -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-table me-2"></i> Data Table
                        </h6>
                    </div>
                    <div class="card-body">
                        <!-- Table Example -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Sample Item</td>
                                        <td><span class="badge bg-success">Active</span></td>
                                        <td>2024-01-15</td>
                                        <td>
                                            <button class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Form Example Card -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-plus me-2"></i> Add New Item
                        </h6>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Item Name</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Save Item
                            </button>
                            <button type="reset" class="btn btn-secondary">
                                <i class="fas fa-undo me-2"></i> Reset
                            </button>
                        </form>
                    </div>
                </div>
                
            </main>
        </div>
    </div>
    
    <!-- Modal Example -->
    <div class="modal fade" id="exampleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-info-circle me-2"></i> Modal Title
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Modal content goes here...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- VIP Admin JavaScript -->
    <script src="js/vip-admin.js"></script>
    
    <!-- Page Specific JavaScript -->
    <script>
        // Page-specific functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Example: Show success notification
            setTimeout(() => {
                VIP.notify('Welcome to the VIP Admin Panel!', 'success');
            }, 1000);
            
            // Example: Add click handlers
            document.querySelectorAll('.btn-vip').forEach(btn => {
                btn.addEventListener('click', function() {
                    VIP.notify('VIP Action Triggered!', 'info');
                });
            });
        });
    </script>
</body>
</html>