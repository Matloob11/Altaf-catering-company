<?php
// Include authentication check
require_once 'includes/auth-check.php';
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}

$data_file = 'data/gallery.json';

// Load gallery data
function loadGalleryData() {
    global $data_file;
    if (file_exists($data_file)) {
        return json_decode(file_get_contents($data_file), true);
    }
    return [];
}

// Save gallery data
function saveGalleryData($data) {
    global $data_file;
    file_put_contents($data_file, json_encode($data, JSON_PRETTY_PRINT));
}

// Handle bulk delete
if (isset($_POST['bulk_delete']) && isset($_POST['selected_ids'])) {
    $selected_ids = array_map('intval', $_POST['selected_ids']);
    $gallery = loadGalleryData();
    $gallery = array_filter($gallery, function($item) use ($selected_ids) {
        return !in_array($item['id'], $selected_ids);
    });
    saveGalleryData(array_values($gallery));
    $success = count($selected_ids) . " gallery item(s) deleted successfully!";
}

// Handle bulk publish
if (isset($_POST['bulk_publish']) && isset($_POST['selected_ids'])) {
    $selected_ids = array_map('intval', $_POST['selected_ids']);
    $gallery = loadGalleryData();
    foreach ($gallery as &$item) {
        if (in_array($item['id'], $selected_ids)) {
            $item['status'] = 'published';
        }
    }
    saveGalleryData($gallery);
    $success = count($selected_ids) . " gallery item(s) published successfully!";
}

// Handle bulk unpublish
if (isset($_POST['bulk_unpublish']) && isset($_POST['selected_ids'])) {
    $selected_ids = array_map('intval', $_POST['selected_ids']);
    $gallery = loadGalleryData();
    foreach ($gallery as &$item) {
        if (in_array($item['id'], $selected_ids)) {
            $item['status'] = 'draft';
        }
    }
    saveGalleryData($gallery);
    $success = count($selected_ids) . " gallery item(s) unpublished successfully!";
}

// Handle single delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $gallery = loadGalleryData();
    $gallery = array_filter($gallery, function($item) use ($id) {
        return $item['id'] != $id;
    });
    saveGalleryData(array_values($gallery));
    $success = "Gallery item deleted successfully!";
}

// Handle add gallery item
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_gallery'])) {
    $gallery = loadGalleryData();
    
    // Get new ID
    $new_id = empty($gallery) ? 1 : max(array_column($gallery, 'id')) + 1;
    
    // Handle image upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../img/gallery/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = time() . '_' . uniqid() . '.' . $ext;
        $image = 'img/gallery/' . $filename;
        move_uploaded_file($_FILES['image']['tmp_name'], "../" . $image);
    }
    
    $new_item = [
        'id' => $new_id,
        'title' => $_POST['title'],
        'description' => $_POST['description'],
        'category' => $_POST['category'],
        'image' => $image,
        'status' => $_POST['status'],
        'date' => date('Y-m-d')
    ];
    
    $gallery[] = $new_item;
    saveGalleryData($gallery);
    $success = "Gallery item added successfully!";
}

// Handle update gallery item
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_gallery'])) {
    $id = intval($_POST['gallery_id']);
    $gallery = loadGalleryData();
    
    foreach ($gallery as &$item) {
        if ($item['id'] == $id) {
            $item['title'] = $_POST['title'];
            $item['description'] = $_POST['description'];
            $item['category'] = $_POST['category'];
            $item['status'] = $_POST['status'];
            
            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $target_dir = "../img/gallery/";
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = time() . '_' . uniqid() . '.' . $ext;
                $item['image'] = 'img/gallery/' . $filename;
                move_uploaded_file($_FILES['image']['tmp_name'], "../" . $item['image']);
            }
            break;
        }
    }
    
    saveGalleryData($gallery);
    $success = "Gallery item updated successfully!";
}

$gallery = loadGalleryData();

// Debug: Check if data loaded
if (empty($gallery)) {
    error_log("Gallery data is empty! File: " . $data_file);
    error_log("File exists: " . (file_exists($data_file) ? "YES" : "NO"));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Gallery - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link href="css/admin-unified.css" rel="stylesheet"></head>
<body>
    <?php
// Include authentication check
require_once 'includes/auth-check.php'; include 'includes/header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php
// Include authentication check
require_once 'includes/auth-check.php'; include 'includes/sidebar.php'; ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
                    <h1 class="h2 gradient-text">
                        <i class="fas fa-images me-2"></i> Manage Gallery (Social & Professional)
                        <small class="badge bg-info"><?php
// Include authentication check
require_once 'includes/auth-check.php'; echo count($gallery); ?> items</small>
                    </h1>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-info" onclick="window.location.reload()">
                            <i class="fas fa-sync-alt me-1"></i> Refresh
                        </button>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGalleryModal">
                            <i class="fas fa-plus me-1"></i> Add New Image
                        </button>
                    </div>
                </div>
                
                <!-- Debug Info -->
                <?php
// Include authentication check
require_once 'includes/auth-check.php'; if (count($gallery) == 0): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Debug Info:</strong> No gallery items loaded. 
                    File: <?php
// Include authentication check
require_once 'includes/auth-check.php'; echo $data_file; ?> | 
                    Exists: <?php
// Include authentication check
require_once 'includes/auth-check.php'; echo file_exists($data_file) ? 'YES' : 'NO'; ?>
                    <br>
                    <a href="test-gallery-data.php" class="btn btn-sm btn-warning mt-2">Run Diagnostic Test</a>
                </div>
                <?php
// Include authentication check
require_once 'includes/auth-check.php'; endif; ?>
                
                <?php
// Include authentication check
require_once 'includes/auth-check.php'; if (isset($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i><?php
// Include authentication check
require_once 'includes/auth-check.php'; echo $success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php
// Include authentication check
require_once 'includes/auth-check.php'; endif; ?>

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
                                <strong>Select All Items</strong>
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Gallery Grid -->
                <div class="row g-4">
                    <?php
// Include authentication check
require_once 'includes/auth-check.php'; if (!empty($gallery)): ?>
                        <?php
// Include authentication check
require_once 'includes/auth-check.php'; foreach($gallery as $item): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card shadow position-relative" data-id="<?php
// Include authentication check
require_once 'includes/auth-check.php'; echo $item['id']; ?>">
                                <input type="checkbox" class="form-check-input item-checkbox position-absolute" 
                                       value="<?php
// Include authentication check
require_once 'includes/auth-check.php'; echo $item['id']; ?>" 
                                       style="top: 10px; left: 10px; z-index: 10; width: 20px; height: 20px; cursor: pointer;">
                                <img src="../<?php
// Include authentication check
require_once 'includes/auth-check.php'; echo $item['image']; ?>" 
                                     class="card-img-top" 
                                     alt="<?php
// Include authentication check
require_once 'includes/auth-check.php'; echo htmlspecialchars($item['title']); ?>"
                                     style="height: 250px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title"><?php
// Include authentication check
require_once 'includes/auth-check.php'; echo htmlspecialchars($item['title']); ?></h5>
                                    <p class="card-text text-muted"><?php
// Include authentication check
require_once 'includes/auth-check.php'; echo htmlspecialchars($item['description']); ?></p>
                                    <div class="mb-2">
                                        <span class="badge bg-info"><?php
// Include authentication check
require_once 'includes/auth-check.php'; echo ucfirst($item['category']); ?></span>
                                        <?php
// Include authentication check
require_once 'includes/auth-check.php'; if ($item['status'] == 'published'): ?>
                                            <span class="badge bg-success">Published</span>
                                        <?php
// Include authentication check
require_once 'includes/auth-check.php'; else: ?>
                                            <span class="badge bg-warning">Draft</span>
                                        <?php
// Include authentication check
require_once 'includes/auth-check.php'; endif; ?>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-info flex-fill edit-gallery"
                                            data-id="<?php
// Include authentication check
require_once 'includes/auth-check.php'; echo $item['id']; ?>"
                                            data-title="<?php
// Include authentication check
require_once 'includes/auth-check.php'; echo htmlspecialchars($item['title']); ?>"
                                            data-description="<?php
// Include authentication check
require_once 'includes/auth-check.php'; echo htmlspecialchars($item['description']); ?>"
                                            data-category="<?php
// Include authentication check
require_once 'includes/auth-check.php'; echo $item['category']; ?>"
                                            data-status="<?php
// Include authentication check
require_once 'includes/auth-check.php'; echo $item['status']; ?>"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editGalleryModal">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <a href="?delete=<?php
// Include authentication check
require_once 'includes/auth-check.php'; echo $item['id']; ?>" 
                                           class="btn btn-sm btn-danger flex-fill"
                                           onclick="return confirm('Delete this image?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
// Include authentication check
require_once 'includes/auth-check.php'; endforeach; ?>
                    <?php
// Include authentication check
require_once 'includes/auth-check.php'; else: ?>
                        <div class="col-12">
                            <div class="alert alert-info">No gallery items found. Add your first image!</div>
                        </div>
                    <?php
// Include authentication check
require_once 'includes/auth-check.php'; endif; ?>
                </div>
            </main>
        </div>
    </div>

    <!-- Add Gallery Modal -->
    <div class="modal fade" id="addGalleryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Gallery Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3" required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select" required>
                                <optgroup label="Social Events">
                                    <option value="wedding">Wedding</option>
                                    <option value="birthday">Birthday</option>
                                    <option value="anniversary">Anniversary</option>
                                    <option value="family-gathering">Family Gathering</option>
                                    <option value="engagement">Engagement</option>
                                </optgroup>
                                <optgroup label="Professional Events">
                                    <option value="corporate">Corporate Event</option>
                                    <option value="conference">Conference</option>
                                    <option value="seminar">Seminar</option>
                                    <option value="business-meeting">Business Meeting</option>
                                    <option value="product-launch">Product Launch</option>
                                </optgroup>
                                <optgroup label="Other">
                                    <option value="food">Food Photography</option>
                                    <option value="setup">Setup & Decor</option>
                                    <option value="outdoor">Outdoor Event</option>
                                    <option value="other">Other</option>
                                </optgroup>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_gallery" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Image
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Gallery Modal -->
    <div class="modal fade" id="editGalleryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Gallery Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="gallery_id" id="edit_gallery_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" id="edit_title" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="edit_description" class="form-control" rows="3" required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category" id="edit_category" class="form-select" required>
                                <optgroup label="Social Events">
                                    <option value="wedding">Wedding</option>
                                    <option value="birthday">Birthday</option>
                                    <option value="anniversary">Anniversary</option>
                                    <option value="family-gathering">Family Gathering</option>
                                    <option value="engagement">Engagement</option>
                                </optgroup>
                                <optgroup label="Professional Events">
                                    <option value="corporate">Corporate Event</option>
                                    <option value="conference">Conference</option>
                                    <option value="seminar">Seminar</option>
                                    <option value="business-meeting">Business Meeting</option>
                                    <option value="product-launch">Product Launch</option>
                                </optgroup>
                                <optgroup label="Other">
                                    <option value="food">Food Photography</option>
                                    <option value="setup">Setup & Decor</option>
                                    <option value="outdoor">Outdoor Event</option>
                                    <option value="other">Other</option>
                                </optgroup>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Image (leave empty to keep current)</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" id="edit_status" class="form-select">
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="update_gallery" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Image
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/bulk-actions.js"></script>
    <script>
        document.querySelectorAll('.edit-gallery').forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('edit_gallery_id').value = this.dataset.id;
                document.getElementById('edit_title').value = this.dataset.title;
                document.getElementById('edit_description').value = this.dataset.description;
                document.getElementById('edit_category').value = this.dataset.category;
                document.getElementById('edit_status').value = this.dataset.status;
            });
        });
    </script>
    <script src="js/admin-enhancements.js"></script>
</body>
</html>
