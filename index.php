<?php
// Prevent caching to always show latest data
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

// Load owner data from JSON
$owner_data = null;
if (file_exists('admin/data/owner.json')) {
    try {
        $content = file_get_contents('admin/data/owner.json');
        if ($content !== false) {
            $owner_data = json_decode($content, true);
        }
    } catch (Exception $e) {
        $owner_data = null;
    }
}

// Load gallery data from JSON (latest 8 for homepage)
$gallery_data = [];
if (file_exists('admin/data/gallery.json')) {
    try {
        $content = file_get_contents('admin/data/gallery.json');
        if ($content !== false) {
            $all_gallery = json_decode($content, true);
            if (is_array($all_gallery)) {
                // Filter only published items
                $published_gallery = array_filter($all_gallery, function($item) {
                    return isset($item['status']) && $item['status'] == 'published';
                });
                // Sort by date descending (latest first)
                usort($published_gallery, function($a, $b) {
                    return strtotime($b['date']) - strtotime($a['date']);
                });
                // Get latest 8 items
                $gallery_data = array_slice($published_gallery, 0, 8);
            }
        }
    } catch (Exception $e) {
        $gallery_data = [];
    }
}

// Load team data from JSON (latest 4 for homepage)
$team_data = [];
if (file_exists('admin/data/team.json')) {
    try {
        $content = file_get_contents('admin/data/team.json');
        if ($content !== false) {
            $all_team = json_decode($content, true);
            if (is_array($all_team)) {
                // Filter only active members
                $active_team = array_filter($all_team, function($member) {
                    return isset($member['status']) && $member['status'] == 'active';
                });
                // Sort by ID descending to get latest entries first
                usort($active_team, function($a, $b) {
                    return $b['id'] - $a['id'];
                });
                // Get only first 4 team members for homepage
                $team_data = array_slice($active_team, 0, 4);
            }
        }
    } catch (Exception $e) {
        // Silently fail, use empty array
        $team_data = [];
    }
}

// Load testimonials data from JSON
$testimonials_data = [];
if (file_exists('admin/data/testimonials.json')) {
    try {
        $content = file_get_contents('admin/data/testimonials.json');
        if ($content !== false) {
            $all_testimonials = json_decode($content, true);
            if (is_array($all_testimonials)) {
                // Filter only published testimonials
                $testimonials_data = array_filter($all_testimonials, function($testimonial) {
                    return isset($testimonial['status']) && $testimonial['status'] == 'published';
                });
                $testimonials_data = array_values($testimonials_data);
                // Sort by ID descending to get latest entries first
                usort($testimonials_data, function($a, $b) {
                    return $b['id'] - $a['id'];
                });
            }
        }
    } catch (Exception $e) {
        $testimonials_data = [];
    }
}

// Load gallery data from JSON (latest 6 for homepage)
$gallery_data = [];
if (file_exists('admin/data/gallery.json')) {
    try {
        $content = file_get_contents('admin/data/gallery.json');
        if ($content !== false) {
            $all_gallery = json_decode($content, true);
            if (is_array($all_gallery)) {
                // Filter only published gallery items
                $published_gallery = array_filter($all_gallery, function($item) {
                    return isset($item['status']) && $item['status'] == 'published';
                });
                // Sort by ID descending to get latest entries first
                usort($published_gallery, function($a, $b) {
                    return $b['id'] - $a['id'];
                });
                // Get only first 6 items for homepage
                $gallery_data = array_slice($published_gallery, 0, 6);
            }
        }
    } catch (Exception $e) {
        $gallery_data = [];
    }
}

// Load menu data from JSON (latest 8 for homepage)
$menu_items = [];
if (file_exists('admin/data/menu.json')) {
    try {
        $content = file_get_contents('admin/data/menu.json');
        if ($content !== false) {
            $all_menu = json_decode($content, true);
            if (is_array($all_menu)) {
                // Filter only active menu items
                $active_menu = array_filter($all_menu, function($item) {
                    return isset($item['status']) && $item['status'] == 'active';
                });
                // Sort by ID descending to get latest entries first
                usort($active_menu, function($a, $b) {
                    return $b['id'] - $a['id'];
                });
                // Get only first 8 items for homepage
                $menu_items = array_slice($active_menu, 0, 8);
            }
        }
    } catch (Exception $e) {
        $menu_items = [];
    }
}

// Load jobs data from JSON (latest 4 for homepage)
$jobs_data = [];
if (file_exists('admin/data/jobs.json')) {
    try {
        $content = file_get_contents('admin/data/jobs.json');
        if ($content !== false) {
            $all_jobs = json_decode($content, true);
            if (is_array($all_jobs)) {
                // Filter only active jobs
                $active_jobs = array_filter($all_jobs, function($job) {
                    return isset($job['status']) && $job['status'] == 'active';
                });
                // Sort by posted date, newest first
                usort($active_jobs, function($a, $b) {
                    return strtotime($b['posted_date']) - strtotime($a['posted_date']);
                });
                // Get only first 4 jobs for homepage
                $jobs_data = array_slice($active_jobs, 0, 4);
            }
        }
    } catch (Exception $e) {
        $jobs_data = [];
    }
}

// Load pricing data from JSON
$pricing_data = ['packages' => [], 'faqs' => []];
if (file_exists('admin/data/pricing.json')) {
    try {
        $content = file_get_contents('admin/data/pricing.json');
        if ($content !== false) {
            $decoded = json_decode($content, true);
            if (is_array($decoded)) {
                $pricing_data = $decoded;
                // Filter only active packages
                $pricing_data['packages'] = array_filter($pricing_data['packages'], function($pkg) {
                    return isset($pkg['status']) && $pkg['status'] == 'active';
                });
                $pricing_data['packages'] = array_values($pricing_data['packages']);
                // Filter only active FAQs
                if (isset($pricing_data['faqs'])) {
                    $pricing_data['faqs'] = array_filter($pricing_data['faqs'], function($faq) {
                        return isset($faq['status']) && $faq['status'] == 'active';
                    });
                    $pricing_data['faqs'] = array_values($pricing_data['faqs']);
                }
            }
        }
    } catch (Exception $e) {
        $pricing_data = ['packages' => [], 'faqs' => []];
    }
}

// Load blog data from JSON
$blog_data = [];
if (file_exists('admin/data/blogs.json')) {
    try {
        $content = file_get_contents('admin/data/blogs.json');
        if ($content !== false) {
            $all_blogs = json_decode($content, true);
            if (is_array($all_blogs)) {
                // Get only published blogs
                $blog_data = array_filter($all_blogs, function($blog) {
                    return isset($blog['status']) && $blog['status'] === 'published';
                });
                // Sort by date, newest first
                usort($blog_data, function($a, $b) {
                    return strtotime($b['date']) - strtotime($a['date']);
                });
                // Get only latest 3 blogs for homepage
                $blog_data = array_slice($blog_data, 0, 3);
            }
        }
    } catch (Exception $e) {
        $blog_data = [];
    }
}

// Load services data from JSON (latest 4 for homepage)
$services_data = [];
if (file_exists('admin/data/services.json')) {
    try {
        $content = file_get_contents('admin/data/services.json');
        if ($content !== false) {
            $all_services = json_decode($content, true);
            if (is_array($all_services)) {
                // Filter only active services
                $active_services = array_filter($all_services, function($service) {
                    return isset($service['status']) && $service['status'] == 'active';
                });
                // Sort by ID descending to get latest entries first
                usort($active_services, function($a, $b) {
                    return $b['id'] - $a['id'];
                });
                // Get only first 4 services for homepage
                $services_data = array_slice($active_services, 0, 4);
            }
        }
    } catch (Exception $e) {
        $services_data = [];
    }
}

// Load events data from JSON (latest 8 for homepage)
$events_data = [];
if (file_exists('admin/data/events.json')) {
    try {
        $content = file_get_contents('admin/data/events.json');
        if ($content !== false) {
            $all_events = json_decode($content, true);
            if (is_array($all_events)) {
                // Filter only published events
                $published_events = array_filter($all_events, function($event) {
                    return isset($event['status']) && $event['status'] == 'published';
                });
                // Sort by ID descending to get latest entries first
                usort($published_events, function($a, $b) {
                    return $b['id'] - $a['id'];
                });
                // Get only first 8 events for homepage
                $events_data = array_slice($published_events, 0, 8);
            }
        }
    } catch (Exception $e) {
        $events_data = [];
    }
}

// Load moments data from JSON (latest 6 for homepage)
$moments_data = [];
if (file_exists('admin/data/moments.json')) {
    try {
        $content = file_get_contents('admin/data/moments.json');
        if ($content !== false) {
            $all_moments = json_decode($content, true);
            if (is_array($all_moments)) {
                // Filter only published moments
                $published_moments = array_filter($all_moments, function($moment) {
                    return isset($moment['status']) && $moment['status'] == 'published';
                });
                // Sort by ID descending to get latest entries first
                usort($published_moments, function($a, $b) {
                    return $b['id'] - $a['id'];
                });
                // Get only first 6 moments for homepage
                $moments_data = array_slice($published_moments, 0, 6);
            }
        }
    } catch (Exception $e) {
        $moments_data = [];
    }
}

// Load Analytics
require_once 'includes/analytics.php';

// Track visitor page view
require_once 'includes/visitor-tracking.php';
trackVisitorPageView('homepage');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo getAnalyticsScript(); ?>
    <meta charset="utf-8">
    <title>Altaf Catering Official Home</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="keywords"
        content="catering services, wedding catering, corporate catering, event catering, Pakistani food, buffet service, food delivery, Karachi catering, professional catering">
    <meta name="description"
        content="Altaf Catering - Professional catering services in Pakistan. We specialize in weddings, corporate events, parties and special occasions with high-quality food and excellent service.">
    <!-- Open Graph / Twitter -->
    <link rel="canonical" href="https://altafcatering.com/" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Altaf Catering — Professional Catering Services in Pakistan" />
    <meta property="og:description"
        content="Fresh seasonal menus and professional event catering for weddings, corporate events and parties across Pakistan." />
    <meta property="og:url" content="https://altafcatering.com/" />
    <meta property="og:image" content="https://altafcatering.com/img/hero.png" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Altaf Catering — Professional Catering Services in Pakistan" />
    <meta name="twitter:description"
        content="Fresh seasonal menus and professional event catering for weddings, corporate events and parties across Pakistan." />
    <meta name="twitter:image" content="https://altafcatering.com/img/hero.png" />

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
    
    <!-- Loader Stylesheet -->
    <link href="css/loader.css" rel="stylesheet">
    
    <!-- CRITICAL: Hover State Fix -->
    <link href="css/hover-fix.css" rel="stylesheet">
</head>

<body>
    <!-- Page Load Progress Bar -->
    <div id="pageLoadProgress"></div>

    <!-- Toast Notification Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Cookie Consent -->
    <div id="cookieConsent" class="alert alert-info alert-dismissible fade show fixed-bottom mb-0" role="alert"
        style="display:none;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-10">
                    <small>We use cookies to enhance your experience. By continuing to visit this site you agree to our
                        use of cookies.</small>
                </div>
                <div class="col-md-2 text-end">
                    <button type="button" class="btn btn-primary btn-sm" onclick="acceptCookies()">Accept</button>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/contact-buttons.php'; ?>

    <?php $loader_text = "Loading Delicious Experience..."; include 'includes/loader.php'; ?>

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
    <div class="container-fluid py-6 my-6 mt-0">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-7 col-md-12">
                    <small
                        class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-4 animated bounceInDown" style="color: #212529 !important;">Welcome
                        to Altaf Catering Company</small>
                    <h1 class="display-1 mb-4 animated bounceInDown" style="color: #212529 !important;">Book <span
                            class="text-primary" style="color: #FE7E00 !important;">Altaf Catering</span>
                        For Your Dream Event</h1>
                    <a href="contact.php"
                        class="btn btn-primary border-0 rounded-pill py-3 px-4 px-md-5 me-4 animated bounceInLeft">Book
                        Now</a>
                    <a href="about.php"
                        class="btn btn-outline-primary border-2 rounded-pill py-3 px-4 px-md-5 animated bounceInLeft">Know
                        More</a>
                </div>
                <div class="col-lg-5 col-md-12">
                    <div class="image-container">
                        <img src="img/hero.png" 
                             class="img-fluid rounded" 
                             alt="Professional catering service setup by Altaf Catering Company" 
                             width="400" 
                             height="300"
                             style="opacity: 1 !important; visibility: visible !important;">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Hero End -->


    <!-- Quick Action Buttons Start -->
    <div class="container-fluid bg-light py-4">
        <div class="container">
            <div class="row g-3">
                <div class="col-lg-3 col-md-6 wow fadeInUp animate-on-scroll" data-wow-delay="0.1s">
                    <a href="contact.php" class="quick-action-card text-decoration-none">
                        <div class="card border-0 shadow-sm h-100 hover-lift">
                            <div class="card-body text-center p-4">
                                <div class="icon-box bg-primary bg-gradient text-white rounded-circle mx-auto mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-calendar-check fa-2x"></i>
                                </div>
                                <h5 class="card-title mb-2">Book Event</h5>
                                <p class="card-text text-muted small mb-0">Reserve your date now</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6 wow fadeInUp animate-on-scroll" data-wow-delay="0.3s">
                    <a href="menu.php" class="quick-action-card text-decoration-none">
                        <div class="card border-0 shadow-sm h-100 hover-lift">
                            <div class="card-body text-center p-4">
                                <div class="icon-box bg-success bg-gradient text-white rounded-circle mx-auto mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-utensils fa-2x"></i>
                                </div>
                                <h5 class="card-title mb-2">View Menu</h5>
                                <p class="card-text text-muted small mb-0">Explore our dishes</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6 wow fadeInUp animate-on-scroll" data-wow-delay="0.5s">
                    <a href="pricing.php" class="quick-action-card text-decoration-none">
                        <div class="card border-0 shadow-sm h-100 hover-lift">
                            <div class="card-body text-center p-4">
                                <div class="icon-box bg-warning bg-gradient text-white rounded-circle mx-auto mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-tags fa-2x"></i>
                                </div>
                                <h5 class="card-title mb-2">Get Pricing</h5>
                                <p class="card-text text-muted small mb-0">View our packages</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6 wow fadeInUp animate-on-scroll" data-wow-delay="0.7s">
                    <a href="https://wa.me/923039907296?text=Hello%20Altaf%20Catering!" class="quick-action-card text-decoration-none" target="_blank">
                        <div class="card border-0 shadow-sm h-100 hover-lift">
                            <div class="card-body text-center p-4">
                                <div class="icon-box bg-success bg-gradient text-white rounded-circle mx-auto mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fab fa-whatsapp fa-2x"></i>
                                </div>
                                <h5 class="card-title mb-2">Chat Now</h5>
                                <p class="card-text text-muted small mb-0">Quick WhatsApp support</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Quick Action Buttons End -->


    <!-- About Start -->
    <div class="container-fluid py-6">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-5 wow bounceInUp" data-wow-delay="0.1s">
                    <div class="image-container">
                        <img src="img/about.jpg" 
                             class="img-fluid rounded" 
                             alt="About Altaf Catering - Professional catering team and kitchen"
                             width="400" 
                             height="300">
                    </div>
                </div>
                <div class="col-lg-7 wow bounceInUp" data-wow-delay="0.3s">
                    <small
                        class="d-inline-block fw-bold text-uppercase mb-3">
                        <i class="fas fa-star me-2"></i>About Us
                    </small>
                    <h1 class="display-5 mb-4">Trusted By <span class="text-primary">200+</span> Satisfied Clients</h1>
                    <p class="mb-4">Welcome to <strong>Altaf Catering Company</strong>, where we specialize in creating exceptional
                        culinary experiences for all kinds of events. With years of experience in the catering industry,
                        we pride ourselves on delivering high-quality, delicious food tailored to your unique needs.
                        Whether you're planning a wedding, corporate event, or any special occasion, we ensure that every
                        detail is handled with care and precision.
                    </p>
                    <div class="row g-4 text-dark mb-5">
                        <div class="col-sm-6">
                            <i class="fas fa-share text-primary me-2"></i>Fresh and Fast food Delivery
                        </div>
                        <div class="col-sm-6">
                            <i class="fas fa-share text-primary me-2"></i>24/7 Customer Support
                        </div>
                        <div class="col-sm-6">
                            <i class="fas fa-share text-primary me-2"></i>Easy Customization Options
                        </div>
                        <div class="col-sm-6">
                            <i class="fas fa-share text-primary me-2"></i>Delicious Deals for Delicious Meals
                        </div>
                    </div>
                    <a href="about.php" class="btn btn-primary py-3 px-5 rounded-pill">About Us<i
                            class="fas fa-arrow-right ps-2"></i></a>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->


    <!-- Why Choose Us Start -->
    <div class="container-fluid py-6">
        <div class="container">
            <div class="text-center wow bounceInUp" data-wow-delay="0.1s">
                <small
                    class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">Why
                    Choose Us</small>
                <h1 class="display-5 mb-5">Why Altaf Catering Stands Out</h1>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6 wow bounceInUp" data-wow-delay="0.1s">
                    <div class="text-center">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                            style="width: 100px; height: 100px;">
                            <i class="fas fa-utensils fa-3x text-primary"></i>
                        </div>
                        <h4 class="mb-3">Premium Quality Food</h4>
                        <p class="mb-0">We use only the finest ingredients and traditional cooking methods to deliver
                            exceptional taste and freshness in every dish.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 wow bounceInUp" data-wow-delay="0.3s">
                    <div class="text-center">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                            style="width: 100px; height: 100px;">
                            <i class="fas fa-users fa-3x text-primary"></i>
                        </div>
                        <h4 class="mb-3">Experienced Team</h4>
                        <p class="mb-0">Our skilled chefs and professional staff have years of experience in catering
                            for weddings, corporate events, and special occasions.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 wow bounceInUp" data-wow-delay="0.5s">
                    <div class="text-center">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                            style="width: 100px; height: 100px;">
                            <i class="fas fa-cogs fa-3x text-primary"></i>
                        </div>
                        <h4 class="mb-3">Customized Services</h4>
                        <p class="mb-0">We tailor our menus and services to match your specific requirements, ensuring a
                            personalized experience for every event.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 wow bounceInUp" data-wow-delay="0.1s">
                    <div class="text-center">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                            style="width: 100px; height: 100px;">
                            <i class="fas fa-clock fa-3x text-primary"></i>
                        </div>
                        <h4 class="mb-3">Timely Delivery</h4>
                        <p class="mb-0">Punctuality is our promise. We ensure on-time setup, service, and delivery for
                            all your catering needs across Pakistan.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 wow bounceInUp" data-wow-delay="0.3s">
                    <div class="text-center">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                            style="width: 100px; height: 100px;">
                            <i class="fas fa-shield-alt fa-3x text-primary"></i>
                        </div>
                        <h4 class="mb-3">Hygienic Standards</h4>
                        <p class="mb-0">We maintain the highest standards of food safety and hygiene, following strict
                            protocols to ensure your guests' well-being.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 wow bounceInUp" data-wow-delay="0.5s">
                    <div class="text-center">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                            style="width: 100px; height: 100px;">
                            <i class="fas fa-handshake fa-3x text-primary"></i>
                        </div>
                        <h4 class="mb-3">Trusted by Many</h4>
                        <p class="mb-0">With over 689 happy customers and 253 successful events, we have built a
                            reputation for excellence in catering services.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Why Choose Us End -->

    <!-- Fact Start-->
    <div class="container-fluid faqt py-6">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-lg-7">
                    <div class="row g-4">
                        <div class="col-sm-4 wow bounceInUp" data-wow-delay="0.3s">
                            <div class="faqt-item bg-primary rounded p-4 text-center">
                                <i class="fas fa-users fa-4x mb-4 text-white"></i>
                                <h1 class="display-4 fw-bold" data-toggle="counter-up">689</h1>
                                <p class="text-dark text-uppercase fw-bold mb-0">Happy Customers</p>
                            </div>
                        </div>
                        <div class="col-sm-4 wow bounceInUp" data-wow-delay="0.5s">
                            <div class="faqt-item bg-primary rounded p-4 text-center">
                                <i class="fas fa-users-cog fa-4x mb-4 text-white"></i>
                                <h1 class="display-4 fw-bold" data-toggle="counter-up">107</h1>
                                <p class="text-dark text-uppercase fw-bold mb-0">Expert Chefs</p>
                            </div>
                        </div>
                        <div class="col-sm-4 wow bounceInUp" data-wow-delay="0.7s">
                            <div class="faqt-item bg-primary rounded p-4 text-center">
                                <i class="fas fa-check fa-4x mb-4 text-white"></i>
                                <h1 class="display-4 fw-bold" data-toggle="counter-up">253</h1>
                                <p class="text-dark text-uppercase fw-bold mb-0">Events Complete</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 wow bounceInUp" data-wow-delay="0.1s">
                    <div class="video">
                        <button type="button" class="btn btn-play" data-bs-toggle="modal"
                            data-src="https://youtu.be/ipO6RH1WElQ?si=kYPlYlFl3FSJaths" data-bs-target="#videoModal">
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Video -->
    <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Youtube Video</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- 16:9 aspect ratio -->
                    <div class="ratio ratio-16x9">
                        <iframe width="560" height="315"
                            src="https://www.youtube.com/embed/ipO6RH1WElQ?si=kYPlYlFl3FSJaths"
                            title="YouTube video player" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Fact End -->


    <!-- Service Start -->
    <div id="services" class="container-fluid service py-6">
        <div class="container">
            <div class="text-center wow bounceInUp" data-wow-delay="0.1s">
                <small
                    class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">Our
                    Services</small>
                <h1 class="display-5 mb-5">What We Offer</h1>
            </div>
            <div class="row g-4">
                <?php 
                if (!empty($services_data)) {
                    $delay = 0.1;
                    foreach ($services_data as $service): 
                ?>
                <div id="service-<?php echo $service['id']; ?>-preview" class="col-lg-3 col-md-6 col-sm-12 wow bounceInUp" data-wow-delay="<?php echo $delay; ?>s">
                    <div class="bg-light rounded service-item">
                        <div class="service-content d-flex align-items-center justify-content-center p-4">
                            <div class="service-content-icon text-center">
                                <i class="<?php echo htmlspecialchars($service['icon']); ?> fa-4x text-primary mb-4"></i>
                                <h4 class="mb-3"><?php echo htmlspecialchars($service['title']); ?></h4>
                                <p class="mb-4"><?php echo htmlspecialchars($service['description']); ?></p>
                                <a href="service.php#service-<?php echo $service['id']; ?>"
                                    class="btn btn-primary px-4 py-2 rounded-pill save-index-scroll">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php 
                    $delay += 0.2;
                    endforeach;
                } else {
                    // Fallback to default service
                ?>
                <div id="service-1-preview" class="col-lg-3 col-md-6 col-sm-12 wow bounceInUp" data-wow-delay="0.1s">
                    <div class="bg-light rounded service-item">
                        <div class="service-content d-flex align-items-center justify-content-center p-4">
                            <div class="service-content-icon text-center">
                                <i class="fas fa-cheese fa-4x text-primary mb-4"></i>
                                <h4 class="mb-3">Wedding Services</h4>
                                <p class="mb-4">
                                    We provide full wedding catering services including elegant food displays,
                                    customized menus, and professional staff to make your big day truly memorable. From
                                    appetizers to desserts — we handle everything with perfection.
                                </p>
                                <a href="service.php#service-1"
                                    class="btn btn-primary px-4 py-2 rounded-pill save-index-scroll">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } // End of if-else for services ?>
            </div>
        </div>
    </div>
    <!-- Service End -->

    <!-- Events Start -->
    <div class="container-fluid event py-6">
        <div class="container">
            <div class="text-center wow bounceInUp" data-wow-delay="0.1s">
                <small
                    class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">Latest
                    Events</small>
                <h1 class="display-5 mb-5">Our Social & Professional Events Gallery</h1>
            </div>
            <div class="tab-class text-center">
                <ul class="nav nav-pills d-inline-flex justify-content-center mb-5 wow bounceInUp"
                    data-wow-delay="0.1s">
                    <li class="nav-item p-2">
                        <a class="d-flex mx-2 py-2 border border-primary bg-light rounded-pill active"
                            data-bs-toggle="pill" href="#tab-1">
                            <span class="text-dark" style="width: 150px;">All Events</span>
                        </a>
                    </li>
                    <li class="nav-item p-2">
                        <a class="d-flex py-2 mx-2 border border-primary bg-light rounded-pill" data-bs-toggle="pill"
                            href="#tab-2">
                            <span class="text-dark" style="width: 150px;">Wedding</span>
                        </a>
                    </li>
                    <li class="nav-item p-2">
                        <a class="d-flex mx-2 py-2 border border-primary bg-light rounded-pill" data-bs-toggle="pill"
                            href="#tab-3">
                            <span class="text-dark" style="width: 150px;">Corporate</span>
                        </a>
                    </li>
                    <li class="nav-item p-2">
                        <a class="d-flex mx-2 py-2 border border-primary bg-light rounded-pill" data-bs-toggle="pill"
                            href="#tab-4">
                            <span class="text-dark" style="width: 150px;">Cocktail</span>
                        </a>
                    </li>
                    <li class="nav-item p-2">
                        <a class="d-flex mx-2 py-2 border border-primary bg-light rounded-pill" data-bs-toggle="pill"
                            href="#tab-5">
                            <span class="text-dark" style="width: 150px;">Buffet</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane fade show p-0 active">
                        <div class="row g-4">
                            <div class="col-lg-12">
                                <div class="row g-4">
                                    <?php 
                                    if (!empty($events_data)) {
                                        $delay = 0.1;
                                        foreach ($events_data as $event) {
                                    ?>
                                    <div class="col-md-6 col-lg-3 wow bounceInUp" data-wow-delay="<?php echo $delay; ?>s">
                                        <div class="event-img position-relative">
                                            <img class="img-fluid rounded w-100" src="<?php echo htmlspecialchars($event['image']); ?>"
                                                alt="<?php echo htmlspecialchars($event['title']); ?>"
                                                style="height: 250px; object-fit: cover;">
                                            <div class="event-overlay d-flex flex-column p-4">
                                                <h4 class="me-auto"><?php echo ucfirst($event['category']); ?></h4>
                                                <a href="<?php echo htmlspecialchars($event['image']); ?>" 
                                                   data-lightbox="event-<?php echo $event['id']; ?>" 
                                                   class="my-auto">
                                                    <i class="fas fa-search-plus text-dark fa-2x"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <?php 
                                            $delay += 0.2;
                                            if ($delay > 0.9) $delay = 0.1;
                                        }
                                    } else {
                                        // Fallback to static images if no data
                                    ?>
                                    <div class="col-md-6 col-lg-3 wow bounceInUp" data-wow-delay="0.1s">
                                        <div class="event-img position-relative">
                                            <img class="img-fluid rounded w-100" src="img/event-1.jpg"
                                                alt="Wedding event catering setup">
                                            <div class="event-overlay d-flex flex-column p-4">
                                                <h4 class="me-auto">Wedding</h4>
                                                <a href="img/event-1.jpg" data-lightbox="event-1" class="my-auto"><i
                                                        class="fas fa-search-plus text-dark fa-2x"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-3 wow bounceInUp" data-wow-delay="0.3s">
                                        <div class="event-img position-relative">
                                            <img class="img-fluid rounded w-100" src="img/event-2.jpg"
                                                alt="Corporate event catering">
                                            <div class="event-overlay d-flex flex-column p-4">
                                                <h4 class="me-auto">Corporate</h4>
                                                <a href="img/event-2.jpg" data-lightbox="event-2" class="my-auto"><i
                                                        class="fas fa-search-plus text-dark fa-2x"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-3 wow bounceInUp" data-wow-delay="0.5s">
                                        <div class="event-img position-relative">
                                            <img class="img-fluid rounded w-100" src="img/event-3.jpg"
                                                alt="Event catering">
                                            <div class="event-overlay d-flex flex-column p-4">
                                                <h4 class="me-auto">Event</h4>
                                                <a href="img/event-3.jpg" data-lightbox="event-3" class="my-auto"><i
                                                        class="fas fa-search-plus text-dark fa-2x"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-3 wow bounceInUp" data-wow-delay="0.7s">
                                        <div class="event-img position-relative">
                                            <img class="img-fluid rounded w-100" src="img/event-4.jpg"
                                                alt="Event setup">
                                            <div class="event-overlay d-flex flex-column p-4">
                                                <h4 class="me-auto">Event</h4>
                                                <a href="img/event-4.jpg" data-lightbox="event-4" class="my-auto"><i
                                                        class="fas fa-search-plus text-dark fa-2x"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } // End else fallback ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Wedding Tab -->
                    <div id="tab-2" class="tab-pane fade show p-0">
                        <div class="row g-4">
                            <div class="col-lg-12">
                                <div class="row g-4">
                                    <?php 
                                    $wedding_events = array_filter($events_data, function($e) { return $e['category'] == 'wedding'; });
                                    if (!empty($wedding_events)) {
                                        foreach ($wedding_events as $event) {
                                    ?>
                                    <div class="col-md-6 col-lg-3">
                                        <div class="event-img position-relative">
                                            <img class="img-fluid rounded w-100" src="<?php echo htmlspecialchars($event['image']); ?>"
                                                alt="<?php echo htmlspecialchars($event['title']); ?>"
                                                style="height: 250px; object-fit: cover;">
                                            <div class="event-overlay d-flex flex-column p-4">
                                                <h4 class="me-auto">Wedding</h4>
                                                <a href="<?php echo htmlspecialchars($event['image']); ?>" 
                                                   data-lightbox="wedding-<?php echo $event['id']; ?>" class="my-auto">
                                                    <i class="fas fa-search-plus text-dark fa-2x"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <?php }} else { ?>
                                    <div class="col-12"><p class="text-center">No wedding events available</p></div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Corporate Tab -->
                    <div id="tab-3" class="tab-pane fade show p-0">
                        <div class="row g-4">
                            <div class="col-lg-12">
                                <div class="row g-4">
                                    <?php 
                                    $corporate_events = array_filter($events_data, function($e) { return $e['category'] == 'corporate'; });
                                    if (!empty($corporate_events)) {
                                        foreach ($corporate_events as $event) {
                                    ?>
                                    <div class="col-md-6 col-lg-3">
                                        <div class="event-img position-relative">
                                            <img class="img-fluid rounded w-100" src="<?php echo htmlspecialchars($event['image']); ?>"
                                                alt="<?php echo htmlspecialchars($event['title']); ?>"
                                                style="height: 250px; object-fit: cover;">
                                            <div class="event-overlay d-flex flex-column p-4">
                                                <h4 class="me-auto">Corporate</h4>
                                                <a href="<?php echo htmlspecialchars($event['image']); ?>" 
                                                   data-lightbox="corporate-<?php echo $event['id']; ?>" class="my-auto">
                                                    <i class="fas fa-search-plus text-dark fa-2x"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <?php }} else { ?>
                                    <div class="col-12"><p class="text-center">No corporate events available</p></div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Cocktail Tab -->
                    <div id="tab-4" class="tab-pane fade show p-0">
                        <div class="row g-4">
                            <div class="col-lg-12">
                                <div class="row g-4">
                                    <?php 
                                    $cocktail_events = array_filter($events_data, function($e) { return $e['category'] == 'cocktail'; });
                                    if (!empty($cocktail_events)) {
                                        foreach ($cocktail_events as $event) {
                                    ?>
                                    <div class="col-md-6 col-lg-3">
                                        <div class="event-img position-relative">
                                            <img class="img-fluid rounded w-100" src="<?php echo htmlspecialchars($event['image']); ?>"
                                                alt="<?php echo htmlspecialchars($event['title']); ?>"
                                                style="height: 250px; object-fit: cover;">
                                            <div class="event-overlay d-flex flex-column p-4">
                                                <h4 class="me-auto">Cocktail</h4>
                                                <a href="<?php echo htmlspecialchars($event['image']); ?>" 
                                                   data-lightbox="cocktail-<?php echo $event['id']; ?>" class="my-auto">
                                                    <i class="fas fa-search-plus text-dark fa-2x"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <?php }} else { ?>
                                    <div class="col-12"><p class="text-center">No cocktail events available</p></div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Buffet Tab -->
                    <div id="tab-5" class="tab-pane fade show p-0">
                        <div class="row g-4">
                            <div class="col-lg-12">
                                <div class="row g-4">
                                    <?php 
                                    $buffet_events = array_filter($events_data, function($e) { return $e['category'] == 'buffet'; });
                                    if (!empty($buffet_events)) {
                                        foreach ($buffet_events as $event) {
                                    ?>
                                    <div class="col-md-6 col-lg-3">
                                        <div class="event-img position-relative">
                                            <img class="img-fluid rounded w-100" src="<?php echo htmlspecialchars($event['image']); ?>"
                                                alt="<?php echo htmlspecialchars($event['title']); ?>"
                                                style="height: 250px; object-fit: cover;">
                                            <div class="event-overlay d-flex flex-column p-4">
                                                <h4 class="me-auto">Buffet</h4>
                                                <a href="<?php echo htmlspecialchars($event['image']); ?>" 
                                                   data-lightbox="buffet-<?php echo $event['id']; ?>" class="my-auto">
                                                    <i class="fas fa-search-plus text-dark fa-2x"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <?php }} else { ?>
                                    <div class="col-12"><p class="text-center">No buffet events available</p></div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Events End -->


    <!-- Menu Start -->
    <div class="container-fluid menu bg-light py-6 my-6">
        <div class="container">
            <div class="text-center wow bounceInUp" data-wow-delay="0.1s">
                <small
                    class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">Our
                    Menu</small>
                <h1 class="display-5 mb-5">Most Popular Food in the World</h1>
            </div>
            
            <?php if (empty($menu_items)): ?>
                <div class="alert alert-info text-center">
                    <h4>Menu items coming soon!</h4>
                    <p>We're preparing something special for you.</p>
                </div>
            <?php else: ?>
            <div class="text-center">
                <!-- Dynamic Menu Items from Admin Panel -->
                <div class="row g-4">
                    <?php 
                    $delay = 0.1;
                    foreach ($menu_items as $item): 
                    ?>
                    <div class="col-lg-6 wow bounceInUp" data-wow-delay="<?php echo $delay; ?>s">
                        <div class="menu-item d-flex align-items-center">
                            <img class="flex-shrink-0 img-fluid rounded-circle" 
                                 src="<?php echo htmlspecialchars($item['image']); ?>"
                                 alt="<?php echo htmlspecialchars($item['name']); ?>"
                                 onerror="this.src='img/menu-01.jpg'">
                            <div class="w-100 d-flex flex-column text-start ps-4">
                                <div class="d-flex justify-content-between border-bottom border-primary pb-2 mb-2">
                                    <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                                    <h4 class="text-primary">PKR <?php echo number_format($item['price']); ?></h4>
                                </div>
                                <p class="mb-0"><?php echo htmlspecialchars($item['description']); ?></p>
                                <small class="text-muted">
                                    <i class="fas fa-tag"></i> <?php echo htmlspecialchars($item['category']); ?>
                                </small>
                            </div>
                        </div>
                    </div>
                    <?php 
                    $delay += 0.1;
                    if ($delay > 0.8) $delay = 0.1;
                    endforeach; 
                    ?>
                </div>
                
                <!-- View Full Menu Button -->
                <div class="text-center mt-5 wow bounceInUp" data-wow-delay="0.1s">
                    <a href="menu.php" class="btn btn-primary btn-lg rounded-pill py-3 px-5">
                        <i class="fas fa-utensils me-2"></i> View Full Menu
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- Menu End -->


    <!-- Book Us Start -->
    <div class="container-fluid contact py-6 wow bounceInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="row g-0">
                <div class="col-1">
                    <img src="img/background-site.jpg" class="img-fluid h-100 w-100 rounded-start"
                        style="object-fit: cover; opacity: 0.7;" alt="">
                </div>
                <div class="col-10">
                    <div class="border-bottom border-top border-primary bg-light py-5 px-4">
                        <div class="text-center">
                            <small
                                class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">Book
                                Us</small>
                            <h1 class="display-5 mb-5">Where you want Our Services</h1>
                        </div>
                        <form id="bookingForm" class="row g-4 form">
                            <div class="col-lg-6 col-md-6">
                                <input id="bookingName" name="name" type="text" class="form-control border-primary p-2"
                                    placeholder="Full Name" required>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <input id="bookingPhone" name="phone" type="tel" class="form-control border-primary p-2"
                                    placeholder="Contact Number" required>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <input id="bookingEmail" name="email" type="email"
                                    class="form-control border-primary p-2" placeholder="Email Address" required>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <input id="bookingCity" name="city" type="text" class="form-control border-primary p-2"
                                    placeholder="City (e.g. Karachi, Lahore)" required>
                            </div>
                            <div class="col-12">
                                <input id="bookingAddress" name="address" type="text"
                                    class="form-control border-primary p-2"
                                    placeholder="Full Address (Street, Area, City)" required>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <select id="bookingEventType" name="eventType" class="form-select border-primary p-2"
                                    required>
                                    <option value="" selected disabled>Event Type</option>
                                    <option value="Wedding">Wedding</option>
                                    <option value="Birthday">Birthday</option>
                                    <option value="Corporate">Corporate Event</option>
                                    <option value="Family">Family Gathering</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <input id="bookingGuests" name="guestCount" type="number"
                                    class="form-control border-primary p-2" placeholder="Number of Guests" min="10" max="5000"
                                    required>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <select id="bookingMenuType" name="menuType" class="form-select border-primary p-2"
                                    required>
                                    <option value="" selected disabled>Menu Type</option>
                                    <option value="Pakistani">Pakistani</option>
                                    <option value="BBQ">BBQ</option>
                                    <option value="Chinese">Chinese</option>
                                    <option value="Continental">Continental</option>
                                    <option value="Custom">Custom</option>
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <input id="bookingDate" name="eventDate" type="date" class="form-control border-primary p-2"
                                    placeholder="Event Date" required>
                            </div>
                            <div class="col-12 text-center">
                                <button id="bookingSubmit" type="submit"
                                    class="btn btn-primary px-5 py-3 rounded-pill">
                                    <i class="fab fa-whatsapp me-2"></i>Book Now via WhatsApp
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-1">
                    <img src="img/background-site.jpg" class="img-fluid h-100 w-100 rounded-end"
                        style="object-fit: cover; opacity: 0.7;" alt="">
                </div>
            </div>
        </div>
    </div>
    <!-- Book Us End -->


    <!-- Team Start -->
    <!-- Owner/Founder Section -->
    <?php if ($owner_data): ?>
    <div class="container-fluid py-6 bg-light">
        <div class="container">
            <div class="text-center wow bounceInUp" data-wow-delay="0.1s">
                <small class="d-inline-block fw-bold text-dark text-uppercase bg-white border border-primary rounded-pill px-4 py-1 mb-3">
                    Leadership
                </small>
                <h1 class="display-5 mb-2">Meet Our Founder</h1>
                <p class="text-muted mb-5">The visionary behind Altaf Catering's success</p>
            </div>
            
            <div class="row g-4 align-items-center">
                <!-- Owner Image -->
                <div class="col-lg-5 wow bounceInUp" data-wow-delay="0.1s">
                    <div class="position-relative">
                        <?php if (!empty($owner_data['image']) && file_exists($owner_data['image'])): ?>
                            <img src="<?php echo htmlspecialchars($owner_data['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($owner_data['name']); ?> - <?php echo htmlspecialchars($owner_data['title']); ?>"
                                 class="img-fluid rounded shadow-lg" 
                                 style="max-height:500px; width:100%; object-fit:cover;">
                        <?php else: ?>
                            <div class="bg-primary rounded d-flex align-items-center justify-content-center" 
                                 style="height: 500px;">
                                <i class="fas fa-user fa-10x text-white" style="opacity: 0.3;"></i>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Experience Badge -->
                        <?php if (!empty($owner_data['years_experience'])): ?>
                        <div class="position-absolute top-0 end-0 m-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow" 
                                 style="width: 100px; height: 100px;">
                                <div class="text-center">
                                    <h3 class="mb-0"><?php echo $owner_data['years_experience']; ?>+</h3>
                                    <small>Years</small>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Owner Info -->
                <div class="col-lg-7 wow bounceInUp" data-wow-delay="0.3s">
                    <div class="ps-lg-4">
                        <h2 class="text-primary mb-2"><?php echo htmlspecialchars($owner_data['name']); ?></h2>
                        <h4 class="text-dark mb-3"><?php echo htmlspecialchars($owner_data['title']); ?></h4>
                        
                        <?php if (!empty($owner_data['tagline'])): ?>
                        <p class="lead text-muted fst-italic mb-4">
                            <i class="fas fa-quote-left me-2"></i><?php echo htmlspecialchars($owner_data['tagline']); ?>
                        </p>
                        <?php endif; ?>
                        
                        <p class="mb-4" style="text-align: justify; line-height: 1.8;">
                            <?php echo nl2br(htmlspecialchars($owner_data['bio'])); ?>
                        </p>
                        
                        <!-- Contact Info -->
                        <div class="mb-4">
                            <?php if (!empty($owner_data['phone'])): ?>
                            <p class="mb-2">
                                <i class="fa fa-phone-alt text-primary me-2"></i>
                                <a href="tel:<?php echo htmlspecialchars($owner_data['phone']); ?>" class="text-dark">
                                    <?php echo htmlspecialchars($owner_data['phone']); ?>
                                </a>
                            </p>
                            <?php endif; ?>
                            
                            <?php if (!empty($owner_data['email'])): ?>
                            <p class="mb-2">
                                <i class="fas fa-envelope text-primary me-2"></i>
                                <a href="mailto:<?php echo htmlspecialchars($owner_data['email']); ?>" class="text-dark">
                                    <?php echo htmlspecialchars($owner_data['email']); ?>
                                </a>
                            </p>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Social Media -->
                        <div class="d-flex gap-2 mb-4">
                            <?php if (!empty($owner_data['facebook'])): ?>
                            <a class="btn btn-primary btn-md-square rounded-circle"
                                href="<?php echo htmlspecialchars($owner_data['facebook']); ?>"
                                target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <?php endif; ?>
                            
                            <?php if (!empty($owner_data['instagram'])): ?>
                            <a class="btn btn-primary btn-md-square rounded-circle"
                                href="<?php echo htmlspecialchars($owner_data['instagram']); ?>"
                                target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <?php endif; ?>
                            
                            <?php if (!empty($owner_data['tiktok'])): ?>
                            <a class="btn btn-primary btn-md-square rounded-circle"
                                href="<?php echo htmlspecialchars($owner_data['tiktok']); ?>"
                                target="_blank" rel="noopener noreferrer" aria-label="TikTok">
                                <i class="fab fa-tiktok"></i>
                            </a>
                            <?php endif; ?>
                            
                            <?php if (!empty($owner_data['youtube'])): ?>
                            <a class="btn btn-primary btn-md-square rounded-circle"
                                href="<?php echo htmlspecialchars($owner_data['youtube']); ?>"
                                target="_blank" rel="noopener noreferrer" aria-label="YouTube">
                                <i class="fab fa-youtube"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Specialties -->
                        <?php if (!empty($owner_data['specialties']) && is_array($owner_data['specialties'])): ?>
                        <div class="mt-4">
                            <h5 class="mb-3"><i class="fas fa-star text-primary me-2"></i>Specialties</h5>
                            <div class="d-flex flex-wrap gap-2">
                                <?php foreach($owner_data['specialties'] as $specialty): ?>
                                <span class="badge bg-primary px-3 py-2"><?php echo htmlspecialchars($specialty); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Achievements Section -->
            <?php if (!empty($owner_data['achievements']) && is_array($owner_data['achievements'])): ?>
            <div class="row mt-5">
                <div class="col-12 wow bounceInUp" data-wow-delay="0.5s">
                    <div class="bg-white rounded shadow p-4">
                        <h4 class="text-primary mb-4"><i class="fas fa-trophy me-2"></i>Key Achievements</h4>
                        <div class="row g-3">
                            <?php foreach($owner_data['achievements'] as $achievement): ?>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-check-circle text-success me-3 mt-1"></i>
                                    <p class="mb-0"><?php echo htmlspecialchars($achievement); ?></p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
    <!-- Owner/Founder Section End -->

    <!-- Team Members Start -->
    <div class="container-fluid team py-6">
        <div class="container">
            <div class="text-center wow bounceInUp" data-wow-delay="0.1s">
                <small
                    class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">Our
                    Team</small>
                <h1 class="display-5 mb-4">Meet Our Professional Team</h1>
                <p class="mb-5">Experienced chefs and dedicated professionals committed to making your events memorable</p>
            </div>
            <div class="row g-4">
                <?php 
                $delay = 0.1;
                // Show only first 4 team members on homepage
                $homepage_team = array_slice($team_data, 0, 4);
                foreach ($homepage_team as $member): 
                ?>
                <div class="col-lg-3 col-md-6 wow bounceInUp" data-wow-delay="<?php echo $delay; ?>s">
                    <div class="team-item rounded position-relative">
                        <img class="img-fluid rounded-top" 
                             src="<?php echo htmlspecialchars($member['image']); ?>"
                             alt="<?php echo htmlspecialchars($member['name']); ?> - <?php echo htmlspecialchars($member['position']); ?>"
                             style="height: 300px; object-fit: cover;">
                        <div class="team-content text-center py-3 bg-dark rounded-bottom">
                            <h4 class="text-primary mb-1"><?php echo htmlspecialchars($member['name']); ?></h4>
                            <p class="text-white mb-2"><?php echo htmlspecialchars($member['position']); ?></p>
                            <?php if (!empty($member['years_experience'])): ?>
                                <p class="text-white-50 small mb-2">
                                    <i class="fas fa-award me-1"></i><?php echo $member['years_experience']; ?>+ Years Experience
                                </p>
                            <?php endif; ?>
                            <a href="team-detail.php?id=<?php echo $member['id']; ?>" 
                               class="btn btn-primary btn-sm mt-2">
                                <i class="fas fa-user me-1"></i> View Profile
                            </a>
                        </div>
                        <div class="team-icon d-flex flex-column justify-content-center m-4">
                            <?php if (!empty($member['facebook'])): ?>
                            <a class="btn btn-primary btn-md-square rounded-circle mb-2"
                                href="<?php echo htmlspecialchars($member['facebook']); ?>"
                                target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <?php endif; ?>
                            
                            <?php if (!empty($member['tiktok'])): ?>
                            <a class="btn btn-primary btn-md-square rounded-circle mb-2"
                                href="<?php echo htmlspecialchars($member['tiktok']); ?>"
                                target="_blank" rel="noopener noreferrer" aria-label="TikTok">
                                <i class="fab fa-tiktok"></i>
                            </a>
                            <?php endif; ?>
                            
                            <?php if (!empty($member['instagram'])): ?>
                            <a class="btn btn-primary btn-md-square rounded-circle mb-2"
                                href="<?php echo htmlspecialchars($member['instagram']); ?>"
                                target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <?php endif; ?>
                            
                            <?php if (!empty($member['youtube'])): ?>
                            <a class="btn btn-primary btn-md-square rounded-circle mb-2"
                                href="<?php echo htmlspecialchars($member['youtube']); ?>"
                                target="_blank" rel="noopener noreferrer" aria-label="YouTube">
                                <i class="fab fa-youtube"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php 
                $delay += 0.2;
                endforeach; 
                ?>
            </div>
            
            <!-- Learn More Button -->
            <?php if (!empty($team_data)): ?>
            <div class="text-center mt-5 wow bounceInUp" data-wow-delay="0.3s">
                <a href="team.php" class="btn btn-primary rounded-pill py-3 px-5">
                    Meet Full Team <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- Team Members End -->


    <?php include "includes/testimonials-section.php"; ?>


    <!-- Social Media Feed Start -->
    <div class="container-fluid py-6 bg-light">
        <div class="container">
            <div class="text-center wow bounceInUp" data-wow-delay="0.1s">
                <small class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">
                    Follow Us
                </small>
                <h1 class="display-5 mb-5">Stay Connected on Social Media</h1>
            </div>

            <div class="row g-4">
                <!-- Instagram Feed -->
                <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.1s">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-gradient text-white" style="background: linear-gradient(45deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%);">
                            <h4 class="mb-0">
                                <i class="fab fa-instagram me-2"></i>Instagram
                            </h4>
                        </div>
                        <div class="card-body p-4">
                            <p class="mb-3">Follow us for daily food inspiration and event highlights!</p>
                            <div class="row g-2 mb-3">
                                <div class="col-4">
                                    <div class="ratio ratio-1x1">
                                        <img src="img/event-1.jpg" class="img-fluid rounded" alt="Instagram post">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="ratio ratio-1x1">
                                        <img src="img/menu-01.jpg" class="img-fluid rounded" alt="Instagram post">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="ratio ratio-1x1">
                                        <img src="img/event-2.jpg" class="img-fluid rounded" alt="Instagram post">
                                    </div>
                                </div>
                            </div>
                            <a href="https://www.instagram.com/altafcateringcompany/" target="_blank" 
                               class="btn btn-outline-primary w-100">
                                <i class="fab fa-instagram me-2"></i>Follow on Instagram
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Facebook Feed -->
                <div class="col-lg-6 wow fadeInRight" data-wow-delay="0.3s">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0">
                                <i class="fab fa-facebook-f me-2"></i>Facebook
                            </h4>
                        </div>
                        <div class="card-body p-4">
                            <p class="mb-3">Join our community and stay updated with latest offers!</p>
                            <div class="d-flex align-items-center mb-3 p-3 bg-light rounded">
                                <img src="img/logo.png" class="rounded-circle me-3" style="width: 50px; height: 50px;" alt="Altaf Catering">
                                <div>
                                    <h6 class="mb-0">Altaf Catering Company</h6>
                                    <small class="text-muted">200+ Happy Clients</small>
                                </div>
                            </div>
                            <a href="https://web.facebook.com/AltafCateringCompany" target="_blank" 
                               class="btn btn-primary w-100 mb-2">
                                <i class="fab fa-facebook-f me-2"></i>Like our Page
                            </a>
                            <a href="https://www.youtube.com/@Altafcateringcompanyy" target="_blank" 
                               class="btn btn-danger w-100">
                                <i class="fab fa-youtube me-2"></i>Subscribe on YouTube
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Social Stats -->
            <div class="row g-4 mt-4">
                <div class="col-md-3 col-6 text-center wow bounceInUp" data-wow-delay="0.1s">
                    <div class="card shadow-sm border-0 p-3">
                        <i class="fab fa-instagram fa-3x text-danger mb-2"></i>
                        <h4 class="mb-0">5K+</h4>
                        <small class="text-muted">Followers</small>
                    </div>
                </div>
                <div class="col-md-3 col-6 text-center wow bounceInUp" data-wow-delay="0.3s">
                    <div class="card shadow-sm border-0 p-3">
                        <i class="fab fa-facebook-f fa-3x text-primary mb-2"></i>
                        <h4 class="mb-0">10K+</h4>
                        <small class="text-muted">Likes</small>
                    </div>
                </div>
                <div class="col-md-3 col-6 text-center wow bounceInUp" data-wow-delay="0.5s">
                    <div class="card shadow-sm border-0 p-3">
                        <i class="fab fa-youtube fa-3x text-danger mb-2"></i>
                        <h4 class="mb-0">2K+</h4>
                        <small class="text-muted">Subscribers</small>
                    </div>
                </div>
                <div class="col-md-3 col-6 text-center wow bounceInUp" data-wow-delay="0.7s">
                    <div class="card shadow-sm border-0 p-3">
                        <i class="fab fa-tiktok fa-3x text-dark mb-2"></i>
                        <h4 class="mb-0">8K+</h4>
                        <small class="text-muted">Followers</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Social Media Feed End -->


    <!-- Blog Start -->
    <div class="container-fluid blog py-6">
        <div class="container">
            <div class="text-center wow bounceInUp" data-wow-delay="0.1s">
                <small
                    class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">Our
                    Blog</small>
                <h1 class="display-5 mb-5">Be First Who Read News</h1>
            </div>
            <div class="row gx-4 justify-content-center">
                <?php 
                // Load latest 3 blog posts
                $blog_data = [];
                if (file_exists('admin/data/blogs.json')) {
                    $all_blogs = json_decode(file_get_contents('admin/data/blogs.json'), true);
                    // Filter only published blogs
                    $published_blogs = array_filter($all_blogs, function($blog) {
                        return $blog['status'] == 'published';
                    });
                    // Sort by date (newest first) and get only 3
                    usort($published_blogs, function($a, $b) {
                        return strtotime($b['date']) - strtotime($a['date']);
                    });
                    $blog_data = array_slice($published_blogs, 0, 3);
                }
                
                if (!empty($blog_data)):
                    $delay = 0.1;
                    foreach ($blog_data as $blog): 
                        $date = date_create($blog['date']);
                        $day = date_format($date, 'd');
                        $month = date_format($date, 'M');
                ?>
                <div class="col-md-6 col-lg-4 wow bounceInUp" data-wow-delay="<?php echo $delay; ?>s">
                    <div class="blog-item">
                        <div class="overflow-hidden rounded">
                            <?php if (!empty($blog['image'])): ?>
                                <img src="<?php echo htmlspecialchars($blog['image']); ?>" class="img-fluid w-100" alt="<?php echo htmlspecialchars($blog['title']); ?>">
                            <?php else: ?>
                                <img src="img/blog-1.jpg" class="img-fluid w-100" alt="<?php echo htmlspecialchars($blog['title']); ?>">
                            <?php endif; ?>
                        </div>
                        <div class="blog-content mx-4 d-flex rounded bg-light">
                            <div class="text-dark bg-primary rounded-start">
                                <div class="h-100 p-3 d-flex flex-column justify-content-center text-center">
                                    <p class="fw-bold mb-0"><?php echo $day; ?></p>
                                    <p class="fw-bold mb-0"><?php echo $month; ?></p>
                                </div>
                            </div>
                            <div class="my-auto h-100 p-3 text-start">
                                <a href="blog-detail.php?id=<?php echo $blog['id']; ?>" class="h5 lh-base d-block">
                                    <?php echo htmlspecialchars($blog['title']); ?>
                                </a>
                                <p class="mb-0 text-muted">
                                    <?php echo htmlspecialchars(substr($blog['content'], 0, 100)) . '...'; ?>
                                </p>
                                <a href="blog-detail.php?id=<?php echo $blog['id']; ?>" class="btn btn-sm btn-primary mt-2">
                                    Read More <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php 
                    $delay += 0.2;
                    endforeach;
                else:
                ?>
                <div class="col-12 text-center">
                    <p class="text-muted">No blog posts available at the moment.</p>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Learn More Button -->
            <?php if (!empty($blog_data)): ?>
            <div class="text-center mt-5 wow bounceInUp" data-wow-delay="0.3s">
                <a href="blog.php" class="btn btn-primary rounded-pill py-3 px-5">
                    Learn More <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- Blog End -->

    <!-- Photo Gallery Start -->
    <div class="container-fluid gallery py-6">
        <div class="container">
            <div class="text-center wow bounceInUp" data-wow-delay="0.1s">
                <small
                    class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">Our
                    Gallery</small>
                <h1 class="display-5 mb-5">Moments We Captured</h1>
            </div>
            <div class="row g-4">
                <?php 
                if (!empty($moments_data)):
                    $delay = 0.1;
                    foreach ($moments_data as $moment): 
                ?>
                <div class="col-lg-4 col-md-6 wow bounceInUp" data-wow-delay="<?php echo $delay; ?>s">
                    <div class="gallery-item rounded overflow-hidden">
                        <div class="position-relative">
                            <img class="img-fluid w-100" src="<?php echo htmlspecialchars($moment['image']); ?>"
                                alt="<?php echo htmlspecialchars($moment['title']); ?> - <?php echo htmlspecialchars($moment['description']); ?>"
                                style="height: 300px; object-fit: cover;">
                            <div
                                class="gallery-overlay position-absolute start-0 top-0 w-100 h-100 d-flex align-items-center justify-content-center">
                                <a href="<?php echo htmlspecialchars($moment['image']); ?>" data-lightbox="moments-gallery"
                                    class="btn btn-primary rounded-circle">
                                    <i class="fas fa-plus"></i>
                                </a>
                            </div>
                        </div>
                        <div class="gallery-content text-center p-4">
                            <h4 class="mb-2"><?php echo htmlspecialchars($moment['title']); ?></h4>
                            <p class="text-muted mb-0"><?php echo htmlspecialchars($moment['description']); ?></p>
                        </div>
                    </div>
                </div>
                <?php 
                    $delay += 0.2;
                    endforeach;
                else:
                ?>
                <div class="col-12 text-center">
                    <p class="text-muted">No moments available at the moment. Check back soon!</p>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- View More Button -->
            <?php if (!empty($moments_data)): ?>
            <div class="text-center mt-5 wow bounceInUp" data-wow-delay="0.3s">
                <a href="event.php" class="btn btn-primary rounded-pill py-3 px-5">
                    View All Events & Moments <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- Photo Gallery End -->

    <!-- Pricing Start -->
    <div class="container-fluid py-6">
        <div class="container">
            <div class="text-center wow bounceInUp" data-wow-delay="0.1s">
                <small
                    class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">Our
                    Packages</small>
                <h1 class="display-5 mb-5">Catering Package Prices</h1>
            </div>
            <?php if (!empty($pricing_data['packages'])): ?>
            <div class="row g-4">
                <?php 
                $delay = 0.1;
                foreach ($pricing_data['packages'] as $package): 
                ?>
                <div class="col-lg-4 col-md-6 wow bounceInUp" data-wow-delay="<?php echo $delay; ?>s">
                    <div class="price-item position-relative border border-primary rounded p-4 mb-4 <?php echo $package['popular'] ? 'premium-package' : ''; ?>">
                        <div class="border-bottom border-primary pb-4 mb-4">
                            <h4 class="text-primary mb-1"><?php echo htmlspecialchars($package['name']); ?></h4>
                            <h1 class="display-5 mb-0">
                                <small class="align-top" style="font-size: 22px; line-height: 45px;">PKR</small><?php echo number_format($package['price']); ?><small
                                    class="align-bottom" style="font-size: 16px; line-height: 40px;">/ Person</small>
                            </h1>
                        </div>
                        <?php if (!empty($package['description'])): ?>
                        <p class="text-muted mb-3"><?php echo htmlspecialchars($package['description']); ?></p>
                        <?php endif; ?>
                        <div class="mb-4">
                            <?php foreach ($package['features'] as $feature): ?>
                            <p class="mb-1"><i class="fa fa-check text-primary me-2"></i><?php echo htmlspecialchars($feature); ?></p>
                            <?php endforeach; ?>
                        </div>
                        <a href="book.php" class="btn btn-primary px-4 py-2 rounded-pill">Book Now</a>
                        <?php if ($package['popular']): ?>
                        <div class="position-absolute top-0 start-0 translate-middle bg-primary rounded-circle p-3">
                            <span class="text-white small">Popular</span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php 
                $delay += 0.2;
                endforeach; 
                ?>
            </div>
            <?php else: ?>
            <div class="alert alert-info text-center">
                <p class="mb-0">No packages available at the moment. Please check back later or contact us directly.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- Pricing End -->

    <!-- FAQ Start -->
    <div class="container-fluid py-6">
        <div class="container">
            <div class="text-center wow bounceInUp" data-wow-delay="0.1s">
                <small
                    class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">FAQ</small>
                <h1 class="display-5 mb-5">Frequently Asked Questions</h1>
            </div>
            <?php if (!empty($pricing_data['faqs'])): ?>
            <div class="row g-4">
                <?php 
                $total_faqs = count($pricing_data['faqs']);
                $half = ceil($total_faqs / 2);
                $col1_faqs = array_slice($pricing_data['faqs'], 0, $half);
                $col2_faqs = array_slice($pricing_data['faqs'], $half);
                ?>
                
                <div class="col-lg-6 wow bounceInUp" data-wow-delay="0.1s">
                    <div class="accordion" id="accordionFAQ1">
                        <?php foreach ($col1_faqs as $index => $faq): ?>
                        <div class="accordion-item border border-primary rounded mb-3">
                            <h2 class="accordion-header" id="headingIndex<?php echo $faq['id']; ?>">
                                <button class="accordion-button <?php echo $index > 0 ? 'collapsed' : ''; ?>" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseIndex<?php echo $faq['id']; ?>">
                                    <?php echo htmlspecialchars($faq['question']); ?>
                                </button>
                            </h2>
                            <div id="collapseIndex<?php echo $faq['id']; ?>" class="accordion-collapse collapse <?php echo $index == 0 ? 'show' : ''; ?>"
                                data-bs-parent="#accordionFAQ1">
                                <div class="accordion-body">
                                    <?php echo nl2br(htmlspecialchars($faq['answer'])); ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <?php if (!empty($col2_faqs)): ?>
                <div class="col-lg-6 wow bounceInUp" data-wow-delay="0.3s">
                    <div class="accordion" id="accordionFAQ2">
                        <?php foreach ($col2_faqs as $faq): ?>
                        <div class="accordion-item border border-primary rounded mb-3">
                            <h2 class="accordion-header" id="headingIndex<?php echo $faq['id']; ?>">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseIndex<?php echo $faq['id']; ?>">
                                    <?php echo htmlspecialchars($faq['question']); ?>
                                </button>
                            </h2>
                            <div id="collapseIndex<?php echo $faq['id']; ?>" class="accordion-collapse collapse"
                                data-bs-parent="#accordionFAQ2">
                                <div class="accordion-body">
                                    <?php echo nl2br(htmlspecialchars($faq['answer'])); ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <?php else: ?>
            <div class="alert alert-info text-center">
                <p class="mb-0">No FAQs available at the moment. Please check back later or contact us directly.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- FAQ End -->


    <!-- Jobs Section Start -->
    <div class="container-fluid bg-light py-6">
        <div class="container">
            <div class="text-center wow bounceInUp" data-wow-delay="0.1s">
                <small
                    class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">
                    Careers</small>
                <h1 class="display-5 mb-5">Join Our Team</h1>
                <p class="mb-5">Explore exciting career opportunities at Altaf Catering. Be part of our growing family!</p>
            </div>
            
            <?php if (empty($jobs_data)): ?>
                <div class="alert alert-info text-center">
                    <h4>No job openings at the moment</h4>
                    <p>Please check back later or send us your resume at <a href="mailto:altafcatering@gmail.com">altafcatering@gmail.com</a></p>
                </div>
            <?php else: ?>
                <div class="row g-4 mb-5">
                    <?php 
                    $delay = 0.1;
                    foreach ($jobs_data as $job): 
                        // Determine badge color based on job type
                        $badge_class = 'bg-primary';
                        if (strtolower($job['type']) == 'part-time') {
                            $badge_class = 'bg-warning text-dark';
                        } elseif (strtolower($job['type']) == 'contract') {
                            $badge_class = 'bg-info';
                        }
                    ?>
                    <div class="col-lg-6 wow bounceInUp" data-wow-delay="<?php echo $delay; ?>s">
                        <div class="bg-white rounded p-4 shadow-sm h-100">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="text-primary mb-0"><?php echo htmlspecialchars($job['title']); ?></h4>
                                <span class="badge <?php echo $badge_class; ?>"><?php echo htmlspecialchars($job['type']); ?></span>
                            </div>
                            <p class="mb-2">
                                <i class="fas fa-map-marker-alt text-primary me-2"></i><?php echo htmlspecialchars($job['location']); ?>
                            </p>
                            <p class="mb-3"><?php echo htmlspecialchars(substr($job['description'], 0, 120)) . '...'; ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i><?php echo date('M d, Y', strtotime($job['posted_date'])); ?>
                                </small>
                                <a href="https://wa.me/923039907296?text=Hi, I'm interested in the <?php echo urlencode($job['title']); ?> position"
                                    class="btn btn-primary btn-sm rounded-pill" target="_blank">
                                    <i class="fab fa-whatsapp me-1"></i>Apply Now
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php 
                    $delay += 0.1;
                    if ($delay > 0.4) $delay = 0.1;
                    endforeach; 
                    ?>
                </div>
                
                <!-- View All Jobs Button -->
                <div class="text-center wow bounceInUp" data-wow-delay="0.5s">
                    <a href="careers.php" class="btn btn-primary btn-lg rounded-pill py-3 px-5">
                        <i class="fas fa-briefcase me-2"></i>View All Job Openings
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- Jobs Section End -->


    <!-- Contact Start -->
    <div class="container-fluid py-6">
        <div class="container">
            <div class="text-center wow bounceInUp" data-wow-delay="0.1s">
                <small
                    class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">Contact
                    Us</small>
                <h1 class="display-5 mb-5">Get In Touch</h1>
            </div>
            <div class="row g-5">
                <div class="col-lg-6 wow bounceInUp" data-wow-delay="0.1s">
                    <div class="contact-info">
                        <div class="d-flex mb-4">
                            <i class="fas fa-map-marker-alt text-primary fa-2x me-3"></i>
                            <div>
                                <h4>Our Location</h4>
                                <p class="mb-0">MM Farm House Sharif Medical Jati Umrah Road, Karachi, Sindh, Pakistan
                                </p>
                            </div>
                        </div>
                        <div class="d-flex mb-4">
                            <i class="fas fa-phone-alt text-primary fa-2x me-3"></i>
                            <div>
                                <h4>Call Us</h4>
                                <p class="mb-0"><a href="tel:+923039907296">+92 303 9907296</a></p>
                            </div>
                        </div>
                        <div class="d-flex mb-4">
                            <i class="fas fa-envelope text-primary fa-2x me-3"></i>
                            <div>
                                <h4>Email Us</h4>
                                <p class="mb-0"><a href="mailto:altafcatering@gmail.com">altafcatering@gmail.com</a></p>
                            </div>
                        </div>
                        <div class="d-flex mb-4">
                            <i class="fas fa-clock text-primary fa-2x me-3"></i>
                            <div>
                                <h4>Business Hours</h4>
                                <p class="mb-0">24/7 Hours Service</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 wow bounceInUp" data-wow-delay="0.3s">
                    <div class="map-container">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3618.0!2d67.0011!3d24.8607!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjTCsDUxJzM4LjUiTiA2N8KwMDAnMDQuMCJF!5e0!3m2!1sen!2s!4v1690000000000!5m2!1sen!2s"
                            width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact End -->


    <!-- Newsletter Subscription Start -->
    <div class="container-fluid newsletter py-6" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.1s">
                    <div class="text-white">
                        <h2 class="display-6 mb-3">📧 Subscribe to Our Newsletter</h2>
                        <p class="mb-4 fs-5">Get exclusive offers, menu updates, and catering tips delivered to your inbox!</p>
                        <div class="d-flex gap-3 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle fa-2x me-2"></i>
                                <span>Weekly Updates</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle fa-2x me-2"></i>
                                <span>Special Offers</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle fa-2x me-2"></i>
                                <span>Event Tips</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 wow fadeInRight" data-wow-delay="0.3s">
                    <div class="card shadow-lg border-0">
                        <div class="card-body p-4">
                            <form id="newsletterForm" class="newsletter-form">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Your Name</label>
                                    <input type="text" id="newsletterName" class="form-control form-control-lg" 
                                           placeholder="Enter your name" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Email Address</label>
                                    <input type="email" id="newsletterEmail" class="form-control form-control-lg" 
                                           placeholder="Enter your email" required>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="newsletterConsent" required>
                                        <label class="form-check-label small" for="newsletterConsent">
                                            I agree to receive marketing emails and updates
                                        </label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-paper-plane me-2"></i>Subscribe Now
                                </button>
                                <div id="newsletterMessage" class="mt-3"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Newsletter Subscription End -->


    <?php include "includes/footer.php"; ?>





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

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    
    <!-- WOW.js Initialization -->
    <script src="js/wow-init.js"></script>
    
    <!-- Animation Fixer & Debugger -->
    <script src="js/animation-fixer.js"></script>
    
    <!-- Booking Form Validation -->
    <script src="js/booking-form.js"></script>
    
    <!-- Form Handler with Email Notifications -->
    <script src="js/form-handler.js"></script>

</body>

</html>