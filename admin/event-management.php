<?php
// Include authentication check
require_once 'includes/auth-check.php';

$events_file = 'data/events.json';
$moments_file = 'data/moments.json';

// Load data functions
function loadData($file) {
    if (file_exists($file)) {
        return json_decode(file_get_contents($file), true);
    }
    return [];
}

function saveData($file, $data) {
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}

// Handle Events (Social & Professional)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_event'])) {
    $events = loadData($events_file);
    $new_id = empty($events) ? 1 : max(array_column($events, 'id')) + 1;
    
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../img/events/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = time() . '_' . uniqid() . '.' . $ext;
        $image = 'img/events/' . $filename;
        move_uploaded_file($_FILES['image']['tmp_name'], "../" . $image);
    }
    
    $events[] = [
        'id' => $new_id,
        'title' => $_POST['title'],
        'category' => $_POST['category'],
        'image' => $image,
        'status' => $_POST['status'],
        'created_date' => date('Y-m-d')
    ];
    
    saveData($events_file, $events);
    $success = "Event added successfully!";
}

// Handle Moments Gallery
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_moment'])) {
    $moments = loadData($moments_file);
    $new_id = empty($moments) ? 1 : max(array_column($moments, 'id')) + 1;
    
    $image = '';
    if (isset($_FILES['moment_image']) && $_FILES['moment_image']['error'] == 0) {
        $target_dir = "../img/gallery/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        $ext = pathinfo($_FILES['moment_image']['name'], PATHINFO_EXTENSION);
        $filename = 'moment_' . time() . '_' . uniqid() . '.' . $ext;
        $image = 'img/gallery/' . $filename;
        move_uploaded_file($_FILES['moment_image']['tmp_name'], "../" . $image);
    }
    
    $moments[] = [
        'id' => $new_id,
        'title' => $_POST['moment_title'],
        'description' => $_POST['moment_description'],
        'image' => $image,
        'status' => $_POST['moment_status'],
        'created_date' => date('Y-m-d')
    ];
    
    saveData($moments_file, $moments);
    $success = "Moment added successfully!";
}

// Handle Delete Event
if (isset($_GET['delete_event'])) {
    $id = intval($_GET['delete_event']);
    $events = loadData($events_file);
    $events = array_filter($events, function($item) use ($id) {
        return $item['id'] != $id;
    });
    saveData($events_file, array_values($events));
    $success = "Event deleted!";
}

// Handle Delete Moment
if (isset($_GET['delete_moment'])) {
    $id = intval($_GET['delete_moment']);
    $moments = loadData($moments_file);
    $moments = array_filter($moments, function($item) use ($id) {
        return $item['id'] != $id;
    });
    saveData($moments_file, array_values($moments));
    $success = "Moment deleted!";
}

$events = loadData($events_file);
$moments = loadData($moments_file);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management - Admin Panel</title>
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
                        <i class="fas fa-calendar-alt me-2"></i> Event Page Management
                    </h1>
                </div>
                
                <?php if (isset($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- SECTION 1: Social & Professional Events Gallery -->
                <div class="card shadow mb-5">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-images me-2"></i> Social & Professional Events Gallery
                            <small class="float-end">Upper Section of Event Page</small>
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <p class="text-muted mb-0">Manage events shown in tabs: Wedding, Corporate, Cocktail, Buffet</p>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addEventModal">
                                <i class="fas fa-plus me-1"></i> Add Event
                            </button>
                        </div>
                        
                        <!-- Events Tabs -->
                        <ul class="nav nav-tabs mb-3" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#all-events">All Events</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#wedding-events">Wedding</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#corporate-events">Corporate</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#cocktail-events">Cocktail</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#buffet-events">Buffet</button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- All Events -->
                            <div class="tab-pane fade show active" id="all-events">
                                <div class="row g-3">
                                    <?php if (!empty($events)): ?>
                                        <?php foreach($events as $event): ?>
                                        <div class="col-md-3">
                                            <div class="card h-100">
                                                <?php if (!empty($event['image'])): ?>
                                                <img src="../<?php echo $event['image']; ?>" class="card-img-top" style="height: 150px; object-fit: cover;">
                                                <?php endif; ?>
                                                <div class="card-body p-2">
                                                    <h6 class="card-title mb-1"><?php echo htmlspecialchars($event['title']); ?></h6>
                                                    <span class="badge bg-info"><?php echo ucfirst($event['category']); ?></span>
                                                    <?php if ($event['status'] == 'published'): ?>
                                                        <span class="badge bg-success">Published</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning">Draft</span>
                                                    <?php endif; ?>
                                                    <div class="mt-2">
                                                        <a href="?delete_event=<?php echo $event['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="col-12">
                                            <div class="alert alert-info">No events found. Add your first event!</div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Wedding Events -->
                            <div class="tab-pane fade" id="wedding-events">
                                <div class="row g-3">
                                    <?php 
                                    $wedding_events = array_filter($events, function($e) { return $e['category'] == 'wedding'; });
                                    if (!empty($wedding_events)): 
                                        foreach($wedding_events as $event): 
                                    ?>
                                        <div class="col-md-3">
                                            <div class="card h-100">
                                                <?php if (!empty($event['image'])): ?>
                                                <img src="../<?php echo $event['image']; ?>" class="card-img-top" style="height: 150px; object-fit: cover;">
                                                <?php endif; ?>
                                                <div class="card-body p-2">
                                                    <h6><?php echo htmlspecialchars($event['title']); ?></h6>
                                                    <a href="?delete_event=<?php echo $event['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; else: ?>
                                        <div class="col-12"><div class="alert alert-info">No wedding events</div></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Corporate Events -->
                            <div class="tab-pane fade" id="corporate-events">
                                <div class="row g-3">
                                    <?php 
                                    $corporate_events = array_filter($events, function($e) { return $e['category'] == 'corporate'; });
                                    if (!empty($corporate_events)): 
                                        foreach($corporate_events as $event): 
                                    ?>
                                        <div class="col-md-3">
                                            <div class="card h-100">
                                                <?php if (!empty($event['image'])): ?>
                                                <img src="../<?php echo $event['image']; ?>" class="card-img-top" style="height: 150px; object-fit: cover;">
                                                <?php endif; ?>
                                                <div class="card-body p-2">
                                                    <h6><?php echo htmlspecialchars($event['title']); ?></h6>
                                                    <a href="?delete_event=<?php echo $event['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; else: ?>
                                        <div class="col-12"><div class="alert alert-info">No corporate events</div></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Cocktail Events -->
                            <div class="tab-pane fade" id="cocktail-events">
                                <div class="row g-3">
                                    <?php 
                                    $cocktail_events = array_filter($events, function($e) { return $e['category'] == 'cocktail'; });
                                    if (!empty($cocktail_events)): 
                                        foreach($cocktail_events as $event): 
                                    ?>
                                        <div class="col-md-3">
                                            <div class="card h-100">
                                                <?php if (!empty($event['image'])): ?>
                                                <img src="../<?php echo $event['image']; ?>" class="card-img-top" style="height: 150px; object-fit: cover;">
                                                <?php endif; ?>
                                                <div class="card-body p-2">
                                                    <h6><?php echo htmlspecialchars($event['title']); ?></h6>
                                                    <a href="?delete_event=<?php echo $event['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; else: ?>
                                        <div class="col-12"><div class="alert alert-info">No cocktail events</div></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Buffet Events -->
                            <div class="tab-pane fade" id="buffet-events">
                                <div class="row g-3">
                                    <?php 
                                    $buffet_events = array_filter($events, function($e) { return $e['category'] == 'buffet'; });
                                    if (!empty($buffet_events)): 
                                        foreach($buffet_events as $event): 
                                    ?>
                                        <div class="col-md-3">
                                            <div class="card h-100">
                                                <?php if (!empty($event['image'])): ?>
                                                <img src="../<?php echo $event['image']; ?>" class="card-img-top" style="height: 150px; object-fit: cover;">
                                                <?php endif; ?>
                                                <div class="card-body p-2">
                                                    <h6><?php echo htmlspecialchars($event['title']); ?></h6>
                                                    <a href="?delete_event=<?php echo $event['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; else: ?>
                                        <div class="col-12"><div class="alert alert-info">No buffet events</div></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: Moments We Captured Gallery -->
                <div class="card shadow mb-5">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-camera me-2"></i> Moments We Captured Gallery
                            <small class="float-end">Lower Section of Event Page</small>
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <p class="text-muted mb-0">Manage gallery images with titles and descriptions</p>
                            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addMomentModal">
                                <i class="fas fa-plus me-1"></i> Add Moment
                            </button>
                        </div>
                        
                        <div class="row g-3">
                            <?php if (!empty($moments)): ?>
                                <?php foreach($moments as $moment): ?>
                                <div class="col-md-4">
                                    <div class="card h-100">
                                        <?php if (!empty($moment['image'])): ?>
                                        <img src="../<?php echo $moment['image']; ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                                        <?php endif; ?>
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo htmlspecialchars($moment['title']); ?></h5>
                                            <p class="card-text text-muted small"><?php echo htmlspecialchars($moment['description']); ?></p>
                                            <?php if ($moment['status'] == 'published'): ?>
                                                <span class="badge bg-success">Published</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">Draft</span>
                                            <?php endif; ?>
                                            <div class="mt-2">
                                                <a href="?delete_moment=<?php echo $moment['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this moment?')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="col-12">
                                    <div class="alert alert-info">No moments found. Add your first gallery moment!</div>
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
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Add Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Event Title *</label>
                            <input type="text" name="title" class="form-control" placeholder="e.g., Grand Wedding" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Category *</label>
                            <select name="category" class="form-select" required>
                                <option value="">Select Category</option>
                                <option value="wedding">Wedding</option>
                                <option value="corporate">Corporate</option>
                                <option value="cocktail">Cocktail</option>
                                <option value="buffet">Buffet</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Event Image *</label>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
                            <small class="text-muted">Upload high-quality image (JPG, PNG)</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Status *</label>
                            <select name="status" class="form-select">
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_event" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Event
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Moment Modal -->
    <div class="modal fade" id="addMomentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-camera me-2"></i>Add Moment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Title *</label>
                            <input type="text" name="moment_title" class="form-control" placeholder="e.g., Wedding Arrangements" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description *</label>
                            <textarea name="moment_description" class="form-control" rows="3" placeholder="Describe this moment..." required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Image *</label>
                            <input type="file" name="moment_image" class="form-control" accept="image/*" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Status *</label>
                            <select name="moment_status" class="form-select">
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_moment" class="btn btn-success">
                            <i class="fas fa-save"></i> Save Moment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
