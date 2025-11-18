<?php
// Include authentication check
require_once 'includes/auth-check.php';

// Load team data
$team_file = 'data/team.json';
$team_members = file_exists($team_file) ? json_decode(file_get_contents($team_file), true) : [];

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle bulk delete
    if (isset($_POST['bulk_delete']) && isset($_POST['selected_ids'])) {
        $selected_ids = array_map('intval', $_POST['selected_ids']);
        $team_members = array_filter($team_members, function($member) use ($selected_ids) {
            return !in_array($member['id'], $selected_ids);
        });
        $team_members = array_values($team_members);
        file_put_contents($team_file, json_encode($team_members, JSON_PRETTY_PRINT));
        $success = count($selected_ids) . " team member(s) deleted successfully!";
    }
    
    // Handle bulk activate
    if (isset($_POST['bulk_publish']) && isset($_POST['selected_ids'])) {
        $selected_ids = array_map('intval', $_POST['selected_ids']);
        foreach ($team_members as &$member) {
            if (in_array($member['id'], $selected_ids)) {
                $member['status'] = 'active';
            }
        }
        file_put_contents($team_file, json_encode($team_members, JSON_PRETTY_PRINT));
        $success = count($selected_ids) . " team member(s) activated successfully!";
    }
    
    // Handle bulk deactivate
    if (isset($_POST['bulk_unpublish']) && isset($_POST['selected_ids'])) {
        $selected_ids = array_map('intval', $_POST['selected_ids']);
        foreach ($team_members as &$member) {
            if (in_array($member['id'], $selected_ids)) {
                $member['status'] = 'inactive';
            }
        }
        file_put_contents($team_file, json_encode($team_members, JSON_PRETTY_PRINT));
        $success = count($selected_ids) . " team member(s) deactivated successfully!";
    }
    
    if (isset($_POST['add_member'])) {
        $new_id = empty($team_members) ? 1 : max(array_column($team_members, 'id')) + 1;
        
        $new_member = [
            'id' => $new_id,
            'name' => $_POST['name'],
            'position' => $_POST['position'],
            'image' => $_POST['image'],
            'bio' => $_POST['bio'] ?? '',
            'tagline' => $_POST['tagline'] ?? '',
            'experience' => $_POST['experience'] ?? '',
            'skills' => $_POST['skills'] ?? '',
            'achievements' => $_POST['achievements'] ?? '',
            'department' => $_POST['department'] ?? '',
            'years_experience' => $_POST['years_experience'] ?? '',
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'facebook' => $_POST['facebook'] ?? '',
            'instagram' => $_POST['instagram'] ?? '',
            'tiktok' => $_POST['tiktok'] ?? '',
            'youtube' => $_POST['youtube'] ?? '',
            'status' => $_POST['status']
        ];
        
        $team_members[] = $new_member;
        file_put_contents($team_file, json_encode($team_members, JSON_PRETTY_PRINT));
        $success = "Team member added successfully with complete details!";
    }
    
    if (isset($_POST['edit_member'])) {
        $id = (int)$_POST['id'];
        foreach ($team_members as &$member) {
            if ($member['id'] == $id) {
                $member['name'] = $_POST['name'];
                $member['position'] = $_POST['position'];
                $member['image'] = $_POST['image'];
                $member['bio'] = $_POST['bio'] ?? '';
                $member['tagline'] = $_POST['tagline'] ?? '';
                $member['experience'] = $_POST['experience'] ?? '';
                $member['skills'] = $_POST['skills'] ?? '';
                $member['achievements'] = $_POST['achievements'] ?? '';
                $member['department'] = $_POST['department'] ?? '';
                $member['years_experience'] = $_POST['years_experience'] ?? '';
                $member['email'] = $_POST['email'] ?? '';
                $member['phone'] = $_POST['phone'] ?? '';
                $member['facebook'] = $_POST['facebook'] ?? '';
                $member['instagram'] = $_POST['instagram'] ?? '';
                $member['tiktok'] = $_POST['tiktok'] ?? '';
                $member['youtube'] = $_POST['youtube'] ?? '';
                $member['status'] = $_POST['status'];
                break;
            }
        }
        file_put_contents($team_file, json_encode($team_members, JSON_PRETTY_PRINT));
        $success = "Team member updated successfully!";
    }
    
    if (isset($_POST['delete_member'])) {
        $id = (int)$_POST['id'];
        $team_members = array_filter($team_members, function($member) use ($id) {
            return $member['id'] != $id;
        });
        $team_members = array_values($team_members);
        file_put_contents($team_file, json_encode($team_members, JSON_PRETTY_PRINT));
        $success = "Team member deleted successfully!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Management - Altaf Catering Admin</title>
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
                        <i class="fas fa-users me-2"></i> Team Management
                    </h1>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-info" onclick="window.location.reload()">
                            <i class="fas fa-sync-alt me-1"></i> Refresh
                        </button>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                            <i class="fas fa-user-plus me-1"></i> Add Team Member
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
                                <strong><span id="selectedCount">0</span> member(s) selected</strong>
                            </div>
                            <div class="col-md-8 text-end">
                                <div class="btn-group" role="group">
                                    <button type="button" id="bulkPublish" class="btn btn-sm btn-success">
                                        <i class="fas fa-check"></i> Activate
                                    </button>
                                    <button type="button" id="bulkUnpublish" class="btn btn-sm btn-warning">
                                        <i class="fas fa-ban"></i> Deactivate
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
                                <strong>Select All Team Members</strong>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <?php foreach ($team_members as $member): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 position-relative" data-id="<?php echo $member['id']; ?>">
                            <input type="checkbox" class="form-check-input item-checkbox position-absolute" 
                                   value="<?php echo $member['id']; ?>" 
                                   style="top: 10px; left: 10px; z-index: 10; width: 20px; height: 20px; cursor: pointer;">
                            <img src="../<?php echo $member['image']; ?>" class="card-img-top" style="height: 250px; object-fit: cover;">
                            <div class="card-body text-center">
                                <h5 class="card-title text-primary"><?php echo htmlspecialchars($member['name']); ?></h5>
                                <p class="text-muted mb-2"><?php echo htmlspecialchars($member['position']); ?></p>
                                <?php if (!empty($member['department'])): ?>
                                    <p class="text-muted small mb-2"><i class="fas fa-building me-1"></i><?php echo htmlspecialchars($member['department']); ?></p>
                                <?php endif; ?>
                                <span class="badge bg-<?php echo $member['status'] == 'active' ? 'success' : 'secondary'; ?> mb-3">
                                    <?php echo ucfirst($member['status']); ?>
                                </span>
                                
                                <!-- View Profile Button -->
                                <?php if ($member['status'] == 'active'): ?>
                                <div class="mb-3">
                                    <a href="../team-detail.php?id=<?php echo $member['id']; ?>" 
                                       class="btn btn-sm btn-primary" 
                                       target="_blank">
                                        <i class="fas fa-eye me-1"></i> View Profile
                                    </a>
                                </div>
                                <?php endif; ?>
                                
                                <div class="mt-2">
                                    <?php if (!empty($member['facebook'])): ?>
                                        <a href="<?php echo $member['facebook']; ?>" class="btn btn-sm btn-outline-primary rounded-circle me-1" target="_blank">
                                            <i class="fab fa-facebook-f"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (!empty($member['instagram'])): ?>
                                        <a href="<?php echo $member['instagram']; ?>" class="btn btn-sm btn-outline-danger rounded-circle me-1" target="_blank">
                                            <i class="fab fa-instagram"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (!empty($member['tiktok'])): ?>
                                        <a href="<?php echo $member['tiktok']; ?>" class="btn btn-sm btn-outline-dark rounded-circle me-1" target="_blank">
                                            <i class="fab fa-tiktok"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (!empty($member['youtube'])): ?>
                                        <a href="<?php echo $member['youtube']; ?>" class="btn btn-sm btn-outline-danger rounded-circle" target="_blank">
                                            <i class="fab fa-youtube"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-sm btn-info" onclick='editMember(<?php echo json_encode($member); ?>)'>
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="id" value="<?php echo $member['id']; ?>">
                                    <button type="submit" name="delete_member" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this team member?')">
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
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Add Team Member</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <!-- Basic Info -->
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-id-card me-2"></i>Basic Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Full Name *</label>
                                        <input type="text" name="name" class="form-control" placeholder="e.g. Chef Ahmed Ali" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Position/Title *</label>
                                        <input type="text" name="position" class="form-control" placeholder="e.g. Executive Chef" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Department</label>
                                        <input type="text" name="department" class="form-control" placeholder="e.g. Kitchen, Management">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Years of Experience</label>
                                        <input type="number" name="years_experience" class="form-control" placeholder="e.g. 15" min="0">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tagline/Quote</label>
                                    <input type="text" name="tagline" class="form-control" placeholder="e.g. Passionate about creating memorable dining experiences">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Profile Image *</label>
                                    <input type="file" id="add_image_file" class="form-control mb-2" accept="image/*">
                                    <input type="hidden" name="image" id="add_image_path" required>
                                    <div id="add_image_preview" class="mt-2"></div>
                                    <small class="text-muted">Recommended: 400x400px square image</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Detailed Info -->
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-file-alt me-2"></i>Detailed Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Biography</label>
                                    <textarea name="bio" class="form-control" rows="4" placeholder="Write a detailed bio about this team member..."></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Experience & Expertise</label>
                                    <textarea name="experience" class="form-control" rows="3" placeholder="Describe their professional experience and expertise..."></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Skills (comma-separated)</label>
                                    <input type="text" name="skills" class="form-control" placeholder="e.g. French Cuisine, Menu Planning, Team Leadership">
                                    <small class="text-muted">Separate skills with commas</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Achievements & Awards (one per line)</label>
                                    <textarea name="achievements" class="form-control" rows="3" placeholder="Best Chef Award 2023&#10;Culinary Excellence Certificate&#10;5-Star Rating"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Contact Info -->
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-address-book me-2"></i>Contact Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" placeholder="email@example.com">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Phone</label>
                                        <input type="tel" name="phone" class="form-control" placeholder="+92 300 1234567">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Social Media -->
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-share-alt me-2"></i>Social Media Links</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><i class="fab fa-facebook text-primary me-2"></i>Facebook URL</label>
                                        <input type="url" name="facebook" class="form-control" placeholder="https://facebook.com/...">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><i class="fab fa-instagram text-danger me-2"></i>Instagram URL</label>
                                        <input type="url" name="instagram" class="form-control" placeholder="https://instagram.com/...">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><i class="fab fa-tiktok text-dark me-2"></i>TikTok URL</label>
                                        <input type="url" name="tiktok" class="form-control" placeholder="https://tiktok.com/@...">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><i class="fab fa-youtube text-danger me-2"></i>YouTube URL</label>
                                        <input type="url" name="youtube" class="form-control" placeholder="https://youtube.com/@...">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Status -->
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-toggle-on me-2"></i>Status *</label>
                            <select name="status" class="form-select" required>
                                <option value="active">Active (Visible on website)</option>
                                <option value="inactive">Inactive (Hidden from website)</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_member" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Add Team Member
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="fas fa-user-edit me-2"></i>Edit Team Member</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="modal-body">
                        <!-- Basic Info -->
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-id-card me-2"></i>Basic Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Full Name *</label>
                                        <input type="text" name="name" id="edit_name" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Position/Title *</label>
                                        <input type="text" name="position" id="edit_position" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Department</label>
                                        <input type="text" name="department" id="edit_department" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Years of Experience</label>
                                        <input type="number" name="years_experience" id="edit_years_experience" class="form-control" min="0">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tagline/Quote</label>
                                    <input type="text" name="tagline" id="edit_tagline" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Profile Image *</label>
                                    <input type="file" id="edit_image_file" class="form-control mb-2" accept="image/*">
                                    <input type="hidden" name="image" id="edit_image" required>
                                    <div id="edit_image_preview" class="mt-2"></div>
                                    <small class="text-muted">Leave empty to keep current image</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Detailed Info -->
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-file-alt me-2"></i>Detailed Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Biography</label>
                                    <textarea name="bio" id="edit_bio" class="form-control" rows="4"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Experience & Expertise</label>
                                    <textarea name="experience" id="edit_experience" class="form-control" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Skills (comma-separated)</label>
                                    <input type="text" name="skills" id="edit_skills" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Achievements & Awards (one per line)</label>
                                    <textarea name="achievements" id="edit_achievements" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Contact Info -->
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-address-book me-2"></i>Contact Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" id="edit_email" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Phone</label>
                                        <input type="tel" name="phone" id="edit_phone" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Social Media -->
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-share-alt me-2"></i>Social Media Links</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><i class="fab fa-facebook text-primary me-2"></i>Facebook URL</label>
                                        <input type="url" name="facebook" id="edit_facebook" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><i class="fab fa-instagram text-danger me-2"></i>Instagram URL</label>
                                        <input type="url" name="instagram" id="edit_instagram" class="form-control">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><i class="fab fa-tiktok text-dark me-2"></i>TikTok URL</label>
                                        <input type="url" name="tiktok" id="edit_tiktok" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><i class="fab fa-youtube text-danger me-2"></i>YouTube URL</label>
                                        <input type="url" name="youtube" id="edit_youtube" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Status -->
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-toggle-on me-2"></i>Status *</label>
                            <select name="status" id="edit_status" class="form-select" required>
                                <option value="active">Active (Visible on website)</option>
                                <option value="inactive">Inactive (Hidden from website)</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="edit_member" class="btn btn-info">
                            <i class="fas fa-save me-1"></i> Update Team Member
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/admin-common.js"></script>
    <script src="js/bulk-actions.js"></script>
    <script>
        // File upload handler
        function handleFileUpload(fileInput, pathInput, previewDiv, uploadType) {
            const file = fileInput.files[0];
            if (!file) return;
            
            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                previewDiv.innerHTML = '<img src="' + e.target.result + '" class="img-thumbnail" style="max-width: 200px;">';
            };
            reader.readAsDataURL(file);
            
            // Upload file
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
                    previewDiv.innerHTML = '';
                }
            })
            .catch(error => {
                alert('Upload error: ' + error);
                previewDiv.innerHTML = '';
            });
        }
        
        // Add modal file upload
        document.getElementById('add_image_file').addEventListener('change', function() {
            handleFileUpload(
                this,
                document.getElementById('add_image_path'),
                document.getElementById('add_image_preview'),
                'team'
            );
        });
        
        // Edit modal file upload
        document.getElementById('edit_image_file').addEventListener('change', function() {
            handleFileUpload(
                this,
                document.getElementById('edit_image'),
                document.getElementById('edit_image_preview'),
                'team'
            );
        });
        
        function editMember(member) {
            document.getElementById('edit_id').value = member.id;
            document.getElementById('edit_name').value = member.name;
            document.getElementById('edit_position').value = member.position;
            document.getElementById('edit_image').value = member.image;
            document.getElementById('edit_bio').value = member.bio || '';
            document.getElementById('edit_tagline').value = member.tagline || '';
            document.getElementById('edit_experience').value = member.experience || '';
            document.getElementById('edit_skills').value = member.skills || '';
            document.getElementById('edit_achievements').value = member.achievements || '';
            document.getElementById('edit_department').value = member.department || '';
            document.getElementById('edit_years_experience').value = member.years_experience || '';
            document.getElementById('edit_email').value = member.email || '';
            document.getElementById('edit_phone').value = member.phone || '';
            document.getElementById('edit_facebook').value = member.facebook || '';
            document.getElementById('edit_instagram').value = member.instagram || '';
            document.getElementById('edit_tiktok').value = member.tiktok || '';
            document.getElementById('edit_youtube').value = member.youtube || '';
            document.getElementById('edit_status').value = member.status;
            
            // Show current image
            document.getElementById('edit_image_preview').innerHTML = 
                '<img src="../' + member.image + '" class="img-thumbnail" style="max-width: 200px;">';
            
            new bootstrap.Modal(document.getElementById('editModal')).show();
        }
    </script>
    <script src="js/admin-enhancements.js"></script>
</body>
</html>
