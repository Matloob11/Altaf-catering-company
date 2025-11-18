<?php
// Include authentication check
require_once 'includes/auth-check.php';

$data_file = 'data/events.json';

// Load events data
function loadEventsData() {
    global $data_file;
    if (file_exists($data_file)) {
        return json_decode(file_get_contents($data_file), true);
    }
    return [];
}

// Save events data
function saveEventsData($data) {
    global $data_file;
    file_put_contents($data_file, json_encode($data, JSON_PRETTY_PRINT));
}

// Handle bulk delete
if (isset($_POST['bulk_delete']) && isset($_POST['selected_ids'])) {
    $selected_ids = array_map('intval', $_POST['selected_ids']);
    $events = loadEventsData();
    $events = array_filter($events, function($item) use ($selected_ids) {
        return !in_array($item['id'], $selected_ids);
    });
    saveEventsData(array_values($events));
    $success = count($selected_ids) . " event(s) deleted successfully!";
}

// Handle bulk publish
if (isset($_POST['bulk_publish']) && isset($_POST['selected_ids'])) {
    $selected_ids = array_map('intval', $_POST['selected_ids']);
    $events = loadEventsData();
    foreach ($events as &$item) {
        if (in_array($item['id'], $selected_ids)) {
            $item['status'] = 'published';
        }
    }
    saveEventsData($events);
    $success = count($selected_ids) . " event(s) published successfully!";
}

// Handle bulk unpublish
if (isset($_POST['bulk_unpublish']) && isset($_POST['selected_ids'])) {
    $selected_ids = array_map('intval', $_POST['selected_ids']);
    $events = loadEventsData();
    foreach ($events as &$item) {
        if (in_array($item['id'], $selected_ids)) {
            $item['status'] = 'draft';
        }
    }
    saveEventsData($events);
    $success = count($selected_ids) . " event(s) unpublished successfully!";
}

// Handle single delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $events = loadEventsData();
    $events = array_filter($events, function($item) use ($id) {
        return $item['id'] != $id;
    });
    saveEventsData(array_values($events));
    $success = "Event deleted successfully!";
}

// Handle add event
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_event'])) {
    $events = loadEventsData();
    
    // Get new ID
    $new_id = empty($events) ? 1 : max(array_column($events, 'id')) + 1;
    
    // Handle image upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../img/events/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = time() . '_' . uniqid() . '.' . $ext;
        $image = 'img/events/' . $filename;
        move_uploaded_file($_FILES['image']['tmp_name'], "../" . $image);
    }
    
    $new_item = [
        'id' => $new_id,
        'title' => $_POST['title'],
        'description' => $_POST['description'],
        'event_type' => $_POST['event_type'],
        'event_date' => $_POST['event_date'],
        'location' => $_POST['location'],
        'image' => $image,
        'status' => $_POST['status'],
        'created_date' => date('Y-m-d')
    ];
    
    $events[] = $new_item;
    saveEventsData($events);
    $success = "Event added successfully!";
}

// Handle update event
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_event'])) {
    $id = intval($_POST['event_id']);
    $events = loadEventsData();
    
    foreach ($events as &$item) {
        if ($item['id'] == $id) {
            $item['title'] = $_POST['title'];
            $item['description'] = $_POST['description'];
            $item['event_type'] = $_POST['event_type'];
            $item['event_date'] = $_POST['event_date'];
            $item['location'] = $_POST['location'];
            $item['status'] = $_POST['status'];
            
            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $target_dir = "../img/events/";
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = time() . '_' . uniqid() . '.' . $ext;
                $item['image'] = 'img/events/' . $filename;
                move_uploaded_file($_FILES['image']['tmp_name'], "../" . $item['image']);
            }
            break;
        }
    }
    
    saveEventsData($events);
    $success = "Event updated successfully!";
}

$events = loadEventsData();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events - Admin Panel</title>
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
                        <i class="fas fa-calendar-alt me-2"></i> Manage Events Gallery
                    </h1>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-info" onclick="window.location.reload()">
                            <i class="fas fa-sync-alt me-1"></i> Refresh
                        </button>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEventModal">
                            <i class="fas fa-plus me-1"></i> Add New Event
                        </button>
                    </div>
                </div>
                
                <?php if (isset($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Filter Tabs -->
                <ul class="nav nav-tabs mb-4" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#all-events" type="button">
                            <i class="fas fa-list"></i> All Events
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#social-events" type="button">
                            <i class="fas fa-users"></i> Social Events
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#professional-events" type="button">
                            <i class="fas fa-briefcase"></i> Professional Events
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content">
                    <!-- All Events Tab -->
                    <div class="tab-pane fade show active" id="all-events">
                        <div class="row g-4">
                            <?php if (!empty($events)): ?>
                                <?php foreach($events as $item): ?>
                                <div class="col-md-6 col-lg-4">
                                    <div class="card shadow h-100">
                                        <?php if (!empty($item['image'])): ?>
                                        <img src="../<?php echo $item['image']; ?>" 
                                             class="card-img-top" 
                                             alt="<?php echo htmlspecialchars($item['title']); ?>"
                                             style="height: 200px; object-fit: cover;">
                                        <?php else: ?>
                                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                                            <i class="fas fa-calendar-alt fa-3x"></i>
                                        </div>
                                        <?php endif; ?>
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo htmlspecialchars($item['title']); ?></h5>
                                            <p class="card-text text-muted small"><?php echo htmlspecialchars($item['description']); ?></p>
                                            <div class="mb-2">
                                                <span class="badge bg-<?php echo $item['event_type'] == 'social' ? 'info' : 'primary'; ?>">
                                                    <i class="fas fa-<?php echo $item['event_type'] == 'social' ? 'users' : 'briefcase'; ?>"></i>
                                                    <?php echo ucfirst($item['event_type']); ?>
                                                </span>
                                                <?php if ($item['status'] == 'published'): ?>
                                                    <span class="badge bg-success">Published</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning">Draft</span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="small text-muted mb-2">
                                                <i class="fas fa-calendar"></i> <?php echo date('M d, Y', strtotime($item['event_date'])); ?><br>
                                                <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($item['location']); ?>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-sm btn-info flex-fill edit-event"
                                                    data-id="<?php echo $item['id']; ?>"
                                                    data-title="<?php echo htmlspecialchars($item['title']); ?>"
                                                    data-description="<?php echo htmlspecialchars($item['description']); ?>"
                                                    data-event_type="<?php echo $item['event_type']; ?>"
                                                    data-event_date="<?php echo $item['event_date']; ?>"
                                                    data-location="<?php echo htmlspecialchars($item['location']); ?>"
                                                    data-status="<?php echo $item['status']; ?>"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editEventModal">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <a href="?delete=<?php echo $item['id']; ?>" 
                                                   class="btn btn-sm btn-danger flex-fill"
                                                   onclick="return confirm('Delete this event?')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        No events found. Add your first event to get started!
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Social Events Tab -->
                    <div class="tab-pane fade" id="social-events">
                        <div class="row g-4">
                            <?php 
                            $social_events = array_filter($events, function($e) { return $e['event_type'] == 'social'; });
                            if (!empty($social_events)): 
                                foreach($social_events as $item): 
                            ?>
                                <div class="col-md-6 col-lg-4">
                                    <div class="card shadow h-100">
                                        <?php if (!empty($item['image'])): ?>
                                        <img src="../<?php echo $item['image']; ?>" 
                                             class="card-img-top" 
                                             alt="<?php echo htmlspecialchars($item['title']); ?>"
                                             style="height: 200px; object-fit: cover;">
                                        <?php else: ?>
                                        <div class="bg-info text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                                            <i class="fas fa-users fa-3x"></i>
                                        </div>
                                        <?php endif; ?>
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo htmlspecialchars($item['title']); ?></h5>
                                            <p class="card-text text-muted small"><?php echo htmlspecialchars($item['description']); ?></p>
                                            <div class="mb-2">
                                                <span class="badge bg-info">
                                                    <i class="fas fa-users"></i> Social Event
                                                </span>
                                                <?php if ($item['status'] == 'published'): ?>
                                                    <span class="badge bg-success">Published</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning">Draft</span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="small text-muted mb-2">
                                                <i class="fas fa-calendar"></i> <?php echo date('M d, Y', strtotime($item['event_date'])); ?><br>
                                                <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($item['location']); ?>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-sm btn-info flex-fill edit-event"
                                                    data-id="<?php echo $item['id']; ?>"
                                                    data-title="<?php echo htmlspecialchars($item['title']); ?>"
                                                    data-description="<?php echo htmlspecialchars($item['description']); ?>"
                                                    data-event_type="<?php echo $item['event_type']; ?>"
                                                    data-event_date="<?php echo $item['event_date']; ?>"
                                                    data-location="<?php echo htmlspecialchars($item['location']); ?>"
                                                    data-status="<?php echo $item['status']; ?>"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editEventModal">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <a href="?delete=<?php echo $item['id']; ?>" 
                                                   class="btn btn-sm btn-danger flex-fill"
                                                   onclick="return confirm('Delete this event?')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php 
                                endforeach;
                            else: 
                            ?>
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        No social events found. Add weddings, birthdays, and other social gatherings!
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Professional Events Tab -->
                    <div class="tab-pane fade" id="professional-events">
                        <div class="row g-4">
                            <?php 
                            $professional_events = array_filter($events, function($e) { return $e['event_type'] == 'professional'; });
                            if (!empty($professional_events)): 
                                foreach($professional_events as $item): 
                            ?>
                                <div class="col-md-6 col-lg-4">
                                    <div class="card shadow h-100">
                                        <?php if (!empty($item['image'])): ?>
                                        <img src="../<?php echo $item['image']; ?>" 
                                             class="card-img-top" 
                                             alt="<?php echo htmlspecialchars($item['title']); ?>"
                                             style="height: 200px; object-fit: cover;">
                                        <?php else: ?>
                                        <div class="bg-primary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                                            <i class="fas fa-briefcase fa-3x"></i>
                                        </div>
                                        <?php endif; ?>
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo htmlspecialchars($item['title']); ?></h5>
                                            <p class="card-text text-muted small"><?php echo htmlspecialchars($item['description']); ?></p>
                                            <div class="mb-2">
                                                <span class="badge bg-primary">
                                                    <i class="fas fa-briefcase"></i> Professional Event
                                                </span>
                                                <?php if ($item['status'] == 'published'): ?>
                                                    <span class="badge bg-success">Published</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning">Draft</span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="small text-muted mb-2">
                                                <i class="fas fa-calendar"></i> <?php echo date('M d, Y', strtotime($item['event_date'])); ?><br>
                                                <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($item['location']); ?>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-sm btn-info flex-fill edit-event"
                                                    data-id="<?php echo $item['id']; ?>"
                                                    data-title="<?php echo htmlspecialchars($item['title']); ?>"
                                                    data-description="<?php echo htmlspecialchars($item['description']); ?>"
                                                    data-event_type="<?php echo $item['event_type']; ?>"
                                                    data-event_date="<?php echo $item['event_date']; ?>"
                                                    data-location="<?php echo htmlspecialchars($item['location']); ?>"
                                                    data-status="<?php echo $item['status']; ?>"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editEventModal">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <a href="?delete=<?php echo $item['id']; ?>" 
                                                   class="btn btn-sm btn-danger flex-fill"
                                                   onclick="return confirm('Delete this event?')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php 
                                endforeach;
                            else: 
                            ?>
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        No professional events found. Add corporate events, conferences, and seminars!
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>


    <!-- Add Event Modal -->
    <div class="modal fade" id="addEventModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Add New Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Event Title *</label>
                                <input type="text" name="title" class="form-control" placeholder="e.g., Grand Wedding Ceremony" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Event Type *</label>
                                <select name="event_type" class="form-select" required>
                                    <option value="">Select Type</option>
                                    <option value="social">Social Event (Wedding, Birthday, etc.)</option>
                                    <option value="professional">Professional Event (Corporate, Conference, etc.)</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description *</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Describe the event..." required></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Event Date *</label>
                                <input type="date" name="event_date" class="form-control" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Location *</label>
                                <input type="text" name="location" class="form-control" placeholder="e.g., Karachi, Pakistan" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Event Image *</label>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
                            <small class="text-muted">Upload a high-quality image (JPG, PNG, max 5MB)</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Status *</label>
                            <select name="status" class="form-select">
                                <option value="draft">Draft (Not visible on website)</option>
                                <option value="published">Published (Visible on website)</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" name="add_event" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Event
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Event Modal -->
    <div class="modal fade" id="editEventModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="event_id" id="edit_event_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Event Title *</label>
                                <input type="text" name="title" id="edit_title" class="form-control" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Event Type *</label>
                                <select name="event_type" id="edit_event_type" class="form-select" required>
                                    <option value="social">Social Event (Wedding, Birthday, etc.)</option>
                                    <option value="professional">Professional Event (Corporate, Conference, etc.)</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description *</label>
                            <textarea name="description" id="edit_description" class="form-control" rows="3" required></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Event Date *</label>
                                <input type="date" name="event_date" id="edit_event_date" class="form-control" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Location *</label>
                                <input type="text" name="location" id="edit_location" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Event Image (leave empty to keep current)</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="text-muted">Upload a new image only if you want to replace the current one</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Status *</label>
                            <select name="status" id="edit_status" class="form-select">
                                <option value="draft">Draft (Not visible on website)</option>
                                <option value="published">Published (Visible on website)</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" name="update_event" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Event
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Edit event modal population
        document.querySelectorAll('.edit-event').forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('edit_event_id').value = this.dataset.id;
                document.getElementById('edit_title').value = this.dataset.title;
                document.getElementById('edit_description').value = this.dataset.description;
                document.getElementById('edit_event_type').value = this.dataset.event_type;
                document.getElementById('edit_event_date').value = this.dataset.event_date;
                document.getElementById('edit_location').value = this.dataset.location;
                document.getElementById('edit_status').value = this.dataset.status;
            });
        });
    </script>
</body>
</html>
