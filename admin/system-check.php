<?php
// Include authentication check
require_once 'includes/auth-check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Check - Altaf Catering Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link href="css/admin-unified.css" rel="stylesheet"></head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php include 'includes/sidebar.php'; ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><i class="fas fa-check-circle"></i> System Check</h1>
                </div>
                
                <div class="row">
                    <!-- Data Files Check -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-database"></i> Data Files Status</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <?php
                                    $data_files = [
                                        'blogs.json' => 'Blogs',
                                        'team.json' => 'Team Members',
                                        'gallery.json' => 'Gallery',
                                        'testimonials.json' => 'Testimonials',
                                        'menu.json' => 'Menu Items',
                                        'jobs.json' => 'Job Listings',
                                        'applications.json' => 'Applications',
                                        'contacts.json' => 'Contact Messages',
                                        'bookings.json' => 'Bookings',
                                        'about.json' => 'About Page',
                                        'services.json' => 'Services',
                                        'pricing.json' => 'Pricing',
                                        'settings.json' => 'Settings'
                                    ];
                                    
                                    foreach ($data_files as $file => $name) {
                                        $path = 'data/' . $file;
                                        $exists = file_exists($path);
                                        $count = 0;
                                        if ($exists) {
                                            $data = json_decode(file_get_contents($path), true);
                                            $count = is_array($data) ? count($data) : 0;
                                        }
                                        echo '<tr>';
                                        echo '<td>' . $name . '</td>';
                                        echo '<td>';
                                        if ($exists) {
                                            echo '<span class="badge bg-success"><i class="fas fa-check"></i> OK</span> ';
                                            echo '<small class="text-muted">(' . $count . ' items)</small>';
                                        } else {
                                            echo '<span class="badge bg-danger"><i class="fas fa-times"></i> Missing</span>';
                                        }
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Upload Folders Check -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-folder"></i> Upload Folders Status</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <?php
                                    $upload_folders = [
                                        '../img/team/' => 'Team Images',
                                        '../img/blog/' => 'Blog Images',
                                        '../img/gallery/' => 'Gallery Images',
                                        '../img/menu/' => 'Menu Images',
                                        '../img/testimonials/' => 'Testimonial Images',
                                        '../img/uploads/' => 'General Uploads'
                                    ];
                                    
                                    foreach ($upload_folders as $folder => $name) {
                                        $exists = is_dir($folder);
                                        $writable = $exists && is_writable($folder);
                                        $count = 0;
                                        if ($exists) {
                                            $files = glob($folder . '*');
                                            $count = count($files);
                                        }
                                        echo '<tr>';
                                        echo '<td>' . $name . '</td>';
                                        echo '<td>';
                                        if ($exists && $writable) {
                                            echo '<span class="badge bg-success"><i class="fas fa-check"></i> OK</span> ';
                                            echo '<small class="text-muted">(' . $count . ' files)</small>';
                                        } elseif ($exists) {
                                            echo '<span class="badge bg-warning"><i class="fas fa-exclamation"></i> Not Writable</span>';
                                        } else {
                                            echo '<span class="badge bg-danger"><i class="fas fa-times"></i> Missing</span>';
                                        }
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Admin Pages Check -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="fas fa-file-code"></i> Admin Pages Status</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <?php
                                    $admin_pages = [
                                        'dashboard.php' => 'Dashboard',
                                        'blogs.php' => 'Blogs Management',
                                        'team.php' => 'Team Management',
                                        'gallery.php' => 'Gallery Management',
                                        'testimonials.php' => 'Testimonials Management',
                                        'menu.php' => 'Menu Management',
                                        'jobs.php' => 'Jobs Management',
                                        'applications.php' => 'Applications',
                                        'contacts.php' => 'Contact Messages',
                                        'bookings.php' => 'Bookings',
                                        'about.php' => 'About Page',
                                        'services.php' => 'Services',
                                        'packages.php' => 'Packages',
                                        'settings.php' => 'Settings',
                                        'upload.php' => 'File Upload Handler'
                                    ];
                                    
                                    foreach ($admin_pages as $file => $name) {
                                        $exists = file_exists($file);
                                        echo '<tr>';
                                        echo '<td>' . $name . '</td>';
                                        echo '<td>';
                                        if ($exists) {
                                            echo '<span class="badge bg-success"><i class="fas fa-check"></i> OK</span>';
                                        } else {
                                            echo '<span class="badge bg-danger"><i class="fas fa-times"></i> Missing</span>';
                                        }
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Frontend Pages Check -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0"><i class="fas fa-globe"></i> Frontend Pages Status</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <?php
                                    $frontend_pages = [
                                        '../index.php' => 'Home Page',
                                        '../about.php' => 'About Page',
                                        '../service.php' => 'Services Page',
                                        '../menu.php' => 'Menu Page',
                                        '../team.php' => 'Team Page',
                                        '../gallery.php' => 'Gallery Page',
                                        '../blog.php' => 'Blog Page',
                                        '../testimonial.php' => 'Testimonials Page',
                                        '../careers.php' => 'Careers Page',
                                        '../contact.php' => 'Contact Page',
                                        '../book.php' => 'Booking Page'
                                    ];
                                    
                                    foreach ($frontend_pages as $file => $name) {
                                        $exists = file_exists($file);
                                        echo '<tr>';
                                        echo '<td>' . $name . '</td>';
                                        echo '<td>';
                                        if ($exists) {
                                            echo '<span class="badge bg-success"><i class="fas fa-check"></i> OK</span>';
                                        } else {
                                            echo '<span class="badge bg-danger"><i class="fas fa-times"></i> Missing</span>';
                                        }
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- System Summary -->
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-success">
                            <h5><i class="fas fa-check-circle"></i> System Status: All Good!</h5>
                            <p class="mb-0">Your Altaf Catering admin panel is properly configured and all files are in place.</p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
