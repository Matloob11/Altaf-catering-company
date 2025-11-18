<?php
// Include authentication check
require_once 'includes/auth-check.php';

// Load contacts data
$contacts_file = 'data/contacts.json';
$contacts = file_exists($contacts_file) ? json_decode(file_get_contents($contacts_file), true) : [];

// Handle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $id = (int)$_POST['id'];
    foreach ($contacts as &$contact) {
        if ($contact['id'] == $id) {
            $contact['status'] = $_POST['status'];
            break;
        }
    }
    file_put_contents($contacts_file, json_encode($contacts, JSON_PRETTY_PRINT));
    $success = "Contact status updated successfully!";
}

// Handle delete
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_contact'])) {
    $id = (int)$_POST['id'];
    $contacts = array_filter($contacts, function($c) use ($id) {
        return $c['id'] != $id;
    });
    $contacts = array_values($contacts);
    file_put_contents($contacts_file, json_encode($contacts, JSON_PRETTY_PRINT));
    $success = "Contact deleted successfully!";
}

// Sort by date, newest first
usort($contacts, function($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages - Altaf Catering Admin</title>
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
                        <i class="fas fa-envelope me-2"></i> Contact Messages
                    </h1>
                    <div class="d-flex gap-2">
                        <span class="badge bg-primary fs-6">Total: <?php echo count($contacts); ?></span>
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
                
                <?php if (empty($contacts)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No contact messages yet.
                    </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($contacts as $contact): ?>
                            <tr>
                                <td><?php echo $contact['id']; ?></td>
                                <td><?php echo htmlspecialchars($contact['name']); ?></td>
                                <td><?php echo htmlspecialchars($contact['email']); ?></td>
                                <td><?php echo htmlspecialchars($contact['subject']); ?></td>
                                <td>
                                    <span class="badge bg-<?php 
                                        echo $contact['status'] == 'new' ? 'primary' : 
                                            ($contact['status'] == 'read' ? 'info' : 'success'); 
                                    ?>">
                                        <?php echo ucfirst($contact['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo $contact['date']; ?></td>
                                <td>
                                    <button class="btn btn-sm btn-info" onclick='viewContact(<?php echo json_encode($contact); ?>)'>
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="id" value="<?php echo $contact['id']; ?>">
                                        <button type="submit" name="delete_contact" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </main>
        </div>
    </div>
    
    <!-- View Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Contact Message Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Name:</strong>
                            <p id="view_name"></p>
                        </div>
                        <div class="col-md-6">
                            <strong>Email:</strong>
                            <p id="view_email"></p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <strong>Subject:</strong>
                        <p id="view_subject"></p>
                    </div>
                    <div class="mb-3">
                        <strong>Message:</strong>
                        <p id="view_message"></p>
                    </div>
                    <div class="mb-3">
                        <strong>Date:</strong>
                        <p id="view_date"></p>
                    </div>
                    <form method="POST">
                        <input type="hidden" name="id" id="status_id">
                        <div class="mb-3">
                            <label class="form-label"><strong>Update Status:</strong></label>
                            <select name="status" id="status_select" class="form-select">
                                <option value="new">New</option>
                                <option value="read">Read</option>
                                <option value="replied">Replied</option>
                            </select>
                        </div>
                        <button type="submit" name="update_status" class="btn btn-primary">Update Status</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function viewContact(contact) {
            document.getElementById('view_name').textContent = contact.name;
            document.getElementById('view_email').textContent = contact.email;
            document.getElementById('view_subject').textContent = contact.subject;
            document.getElementById('view_message').textContent = contact.message;
            document.getElementById('view_date').textContent = contact.date;
            document.getElementById('status_id').value = contact.id;
            document.getElementById('status_select').value = contact.status;
            new bootstrap.Modal(document.getElementById('viewModal')).show();
        }
    </script>
    <script src="js/admin-enhancements.js"></script>
</body>
</html>
