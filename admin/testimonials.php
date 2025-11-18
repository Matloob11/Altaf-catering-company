<?php
// Include authentication check
require_once 'includes/auth-check.php';

// Load testimonials data
$testimonials_file = 'data/testimonials.json';
$testimonials = file_exists($testimonials_file) ? json_decode(file_get_contents($testimonials_file), true) : [];

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle bulk delete
    if (isset($_POST['bulk_delete']) && isset($_POST['selected_ids'])) {
        $selected_ids = array_map('intval', $_POST['selected_ids']);
        $testimonials = array_filter($testimonials, function($t) use ($selected_ids) {
            return !in_array($t['id'], $selected_ids);
        });
        $testimonials = array_values($testimonials);
        file_put_contents($testimonials_file, json_encode($testimonials, JSON_PRETTY_PRINT));
        $success = count($selected_ids) . " testimonial(s) deleted successfully!";
    }
    
    // Handle bulk publish
    if (isset($_POST['bulk_publish']) && isset($_POST['selected_ids'])) {
        $selected_ids = array_map('intval', $_POST['selected_ids']);
        foreach ($testimonials as &$testimonial) {
            if (in_array($testimonial['id'], $selected_ids)) {
                $testimonial['status'] = 'published';
            }
        }
        file_put_contents($testimonials_file, json_encode($testimonials, JSON_PRETTY_PRINT));
        $success = count($selected_ids) . " testimonial(s) published successfully!";
    }
    
    // Handle bulk unpublish
    if (isset($_POST['bulk_unpublish']) && isset($_POST['selected_ids'])) {
        $selected_ids = array_map('intval', $_POST['selected_ids']);
        foreach ($testimonials as &$testimonial) {
            if (in_array($testimonial['id'], $selected_ids)) {
                $testimonial['status'] = 'draft';
            }
        }
        file_put_contents($testimonials_file, json_encode($testimonials, JSON_PRETTY_PRINT));
        $success = count($selected_ids) . " testimonial(s) unpublished successfully!";
    }
    
    if (isset($_POST['add_testimonial'])) {
        $new_id = empty($testimonials) ? 1 : max(array_column($testimonials, 'id')) + 1;
        
        $new_testimonial = [
            'id' => $new_id,
            'name' => $_POST['name'],
            'position' => $_POST['position'],
            'company' => $_POST['company'],
            'rating' => (int)$_POST['rating'],
            'message' => $_POST['message'],
            'image' => $_POST['image'],
            'status' => $_POST['status'],
            'date' => date('Y-m-d')
        ];
        
        $testimonials[] = $new_testimonial;
        file_put_contents($testimonials_file, json_encode($testimonials, JSON_PRETTY_PRINT));
        $success = "Testimonial added successfully!";
    }
    
    if (isset($_POST['edit_testimonial'])) {
        $id = (int)$_POST['id'];
        foreach ($testimonials as &$testimonial) {
            if ($testimonial['id'] == $id) {
                $testimonial['name'] = $_POST['name'];
                $testimonial['position'] = $_POST['position'];
                $testimonial['company'] = $_POST['company'];
                $testimonial['rating'] = (int)$_POST['rating'];
                $testimonial['message'] = $_POST['message'];
                $testimonial['image'] = $_POST['image'];
                $testimonial['status'] = $_POST['status'];
                break;
            }
        }
        file_put_contents($testimonials_file, json_encode($testimonials, JSON_PRETTY_PRINT));
        $success = "Testimonial updated successfully!";
    }
    
    if (isset($_POST['delete_testimonial'])) {
        $id = (int)$_POST['id'];
        $testimonials = array_filter($testimonials, function($t) use ($id) {
            return $t['id'] != $id;
        });
        $testimonials = array_values($testimonials);
        file_put_contents($testimonials_file, json_encode($testimonials, JSON_PRETTY_PRINT));
        $success = "Testimonial deleted successfully!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testimonials Management - Altaf Catering Admin</title>
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
                        <i class="fas fa-star me-2"></i> Testimonials Management
                    </h1>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-info" onclick="window.location.reload()">
                            <i class="fas fa-sync-alt me-1"></i> Refresh
                        </button>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                            <i class="fas fa-plus me-1"></i> Add New Testimonial
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
                                    <button type="button" id="bulkPublish" class="btn btn-sm btn-success">
                                        <i class="fas fa-check"></i> Publish
                                    </button>
                                    <button type="button" id="bulkUnpublish" class="btn btn-sm btn-warning">
                                        <i class="fas fa-eye-slash"></i> Unpublish
                                    </button>
                                    <button type="button" id="bulkExport" class="btn btn-sm btn-info">
                                        <i class="fas fa-download"></i> Export
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

                <!-- Select All -->
                <div class="card shadow-sm mb-3">
                    <div class="card-body py-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="selectAll">
                            <label class="form-check-label" for="selectAll">
                                <strong>Select All Testimonials</strong>
                            </label>
                        </div>
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
                                <th>Name</th>
                                <th>Position</th>
                                <th>Rating</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($testimonials as $testimonial): ?>
                            <tr data-id="<?php echo $testimonial['id']; ?>">
                                <td>
                                    <input type="checkbox" class="form-check-input item-checkbox" value="<?php echo $testimonial['id']; ?>">
                                </td>
                                <td><?php echo $testimonial['id']; ?></td>
                                <td>
                                    <img src="../<?php echo $testimonial['image']; ?>" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                </td>
                                <td><?php echo htmlspecialchars($testimonial['name']); ?></td>
                                <td><?php echo htmlspecialchars($testimonial['position']); ?></td>
                                <td>
                                    <?php for($i = 0; $i < $testimonial['rating']; $i++): ?>
                                        <i class="fas fa-star text-warning"></i>
                                    <?php endfor; ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo $testimonial['status'] == 'published' ? 'success' : 'warning'; ?>">
                                        <?php echo ucfirst($testimonial['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo $testimonial['date']; ?></td>
                                <td>
                                    <button class="btn btn-sm btn-info" onclick='editTestimonial(<?php echo json_encode($testimonial); ?>)'>
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="id" value="<?php echo $testimonial['id']; ?>">
                                        <button type="submit" name="delete_testimonial" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
    
    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Testimonial</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Name *</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Position *</label>
                                <input type="text" name="position" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Company</label>
                                <input type="text" name="company" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Rating *</label>
                                <select name="rating" class="form-select" required>
                                    <option value="5">5 Stars</option>
                                    <option value="4">4 Stars</option>
                                    <option value="3">3 Stars</option>
                                    <option value="2">2 Stars</option>
                                    <option value="1">1 Star</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Message *</label>
                            <textarea name="message" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Image *</label>
                                <input type="file" id="add_testimonial_image" class="form-control mb-2" accept="image/*">
                                <input type="hidden" name="image" id="add_testimonial_path" required>
                                <div id="add_testimonial_preview" class="mt-2"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status *</label>
                                <select name="status" class="form-select" required>
                                    <option value="published">Published</option>
                                    <option value="draft">Draft</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add_testimonial" class="btn btn-primary">Add Testimonial</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Testimonial</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="editForm">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Name *</label>
                                <input type="text" name="name" id="edit_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Position *</label>
                                <input type="text" name="position" id="edit_position" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Company</label>
                                <input type="text" name="company" id="edit_company" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Rating *</label>
                                <select name="rating" id="edit_rating" class="form-select" required>
                                    <option value="5">5 Stars</option>
                                    <option value="4">4 Stars</option>
                                    <option value="3">3 Stars</option>
                                    <option value="2">2 Stars</option>
                                    <option value="1">1 Star</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Message *</label>
                            <textarea name="message" id="edit_message" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Image *</label>
                                <input type="file" id="edit_testimonial_image" class="form-control mb-2" accept="image/*">
                                <input type="hidden" name="image" id="edit_image" required>
                                <div id="edit_testimonial_preview" class="mt-2"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status *</label>
                                <select name="status" id="edit_status" class="form-select" required>
                                    <option value="published">Published</option>
                                    <option value="draft">Draft</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="edit_testimonial" class="btn btn-primary">Update Testimonial</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/bulk-actions.js"></script>
    <script>
        function handleFileUpload(fileInput, pathInput, previewDiv, uploadType) {
            const file = fileInput.files[0];
            if (!file) return;
            
            const reader = new FileReader();
            reader.onload = function(e) {
                previewDiv.innerHTML = '<img src="' + e.target.result + '" class="img-thumbnail" style="max-width: 150px;">';
            };
            reader.readAsDataURL(file);
            
            const formData = new FormData();
            formData.append('file', file);
            formData.append('type', uploadType);
            
            fetch('upload.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    pathInput.value = data.path;
                    alert('File uploaded successfully!');
                } else {
                    alert('Upload failed: ' + data.message);
                }
            });
        }
        
        document.getElementById('add_testimonial_image').addEventListener('change', function() {
            handleFileUpload(this, document.getElementById('add_testimonial_path'), 
                document.getElementById('add_testimonial_preview'), 'testimonial');
        });
        
        document.getElementById('edit_testimonial_image').addEventListener('change', function() {
            handleFileUpload(this, document.getElementById('edit_image'), 
                document.getElementById('edit_testimonial_preview'), 'testimonial');
        });
        
        function editTestimonial(testimonial) {
            document.getElementById('edit_id').value = testimonial.id;
            document.getElementById('edit_name').value = testimonial.name;
            document.getElementById('edit_position').value = testimonial.position;
            document.getElementById('edit_company').value = testimonial.company;
            document.getElementById('edit_rating').value = testimonial.rating;
            document.getElementById('edit_message').value = testimonial.message;
            document.getElementById('edit_image').value = testimonial.image;
            document.getElementById('edit_status').value = testimonial.status;
            document.getElementById('edit_testimonial_preview').innerHTML = 
                '<img src="../' + testimonial.image + '" class="img-thumbnail" style="max-width: 150px;">';
            new bootstrap.Modal(document.getElementById('editModal')).show();
        }
    </script>
    <script src="js/admin-enhancements.js"></script>
</body>
</html>
