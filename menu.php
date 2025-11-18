<?php
// Prevent caching to always show latest data
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

// Load menu data from JSON
$menu_items = [];
if (file_exists('admin/data/menu.json')) {
    $all_menu = json_decode(file_get_contents('admin/data/menu.json'), true);
    // Filter only active menu items
    $menu_items = array_filter($all_menu, function($item) {
        return $item['status'] == 'active';
    });
    $menu_items = array_values($menu_items);
}

// Group menu items by category
$menu_by_category = [];
foreach ($menu_items as $item) {
    $category = $item['category'];
    if (!isset($menu_by_category[$category])) {
        $menu_by_category[$category] = [];
    }
    $menu_by_category[$category][] = $item;
}

// Load Analytics
require_once 'includes/analytics.php';

// Track visitor
require_once 'includes/visitor-tracking.php';
trackVisitorPageView('menu');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo getAnalyticsScript(); ?>
    <meta charset="utf-8">
    <title>Menu — Altaf Catering | Pakistani & Continental Cuisine</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="keywords"
        content="catering menu, Pakistani food, Continental dishes, wedding menu, event catering menu, BBQ, Altaf Catering">
    <meta name="description"
        content="Explore Altaf Catering's diverse menu featuring Pakistani, Continental, Chinese, and BBQ cuisine. Customize your menu for any event.">
    <link rel="canonical" href="https://altafcatering.com/menu.html" />
    <!-- Open Graph / Twitter -->
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Our Menu — Altaf Catering" />
    <meta property="og:description"
        content="Diverse menu options: Pakistani, Continental, Chinese, and BBQ. Perfect for weddings, corporate events, and celebrations." />
    <meta property="og:url" content="https://altafcatering.com/menu.html" />
    <meta property="og:image" content="https://altafcatering.com/img/hero.png" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Altaf Catering Menu" />
    <meta name="twitter:description" content="Discover our delicious menu options." />

    <!-- Favicon & Theme Color -->
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <meta name="theme-color" content="#0d6efd">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Playball&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/loader.css" rel="stylesheet">
    
    <!-- CRITICAL: Text Visibility Fix -->
    <link href="css/text-fix.css" rel="stylesheet">
    
    <!-- CRITICAL: Hover State Fix -->
    <link href="css/hover-fix.css" rel="stylesheet">
</head>

<body>

    <?php include 'includes/contact-buttons.php'; ?>

    <?php $loader_text = "Loading Our Menu..."; include 'includes/loader.php'; ?>


    <?php include 'includes/navbar.php'; ?>


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
    <!-- Modal Search End -->


    <!-- Hero Start -->
    <div class="container-fluid bg-light py-6 my-6 mt-0">
        <div class="container text-center animated bounceInDown">
            <h1 class="display-1 mb-4">Menu</h1>
            <ol class="breadcrumb justify-content-center mb-0 animated bounceInDown">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item text-dark" aria-current="page">Menu</li>
            </ol>
        </div>
    </div>
    <!-- Hero End -->


    <!-- Menu Start -->
    <div class="container-fluid menu bg-light py-6 my-6">
        <div class="container">
            <div class="text-center wow bounceInUp" data-wow-delay="0.1s">
                <small
                    class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">Our
                    Menu</small>
                <h1 class="display-5 mb-5">Most Popular Food in the World</h1>
            </div>

            <!-- Enhanced Menu Search & Filter Bar -->
            <div class="row justify-content-center mb-4 wow bounceInUp" data-wow-delay="0.1s">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <!-- Search Input -->
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-primary text-white border-primary">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" id="menuSearch" class="form-control border-primary p-3"
                                    placeholder="Search menu items by name or description..." 
                                    aria-label="Search menu items">
                                <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            
                            <!-- Quick Filters -->
                            <div class="d-flex flex-wrap gap-2 justify-content-center">
                                <button class="btn btn-sm btn-outline-primary filter-btn active" data-filter="all">
                                    <i class="fas fa-th"></i> All Items
                                </button>
                                <button class="btn btn-sm btn-outline-success filter-btn" data-filter="veg">
                                    <i class="fas fa-leaf"></i> Vegetarian
                                </button>
                                <button class="btn btn-sm btn-outline-danger filter-btn" data-filter="non-veg">
                                    <i class="fas fa-drumstick-bite"></i> Non-Veg
                                </button>
                                <button class="btn btn-sm btn-outline-warning filter-btn" data-filter="popular">
                                    <i class="fas fa-star"></i> Popular
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Results Counter -->
            <div class="text-center mb-3">
                <span id="resultsCount" class="badge bg-primary fs-6">
                    Showing <span id="countNumber"><?php echo count($menu_items); ?></span> items
                </span>
            </div>

            <?php if (empty($menu_items)): ?>
                <div class="alert alert-info text-center">
                    <h4>No menu items available at the moment.</h4>
                    <p>Please check back later or contact us for more information.</p>
                </div>
            <?php else: ?>
                <div class="tab-class text-center">
                    <!-- Dynamic Category Tabs -->
                    <ul class="nav nav-pills d-inline-flex justify-content-center mb-5 wow bounceInUp" data-wow-delay="0.1s">
                        <?php 
                        $categories = array_keys($menu_by_category);
                        $first = true;
                        foreach ($categories as $category): 
                        ?>
                        <li class="nav-item p-2">
                            <a class="d-flex py-2 mx-2 border border-primary bg-white rounded-pill <?php echo $first ? 'active' : ''; ?>"
                                data-bs-toggle="pill" href="#tab-<?php echo strtolower(str_replace(' ', '-', $category)); ?>">
                                <span class="text-dark" style="width: 150px;"><?php echo htmlspecialchars($category); ?></span>
                            </a>
                        </li>
                        <?php 
                        $first = false;
                        endforeach; 
                        ?>
                    </ul>

                    <!-- Dynamic Menu Items by Category -->
                    <div class="tab-content">
                        <?php 
                        $first = true;
                        foreach ($menu_by_category as $category => $items): 
                        ?>
                        <div id="tab-<?php echo strtolower(str_replace(' ', '-', $category)); ?>" 
                             class="tab-pane fade <?php echo $first ? 'show active' : ''; ?> p-0">
                            <div class="row g-4">
                                <?php 
                                $delay = 0.1;
                                foreach ($items as $item): 
                                ?>
                                <div class="col-lg-6 wow bounceInUp" data-wow-delay="<?php echo $delay; ?>s">
                                    <div class="menu-item d-flex align-items-center">
                                        <img class="flex-shrink-0 img-fluid rounded-circle" 
                                             src="<?php echo htmlspecialchars($item['image']); ?>"
                                             alt="<?php echo htmlspecialchars($item['name']); ?> - <?php echo htmlspecialchars($item['description']); ?>"
                                             onerror="this.src='img/menu-01.jpg'">
                                        <div class="w-100 d-flex flex-column text-start ps-4">
                                            <div class="d-flex justify-content-between border-bottom border-primary pb-2 mb-2">
                                                <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                                                <h4 class="text-primary">PKR <?php echo number_format($item['price']); ?></h4>
                                            </div>
                                            <p class="mb-0"><?php echo htmlspecialchars($item['description']); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                $delay += 0.1;
                                if ($delay > 0.8) $delay = 0.1;
                                endforeach; 
                                ?>
                            </div>
                        </div>
                        <?php 
                        $first = false;
                        endforeach; 
                        ?>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="menu-item d-flex align-items-center">
                                    <img class="flex-shrink-0 img-fluid rounded-circle" src="img/menu-04.jpg"
                                        alt="Apple Juice - sweet and crisp apple juice, served cold">
                                    <div class="w-100 d-flex flex-column text-start ps-4">
                                        <div
                                            class="d-flex justify-content-between border-bottom border-primary pb-2 mb-2">
                                            <h4>Apple Juice</h4>
                                            <h4 class="text-primary">PKR 180</h4>
                                        </div>
                                        <p class="mb-0">Sweet and crisp apple juice, served cold.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="menu-item d-flex align-items-center">
                                    <img class="flex-shrink-0 img-fluid rounded-circle" src="img/menu-05.jpg"
                                        alt="Banana Shake - creamy banana shake made with fresh bananas and milk">
                                    <div class="w-100 d-flex flex-column text-start ps-4">
                                        <div
                                            class="d-flex justify-content-between border-bottom border-primary pb-2 mb-2">
                                            <h4>Banana Shake</h4>
                                            <h4 class="text-primary">PKR 200</h4>
                                        </div>
                                        <p class="mb-0">Creamy banana shake made with fresh bananas and milk.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="menu-item d-flex align-items-center">
                                    <img class="flex-shrink-0 img-fluid rounded-circle" src="img/menu-06.jpg"
                                        alt="Sweet Water - chilled sweetened water, a local favorite">
                                    <div class="w-100 d-flex flex-column text-start ps-4">
                                        <div
                                            class="d-flex justify-content-between border-bottom border-primary pb-2 mb-2">
                                            <h4>Sweet Water</h4>
                                            <h4 class="text-primary">PKR 100</h4>
                                        </div>
                                        <p class="mb-0">Chilled sweetened water, a local favorite for hydration.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="menu-item d-flex align-items-center">
                                    <img class="flex-shrink-0 img-fluid rounded-circle" src="img/menu-07.jpg"
                                        alt="Hot Coffee - freshly brewed, rich and aromatic">
                                    <div class="w-100 d-flex flex-column text-start ps-4">
                                        <div
                                            class="d-flex justify-content-between border-bottom border-primary pb-2 mb-2">
                                            <h4>Hot Coffee</h4>
                                            <h4 class="text-primary">PKR 150</h4>
                                        </div>
                                        <p class="mb-0">Freshly brewed hot coffee, rich and aromatic.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="menu-item d-flex align-items-center">
                                    <img class="flex-shrink-0 img-fluid rounded-circle" src="img/menu-08.jpg"
                                        alt="Sweet Potato - sweet potato cubes, lightly spiced and roasted">
                                    <div class="w-100 d-flex flex-column text-start ps-4">
                                        <div
                                            class="d-flex justify-content-between border-bottom border-primary pb-2 mb-2">
                                            <h4>Sweet Potato</h4>
                                            <h4 class="text-primary">PKR 160</h4>
                                        </div>
                                        <p class="mb-0">Sweet potato cubes, lightly spiced and roasted.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- Menu End -->


    <!-- Footer Start -->
    <div class="container-fluid footer py-6 my-6 mb-0 bg-light wow bounceInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="footer-item">
                        <!-- Footer logo (small) -->
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
                            <a class="text-body mb-3" href=""><i class="fa fa-check text-primary me-2"></i>Panner
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
                            <p><i class="fa fa-map-marker-alt text-primary me-2"></i><span itemprop="address" itemscope
                                    itemtype="http://schema.org/PostalAddress"><span itemprop="streetAddress">MM Farm
                                        House Sharif Medical Jati Umrah Road</span>, <span
                                        itemprop="addressLocality">Karachi</span>, <span
                                        itemprop="addressCountry">Pakistan</span></span></p>
                            <p><i class="fa fa-phone-alt text-primary me-2"></i><a href="tel:+923039907296"
                                    itemprop="telephone">+923039907296</a></p>
                            <p><i class="fas fa-envelope text-primary me-2"></i><a href="mailto:altafcatering@gmail.com"
                                    itemprop="email">altafcatering@gmail.com</a></p>
                            <p><i class="fa fa-clock text-primary me-2"></i><span itemprop="openingHours">24/7 Hours
                                    Service</span></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer-item">
                        <h4 class="mb-4">Social Gallery</h4>
                        <div class="row g-2">
                            <div class="col-4">
                                <img src="img/menu-01.jpg" class="img-fluid rounded-circle border border-primary p-2"
                                    alt="">
                            </div>
                            <div class="col-4">
                                <img src="img/menu-02.jpg" class="img-fluid rounded-circle border border-primary p-2"
                                    alt="">
                            </div>
                            <div class="col-4">
                                <img src="img/menu-03.jpg" class="img-fluid rounded-circle border border-primary p-2"
                                    alt="">
                            </div>
                            <div class="col-4">
                                <img src="img/menu-04.jpg" class="img-fluid rounded-circle border border-primary p-2"
                                    alt="">
                            </div>
                            <div class="col-4">
                                <img src="img/menu-05.jpg" class="img-fluid rounded-circle border border-primary p-2"
                                    alt="">
                            </div>
                            <div class="col-4">
                                <img src="img/menu-06.jpg" class="img-fluid rounded-circle border border-primary p-2"
                                    alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Newsletter (footer) -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="newsletter-footer">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h5>Subscribe</h5>
                            <p class="mb-0">Get updates, offers and event tips — straight to your inbox.</p>
                        </div>
                        <div>
                            <form id="newsletterForm" class="d-flex">
                                <input id="newsletterEmail" type="email" class="form-control me-2"
                                    placeholder="Email address" required>
                                <button type="submit" class="btn btn-primary rounded-pill">Subscribe</button>
                            </form>
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
                    <a href="privacy.php" class="text-light me-3" aria-label="Privacy Policy">Privacy Policy</a>
                    <a href="terms.php" class="text-light me-3" aria-label="Terms and Conditions">Terms</a>
                    <a href="contact.php" class="text-light" aria-label="Contact">Contact</a>
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

    <!-- Site search (client-side) -->
    <script src="js/search.js"></script>

    <!-- Menu Filter & Search -->
    <script src="js/menu-filter.js"></script>
    <script src="js/menu-filter-enhanced.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>