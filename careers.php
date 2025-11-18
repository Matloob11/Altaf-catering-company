<?php
// Prevent caching to always show latest data
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

// Load jobs data from JSON
$jobs = [];
if (file_exists('admin/data/jobs.json')) {
    $all_jobs = json_decode(file_get_contents('admin/data/jobs.json'), true);
    // Filter only active jobs
    $jobs = array_filter($all_jobs, function($job) {
        return $job['status'] == 'active';
    });
    $jobs = array_values($jobs);
    // Sort by posted date, newest first
    usort($jobs, function($a, $b) {
        return strtotime($b['posted_date']) - strtotime($a['posted_date']);
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
    <title>Careers — Join Altaf Catering Team</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="keywords" content="catering jobs, chef jobs, kitchen jobs, hospitality careers, Altaf Catering careers">
    <meta name="description"
        content="Join the Altaf Catering family! Explore exciting career opportunities in catering, cooking, and event management. Apply now!">
    <link rel="canonical" href="https://altafcatering.com/careers.html" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Careers at Altaf Catering" />
    <meta property="og:description"
        content="Join our team of passionate food professionals. Multiple positions available." />
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
    
    <!-- CRITICAL: Text Visibility Fix -->
    <link href="css/text-fix.css" rel="stylesheet">
    
    <!-- CRITICAL: Hover State Fix -->
    <link href="css/hover-fix.css" rel="stylesheet">
</head>

<body>
    <?php include 'includes/contact-buttons.php'; ?>
    <?php $loader_text = "Loading Career Opportunities..."; include 'includes/loader.php'; ?>

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

    <?php include 'includes/navbar.php'; ?>

    <div class="container-fluid bg-light py-6 my-6 mt-0">
        <div class="container text-center animated bounceInDown">
            <h1 class="display-1 mb-4">Careers</h1>
            <ol class="breadcrumb justify-content-center mb-0 animated bounceInDown">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item text-dark" aria-current="page">Careers</li>
            </ol>
        </div>
    </div>

    <!-- Why Join Us -->
    <div class="container-fluid py-6">
        <div class="container">
            <div class="text-center wow bounceInUp" data-wow-delay="0.1s">
                <small
                    class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">Join
                    Our Team</small>
                <h1 class="display-5 mb-5">Why Work With Altaf Catering?</h1>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6 wow bounceInUp" data-wow-delay="0.1s">
                    <div class="text-center">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                            style="width: 100px; height: 100px;">
                            <i class="fas fa-users fa-3x text-primary"></i>
                        </div>
                        <h4 class="mb-3">Great Team</h4>
                        <p class="mb-0">Work with passionate professionals who love what they do.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 wow bounceInUp" data-wow-delay="0.2s">
                    <div class="text-center">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                            style="width: 100px; height: 100px;">
                            <i class="fas fa-graduation-cap fa-3x text-primary"></i>
                        </div>
                        <h4 class="mb-3">Training</h4>
                        <p class="mb-0">Learn from experienced chefs and grow your skills.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 wow bounceInUp" data-wow-delay="0.3s">
                    <div class="text-center">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                            style="width: 100px; height: 100px;">
                            <i class="fas fa-chart-line fa-3x text-primary"></i>
                        </div>
                        <h4 class="mb-3">Career Growth</h4>
                        <p class="mb-0">Clear path for advancement and promotions.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 wow bounceInUp" data-wow-delay="0.4s">
                    <div class="text-center">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                            style="width: 100px; height: 100px;">
                            <i class="fas fa-hand-holding-usd fa-3x text-primary"></i>
                        </div>
                        <h4 class="mb-3">Good Pay</h4>
                        <p class="mb-0">Competitive salary and benefits package.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Job Openings -->
    <div class="container-fluid bg-light py-6">
        <div class="container">
            <div class="text-center wow bounceInUp" data-wow-delay="0.1s">
                <small
                    class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">Open
                    Positions</small>
                <h1 class="display-5 mb-5">Current Job Openings</h1>
            </div>
            <?php if (empty($jobs)): ?>
                <div class="alert alert-info text-center">
                    <h4>No job openings at the moment</h4>
                    <p>Please check back later or send us your resume at <a href="mailto:altafcatering@gmail.com">altafcatering@gmail.com</a></p>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php 
                    $delay = 0.1;
                    foreach ($jobs as $job): 
                        // Determine badge color based on job type
                        $badge_class = 'bg-primary';
                        if (strtolower($job['type']) == 'part time') {
                            $badge_class = 'bg-warning text-dark';
                        } elseif (strtolower($job['type']) == 'contract') {
                            $badge_class = 'bg-info';
                        } elseif (stripos($job['title'], 'entry') !== false || stripos($job['title'], 'helper') !== false) {
                            $badge_class = 'bg-success';
                        }
                        
                        // Parse requirements if it's a string
                        $requirements = is_array($job['requirements']) ? $job['requirements'] : explode(',', $job['requirements']);
                        $requirements = array_slice($requirements, 0, 3); // Show only first 3
                    ?>
                    <div class="col-lg-6 wow bounceInUp" data-wow-delay="<?php echo $delay; ?>s">
                        <div class="bg-white rounded p-4 shadow-sm">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="text-primary mb-0"><?php echo htmlspecialchars($job['title']); ?></h4>
                                <span class="badge <?php echo $badge_class; ?>"><?php echo htmlspecialchars($job['type']); ?></span>
                            </div>
                            <p class="mb-3">
                                <i class="fas fa-map-marker-alt text-primary me-2"></i><?php echo htmlspecialchars($job['location']); ?>
                            </p>
                            <p class="mb-3"><?php echo htmlspecialchars($job['description']); ?></p>
                            <?php if (!empty($requirements)): ?>
                            <div class="mb-3">
                                <?php foreach ($requirements as $req): ?>
                                    <span class="badge bg-light text-dark me-2"><?php echo htmlspecialchars(trim($req)); ?></span>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>Posted: <?php echo date('M d, Y', strtotime($job['posted_date'])); ?>
                                </small>
                                <a href="https://wa.me/923039907296?text=Hi, I'm interested in the <?php echo urlencode($job['title']); ?> position"
                                    class="btn btn-primary rounded-pill" target="_blank">Apply Now</a>
                            </div>
                        </div>
                    </div>
                    <?php 
                    $delay += 0.1;
                    if ($delay > 0.6) $delay = 0.1;
                    endforeach; 
                    ?>
                </div>
            <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- Ap
plication Process -->
    <div class="container-fluid py-6">
        <div class="container">
            <div class="text-center wow bounceInUp" data-wow-delay="0.1s">
                <small
                    class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">How
                    to Apply</small>
                <h1 class="display-5 mb-5">Simple Application Process</h1>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6 wow bounceInUp" data-wow-delay="0.1s">
                    <div class="text-center">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                            style="width: 80px; height: 80px;">
                            <h2 class="mb-0">1</h2>
                        </div>
                        <h5 class="mb-3">Choose Position</h5>
                        <p>Browse available positions and find the right fit for you.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 wow bounceInUp" data-wow-delay="0.2s">
                    <div class="text-center">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                            style="width: 80px; height: 80px;">
                            <h2 class="mb-0">2</h2>
                        </div>
                        <h5 class="mb-3">Send Application</h5>
                        <p>Click "Apply Now" and send your details via WhatsApp.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 wow bounceInUp" data-wow-delay="0.3s">
                    <div class="text-center">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                            style="width: 80px; height: 80px;">
                            <h2 class="mb-0">3</h2>
                        </div>
                        <h5 class="mb-3">Interview</h5>
                        <p>We'll review your application and schedule an interview.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 wow bounceInUp" data-wow-delay="0.4s">
                    <div class="text-center">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                            style="width: 80px; height: 80px;">
                            <h2 class="mb-0">4</h2>
                        </div>
                        <h5 class="mb-3">Join Team</h5>
                        <p>Start your exciting career with Altaf Catering!</p>
                    </div>
                </div>
            </div>
            <div class="text-center mt-5 wow bounceInUp" data-wow-delay="0.5s">
                <p class="lead mb-4">Don't see the right position? Send us your CV anyway!</p>
                <a href="https://wa.me/923039907296?text=Hi, I'd like to submit my CV for future opportunities"
                    class="btn btn-primary py-3 px-5 rounded-pill" target="_blank">Send Your CV</a>
            </div>
        </div>
    </div>

    <!-- Footer -->
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
                        <p class="lh-lg mb-4">Join our team and be part of Pakistan's leading catering company.</p>
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
                        <h4 class="mb-4">Quick Links</h4>
                        <div class="d-flex flex-column align-items-start">
                            <a class="text-body mb-3" href="about.php"><i
                                    class="fa fa-check text-primary me-2"></i>About Us</a>
                            <a class="text-body mb-3" href="service.php"><i
                                    class="fa fa-check text-primary me-2"></i>Our Services</a>
                            <a class="text-body mb-3" href="team.php"><i class="fa fa-check text-primary me-2"></i>Our
                                Team</a>
                            <a class="text-body mb-3" href="contact.php"><i
                                    class="fa fa-check text-primary me-2"></i>Contact Us</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer-item">
                        <h4 class="mb-4">Contact Us</h4>
                        <div class="d-flex flex-column align-items-start">
                            <p><i class="fa fa-map-marker-alt text-primary me-2"></i>MM Farm House, Karachi, Pakistan
                            </p>
                            <p><i class="fa fa-phone-alt text-primary me-2"></i><a href="tel:+923039907296">+92 303
                                    9907296</a></p>
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
                                    class="img-fluid rounded-circle border border-primary p-2" alt="Paneer dish"></div>
                            <div class="col-4"><img src="img/menu-02.jpg"
                                    class="img-fluid rounded-circle border border-primary p-2" alt="Sweet Potato"></div>
                            <div class="col-4"><img src="img/menu-03.jpg"
                                    class="img-fluid rounded-circle border border-primary p-2" alt="Sabudana Tikki">
                            </div>
                            <div class="col-4"><img src="img/menu-04.jpg"
                                    class="img-fluid rounded-circle border border-primary p-2" alt="Pizza"></div>
                            <div class="col-4"><img src="img/menu-05.jpg"
                                    class="img-fluid rounded-circle border border-primary p-2" alt="Bacon"></div>
                            <div class="col-4"><img src="img/menu-06.jpg"
                                    class="img-fluid rounded-circle border border-primary p-2" alt="Chicken"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

    <a href="#" class="btn btn-md-square btn-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>

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
</body>

</html>