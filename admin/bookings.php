<?php
// Include authentication check
require_once 'includes/auth-check.php';

// Load bookings data
$bookings_file = 'data/bookings.json';
$bookings = file_exists($bookings_file) ? json_decode(file_get_contents($bookings_file), true) : [];

// Handle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $id = (int)$_POST['id'];
    foreach ($bookings as &$booking) {
        if ($booking['id'] == $id) {
            $booking['status'] = $_POST['status'];
            break;
        }
    }
    file_put_contents($bookings_file, json_encode($bookings, JSON_PRETTY_PRINT));
    $success = "Booking status updated successfully!";
}

// Handle delete
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_booking'])) {
    $id = (int)$_POST['id'];
    $bookings = array_filter($bookings, function($b) use ($id) {
        return $b['id'] != $id;
    });
    $bookings = array_values($bookings);
    file_put_contents($bookings_file, json_encode($bookings, JSON_PRETTY_PRINT));
    $success = "Booking deleted successfully!";
}

// Sort by event date
usort($bookings, function($a, $b) {
    return strtotime($b['event_date']) - strtotime($a['event_date']);
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Requests - Altaf Catering Admin</title>
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
                        <i class="fas fa-calendar-check me-2"></i> Booking Requests
                    </h1>
                    <div class="d-flex gap-2">
                        <span class="badge bg-primary fs-6">Total: <?php echo count($bookings); ?></span>
                        <span class="badge bg-warning fs-6">Pending: <?php echo count(array_filter($bookings, fn($b) => $b['status'] == 'pending')); ?></span>
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
                
                <?php if (empty($bookings)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No booking requests yet.
                    </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>City</th>
                                <th>Event Type</th>
                                <th>Event Date</th>
                                <th>Guests</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td><?php echo $booking['id']; ?></td>
                                <td><?php echo htmlspecialchars($booking['name']); ?></td>
                                <td><?php echo htmlspecialchars($booking['phone'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($booking['city'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($booking['event_type']); ?></td>
                                <td><?php echo $booking['event_date']; ?></td>
                                <td><?php echo $booking['guests']; ?></td>
                                <td>
                                    <span class="badge bg-<?php 
                                        echo $booking['status'] == 'pending' ? 'warning' : 
                                            ($booking['status'] == 'confirmed' ? 'success' : 
                                            ($booking['status'] == 'cancelled' ? 'danger' : 'info')); 
                                    ?>">
                                        <?php echo ucfirst($booking['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info" onclick='viewBooking(<?php echo json_encode($booking); ?>)' title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <?php
                                    $customerPhone = preg_replace('/[^0-9]/', '', $booking['phone'] ?? '');
                                    $adminMsg = "Hello " . $booking['name'] . "! Thank you for booking with Altaf Catering.";
                                    $whatsappLink = 'https://wa.me/' . $customerPhone . '?text=' . urlencode($adminMsg);
                                    ?>
                                    <a href="<?php echo $whatsappLink; ?>" target="_blank" class="btn btn-sm btn-success" title="WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="id" value="<?php echo $booking['id']; ?>">
                                        <button type="submit" name="delete_booking" class="btn btn-sm btn-danger" 
                                                onclick="return confirm('Delete this booking?')" title="Delete">
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
                    <h5 class="modal-title">Booking Details</h5>
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
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Phone:</strong>
                            <p id="view_phone"></p>
                        </div>
                        <div class="col-md-6">
                            <strong>City:</strong>
                            <p id="view_city"></p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <strong>Address:</strong>
                        <p id="view_address"></p>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Event Type:</strong>
                            <p id="view_event_type"></p>
                        </div>
                        <div class="col-md-6">
                            <strong>Menu Type:</strong>
                            <p id="view_menu_type"></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Event Date:</strong>
                            <p id="view_event_date"></p>
                        </div>
                        <div class="col-md-6">
                            <strong>Number of Guests:</strong>
                            <p id="view_guests"></p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <strong>Submitted On:</strong>
                        <p id="view_date"></p>
                    </div>
                    <form method="POST">
                        <input type="hidden" name="id" id="status_id">
                        <div class="mb-3">
                            <label class="form-label"><strong>Update Status:</strong></label>
                            <select name="status" id="status_select" class="form-select">
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
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
        function viewBooking(booking) {
            document.getElementById('view_name').textContent = booking.name;
            document.getElementById('view_email').textContent = booking.email;
            document.getElementById('view_phone').textContent = booking.phone || 'N/A';
            document.getElementById('view_city').textContent = booking.city || 'N/A';
            document.getElementById('view_address').textContent = booking.address || 'N/A';
            document.getElementById('view_event_type').textContent = booking.event_type;
            document.getElementById('view_menu_type').textContent = booking.menu_type || 'N/A';
            document.getElementById('view_event_date').textContent = booking.event_date;
            document.getElementById('view_guests').textContent = booking.guests;
            document.getElementById('view_date').textContent = booking.date;
            document.getElementById('status_id').value = booking.id;
            document.getElementById('status_select').value = booking.status;
            new bootstrap.Modal(document.getElementById('viewModal')).show();
        }
    </script>
</body>
</html>
