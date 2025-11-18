<?php
// Include authentication check
require_once 'includes/auth-check.php';

// Load about data
$about_file = 'data/about.json';
$about = file_exists($about_file) ? json_decode(file_get_contents($about_file), true) : [
    'title' => 'About Altaf Catering',
    'subtitle' => 'Trusted By 200+ satisfied clients',
    'description' => 'Welcome to Altaf Catering Company...',
    'mission' => '',
    'vision' => '',
    'values' => [],
    'stats' => [
        'customers' => 689,
        'chefs' => 107,
        'events' => 253
    ],
    'features' => [],
    'video_url' => 'https://youtu.be/ipO6RH1WElQ',
    'images' => [
        'main' => 'img/about.jpg',
        'hero' => 'img/hero.png'
    ]
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_about'])) {
    $about = [
        'title' => $_POST['title'],
        'subtitle' => $_POST['subtitle'],
        'description' => $_POST['description'],
        'mission' => $_POST['mission'],
        'vision' => $_POST['vision'],
        'values' => explode("\n", trim($_POST['values'])),
        'stats' => [
            'customers' => (int)$_POST['customers'],
            'chefs' => (int)$_POST['chefs'],
            'events' => (int)$_POST['events']
        ],
        'features' => explode("\n", trim($_POST['features'])),
        'video_url' => $_POST['video_url'],
        'images' => [
            'main' => $_POST['main_image'],
            'hero' => $_POST['hero_image']
        ]
    ];
    
    file_put_contents($about_file, json_encode($about, JSON_PRETTY_PRINT));
    $success = "About page updated successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Page Management - Altaf Catering Admin</title>
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
                        <i class="fas fa-info-circle me-2"></i> About Page Management
                    </h1>
                    <div class="d-flex gap-2">
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
                
                <form method="POST">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Basic Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Page Title</label>
                                <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($about['title']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Subtitle</label>
                                <input type="text" name="subtitle" class="form-control" value="<?php echo htmlspecialchars($about['subtitle']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Main Description</label>
                                <textarea name="description" class="form-control" rows="5" required><?php echo htmlspecialchars($about['description']); ?></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Mission & Vision</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Mission Statement</label>
                                <textarea name="mission" class="form-control" rows="3"><?php echo htmlspecialchars($about['mission']); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Vision Statement</label>
                                <textarea name="vision" class="form-control" rows="3"><?php echo htmlspecialchars($about['vision']); ?></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Company Values</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Values (one per line)</label>
                                <textarea name="values" class="form-control" rows="5"><?php echo implode("\n", $about['values']); ?></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Statistics</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Happy Customers</label>
                                    <input type="number" name="customers" class="form-control" value="<?php echo $about['stats']['customers']; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Expert Chefs</label>
                                    <input type="number" name="chefs" class="form-control" value="<?php echo $about['stats']['chefs']; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Events Complete</label>
                                    <input type="number" name="events" class="form-control" value="<?php echo $about['stats']['events']; ?>" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Features</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Key Features (one per line)</label>
                                <textarea name="features" class="form-control" rows="6"><?php echo implode("\n", $about['features']); ?></textarea>
                                <small class="text-muted">Example: Fresh and Fast food Delivery</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Media</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Main Image Path</label>
                                    <input type="text" name="main_image" class="form-control" value="<?php echo $about['images']['main']; ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Hero Image Path</label>
                                    <input type="text" name="hero_image" class="form-control" value="<?php echo $about['images']['hero']; ?>" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Video URL (YouTube)</label>
                                <input type="url" name="video_url" class="form-control" value="<?php echo $about['video_url']; ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <button type="submit" name="save_about" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </div>
                </form>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/admin-enhancements.js"></script>
</body>
</html>
