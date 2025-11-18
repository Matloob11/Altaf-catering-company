<?php
// Prevent caching to always show latest data
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

// Load gallery data from JSON
$gallery_data = [];
if (file_exists('admin/data/gallery.json')) {
    $all_gallery = json_decode(file_get_contents('admin/data/gallery.json'), true);
    // Filter only published gallery items
    $gallery_data = array_filter($all_gallery, function($item) {
        return $item['status'] == 'published';
    });
    $gallery_data = array_values($gallery_data);
    // Sort by ID descending to get latest entries first
    usort($gallery_data, function($a, $b) {
        return $b['id'] - $a['id'];
    });
}

// Load Analytics
require_once 'includes/analytics.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo getAnalyticsScript(); ?>
    <meta charset="utf-8">
    <title>Photo Gallery — Altaf Catering Events & Food</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="keywords" content="catering gallery, food photos, event photos, wedding gallery, Altaf Catering">
    <meta name="description"
        content="Browse our stunning photo gallery featuring beautiful events, delicious food, and happy clients. See why Altaf Catering is the best choice.">
    <link rel="canonical" href="https://altafcatering.com/gallery.html" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Photo Gallery — Altaf Catering" />
    <meta property="og:description" content="Beautiful photos from our events and catering services." />
    <meta property="og:url" content="https://altafcatering.com/gallery.html" />
    <meta property="og:image" content="https://altafcatering.com/img/hero.png" />
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <meta name="theme-color" content="#0d6efd">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Playball&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/owl.carousel.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/loader.css" rel="stylesheet">
    <link href="css/gallery-professional.css" rel="stylesheet">
    
    <!-- CRITICAL: Text Visibility Fix -->
    <link href="css/text-fix.css" rel="stylesheet">
    
    <!-- CRITICAL: Hover State Fix -->
    <link href="css/hover-fix.css" rel="stylesheet">
    
    <!-- CRITICAL: Force 2 columns minimum - Inline CSS -->
    <style>
        /* Override Bootstrap - Force minimum 2 columns */
        .gallery-item {
            width: 50% !important;
            flex: 0 0 50% !important;
            max-width: 50% !important;
            min-width: 50% !important;
            float: left !important;
        }
        
        /* Desktop - 3 columns */
        @media (min-width: 992px) {
            .gallery-item {
                width: 33.333333% !important;
                flex: 0 0 33.333333% !important;
                max-width: 33.333333% !important;
            }
        }
        
        /* Tablet - 2 columns */
        @media (min-width: 768px) and (max-width: 991px) {
            .gallery-item {
                width: 50% !important;
                flex: 0 0 50% !important;
                max-width: 50% !important;
            }
        }
        
        /* Mobile and below - FORCE 2 columns */
        @media (max-width: 767px) {
            .gallery-item {
                width: 50% !important;
                flex: 0 0 50% !important;
                max-width: 50% !important;
                min-width: 50% !important;
            }
        }
        
        /* Clear floats */
        .row.g-4::after {
            content: "";
            display: table;
            clear: both;
        }
        
        /* Fix image aspect ratio and card heights */
        .gallery-card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .gallery-image-wrapper {
            position: relative;
            width: 100%;
            padding-top: 75%; /* 4:3 aspect ratio */
            overflow: hidden;
            background: #000;
        }
        
        .gallery-image-wrapper img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover !important;
            object-position: center !important;
        }
        
        .gallery-card-footer {
            margin-top: auto;
        }
        
        /* Overlay text centering */
        .gallery-overlay-modern {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            opacity: 0 !important;
            visibility: hidden !important;
            transition: opacity 0.4s ease, visibility 0.4s ease !important;
        }
        
        .gallery-card:hover .gallery-overlay-modern {
            opacity: 1 !important;
            visibility: visible !important;
        }
        
        /* Hide overlay by default - CRITICAL FIX */
        .gallery-overlay-modern {
            display: none !important;
        }
        
        .gallery-card:hover .gallery-overlay-modern {
            display: flex !important;
            animation: fadeInOverlay 0.4s ease forwards;
        }
        
        @keyframes fadeInOverlay {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        /* Mobile adjustments */
        @media (max-width: 767px) {
            .gallery-image-wrapper {
                padding-top: 100%; /* Square on mobile */
            }
            
            .gallery-card-footer h6 {
                font-size: 0.9rem;
            }
            
            .gallery-card-footer small {
                font-size: 0.75rem;
            }
        }
    </style>
</head>

<body>
    <?php include 'includes/contact-buttons.php'; ?>
    <?php $loader_text = "Loading Gallery..."; include 'includes/loader.php'; ?>

    <!-- Modal Search Start -->
    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchModalLabel">Search the site</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex align-items-start flex-column">
                    <form id="siteSearchForm" class="w-100">
                        <div class="input-group w-75 mx-auto mb-3">
                            <input id="siteSearchInput" name="q" type="search" class="form-control bg-transparent p-3"
                                placeholder="Type keywords and press Enter (e.g. wedding, menu, booking)"
                                aria-label="Search site">
                            <button class="btn btn-primary" type="submit" aria-label="Search"><i
                                    class="fa fa-search"></i></button>
                        </div>
                    </form>
                    <div class="container">
                        <div id="searchStatus" class="text-center text-muted mb-3">Enter a keyword to search the site.
                        </div>
                        <div id="searchResults" class="row g-3 justify-content-center"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'includes/navbar.php'; ?>

    <!-- Hero Start -->
    <div class="container-fluid bg-light py-6 my-6 mt-0">
        <div class="container text-center animated bounceInDown">
            <h1 class="display-1 mb-4">Photo Gallery</h1>
            <ol class="breadcrumb justify-content-center mb-0 animated bounceInDown">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item text-dark" aria-current="page">Gallery</li>
            </ol>
        </div>
    </div>
    <!-- Hero End -->

    <!-- Gallery Start -->
    <div class="container-fluid gallery py-6" style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);">
        <div class="container">
            <!-- Header Section -->
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s" style="margin-bottom: 3rem;">
                <div class="d-inline-block position-relative mb-3">
                    <small class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-2 shadow-sm">
                        <i class="fas fa-images me-2"></i>Our Gallery
                    </small>
                </div>
                <h1 class="display-4 mb-3 fw-bold" style="color: #2c3e50;">
                    Moments We've <span style="color: #FEA116;">Created</span>
                </h1>
                <p class="lead text-muted mb-4" style="max-width: 700px; margin: 0 auto;">
                    Explore our stunning collection of events, celebrations, and culinary masterpieces
                </p>
                <div class="d-flex justify-content-center align-items-center gap-3 flex-wrap">
                    <span class="badge bg-light text-dark px-3 py-2">
                        <i class="fas fa-check-circle text-success me-1"></i>
                        <?php echo count($gallery_data); ?>+ Events Captured
                    </span>
                    <span class="badge bg-light text-dark px-3 py-2">
                        <i class="fas fa-star text-warning me-1"></i>
                        Professional Photography
                    </span>
                    <span class="badge bg-light text-dark px-3 py-2">
                        <i class="fas fa-heart text-danger me-1"></i>
                        Memorable Moments
                    </span>
                </div>
            </div>

            <!-- Filter Buttons - Modern Design -->
            <div class="text-center mb-5 wow fadeInUp" data-wow-delay="0.2s">
                <div class="btn-group-modern d-inline-flex flex-wrap justify-content-center gap-2 p-3 bg-white rounded-4 shadow-sm">
                    <button class="btn btn-filter filter-btn active" data-filter="all">
                        <i class="fas fa-th me-2"></i>
                        <span>All Photos</span>
                        <span class="badge bg-primary ms-2"><?php echo count($gallery_data); ?></span>
                    </button>
                    <button class="btn btn-filter filter-btn" data-filter="social">
                        <i class="fas fa-users me-2"></i>
                        <span>Social Events</span>
                        <span class="badge bg-info ms-2">
                            <?php 
                            $social_cats = ['wedding', 'birthday', 'anniversary', 'family-gathering', 'engagement'];
                            echo count(array_filter($gallery_data, function($item) use ($social_cats) {
                                return in_array($item['category'], $social_cats);
                            }));
                            ?>
                        </span>
                    </button>
                    <button class="btn btn-filter filter-btn" data-filter="professional">
                        <i class="fas fa-briefcase me-2"></i>
                        <span>Professional</span>
                        <span class="badge bg-success ms-2">
                            <?php 
                            $prof_cats = ['corporate', 'conference', 'seminar', 'business-meeting', 'product-launch'];
                            echo count(array_filter($gallery_data, function($item) use ($prof_cats) {
                                return in_array($item['category'], $prof_cats);
                            }));
                            ?>
                        </span>
                    </button>
                    <button class="btn btn-filter filter-btn" data-filter="food">
                        <i class="fas fa-utensils me-2"></i>
                        <span>Food</span>
                        <span class="badge bg-warning ms-2">
                            <?php echo count(array_filter($gallery_data, function($item) {
                                return $item['category'] == 'food';
                            })); ?>
                        </span>
                    </button>
                    <button class="btn btn-filter filter-btn" data-filter="decor">
                        <i class="fas fa-paint-brush me-2"></i>
                        <span>Decor</span>
                        <span class="badge bg-secondary ms-2">
                            <?php echo count(array_filter($gallery_data, function($item) {
                                return in_array($item['category'], ['setup', 'outdoor']);
                            })); ?>
                        </span>
                    </button>
                </div>
            </div>

            <!-- Gallery Grid -->
            <div class="row g-4" id="galleryContainer">
                <?php if (!empty($gallery_data)): ?>
                    <?php 
                    $delay = 0.1;
                    foreach($gallery_data as $item): 
                        // Determine category class for filtering
                        $social_categories = ['wedding', 'birthday', 'anniversary', 'family-gathering', 'engagement'];
                        $professional_categories = ['corporate', 'conference', 'seminar', 'business-meeting', 'product-launch'];
                        
                        $category_class = $item['category'];
                        if (in_array($item['category'], $social_categories)) {
                            $category_class .= ' social';
                        }
                        if (in_array($item['category'], $professional_categories)) {
                            $category_class .= ' professional';
                        }
                        
                        // Get category display name
                        $category_names = [
                            'wedding' => 'Wedding',
                            'birthday' => 'Birthday',
                            'anniversary' => 'Anniversary',
                            'family-gathering' => 'Family Gathering',
                            'engagement' => 'Engagement',
                            'corporate' => 'Corporate Event',
                            'conference' => 'Conference',
                            'seminar' => 'Seminar',
                            'business-meeting' => 'Business Meeting',
                            'product-launch' => 'Product Launch',
                            'food' => 'Food',
                            'setup' => 'Setup & Decor',
                            'outdoor' => 'Outdoor Event',
                            'other' => 'Other'
                        ];
                        $category_display = isset($category_names[$item['category']]) ? $category_names[$item['category']] : ucfirst($item['category']);
                    ?>
                <div class="col-lg-4 col-md-6 col-sm-6 col-6 gallery-item <?php echo $category_class; ?> wow fadeInUp" data-wow-delay="<?php echo $delay; ?>s">
                    <div class="gallery-card position-relative overflow-hidden rounded-4 shadow-hover">
                        <!-- Image Container -->
                        <div class="gallery-image-wrapper position-relative">
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" 
                                 class="img-fluid w-100 gallery-image"
                                 alt="<?php echo htmlspecialchars($item['title']); ?>"
                                 style="height: 350px; object-fit: cover; transition: transform 0.5s ease;">
                            
                            <!-- Category Badge -->
                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge badge-category px-3 py-2 rounded-pill shadow-sm">
                                    <?php 
                                    $icon_map = [
                                        'wedding' => 'fa-ring', 'birthday' => 'fa-birthday-cake',
                                        'corporate' => 'fa-building', 'conference' => 'fa-users',
                                        'food' => 'fa-utensils', 'setup' => 'fa-paint-brush'
                                    ];
                                    $icon = isset($icon_map[$item['category']]) ? $icon_map[$item['category']] : 'fa-camera';
                                    ?>
                                    <i class="fas <?php echo $icon; ?> me-1"></i>
                                    <?php echo $category_display; ?>
                                </span>
                            </div>
                            
                            <!-- Hover Overlay -->
                            <div class="gallery-overlay-modern d-flex flex-column align-items-center justify-content-center">
                                <div class="text-center text-white p-4">
                                    <h5 class="mb-3 fw-bold"><?php echo htmlspecialchars($item['title']); ?></h5>
                                    <p class="mb-4 small"><?php echo htmlspecialchars(substr($item['description'], 0, 80)) . '...'; ?></p>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="<?php echo htmlspecialchars($item['image']); ?>" 
                                           data-lightbox="gallery" 
                                           data-title="<?php echo htmlspecialchars($item['title']); ?>"
                                           class="btn btn-light btn-sm rounded-pill px-4 shadow">
                                            <i class="fas fa-search-plus me-2"></i>View Full
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Card Footer -->
                        <div class="gallery-card-footer bg-white p-3 border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark"><?php echo htmlspecialchars($item['title']); ?></h6>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        <?php echo date('M d, Y', strtotime($item['date'])); ?>
                                    </small>
                                </div>
                                <div>
                                    <a href="<?php echo htmlspecialchars($item['image']); ?>" 
                                       data-lightbox="gallery"
                                       class="btn btn-primary btn-sm rounded-circle"
                                       style="width: 35px; height: 35px; padding: 0; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <?php 
                    $delay += 0.1;
                    if ($delay > 0.9) $delay = 0.1;
                    endforeach; 
                    ?>
                <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i>
                        No gallery items available at the moment. Check back soon!
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="text-center mt-5 wow bounceInUp" data-wow-delay="0.1s">
                <p class="lead mb-4">Want to see your event featured here?</p>
                <a href="contact.php" class="btn btn-primary py-3 px-5 rounded-pill">Book Your Event Now</a>
            </div>
        </div>
    </div>
    <!-- Gallery End -->

    <!-- Footer Start -->
    <div class="container-fluid footer py-6 my-6 mb-0 bg-light wow bounceInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="footer-item">
                        <div class="d-flex align-items-center">
                            <img src="img/logo.png" alt="Altaf Catering" style="height:40px; width:auto;" class="me-2">
                            <span class="h6 mb-0 fw-bold text-primary d-none d-sm-inline">Altaf<span
                                    class="text-dark">Catering</span></span>
                        </div>
                        <p class="lh-lg mb-4">Altaf Catering — fresh seasonal menus and professional event services
                            across Pakistan.</p>
                        <div class="footer-icon d-flex">
                            <a class="btn btn-primary btn-sm-square me-2 rounded-circle"
                                href="https://web.facebook.com/AltafCateringCompany?mibextid=ZbWKwL&_rdc=1&_rdr#"
                                target="_blank"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-primary btn-sm-square me-2 rounded-circle"
                                href="https://www.tiktok.com/@altafcateringcompany?_t=8scdCc9SFQ9&_r=1"
                                target="_blank"><i class="fab fa-tiktok"></i></a>
                            <a href="https://www.instagram.com/altafcateringcompany/" target="_blank"
                                class="btn btn-primary btn-sm-square me-2 rounded-circle"><i
                                    class="fab fa-instagram"></i></a>
                            <a href="https://www.youtube.com/@Altafcateringcompanyy" target="_blank"
                                class="btn btn-primary btn-sm-square rounded-circle"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer-item">
                        <h4 class="mb-4">Special Facilities</h4>
                        <div class="d-flex flex-column align-items-start">
                            <a class="text-body mb-3" href=""><i class="fa fa-check text-primary me-2"></i>Cheese
                                Burger</a>
                            <a class="text-body mb-3" href=""><i class="fa fa-check text-primary me-2"></i>Sandwich</a>
                            <a class="text-body mb-3" href=""><i class="fa fa-check text-primary me-2"></i>Paneer
                                Burger</a>
                            <a class="text-body mb-3" href=""><i class="fa fa-check text-primary me-2"></i>Special
                                Sweets</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer-item">
                        <h4 class="mb-4">Contact Us</h4>
                        <div class="d-flex flex-column align-items-start">
                            <p><i class="fa fa-map-marker-alt text-primary me-2"></i>MM Farm House Sharif Medical Jati
                                Umrah Road, Karachi, Pakistan</p>
                            <p><i class="fa fa-phone-alt text-primary me-2"></i><a
                                    href="tel:+923039907296">+923039907296</a></p>
                            <p><i class="fas fa-envelope text-primary me-2"></i><a
                                    href="mailto:altafcatering@gmail.com">altafcatering@gmail.com</a></p>
                            <p><i class="fa fa-clock text-primary me-2"></i>24/7 Hours Service</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer-item">
                        <h4 class="mb-4">Social Gallery</h4>
                        <div class="row g-2">
                            <div class="col-4"><img src="img/menu-01.jpg"
                                    class="img-fluid rounded-circle border border-primary p-2"
                                    alt="Delicious Paneer dish"></div>
                            <div class="col-4"><img src="img/menu-02.jpg"
                                    class="img-fluid rounded-circle border border-primary p-2" alt="Sweet Potato fries">
                            </div>
                            <div class="col-4"><img src="img/menu-03.jpg"
                                    class="img-fluid rounded-circle border border-primary p-2" alt="Sabudana Tikki">
                            </div>
                            <div class="col-4"><img src="img/menu-04.jpg"
                                    class="img-fluid rounded-circle border border-primary p-2" alt="Classic Pizza">
                            </div>
                            <div class="col-4"><img src="img/menu-05.jpg"
                                    class="img-fluid rounded-circle border border-primary p-2" alt="Crispy Bacon"></div>
                            <div class="col-4"><img src="img/menu-06.jpg"
                                    class="img-fluid rounded-circle border border-primary p-2" alt="Grilled Chicken">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- Copyright Start -->
    <div class="container-fluid copyright bg-dark py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                    <small class="text-light">&copy; <span id="copyright-year">2025</span> Altaf Catering Company. All
                        rights reserved.</small>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="privacy.php" class="text-light me-3">Privacy Policy</a>
                    <a href="terms.php" class="text-light me-3">Terms</a>
                    <a href="contact.php" class="text-light">Contact</a>
                </div>
            </div>
        </div>
    </div>
    <script>try { document.getElementById('copyright-year').textContent = new Date().getFullYear(); } catch (e) { }</script>
    <!-- Copyright End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-md-square btn-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="js/search.js"></script>
    <script src="js/main.js"></script>
    
    <!-- Gallery Filter Script - Professional -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterButtons = document.querySelectorAll('.filter-btn');
        const galleryItems = document.querySelectorAll('.gallery-item');
        const galleryContainer = document.getElementById('galleryContainer');
        
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                filterButtons.forEach(btn => {
                    btn.classList.remove('active');
                });
                
                // Add active class to clicked button
                this.classList.add('active');
                
                const filterValue = this.getAttribute('data-filter');
                
                // Add fade out animation
                galleryContainer.style.opacity = '0.3';
                galleryContainer.style.transform = 'scale(0.95)';
                galleryContainer.style.transition = 'all 0.3s ease';
                
                setTimeout(() => {
                    let visibleCount = 0;
                    
                    galleryItems.forEach((item, index) => {
                        if (filterValue === 'all') {
                            item.style.display = 'block';
                            visibleCount++;
                            // Stagger animation
                            setTimeout(() => {
                                item.style.opacity = '1';
                                item.style.transform = 'translateY(0)';
                            }, index * 50);
                        } else {
                            if (item.classList.contains(filterValue)) {
                                item.style.display = 'block';
                                visibleCount++;
                                setTimeout(() => {
                                    item.style.opacity = '1';
                                    item.style.transform = 'translateY(0)';
                                }, index * 50);
                            } else {
                                item.style.display = 'none';
                                item.style.opacity = '0';
                                item.style.transform = 'translateY(20px)';
                            }
                        }
                    });
                    
                    // Fade in container
                    galleryContainer.style.opacity = '1';
                    galleryContainer.style.transform = 'scale(1)';
                    
                    // Show count message
                    console.log(`Showing ${visibleCount} items for filter: ${filterValue}`);
                    
                }, 300);
            });
        });
        
        // Initialize item transitions
        galleryItems.forEach(item => {
            item.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
        });
    });
    </script>
</body>

</html>