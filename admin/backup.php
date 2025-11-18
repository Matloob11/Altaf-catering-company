<?php
// Include authentication check
require_once 'includes/auth-check.php';

require_once '../includes/backup.php';

$message = '';
$messageType = '';

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create_full_backup'])) {
        $result = createFullBackup();
        $message = $result['message'];
        $messageType = $result['success'] ? 'success' : 'danger';
    } elseif (isset($_POST['create_quick_backup'])) {
        $result = createQuickBackup();
        $message = $result['message'];
        $messageType = $result['success'] ? 'success' : 'danger';
    } elseif (isset($_POST['restore_backup'])) {
        $result = restoreBackup($_POST['backup_file']);
        $message = $result['message'];
        $messageType = $result['success'] ? 'success' : 'danger';
    } elseif (isset($_POST['delete_backup'])) {
        $result = deleteBackup($_POST['backup_file']);
        $message = $result['message'];
        $messageType = $result['success'] ? 'success' : 'danger';
    }
}

// Handle download
if (isset($_GET['download'])) {
    downloadBackup($_GET['download']);
}

// Get backups and stats
$backups = getAllBackups();
$stats = getBackupStats();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backup & Restore - Altaf Catering Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link href="css/admin-unified.css" rel="stylesheet"><style>
        .backup-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .backup-item {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
            transition: all 0.3s;
        }
        .backup-item:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }
        .stat-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
        }
        .stat-number {
            font-size: 32px;
            font-weight: bold;
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
                        <i class="fas fa-database me-2"></i> Backup & Restore
                    </h1>
                </div>

                <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show">
                    <?php echo $message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <!-- Statistics -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stat-box">
                            <div class="stat-number"><?php echo $stats['total_backups']; ?></div>
                            <div>Total Backups</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-box" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <div class="stat-number"><?php echo $stats['total_size']; ?></div>
                            <div>Total Size</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="stat-box" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <div style="font-size: 18px;">Latest Backup</div>
                            <div class="stat-number" style="font-size: 20px;"><?php echo $stats['latest_backup']; ?></div>
                        </div>
                    </div>
                </div>

                <!-- Create Backup -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="backup-card">
                            <h5><i class="fas fa-save me-2"></i> Create Full Backup</h5>
                            <p class="text-muted">Backup all data files and uploads</p>
                            <form method="POST">
                                <button type="submit" name="create_full_backup" class="btn btn-primary w-100">
                                    <i class="fas fa-database"></i> Create Full Backup
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="backup-card">
                            <h5><i class="fas fa-bolt me-2"></i> Create Quick Backup</h5>
                            <p class="text-muted">Backup data files only (faster)</p>
                            <form method="POST">
                                <button type="submit" name="create_quick_backup" class="btn btn-success w-100">
                                    <i class="fas fa-zap"></i> Create Quick Backup
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Backup List -->
                <div class="backup-card">
                    <h5 class="mb-4"><i class="fas fa-list me-2"></i> Available Backups</h5>
                    
                    <?php if (empty($backups)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> No backups yet. Create your first backup above!
                        </div>
                    <?php else: ?>
                        <?php foreach ($backups as $backup): ?>
                        <div class="backup-item">
                            <div class="row align-items-center">
                                <div class="col-md-5">
                                    <strong><i class="fas fa-file-archive text-primary"></i> <?php echo $backup['filename']; ?></strong>
                                </div>
                                <div class="col-md-2">
                                    <span class="badge bg-info"><?php echo $backup['size']; ?></span>
                                </div>
                                <div class="col-md-2">
                                    <small class="text-muted">
                                        <i class="fas fa-clock"></i> <?php echo $backup['date']; ?>
                                    </small>
                                </div>
                                <div class="col-md-3 text-end">
                                    <a href="?download=<?php echo urlencode($backup['filename']); ?>" 
                                       class="btn btn-sm btn-primary" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-success" 
                                            onclick="restoreBackup('<?php echo $backup['filename']; ?>')" 
                                            title="Restore">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" 
                                            onclick="deleteBackup('<?php echo $backup['filename']; ?>')" 
                                            title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Information -->
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="alert alert-info">
                            <strong><i class="fas fa-info-circle"></i> Full Backup</strong>
                            <p class="mb-0 mt-2">Includes all data files and uploaded images. Recommended for complete protection.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="alert alert-success">
                            <strong><i class="fas fa-bolt"></i> Quick Backup</strong>
                            <p class="mb-0 mt-2">Only data files (JSON). Faster and smaller. Good for frequent backups.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="alert alert-warning">
                            <strong><i class="fas fa-exclamation-triangle"></i> Important</strong>
                            <p class="mb-0 mt-2">Download backups regularly and store them safely offline!</p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Restore Confirmation Modal -->
    <div class="modal fade" id="restoreModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Restore</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Warning:</strong> This will replace all current data with the backup data.</p>
                    <p>Current data will be backed up automatically before restore.</p>
                    <p>Are you sure you want to continue?</p>
                </div>
                <div class="modal-footer">
                    <form method="POST" id="restoreForm">
                        <input type="hidden" name="backup_file" id="restoreFile">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="restore_backup" class="btn btn-success">
                            <i class="fas fa-undo"></i> Restore Backup
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this backup?</p>
                    <p class="text-danger"><strong>This action cannot be undone!</strong></p>
                </div>
                <div class="modal-footer">
                    <form method="POST" id="deleteForm">
                        <input type="hidden" name="backup_file" id="deleteFile">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="delete_backup" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Delete Backup
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function restoreBackup(filename) {
            document.getElementById('restoreFile').value = filename;
            new bootstrap.Modal(document.getElementById('restoreModal')).show();
        }

        function deleteBackup(filename) {
            document.getElementById('deleteFile').value = filename;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }
    </script>
</body>
</html>
