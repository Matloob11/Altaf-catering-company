<?php
// Include authentication check
require_once 'includes/auth-check.php';

$data_file = 'data/services.json';

// Load services data
function loadServicesData() {
    global $data_file;
    if (file_exists($data_file)) {
        return json_decode(file_get_contents($data_file), true);
    }
    return [];
}

// Save services data
function saveServicesData($data) {
    global $data_file;
    file_put_contents($data_file, json_encode($data, JSON_PRETTY_PRINT));
}

// Handle bulk delete
if (isset($_POST['bulk_delete']) && isset($_POST['selected_ids'])) {
    $selected_ids = array_map('intval', $_POST['selected_ids']);
    $services = loadServicesData();
    $services = array_filter($services, function($service) use ($selected_ids) {
        return !in_array($service['id'], $selected_ids);
    });
    saveServicesData(array_values($services));
    $success = count($selected_ids) . " service(s) deleted successfully!";
}

// Handle bulk activate
if (isset($_POST['bulk_activate']) && isset($_POST['selected_ids'])) {
    $selected_ids = array_map('intval', $_POST['selected_ids']);
    $services = loadServicesData();
    foreach ($services as &$service) {
        if (in_array($service['id'], $selected_ids)) {
            $service['status'] = 'active';
        }
    }
    saveServicesData($services);
    $success = count($selected_ids) . " service(s) activated successfully!";
}

// Handle bulk deactivate
if (isset($_POST['bulk_deactivate']) && isset($_POST['selected_ids'])) {
    $selected_ids = array_map('intval', $_POST['selected_ids']);
    $services = loadServicesData();
    foreach ($services as &$service) {
        if (in_array($service['id'], $selected_ids)) {
            $service['status'] = 'inactive';
        }
    }
    saveServicesData($services);
    $success = count($selected_ids) . " service(s) deactivated successfully!";
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $services = loadServicesData();
    $services = array_filter($services, function($service) use ($id) {
        return $service['id'] != $id;
    });
    saveServicesData(array_values($services));
    $success = "Service deleted successfully!";
}

// Handle add service
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_service'])) {
    $services = loadServicesData();
    
    // Get new ID
    $new_id = empty($services) ? 1 : max(array_column($services, 'id')) + 1;
    
    // Handle image upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../img/services/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = time() . '_' . uniqid() . '.' . $ext;
        $image = 'img/services/' . $filename;
        move_uploaded_file($_FILES['image']['tmp_name'], "../" . $image);
    }
    
    $new_service = [
        'id' => $new_id,
        'title' => $_POST['title'],
        'icon' => $_POST['icon'],
        'category' => $_POST['category'],
        'description' => $_POST['description'],
        'content' => $_POST['content'],
        'features' => array_filter(explode("\n", $_POST['features']), 'trim'),
        'image' => $image,
        'status' => $_POST['status']
    ];
    
    $services[] = $new_service;
    saveServicesData($services);
    $success = "Service added successfully with full details!";
}

// Handle update service
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_service'])) {
    $id = intval($_POST['service_id']);
    $services = loadServicesData();
    
    foreach ($services as &$service) {
        if ($service['id'] == $id) {
            $service['title'] = $_POST['title'];
            $service['icon'] = $_POST['icon'];
            $service['category'] = $_POST['category'];
            $service['description'] = $_POST['description'];
            $service['content'] = $_POST['content'];
            $service['features'] = array_filter(explode("\n", $_POST['features']), 'trim');
            $service['status'] = $_POST['status'];
            
            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $target_dir = "../img/services/";
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = time() . '_' . uniqid() . '.' . $ext;
                $service['image'] = 'img/services/' . $filename;
                move_uploaded_file($_FILES['image']['tmp_name'], "../" . $service['image']);
            }
            break;
        }
    }
    
    saveServicesData($services);
    $success = "Service updated successfully!";
}

$services = loadServicesData();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services - Admin Panel</title>
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
                        <i class="fas fa-concierge-bell me-2"></i> Manage Services
                    </h1>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-info" onclick="window.location.reload()">
                            <i class="fas fa-sync-alt me-1"></i> Refresh
                        </button>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addServiceModal">
                            <i class="fas fa-plus me-1"></i> Add New Service
                        </button>
                    </div>
                </div>
                
                <?php if (isset($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Bulk Actions Bar -->
                <div id="bulkActionsBar" class="card shadow-sm mb-3" style="display: none;">
                    <div class="card-body py-2">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <strong><span id="selectedCount">0</span> item(s) selected</strong>
                            </div>
                            <div class="col-md-8 text-end">
                                <div class="btn-group" role="group">
                                    <button type="button" id="bulkActivate" class="btn btn-sm btn-success">
                                        <i class="fas fa-check"></i> Activate
                                    </button>
                                    <button type="button" id="bulkDeactivate" class="btn btn-sm btn-warning">
                                        <i class="fas fa-eye-slash"></i> Deactivate
                                    </button>
                                    <button type="button" id="bulkDelete" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                    <button type="button" id="clearSelection" class="btn btn-sm btn-secondary">
                                        <i class="fas fa-times"></i> Clear
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Services Table -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">All Services</h6>
                    </div>
                    <div class="card-body">
                        <!-- Select All -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                                <label class="form-check-label" for="selectAll">
                                    <strong>Select All Services</strong>
                                </label>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="50">
                                            <input type="checkbox" class="form-check-input" id="selectAllTable" onclick="document.getElementById('selectAll').click()">
                                        </th>
                                        <th>ID</th>
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Icon</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($services)): ?>
                                        <?php foreach($services as $service): ?>
                                        <tr data-id="<?php echo $service['id']; ?>">
                                            <td>
                                                <input type="checkbox" class="form-check-input item-checkbox" value="<?php echo $service['id']; ?>">
                                            </td>
                                            <td><?php echo $service['id']; ?></td>
                                            <td>
                                                <?php if (!empty($service['image'])): ?>
                                                    <img src="../<?php echo htmlspecialchars($service['image']); ?>" 
                                                         alt="Service" 
                                                         style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                                <?php else: ?>
                                                    <div style="width: 60px; height: 60px; background: #e2e8f0; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($service['title']); ?></strong>
                                                <br><small class="text-muted"><?php echo htmlspecialchars(substr($service['description'], 0, 50)) . '...'; ?></small>
                                            </td>
                                            <td>
                                                <?php if (!empty($service['category'])): ?>
                                                    <span class="badge bg-info"><?php echo htmlspecialchars($service['category']); ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <i class="<?php echo htmlspecialchars($service['icon']); ?> fa-2x text-primary"></i>
                                            </td>
                                            <td>
                                                <?php if ($service['status'] == 'active'): ?>
                                                    <span class="badge bg-success">Active</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-info edit-service" 
                                                    data-id="<?php echo $service['id']; ?>"
                                                    data-title="<?php echo htmlspecialchars($service['title']); ?>"
                                                    data-icon="<?php echo htmlspecialchars($service['icon']); ?>"
                                                    data-category="<?php echo htmlspecialchars($service['category'] ?? ''); ?>"
                                                    data-description="<?php echo htmlspecialchars($service['description']); ?>"
                                                    data-content="<?php echo htmlspecialchars($service['content'] ?? ''); ?>"
                                                    data-features="<?php echo htmlspecialchars(is_array($service['features']) ? implode("\n", $service['features']) : ''); ?>"
                                                    data-status="<?php echo $service['status']; ?>"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editServiceModal">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <a href="../service-detail.php?id=<?php echo $service['id']; ?>" 
                                                   class="btn btn-sm btn-success" 
                                                   target="_blank"
                                                   title="View Service">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="?delete=<?php echo $service['id']; ?>" 
                                                   class="btn btn-sm btn-danger" 
                                                   onclick="return confirm('Delete this service?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center">No services found. Add your first service!</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Add Service Modal -->
    <div class="modal fade" id="addServiceModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-heading me-2"></i>Service Title *</label>
                                    <input type="text" name="title" class="form-control" placeholder="e.g. Wedding Catering" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-align-left me-2"></i>Short Description *</label>
                                    <textarea name="description" class="form-control" rows="2" placeholder="Brief summary (150-200 characters)" required></textarea>
                                    <small class="text-muted">This will appear on service cards</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-file-alt me-2"></i>Full Content</label>
                                    <textarea name="content" class="form-control" rows="8" placeholder="Write detailed service content here..."></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-list me-2"></i>Features (one per line)</label>
                                    <textarea name="features" class="form-control" rows="5" placeholder="Feature 1&#10;Feature 2&#10;Feature 3"></textarea>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-image me-2"></i>Service Image</label>
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                    <small class="text-muted">Recommended: 800x600px</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-icons me-2"></i>Icon Class *</label>
                                    <input type="text" name="icon" class="form-control" placeholder="fas fa-utensils" required>
                                    <small class="text-muted">FontAwesome icon class</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-tag me-2"></i>Category</label>
                                    <select name="category" class="form-select">
                                        <option value="">Select Category</option>
                                        <option value="Wedding">Wedding</option>
                                        <option value="Corporate">Corporate</option>
                                        <option value="Party">Party</option>
                                        <option value="Delivery">Delivery</option>
                                        <option value="Buffet">Buffet</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-toggle-on me-2"></i>Status</label>
                                    <select name="status" class="form-select">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_service" class="btn btn-primary">
                            <i class="fas fa-save"></i> Add Service
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Service Modal -->
    <div class="modal fade" id="editServiceModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Service</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="service_id" id="edit_service_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-heading me-2"></i>Service Title *</label>
                                    <input type="text" name="title" id="edit_title" class="form-control" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-align-left me-2"></i>Short Description *</label>
                                    <textarea name="description" id="edit_description" class="form-control" rows="2" required></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-file-alt me-2"></i>Full Content</label>
                                    <textarea name="content" id="edit_content" class="form-control" rows="8"></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-list me-2"></i>Features (one per line)</label>
                                    <textarea name="features" id="edit_features" class="form-control" rows="5"></textarea>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-image me-2"></i>Service Image</label>
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                    <small class="text-muted">Leave empty to keep current</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-icons me-2"></i>Icon Class *</label>
                                    <input type="text" name="icon" id="edit_icon" class="form-control" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-tag me-2"></i>Category</label>
                                    <select name="category" id="edit_category" class="form-select">
                                        <option value="">Select Category</option>
                                        <option value="Wedding">Wedding</option>
                                        <option value="Corporate">Corporate</option>
                                        <option value="Party">Party</option>
                                        <option value="Delivery">Delivery</option>
                                        <option value="Buffet">Buffet</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-toggle-on me-2"></i>Status</label>
                                    <select name="status" id="edit_status" class="form-select">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="update_service" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Service
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Edit service functionality
        document.querySelectorAll('.edit-service').forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('edit_service_id').value = this.dataset.id;
                document.getElementById('edit_title').value = this.dataset.title;
                document.getElementById('edit_icon').value = this.dataset.icon;
                document.getElementById('edit_category').value = this.dataset.category;
                document.getElementById('edit_description').value = this.dataset.description;
                document.getElementById('edit_content').value = this.dataset.content;
                document.getElementById('edit_features').value = this.dataset.features;
                document.getElementById('edit_status').value = this.dataset.status;
            });
        });

        // Bulk actions functionality
        const selectAll = document.getElementById('selectAll');
        const selectAllTable = document.getElementById('selectAllTable');
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        const bulkActionsBar = document.getElementById('bulkActionsBar');
        const selectedCount = document.getElementById('selectedCount');

        function updateBulkActions() {
            const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
            if (checkedBoxes.length > 0) {
                bulkActionsBar.style.display = 'block';
                selectedCount.textContent = checkedBoxes.length;
            } else {
                bulkActionsBar.style.display = 'none';
            }
        }

        selectAll.addEventListener('change', function() {
            itemCheckboxes.forEach(cb => cb.checked = this.checked);
            selectAllTable.checked = this.checked;
            updateBulkActions();
        });

        selectAllTable.addEventListener('change', function() {
            itemCheckboxes.forEach(cb => cb.checked = this.checked);
            selectAll.checked = this.checked;
            updateBulkActions();
        });

        itemCheckboxes.forEach(cb => {
            cb.addEventListener('change', updateBulkActions);
        });

        document.getElementById('clearSelection').addEventListener('click', function() {
            itemCheckboxes.forEach(cb => cb.checked = false);
            selectAll.checked = false;
            selectAllTable.checked = false;
            updateBulkActions();
        });

        // Bulk activate
        document.getElementById('bulkActivate').addEventListener('click', function() {
            const checkedBoxes = Array.from(document.querySelectorAll('.item-checkbox:checked'));
            if (checkedBoxes.length > 0 && confirm('Activate ' + checkedBoxes.length + ' service(s)?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                checkedBoxes.forEach(cb => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'selected_ids[]';
                    input.value = cb.value;
                    form.appendChild(input);
                });
                const submitBtn = document.createElement('input');
                submitBtn.type = 'hidden';
                submitBtn.name = 'bulk_activate';
                submitBtn.value = '1';
                form.appendChild(submitBtn);
                document.body.appendChild(form);
                form.submit();
            }
        });

        // Bulk deactivate
        document.getElementById('bulkDeactivate').addEventListener('click', function() {
            const checkedBoxes = Array.from(document.querySelectorAll('.item-checkbox:checked'));
            if (checkedBoxes.length > 0 && confirm('Deactivate ' + checkedBoxes.length + ' service(s)?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                checkedBoxes.forEach(cb => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'selected_ids[]';
                    input.value = cb.value;
                    form.appendChild(input);
                });
                const submitBtn = document.createElement('input');
                submitBtn.type = 'hidden';
                submitBtn.name = 'bulk_deactivate';
                submitBtn.value = '1';
                form.appendChild(submitBtn);
                document.body.appendChild(form);
                form.submit();
            }
        });

        // Bulk delete
        document.getElementById('bulkDelete').addEventListener('click', function() {
            const checkedBoxes = Array.from(document.querySelectorAll('.item-checkbox:checked'));
            if (checkedBoxes.length > 0 && confirm('Delete ' + checkedBoxes.length + ' service(s)? This cannot be undone!')) {
                const form = document.createElement('form');
                form.method = 'POST';
                checkedBoxes.forEach(cb => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'selected_ids[]';
                    input.value = cb.value;
                    form.appendChild(input);
                });
                const submitBtn = document.createElement('input');
                submitBtn.type = 'hidden';
                submitBtn.name = 'bulk_delete';
                submitBtn.value = '1';
                form.appendChild(submitBtn);
                document.body.appendChild(form);
                form.submit();
            }
        });
    </script>
</body>
</html>
