<?php
// Include authentication check
require_once 'includes/auth-check.php';

// Load jobs data
$jobs_file = 'data/jobs.json';
$jobs = file_exists($jobs_file) ? json_decode(file_get_contents($jobs_file), true) : [];

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_job'])) {
        $new_id = empty($jobs) ? 1 : max(array_column($jobs, 'id')) + 1;
        
        $new_job = [
            'id' => $new_id,
            'title' => $_POST['title'],
            'department' => $_POST['department'],
            'location' => $_POST['location'],
            'type' => $_POST['type'],
            'description' => $_POST['description'],
            'requirements' => $_POST['requirements'],
            'salary' => $_POST['salary'],
            'status' => $_POST['status'],
            'posted_date' => date('Y-m-d')
        ];
        
        $jobs[] = $new_job;
        file_put_contents($jobs_file, json_encode($jobs, JSON_PRETTY_PRINT));
        $success = "Job listing added successfully!";
    }
    
    if (isset($_POST['edit_job'])) {
        $id = (int)$_POST['id'];
        foreach ($jobs as &$job) {
            if ($job['id'] == $id) {
                $job['title'] = $_POST['title'];
                $job['department'] = $_POST['department'];
                $job['location'] = $_POST['location'];
                $job['type'] = $_POST['type'];
                $job['description'] = $_POST['description'];
                $job['requirements'] = $_POST['requirements'];
                $job['salary'] = $_POST['salary'];
                $job['status'] = $_POST['status'];
                break;
            }
        }
        file_put_contents($jobs_file, json_encode($jobs, JSON_PRETTY_PRINT));
        $success = "Job listing updated successfully!";
    }
    
    if (isset($_POST['delete_job'])) {
        $id = (int)$_POST['id'];
        $jobs = array_filter($jobs, function($job) use ($id) {
            return $job['id'] != $id;
        });
        $jobs = array_values($jobs);
        file_put_contents($jobs_file, json_encode($jobs, JSON_PRETTY_PRINT));
        $success = "Job listing deleted successfully!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Listings - Altaf Catering Admin</title>
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
                        <i class="fas fa-briefcase me-2"></i> Job Listings
                    </h1>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-info" onclick="window.location.reload()">
                            <i class="fas fa-sync-alt me-1"></i> Refresh
                        </button>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                            <i class="fas fa-plus me-1"></i> Add Job Listing
                        </button>
                    </div>
                </div>
                
                <?php if (isset($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?php echo $success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Department</th>
                                <th>Location</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Posted Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($jobs as $job): ?>
                            <tr>
                                <td><?php echo $job['id']; ?></td>
                                <td><?php echo htmlspecialchars($job['title']); ?></td>
                                <td><?php echo htmlspecialchars($job['department']); ?></td>
                                <td><?php echo htmlspecialchars($job['location']); ?></td>
                                <td><span class="badge bg-info"><?php echo $job['type']; ?></span></td>
                                <td>
                                    <span class="badge bg-<?php echo $job['status'] == 'active' ? 'success' : 'secondary'; ?>">
                                        <?php echo ucfirst($job['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo $job['posted_date']; ?></td>
                                <td>
                                    <button class="btn btn-sm btn-info" onclick='editJob(<?php echo json_encode($job); ?>)'>
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="id" value="<?php echo $job['id']; ?>">
                                        <button type="submit" name="delete_job" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
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
                    <h5 class="modal-title">Add Job Listing</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Job Title *</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Department *</label>
                                <select name="department" class="form-select" required>
                                    <option value="Kitchen">Kitchen</option>
                                    <option value="Service">Service</option>
                                    <option value="Management">Management</option>
                                    <option value="Operations">Operations</option>
                                    <option value="Sales">Sales</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Location *</label>
                                <input type="text" name="location" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Job Type *</label>
                                <select name="type" class="form-select" required>
                                    <option value="Full-time">Full-time</option>
                                    <option value="Part-time">Part-time</option>
                                    <option value="Contract">Contract</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Salary Range</label>
                                <input type="text" name="salary" class="form-control" placeholder="e.g., 30,000 - 50,000">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description *</label>
                            <textarea name="description" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Requirements *</label>
                            <textarea name="requirements" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status *</label>
                            <select name="status" class="form-select" required>
                                <option value="active">Active</option>
                                <option value="closed">Closed</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add_job" class="btn btn-primary">Add Job</button>
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
                    <h5 class="modal-title">Edit Job Listing</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Job Title *</label>
                                <input type="text" name="title" id="edit_title" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Department *</label>
                                <select name="department" id="edit_department" class="form-select" required>
                                    <option value="Kitchen">Kitchen</option>
                                    <option value="Service">Service</option>
                                    <option value="Management">Management</option>
                                    <option value="Operations">Operations</option>
                                    <option value="Sales">Sales</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Location *</label>
                                <input type="text" name="location" id="edit_location" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Job Type *</label>
                                <select name="type" id="edit_type" class="form-select" required>
                                    <option value="Full-time">Full-time</option>
                                    <option value="Part-time">Part-time</option>
                                    <option value="Contract">Contract</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Salary Range</label>
                                <input type="text" name="salary" id="edit_salary" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description *</label>
                            <textarea name="description" id="edit_description" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Requirements *</label>
                            <textarea name="requirements" id="edit_requirements" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status *</label>
                            <select name="status" id="edit_status" class="form-select" required>
                                <option value="active">Active</option>
                                <option value="closed">Closed</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="edit_job" class="btn btn-primary">Update Job</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editJob(job) {
            document.getElementById('edit_id').value = job.id;
            document.getElementById('edit_title').value = job.title;
            document.getElementById('edit_department').value = job.department;
            document.getElementById('edit_location').value = job.location;
            document.getElementById('edit_type').value = job.type;
            document.getElementById('edit_salary').value = job.salary;
            document.getElementById('edit_description').value = job.description;
            document.getElementById('edit_requirements').value = job.requirements;
            document.getElementById('edit_status').value = job.status;
            new bootstrap.Modal(document.getElementById('editModal')).show();
        }
    </script>
    <script src="js/admin-enhancements.js"></script>
</body>
</html>
