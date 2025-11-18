<?php
// Include authentication check
require_once 'includes/auth-check.php';

// Load packages and FAQs data
$data_file = 'data/pricing.json';
$data = file_exists($data_file) ? json_decode(file_get_contents($data_file), true) : [
    'packages' => [],
    'faqs' => []
];

// Handle package operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle bulk delete packages
    if (isset($_POST['bulk_delete']) && isset($_POST['selected_ids']) && isset($_POST['type']) && $_POST['type'] == 'package') {
        $selected_ids = array_map('intval', $_POST['selected_ids']);
        $data['packages'] = array_filter($data['packages'], function($p) use ($selected_ids) {
            return !in_array($p['id'], $selected_ids);
        });
        $data['packages'] = array_values($data['packages']);
        file_put_contents($data_file, json_encode($data, JSON_PRETTY_PRINT));
        $success = count($selected_ids) . " package(s) deleted successfully!";
    }
    
    // Handle bulk activate packages
    if (isset($_POST['bulk_publish']) && isset($_POST['selected_ids']) && isset($_POST['type']) && $_POST['type'] == 'package') {
        $selected_ids = array_map('intval', $_POST['selected_ids']);
        foreach ($data['packages'] as &$pkg) {
            if (in_array($pkg['id'], $selected_ids)) {
                $pkg['status'] = 'active';
            }
        }
        file_put_contents($data_file, json_encode($data, JSON_PRETTY_PRINT));
        $success = count($selected_ids) . " package(s) activated successfully!";
    }
    
    // Handle bulk deactivate packages
    if (isset($_POST['bulk_unpublish']) && isset($_POST['selected_ids']) && isset($_POST['type']) && $_POST['type'] == 'package') {
        $selected_ids = array_map('intval', $_POST['selected_ids']);
        foreach ($data['packages'] as &$pkg) {
            if (in_array($pkg['id'], $selected_ids)) {
                $pkg['status'] = 'inactive';
            }
        }
        file_put_contents($data_file, json_encode($data, JSON_PRETTY_PRINT));
        $success = count($selected_ids) . " package(s) deactivated successfully!";
    }
    
    if (isset($_POST['add_package'])) {
        $new_id = empty($data['packages']) ? 1 : max(array_column($data['packages'], 'id')) + 1;
        
        $data['packages'][] = [
            'id' => $new_id,
            'name' => $_POST['name'],
            'price' => $_POST['price'],
            'description' => $_POST['description'],
            'features' => explode("\n", trim($_POST['features'])),
            'popular' => isset($_POST['popular']) ? true : false,
            'status' => $_POST['status']
        ];
        
        file_put_contents($data_file, json_encode($data, JSON_PRETTY_PRINT));
        $success = "Package added successfully!";
    }
    
    if (isset($_POST['edit_package'])) {
        $id = (int)$_POST['id'];
        foreach ($data['packages'] as &$pkg) {
            if ($pkg['id'] == $id) {
                $pkg['name'] = $_POST['name'];
                $pkg['price'] = $_POST['price'];
                $pkg['description'] = $_POST['description'];
                $pkg['features'] = explode("\n", trim($_POST['features']));
                $pkg['popular'] = isset($_POST['popular']) ? true : false;
                $pkg['status'] = $_POST['status'];
                break;
            }
        }
        file_put_contents($data_file, json_encode($data, JSON_PRETTY_PRINT));
        $success = "Package updated successfully!";
    }
    
    if (isset($_POST['delete_package'])) {
        $id = (int)$_POST['id'];
        $data['packages'] = array_filter($data['packages'], function($p) use ($id) {
            return $p['id'] != $id;
        });
        $data['packages'] = array_values($data['packages']);
        file_put_contents($data_file, json_encode($data, JSON_PRETTY_PRINT));
        $success = "Package deleted successfully!";
    }
    
    // FAQ operations
    if (isset($_POST['add_faq'])) {
        $new_id = empty($data['faqs']) ? 1 : max(array_column($data['faqs'], 'id')) + 1;
        
        $data['faqs'][] = [
            'id' => $new_id,
            'question' => $_POST['question'],
            'answer' => $_POST['answer'],
            'status' => $_POST['status']
        ];
        
        file_put_contents($data_file, json_encode($data, JSON_PRETTY_PRINT));
        $success = "FAQ added successfully!";
    }
    
    if (isset($_POST['edit_faq'])) {
        $id = (int)$_POST['id'];
        foreach ($data['faqs'] as &$faq) {
            if ($faq['id'] == $id) {
                $faq['question'] = $_POST['question'];
                $faq['answer'] = $_POST['answer'];
                $faq['status'] = $_POST['status'];
                break;
            }
        }
        file_put_contents($data_file, json_encode($data, JSON_PRETTY_PRINT));
        $success = "FAQ updated successfully!";
    }
    
    if (isset($_POST['delete_faq'])) {
        $id = (int)$_POST['id'];
        $data['faqs'] = array_filter($data['faqs'], function($f) use ($id) {
            return $f['id'] != $id;
        });
        $data['faqs'] = array_values($data['faqs']);
        file_put_contents($data_file, json_encode($data, JSON_PRETTY_PRINT));
        $success = "FAQ deleted successfully!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Packages & FAQs - Altaf Catering Admin</title>
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
                        <i class="fas fa-tags me-2"></i> Packages & FAQs Management
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
                
                <!-- Packages Section -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-box"></i> Packages</h5>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPackageModal">
                            <i class="fas fa-plus"></i> Add Package
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- Bulk Actions Bar for Packages -->
                        <div id="bulkActionsBar" class="alert alert-info mb-3" style="display: none;">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <strong><span id="selectedCount">0</span> package(s) selected</strong>
                                </div>
                                <div class="col-md-8 text-end">
                                    <div class="btn-group" role="group">
                                        <button type="button" onclick="bulkActionPackages('publish')" class="btn btn-sm btn-success">
                                            <i class="fas fa-check"></i> Activate
                                        </button>
                                        <button type="button" onclick="bulkActionPackages('unpublish')" class="btn btn-sm btn-warning">
                                            <i class="fas fa-ban"></i> Deactivate
                                        </button>
                                        <button type="button" onclick="bulkActionPackages('delete')" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                        <button type="button" onclick="clearPackageSelection()" class="btn btn-sm btn-secondary">
                                            <i class="fas fa-times"></i> Clear
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row g-3">
                            <?php foreach ($data['packages'] as $package): ?>
                            <div class="col-md-4 mb-3">
                                <div class="card h-100 <?php echo $package['popular'] ? 'border-primary' : ''; ?>">
                                    <div class="position-absolute top-0 start-0 m-2">
                                        <input type="checkbox" class="form-check-input package-checkbox" value="<?php echo $package['id']; ?>" onchange="updatePackageSelection()">
                                    </div>
                                    <?php if ($package['popular']): ?>
                                    <div class="card-header bg-primary text-white text-center">
                                        <i class="fas fa-star"></i> Most Popular
                                    </div>
                                    <?php endif; ?>
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($package['name']); ?></h5>
                                        <h3 class="text-primary">Rs. <?php echo number_format($package['price']); ?></h3>
                                        <p class="text-muted"><?php echo htmlspecialchars($package['description']); ?></p>
                                        <ul class="list-unstyled">
                                            <?php foreach ($package['features'] as $feature): ?>
                                            <li><i class="fas fa-check text-success"></i> <?php echo htmlspecialchars($feature); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <span class="badge bg-<?php echo $package['status'] == 'active' ? 'success' : 'secondary'; ?>">
                                            <?php echo ucfirst($package['status']); ?>
                                        </span>
                                    </div>
                                    <div class="card-footer">
                                        <button class="btn btn-sm btn-info" onclick='editPackage(<?php echo json_encode($package); ?>)'>
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="id" value="<?php echo $package['id']; ?>">
                                            <button type="submit" name="delete_package" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <!-- FAQs Section -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-question-circle"></i> FAQs</h5>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addFaqModal">
                            <i class="fas fa-plus"></i> Add FAQ
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="accordion" id="faqAccordion">
                            <?php foreach ($data['faqs'] as $index => $faq): ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq<?php echo $faq['id']; ?>">
                                        <?php echo htmlspecialchars($faq['question']); ?>
                                        <span class="badge bg-<?php echo $faq['status'] == 'active' ? 'success' : 'secondary'; ?> ms-2">
                                            <?php echo ucfirst($faq['status']); ?>
                                        </span>
                                    </button>
                                </h2>
                                <div id="faq<?php echo $faq['id']; ?>" class="accordion-collapse collapse">
                                    <div class="accordion-body">
                                        <p><?php echo nl2br(htmlspecialchars($faq['answer'])); ?></p>
                                        <button class="btn btn-sm btn-info" onclick='editFaq(<?php echo json_encode($faq); ?>)'>
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="id" value="<?php echo $faq['id']; ?>">
                                            <button type="submit" name="delete_faq" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <!-- Add Package Modal -->
    <div class="modal fade" id="addPackageModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Package</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Package Name *</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Price (Rs.) *</label>
                                <input type="number" name="price" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Features (one per line) *</label>
                            <textarea name="features" class="form-control" rows="5" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="popular" class="form-check-input" id="popular">
                                    <label class="form-check-label" for="popular">Mark as Popular</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status *</label>
                                <select name="status" class="form-select" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add_package" class="btn btn-primary">Add Package</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Edit Package Modal -->
    <div class="modal fade" id="editPackageModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Package</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <input type="hidden" name="id" id="edit_pkg_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Package Name *</label>
                                <input type="text" name="name" id="edit_pkg_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Price (Rs.) *</label>
                                <input type="number" name="price" id="edit_pkg_price" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="edit_pkg_description" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Features (one per line) *</label>
                            <textarea name="features" id="edit_pkg_features" class="form-control" rows="5" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="popular" class="form-check-input" id="edit_pkg_popular">
                                    <label class="form-check-label" for="edit_pkg_popular">Mark as Popular</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status *</label>
                                <select name="status" id="edit_pkg_status" class="form-select" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="edit_package" class="btn btn-primary">Update Package</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Add FAQ Modal -->
    <div class="modal fade" id="addFaqModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add FAQ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Question *</label>
                            <input type="text" name="question" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Answer *</label>
                            <textarea name="answer" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status *</label>
                            <select name="status" class="form-select" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add_faq" class="btn btn-primary">Add FAQ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Edit FAQ Modal -->
    <div class="modal fade" id="editFaqModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit FAQ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <input type="hidden" name="id" id="edit_faq_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Question *</label>
                            <input type="text" name="question" id="edit_faq_question" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Answer *</label>
                            <textarea name="answer" id="edit_faq_answer" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status *</label>
                            <select name="status" id="edit_faq_status" class="form-select" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="edit_faq" class="btn btn-primary">Update FAQ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editPackage(pkg) {
            document.getElementById('edit_pkg_id').value = pkg.id;
            document.getElementById('edit_pkg_name').value = pkg.name;
            document.getElementById('edit_pkg_price').value = pkg.price;
            document.getElementById('edit_pkg_description').value = pkg.description;
            document.getElementById('edit_pkg_features').value = pkg.features.join('\n');
            document.getElementById('edit_pkg_popular').checked = pkg.popular;
            document.getElementById('edit_pkg_status').value = pkg.status;
            new bootstrap.Modal(document.getElementById('editPackageModal')).show();
        }
        
        function editFaq(faq) {
            document.getElementById('edit_faq_id').value = faq.id;
            document.getElementById('edit_faq_question').value = faq.question;
            document.getElementById('edit_faq_answer').value = faq.answer;
            document.getElementById('edit_faq_status').value = faq.status;
            new bootstrap.Modal(document.getElementById('editFaqModal')).show();
        }
        
        // Bulk actions for packages
        function updatePackageSelection() {
            const checkboxes = document.querySelectorAll('.package-checkbox:checked');
            const count = checkboxes.length;
            document.getElementById('selectedCount').textContent = count;
            document.getElementById('bulkActionsBar').style.display = count > 0 ? 'block' : 'none';
        }
        
        function clearPackageSelection() {
            document.querySelectorAll('.package-checkbox').forEach(cb => cb.checked = false);
            updatePackageSelection();
        }
        
        function bulkActionPackages(action) {
            const checkboxes = document.querySelectorAll('.package-checkbox:checked');
            const ids = Array.from(checkboxes).map(cb => cb.value);
            
            if (ids.length === 0) {
                alert('Please select at least one package');
                return;
            }
            
            let confirmMsg = '';
            if (action === 'delete') {
                confirmMsg = `Are you sure you want to delete ${ids.length} package(s)?`;
            } else if (action === 'publish') {
                confirmMsg = `Activate ${ids.length} package(s)?`;
            } else if (action === 'unpublish') {
                confirmMsg = `Deactivate ${ids.length} package(s)?`;
            }
            
            if (!confirm(confirmMsg)) return;
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `<input type="hidden" name="type" value="package">`;
            ids.forEach(id => {
                form.innerHTML += `<input type="hidden" name="selected_ids[]" value="${id}">`;
            });
            form.innerHTML += `<input type="hidden" name="bulk_${action}" value="1">`;
            document.body.appendChild(form);
            form.submit();
        }
    </script>
    <script src="js/admin-enhancements.js"></script>
</body>
</html>
