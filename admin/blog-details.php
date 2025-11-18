<?php
// Include authentication check
require_once 'includes/auth-check.php';

$data_file = 'data/blog-details.json';

// Load blog details data
function loadBlogDetailsData() {
    global $data_file;
    if (file_exists($data_file)) {
        return json_decode(file_get_contents($data_file), true);
    }
    return [];
}

// Save blog details data
function saveBlogDetailsData($data) {
    global $data_file;
    file_put_contents($data_file, json_encode($data, JSON_PRETTY_PRINT));
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $blog_details = loadBlogDetailsData();
    $blog_details = array_filter($blog_details, function($detail) use ($id) {
        return $detail['id'] != $id;
    });
    saveBlogDetailsData(array_values($blog_details));
    $success = "Blog detail deleted successfully!";
}

// Handle add blog detail
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_detail'])) {
    $blog_details = loadBlogDetailsData();
    
    // Get new ID
    $new_id = empty($blog_details) ? 1 : max(array_column($blog_details, 'id')) + 1;
    
    // Handle image upload
    $author_image = '';
    if (isset($_FILES['author_image']) && $_FILES['author_image']['error'] == 0) {
        $target_dir = "../img/team/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $ext = pathinfo($_FILES['author_image']['name'], PATHINFO_EXTENSION);
        $filename = time() . '_' . uniqid() . '.' . $ext;
        $author_image = 'img/team/' . $filename;
        move_uploaded_file($_FILES['author_image']['tmp_name'], "../" . $author_image);
    }
    
    $new_detail = [
        'id' => $new_id,
        'title' => $_POST['title'],
        'author' => $_POST['author'],
        'author_bio' => $_POST['author_bio'],
        'author_image' => $author_image,
        'content' => $_POST['content'],
        'read_time' => $_POST['read_time'],
        'status' => $_POST['status'],
        'date' => $_POST['date']
    ];
    
    $blog_details[] = $new_detail;
    saveBlogDetailsData($blog_details);
    $success = "Blog detail added successfully!";
}

// Handle update blog detail
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_detail'])) {
    $id = intval($_POST['detail_id']);
    $blog_details = loadBlogDetailsData();
    
    foreach ($blog_details as &$detail) {
        if ($detail['id'] == $id) {
            $detail['title'] = $_POST['title'];
            $detail['author'] = $_POST['author'];
            $detail['author_bio'] = $_POST['author_bio'];
            $detail['content'] = $_POST['content'];
            $detail['read_time'] = $_POST['read_time'];
            $detail['status'] = $_POST['status'];
            $detail['date'] = $_POST['date'];
            
            // Handle image upload
            if (isset($_FILES['author_image']) && $_FILES['author_image']['error'] == 0) {
                $target_dir = "../img/team/";
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                $ext = pathinfo($_FILES['author_image']['name'], PATHINFO_EXTENSION);
                $filename = time() . '_' . uniqid() . '.' . $ext;
                $detail['author_image'] = 'img/team/' . $filename;
                move_uploaded_file($_FILES['author_image']['tmp_name'], "../" . $detail['author_image']);
            }
            break;
        }
    }
    
    saveBlogDetailsData($blog_details);
    $success = "Blog detail updated successfully!";
}

$blog_details = loadBlogDetailsData();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Blog Details - Admin Panel</title>
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
                        <i class="fas fa-file-alt me-2"></i> Manage Blog Details
                    </h1>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-info" onclick="window.location.reload()">
                            <i class="fas fa-sync-alt me-1"></i> Refresh
                        </button>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDetailModal">
                            <i class="fas fa-plus me-1"></i> Add Blog Detail
                        </button>
                    </div>
                </div>
                
                <?php if (isset($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <!-- Blog Details Table -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">All Blog Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Author</th>
                                        <th>Date</th>
                                        <th>Read Time</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($blog_details)): ?>
                                        <?php foreach($blog_details as $detail): ?>
                                        <tr>
                                            <td><?php echo $detail['id']; ?></td>
                                            <td><?php echo htmlspecialchars($detail['title']); ?></td>
                                            <td><?php echo htmlspecialchars($detail['author']); ?></td>
                                            <td><?php echo date('M d, Y', strtotime($detail['date'])); ?></td>
                                            <td><?php echo $detail['read_time']; ?> min</td>
                                            <td>
                                                <?php if ($detail['status'] == 'published'): ?>
                                                    <span class="badge bg-success">Published</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning">Draft</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-info edit-detail" 
                                                    data-id="<?php echo $detail['id']; ?>"
                                                    data-title="<?php echo htmlspecialchars($detail['title']); ?>"
                                                    data-author="<?php echo htmlspecialchars($detail['author']); ?>"
                                                    data-author-bio="<?php echo htmlspecialchars($detail['author_bio']); ?>"
                                                    data-content="<?php echo htmlspecialchars($detail['content']); ?>"
                                                    data-read-time="<?php echo $detail['read_time']; ?>"
                                                    data-status="<?php echo $detail['status']; ?>"
                                                    data-date="<?php echo $detail['date']; ?>"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editDetailModal">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <a href="?delete=<?php echo $detail['id']; ?>" 
                                                   class="btn btn-sm btn-danger" 
                                                   onclick="return confirm('Delete this blog detail?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center">No blog details found. Add your first blog detail!</td>
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

    <!-- Add Detail Modal -->
    <div class="modal fade" id="addDetailModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Blog Detail</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Blog Title</label>
                                    <input type="text" name="title" class="form-control" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Author Name</label>
                                    <input type="text" name="author" class="form-control" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Author Bio</label>
                                    <textarea name="author_bio" class="form-control" rows="3" required></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Author Image</label>
                                    <input type="file" name="author_image" class="form-control" accept="image/*">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Publication Date</label>
                                    <input type="date" name="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Read Time (minutes)</label>
                                    <input type="number" name="read_time" class="form-control" min="1" value="5" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="draft">Draft</option>
                                        <option value="published">Published</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Blog Content</label>
                            <textarea name="content" class="form-control" rows="12" required placeholder="Write your detailed blog content here..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_detail" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Blog Detail
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Detail Modal -->
    <div class="modal fade" id="editDetailModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Blog Detail</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="detail_id" id="edit_detail_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Blog Title</label>
                                    <input type="text" name="title" id="edit_title" class="form-control" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Author Name</label>
                                    <input type="text" name="author" id="edit_author" class="form-control" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Author Bio</label>
                                    <textarea name="author_bio" id="edit_author_bio" class="form-control" rows="3" required></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Author Image (leave empty to keep current)</label>
                                    <input type="file" name="author_image" class="form-control" accept="image/*">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Publication Date</label>
                                    <input type="date" name="date" id="edit_date" class="form-control" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Read Time (minutes)</label>
                                    <input type="number" name="read_time" id="edit_read_time" class="form-control" min="1" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" id="edit_status" class="form-select">
                                        <option value="draft">Draft</option>
                                        <option value="published">Published</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Blog Content</label>
                            <textarea name="content" id="edit_content" class="form-control" rows="12" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="update_detail" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Blog Detail
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.edit-detail').forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('edit_detail_id').value = this.dataset.id;
                document.getElementById('edit_title').value = this.dataset.title;
                document.getElementById('edit_author').value = this.dataset.author;
                document.getElementById('edit_author_bio').value = this.dataset.authorBio;
                document.getElementById('edit_content').value = this.dataset.content;
                document.getElementById('edit_read_time').value = this.dataset.readTime;
                document.getElementById('edit_status').value = this.dataset.status;
                document.getElementById('edit_date').value = this.dataset.date;
            });
        });
    </script>
</body>
</html>