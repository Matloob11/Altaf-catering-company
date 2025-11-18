<?php
// Include authentication check
require_once 'includes/auth-check.php';

$data_file = 'data/owner.json';

// Load owner data
function loadOwnerData() {
    global $data_file;
    if (file_exists($data_file)) {
        return json_decode(file_get_contents($data_file), true);
    }
    return null;
}

// Save owner data
function saveOwnerData($data) {
    global $data_file;
    file_put_contents($data_file, json_encode($data, JSON_PRETTY_PRINT));
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_owner'])) {
    $owner_data = [
        'name' => $_POST['name'],
        'title' => $_POST['title'],
        'tagline' => $_POST['tagline'],
        'image' => $_POST['image'],
        'bio' => $_POST['bio'],
        'years_experience' => $_POST['years_experience'],
        'email' => $_POST['email'],
        'phone' => $_POST['phone'],
        'facebook' => $_POST['facebook'] ?? '',
        'instagram' => $_POST['instagram'] ?? '',
        'tiktok' => $_POST['tiktok'] ?? '',
        'youtube' => $_POST['youtube'] ?? '',
        'achievements' => array_filter(array_map('trim', explode("\n", $_POST['achievements']))),
        'specialties' => array_filter(array_map('trim', explode(',', $_POST['specialties'])))
    ];
    
    saveOwnerData($owner_data);
    $success = "Owner information updated successfully!";
}

$owner = loadOwnerData();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner/Founder Management - Admin Panel</title>
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
                        <i class="fas fa-user-tie me-2"></i> Owner/Founder Management
                    </h1>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-info" onclick="window.location.reload()">
                            <i class="fas fa-sync-alt me-1"></i> Refresh
                        </button>
                        <a href="../index.php#owner-section" class="btn btn-sm btn-success" target="_blank">
                            <i class="fas fa-eye me-1"></i> Preview on Website
                        </a>
                    </div>
                </div>
                
                <?php if (isset($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <!-- Owner Information Form -->
                <div class="card shadow mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Owner/Founder Information</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <!-- Basic Information -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-id-card me-2"></i>Basic Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Full Name *</label>
                                            <input type="text" name="name" class="form-control" 
                                                   value="<?php echo htmlspecialchars($owner['name'] ?? ''); ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Title/Position *</label>
                                            <input type="text" name="title" class="form-control" 
                                                   value="<?php echo htmlspecialchars($owner['title'] ?? ''); ?>" 
                                                   placeholder="e.g. Owner & Managing Director" required>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Tagline/Quote</label>
                                        <input type="text" name="tagline" class="form-control" 
                                               value="<?php echo htmlspecialchars($owner['tagline'] ?? ''); ?>" 
                                               placeholder="e.g. Leading with passion, serving with excellence">
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Years of Experience *</label>
                                            <input type="number" name="years_experience" class="form-control" 
                                                   value="<?php echo htmlspecialchars($owner['years_experience'] ?? ''); ?>" 
                                                   min="0" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Profile Image *</label>
                                            <input type="file" id="owner_image_file" class="form-control mb-2" accept="image/*">
                                            <input type="hidden" name="image" id="owner_image_path" 
                                                   value="<?php echo htmlspecialchars($owner['image'] ?? ''); ?>" required>
                                            <div id="owner_image_preview" class="mt-2">
                                                <?php if (!empty($owner['image'])): ?>
                                                <img src="../<?php echo htmlspecialchars($owner['image']); ?>" 
                                                     class="img-thumbnail" style="max-width: 200px;">
                                                <?php endif; ?>
                                            </div>
                                            <small class="text-muted">Recommended: 800x1000px portrait image</small>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Biography *</label>
                                        <textarea name="bio" class="form-control" rows="6" required><?php echo htmlspecialchars($owner['bio'] ?? ''); ?></textarea>
                                        <small class="text-muted">Write a detailed biography (2-3 paragraphs)</small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Contact Information -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-address-book me-2"></i>Contact Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Email *</label>
                                            <input type="email" name="email" class="form-control" 
                                                   value="<?php echo htmlspecialchars($owner['email'] ?? ''); ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Phone *</label>
                                            <input type="tel" name="phone" class="form-control" 
                                                   value="<?php echo htmlspecialchars($owner['phone'] ?? ''); ?>" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Social Media -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-share-alt me-2"></i>Social Media Links</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label"><i class="fab fa-facebook text-primary me-2"></i>Facebook URL</label>
                                            <input type="url" name="facebook" class="form-control" 
                                                   value="<?php echo htmlspecialchars($owner['facebook'] ?? ''); ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label"><i class="fab fa-instagram text-danger me-2"></i>Instagram URL</label>
                                            <input type="url" name="instagram" class="form-control" 
                                                   value="<?php echo htmlspecialchars($owner['instagram'] ?? ''); ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label"><i class="fab fa-tiktok text-dark me-2"></i>TikTok URL</label>
                                            <input type="url" name="tiktok" class="form-control" 
                                                   value="<?php echo htmlspecialchars($owner['tiktok'] ?? ''); ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label"><i class="fab fa-youtube text-danger me-2"></i>YouTube URL</label>
                                            <input type="url" name="youtube" class="form-control" 
                                                   value="<?php echo htmlspecialchars($owner['youtube'] ?? ''); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Additional Information -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Additional Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Specialties (comma-separated)</label>
                                        <input type="text" name="specialties" class="form-control" 
                                               value="<?php echo htmlspecialchars(is_array($owner['specialties'] ?? null) ? implode(', ', $owner['specialties']) : ''); ?>" 
                                               placeholder="e.g. Business Strategy, Client Relations, Team Leadership">
                                        <small class="text-muted">Separate each specialty with a comma</small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Key Achievements (one per line)</label>
                                        <textarea name="achievements" class="form-control" rows="5"><?php echo htmlspecialchars(is_array($owner['achievements'] ?? null) ? implode("\n", $owner['achievements']) : ''); ?></textarea>
                                        <small class="text-muted">Enter each achievement on a new line</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-end">
                                <button type="submit" name="update_owner" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save me-2"></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/admin-common.js"></script>
    <script>
        // File upload handler
        document.getElementById('owner_image_file').addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;
            
            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('owner_image_preview').innerHTML = 
                    '<img src="' + e.target.result + '" class="img-thumbnail" style="max-width: 200px;">';
            };
            reader.readAsDataURL(file);
            
            // Upload file
            const formData = new FormData();
            formData.append('file', file);
            formData.append('type', 'owner');
            
            fetch('upload.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('owner_image_path').value = data.path;
                    alert('Image uploaded successfully!');
                } else {
                    alert('Upload failed: ' + data.message);
                }
            })
            .catch(error => {
                alert('Upload error: ' + error);
            });
        });
    </script>
    <script src="js/admin-enhancements.js"></script>
</body>
</html>
