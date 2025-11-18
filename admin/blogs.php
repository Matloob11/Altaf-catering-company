<?php
// Include authentication check
require_once 'includes/auth-check.php';

$data_file = 'data/blogs.json';

// Load blogs data
function loadBlogsData() {
    global $data_file;
    if (file_exists($data_file)) {
        return json_decode(file_get_contents($data_file), true);
    }
    return [];
}

// Save blogs data
function saveBlogsData($data) {
    global $data_file;
    file_put_contents($data_file, json_encode($data, JSON_PRETTY_PRINT));
}

// Handle bulk delete
if (isset($_POST['bulk_delete']) && isset($_POST['selected_ids'])) {
    $selected_ids = array_map('intval', $_POST['selected_ids']);
    $blogs = loadBlogsData();
    $blogs = array_filter($blogs, function($blog) use ($selected_ids) {
        return !in_array($blog['id'], $selected_ids);
    });
    saveBlogsData(array_values($blogs));
    $success = count($selected_ids) . " blog(s) deleted successfully!";
}

// Handle bulk publish
if (isset($_POST['bulk_publish']) && isset($_POST['selected_ids'])) {
    $selected_ids = array_map('intval', $_POST['selected_ids']);
    $blogs = loadBlogsData();
    foreach ($blogs as &$blog) {
        if (in_array($blog['id'], $selected_ids)) {
            $blog['status'] = 'published';
        }
    }
    saveBlogsData($blogs);
    $success = count($selected_ids) . " blog(s) published successfully!";
}

// Handle bulk unpublish
if (isset($_POST['bulk_unpublish']) && isset($_POST['selected_ids'])) {
    $selected_ids = array_map('intval', $_POST['selected_ids']);
    $blogs = loadBlogsData();
    foreach ($blogs as &$blog) {
        if (in_array($blog['id'], $selected_ids)) {
            $blog['status'] = 'draft';
        }
    }
    saveBlogsData($blogs);
    $success = count($selected_ids) . " blog(s) unpublished successfully!";
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $blogs = loadBlogsData();
    $blogs = array_filter($blogs, function($blog) use ($id) {
        return $blog['id'] != $id;
    });
    saveBlogsData(array_values($blogs));
    $success = "Blog deleted successfully!";
}

// Handle add blog
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_blog'])) {
    $blogs = loadBlogsData();
    
    // Get new ID
    $new_id = empty($blogs) ? 1 : max(array_column($blogs, 'id')) + 1;
    
    // Handle featured image upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../img/blog/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = time() . '_' . uniqid() . '.' . $ext;
        $image = 'img/blog/' . $filename;
        move_uploaded_file($_FILES['image']['tmp_name'], "../" . $image);
    }
    
    // Handle author image upload
    $author_image = '';
    if (isset($_FILES['author_image']) && $_FILES['author_image']['error'] == 0) {
        $target_dir = "../img/team/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $ext = pathinfo($_FILES['author_image']['name'], PATHINFO_EXTENSION);
        $filename = 'author_' . time() . '_' . uniqid() . '.' . $ext;
        $author_image = 'img/team/' . $filename;
        move_uploaded_file($_FILES['author_image']['tmp_name'], "../" . $author_image);
    }
    
    $new_blog = [
        'id' => $new_id,
        'title' => $_POST['title'],
        'author' => $_POST['author'],
        'author_bio' => $_POST['author_bio'],
        'author_image' => $author_image,
        'excerpt' => $_POST['excerpt'],
        'content' => $_POST['content'],
        'image' => $image,
        'category' => $_POST['category'],
        'read_time' => $_POST['read_time'],
        'status' => $_POST['status'],
        'date' => $_POST['date']
    ];
    
    $blogs[] = $new_blog;
    saveBlogsData($blogs);
    $success = "Blog post added successfully with full details!";
}

// Handle update blog
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_blog'])) {
    $id = intval($_POST['blog_id']);
    $blogs = loadBlogsData();
    
    foreach ($blogs as &$blog) {
        if ($blog['id'] == $id) {
            $blog['title'] = $_POST['title'];
            $blog['author'] = $_POST['author'];
            $blog['author_bio'] = $_POST['author_bio'];
            $blog['excerpt'] = $_POST['excerpt'];
            $blog['content'] = $_POST['content'];
            $blog['category'] = $_POST['category'];
            $blog['read_time'] = $_POST['read_time'];
            $blog['status'] = $_POST['status'];
            $blog['date'] = $_POST['date'];
            
            // Handle featured image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $target_dir = "../img/blog/";
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = time() . '_' . uniqid() . '.' . $ext;
                $blog['image'] = 'img/blog/' . $filename;
                move_uploaded_file($_FILES['image']['tmp_name'], "../" . $blog['image']);
            }
            
            // Handle author image upload
            if (isset($_FILES['author_image']) && $_FILES['author_image']['error'] == 0) {
                $target_dir = "../img/team/";
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                $ext = pathinfo($_FILES['author_image']['name'], PATHINFO_EXTENSION);
                $filename = 'author_' . time() . '_' . uniqid() . '.' . $ext;
                $blog['author_image'] = 'img/team/' . $filename;
                move_uploaded_file($_FILES['author_image']['tmp_name'], "../" . $blog['author_image']);
            }
            break;
        }
    }
    
    saveBlogsData($blogs);
    $success = "Blog post updated successfully!";
}

$blogs = loadBlogsData();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Blogs - Admin Panel</title>
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
                        <i class="fas fa-blog me-2"></i> Manage Blogs
                    </h1>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-info" onclick="window.location.reload()">
                            <i class="fas fa-sync-alt me-1"></i> Refresh
                        </button>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBlogModal">
                            <i class="fas fa-plus me-1"></i> Add New Blog
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
                
                <!-- Blogs Table -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">All Blog Posts</h6>
                    </div>
                    <div class="card-body">
                        <!-- Select All -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                                <label class="form-check-label" for="selectAll">
                                    <strong>Select All Blogs</strong>
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
                                        <th>Author</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($blogs)): ?>
                                        <?php foreach($blogs as $blog): ?>
                                        <tr data-id="<?php echo $blog['id']; ?>">
                                            <td>
                                                <input type="checkbox" class="form-check-input item-checkbox" value="<?php echo $blog['id']; ?>">
                                            </td>
                                            <td><?php echo $blog['id']; ?></td>
                                            <td>
                                                <?php if (!empty($blog['image'])): ?>
                                                    <img src="../<?php echo htmlspecialchars($blog['image']); ?>" 
                                                         alt="Blog" 
                                                         style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                                <?php else: ?>
                                                    <div style="width: 60px; height: 60px; background: #e2e8f0; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($blog['title']); ?></strong>
                                                <?php if (!empty($blog['excerpt'])): ?>
                                                    <br><small class="text-muted"><?php echo htmlspecialchars(substr($blog['excerpt'], 0, 50)) . '...'; ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($blog['category'])): ?>
                                                    <span class="badge bg-info"><?php echo htmlspecialchars($blog['category']); ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($blog['author']); ?></td>
                                            <td><?php echo date('M d, Y', strtotime($blog['date'])); ?></td>
                                            <td>
                                                <?php if ($blog['status'] == 'published'): ?>
                                                    <span class="badge bg-success">Published</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning">Draft</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-info edit-blog" 
                                                    data-id="<?php echo $blog['id']; ?>"
                                                    data-title="<?php echo htmlspecialchars($blog['title']); ?>"
                                                    data-author="<?php echo htmlspecialchars($blog['author']); ?>"
                                                    data-author-bio="<?php echo htmlspecialchars($blog['author_bio'] ?? ''); ?>"
                                                    data-excerpt="<?php echo htmlspecialchars($blog['excerpt'] ?? ''); ?>"
                                                    data-content="<?php echo htmlspecialchars($blog['content']); ?>"
                                                    data-category="<?php echo htmlspecialchars($blog['category'] ?? ''); ?>"
                                                    data-read-time="<?php echo htmlspecialchars($blog['read_time'] ?? '5'); ?>"
                                                    data-status="<?php echo $blog['status']; ?>"
                                                    data-date="<?php echo $blog['date']; ?>"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editBlogModal">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <a href="../blog-detail.php?id=<?php echo $blog['id']; ?>" 
                                                   class="btn btn-sm btn-success" 
                                                   target="_blank"
                                                   title="View Post">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="?delete=<?php echo $blog['id']; ?>" 
                                                   class="btn btn-sm btn-danger" 
                                                   onclick="return confirm('Delete this blog?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center">No blogs found. Add your first blog!</td>
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

    <!-- Add Blog Modal -->
    <div class="modal fade" id="addBlogModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Blog Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-heading me-2"></i>Blog Title *</label>
                                    <input type="text" name="title" class="form-control" placeholder="Enter an engaging title" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-align-left me-2"></i>Short Excerpt *</label>
                                    <textarea name="excerpt" class="form-control" rows="2" placeholder="Brief summary (150-200 characters)" required></textarea>
                                    <small class="text-muted">This will appear on blog cards</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-file-alt me-2"></i>Full Content *</label>
                                    <textarea name="content" class="form-control" rows="10" placeholder="Write your detailed blog content here..." required></textarea>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-image me-2"></i>Featured Image</label>
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                    <small class="text-muted">Recommended: 800x600px</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-tag me-2"></i>Category</label>
                                    <select name="category" class="form-select">
                                        <option value="Tips">Tips</option>
                                        <option value="Recipes">Recipes</option>
                                        <option value="Events">Events</option>
                                        <option value="News">News</option>
                                        <option value="Guide">Guide</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-calendar me-2"></i>Publish Date</label>
                                    <input type="date" name="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-clock me-2"></i>Read Time (min)</label>
                                    <input type="number" name="read_time" class="form-control" value="5" min="1" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-toggle-on me-2"></i>Status</label>
                                    <select name="status" class="form-select">
                                        <option value="draft">Draft</option>
                                        <option value="published">Published</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Author Details Section -->
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-user-edit me-2"></i>Author Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Author Name *</label>
                                            <input type="text" name="author" class="form-control" placeholder="e.g. Chef Ahmed" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Author Image</label>
                                            <input type="file" name="author_image" class="form-control" accept="image/*">
                                            <small class="text-muted">Recommended: 200x200px</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Author Bio *</label>
                                    <textarea name="author_bio" class="form-control" rows="3" placeholder="Brief bio about the author (e.g. Head Chef at Altaf Catering with 15 years of experience...)" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_blog" class="btn btn-primary">
                            <i class="fas fa-save"></i> Publish Blog Post
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Blog Modal -->
    <div class="modal fade" id="editBlogModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Blog Post</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="blog_id" id="edit_blog_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-heading me-2"></i>Blog Title *</label>
                                    <input type="text" name="title" id="edit_title" class="form-control" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-align-left me-2"></i>Short Excerpt *</label>
                                    <textarea name="excerpt" id="edit_excerpt" class="form-control" rows="2" required></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-file-alt me-2"></i>Full Content *</label>
                                    <textarea name="content" id="edit_content" class="form-control" rows="10" required></textarea>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-image me-2"></i>Featured Image</label>
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                    <small class="text-muted">Leave empty to keep current</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-tag me-2"></i>Category</label>
                                    <select name="category" id="edit_category" class="form-select">
                                        <option value="Tips">Tips</option>
                                        <option value="Recipes">Recipes</option>
                                        <option value="Events">Events</option>
                                        <option value="News">News</option>
                                        <option value="Guide">Guide</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-calendar me-2"></i>Publish Date</label>
                                    <input type="date" name="date" id="edit_date" class="form-control" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-clock me-2"></i>Read Time (min)</label>
                                    <input type="number" name="read_time" id="edit_read_time" class="form-control" min="1" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-toggle-on me-2"></i>Status</label>
                                    <select name="status" id="edit_status" class="form-select">
                                        <option value="draft">Draft</option>
                                        <option value="published">Published</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Author Details Section -->
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-user-edit me-2"></i>Author Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Author Name *</label>
                                            <input type="text" name="author" id="edit_author" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Author Image</label>
                                            <input type="file" name="author_image" class="form-control" accept="image/*">
                                            <small class="text-muted">Leave empty to keep current</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Author Bio *</label>
                                    <textarea name="author_bio" id="edit_author_bio" class="form-control" rows="3" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="update_blog" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Blog Post
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/bulk-actions.js"></script>
    <script>
        document.querySelectorAll('.edit-blog').forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('edit_blog_id').value = this.dataset.id;
                document.getElementById('edit_title').value = this.dataset.title;
                document.getElementById('edit_author').value = this.dataset.author;
                document.getElementById('edit_author_bio').value = this.dataset.authorBio;
                document.getElementById('edit_excerpt').value = this.dataset.excerpt;
                document.getElementById('edit_content').value = this.dataset.content;
                document.getElementById('edit_category').value = this.dataset.category;
                document.getElementById('edit_read_time').value = this.dataset.readTime;
                document.getElementById('edit_status').value = this.dataset.status;
                document.getElementById('edit_date').value = this.dataset.date;
            });
        });
    </script>
    <script src="js/admin-enhancements.js"></script>
</body>
</html>
