<?php
// Include authentication check
require_once 'includes/auth-check.php';

// Load menu data
$menu_file = 'data/menu.json';
$menu_items = file_exists($menu_file) ? json_decode(file_get_contents($menu_file), true) : [];

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_item'])) {
        $new_id = empty($menu_items) ? 1 : max(array_column($menu_items, 'id')) + 1;
        
        $new_item = [
            'id' => $new_id,
            'name' => $_POST['name'],
            'category' => $_POST['category'],
            'description' => $_POST['description'],
            'price' => $_POST['price'],
            'image' => $_POST['image'],
            'status' => $_POST['status']
        ];
        
        $menu_items[] = $new_item;
        file_put_contents($menu_file, json_encode($menu_items, JSON_PRETTY_PRINT));
        $success = "Menu item added successfully!";
    }
    
    if (isset($_POST['edit_item'])) {
        $id = (int)$_POST['id'];
        foreach ($menu_items as &$item) {
            if ($item['id'] == $id) {
                $item['name'] = $_POST['name'];
                $item['category'] = $_POST['category'];
                $item['description'] = $_POST['description'];
                $item['price'] = $_POST['price'];
                $item['image'] = $_POST['image'];
                $item['status'] = $_POST['status'];
                break;
            }
        }
        file_put_contents($menu_file, json_encode($menu_items, JSON_PRETTY_PRINT));
        $success = "Menu item updated successfully!";
    }
    
    if (isset($_POST['delete_item'])) {
        $id = (int)$_POST['id'];
        $menu_items = array_filter($menu_items, function($item) use ($id) {
            return $item['id'] != $id;
        });
        $menu_items = array_values($menu_items);
        file_put_contents($menu_file, json_encode($menu_items, JSON_PRETTY_PRINT));
        $success = "Menu item deleted successfully!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Management - Altaf Catering Admin</title>
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
                        <i class="fas fa-utensils me-2"></i> Menu Management
                    </h1>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-info" onclick="window.location.reload()">
                            <i class="fas fa-sync-alt me-1"></i> Refresh
                        </button>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                            <i class="fas fa-plus me-1"></i> Add Menu Item
                        </button>
                    </div>
                </div>
                
                <?php if (isset($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?php echo $success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <div class="row">
                    <?php foreach ($menu_items as $item): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            <img src="../<?php echo $item['image']; ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($item['name']); ?></h5>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-tag"></i> <?php echo htmlspecialchars($item['category']); ?>
                                </p>
                                <p class="card-text"><?php echo htmlspecialchars($item['description']); ?></p>
                                <h6 class="text-primary">Rs. <?php echo number_format($item['price']); ?></h6>
                                <span class="badge bg-<?php echo $item['status'] == 'active' ? 'success' : 'secondary'; ?>">
                                    <?php echo ucfirst($item['status']); ?>
                                </span>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-sm btn-info" onclick='editItem(<?php echo json_encode($item); ?>)'>
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                    <button type="submit" name="delete_item" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </main>
        </div>
    </div>
    
    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Menu Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Item Name *</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category *</label>
                                <select name="category" class="form-select" required>
                                    <option value="Pakistani">Pakistani</option>
                                    <option value="Continental">Continental</option>
                                    <option value="Chinese">Chinese</option>
                                    <option value="BBQ">BBQ</option>
                                    <option value="Desserts">Desserts</option>
                                    <option value="Beverages">Beverages</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description *</label>
                            <textarea name="description" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Price (Rs.) *</label>
                                <input type="number" name="price" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Image *</label>
                                <input type="file" id="add_menu_image" class="form-control" accept="image/*">
                                <input type="hidden" name="image" id="add_menu_path" required>
                                <div id="add_menu_preview" class="mt-2"></div>
                            </div>
                            <div class="col-md-4 mb-3">
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
                        <button type="submit" name="add_item" class="btn btn-primary">Add Item</button>
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
                    <h5 class="modal-title">Edit Menu Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Item Name *</label>
                                <input type="text" name="name" id="edit_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category *</label>
                                <select name="category" id="edit_category" class="form-select" required>
                                    <option value="Pakistani">Pakistani</option>
                                    <option value="Continental">Continental</option>
                                    <option value="Chinese">Chinese</option>
                                    <option value="BBQ">BBQ</option>
                                    <option value="Desserts">Desserts</option>
                                    <option value="Beverages">Beverages</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description *</label>
                            <textarea name="description" id="edit_description" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Price (Rs.) *</label>
                                <input type="number" name="price" id="edit_price" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Image *</label>
                                <input type="file" id="edit_menu_image" class="form-control" accept="image/*">
                                <input type="hidden" name="image" id="edit_image" required>
                                <div id="edit_menu_preview" class="mt-2"></div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Status *</label>
                                <select name="status" id="edit_status" class="form-select" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="edit_item" class="btn btn-primary">Update Item</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
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
        
        document.getElementById('add_menu_image').addEventListener('change', function() {
            handleFileUpload(this, document.getElementById('add_menu_path'), 
                document.getElementById('add_menu_preview'), 'menu');
        });
        
        document.getElementById('edit_menu_image').addEventListener('change', function() {
            handleFileUpload(this, document.getElementById('edit_image'), 
                document.getElementById('edit_menu_preview'), 'menu');
        });
        
        function editItem(item) {
            document.getElementById('edit_id').value = item.id;
            document.getElementById('edit_name').value = item.name;
            document.getElementById('edit_category').value = item.category;
            document.getElementById('edit_description').value = item.description;
            document.getElementById('edit_price').value = item.price;
            document.getElementById('edit_image').value = item.image;
            document.getElementById('edit_status').value = item.status;
            document.getElementById('edit_menu_preview').innerHTML = 
                '<img src="../' + item.image + '" class="img-thumbnail" style="max-width: 150px;">';
            new bootstrap.Modal(document.getElementById('editModal')).show();
        }
    </script>
    <script src="js/admin-enhancements.js"></script>
</body>
</html>
