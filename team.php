<?php
// Prevent caching to always show latest data
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

// Load team data from JSON
$team_data = [];
if (file_exists('admin/data/team.json')) {
    $team_data = json_decode(file_get_contents('admin/data/team.json'), true);
    // Sort by ID descending to get latest entries first
    usort($team_data, function($a, $b) {
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
    <title>Our Team — Altaf Catering Professional Staff</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="keywords"
        content="catering team, professional chefs, experienced staff, Altaf Catering, catering professionals">
    <meta name="description"
        content="Meet the talented team behind Altaf Catering. Expert chefs, experienced managers, and dedicated professionals delivering exceptional catering experiences.">
    <link rel="canonical" href="https://altafcatering.com/team.html" />
    <!-- Open Graph / Twitter -->
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Our Team — Altaf Catering" />
    <meta property="og:description"
        content="Meet our experienced chefs and catering professionals dedicated to your event's success." />
    <meta property="og:url" content="https://altafcatering.com/team.html" />
    <meta property="og:image" content="https://altafcatering.com/img/hero.png" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Our Team" />
    <meta name="twitter:description" content="Expert catering professionals." />

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
    
    <!-- CRITICAL: Text Visibility Fix -->
    <link href="css/text-fix.css" rel="stylesheet">
    
    <!-- CRITICAL: Hover State Fix -->
    <link href="css/hover-fix.css" rel="stylesheet">
</head>

<body>

    <?php include 'includes/contact-buttons.php'; ?>

    <?php $loader_text = "Loading Our Team..."; include 'includes/loader.php'; ?>


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
            <h1 class="display-1 mb-4">Our Team</h1>
            <ol class="breadcrumb justify-content-center mb-0 animated bounceInDown">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item text-dark" aria-current="page">Our Team</li>
            </ol>
        </div>
    </div>
    <!-- Hero End -->


    <!-- Team Start -->
    <div class="container-fluid team py-6">
        <div class="container">
            <div class="text-center wow bounceInUp" data-wow-delay="0.1s">
                <small
                    class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">Our
                    Team</small>
                <h1 class="display-5 mb-5">We have experienced chef Team</h1>
            </div>
            <div class="row g-4">
                <?php 
                // Filter only active team members
                $active_team = array_filter($team_data, function($member) {
                    return isset($member['status']) && $member['status'] == 'active';
                });
                
                $delay = 0.1;
                foreach ($active_team as $member): 
                ?>
                <div class="col-lg-3 col-md-6 wow bounceInUp" data-wow-delay="<?php echo $delay; ?>s">
                    <div class="team-item rounded position-relative">
                        <img class="img-fluid rounded-top" src="<?php echo htmlspecialchars($member['image']); ?>"
                            alt="<?php echo htmlspecialchars($member['name']); ?> - <?php echo htmlspecialchars($member['position']); ?> at Altaf Catering"
                            style="height: 300px; object-fit: cover;">
                        <div class="team-content text-center py-3 bg-dark rounded-bottom">
                            <h4 class="text-primary"><?php echo htmlspecialchars($member['name']); ?></h4>
                            <p class="text-white mb-2"><?php echo htmlspecialchars($member['position']); ?></p>
                            <a href="team-detail.php?id=<?php echo $member['id']; ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-user me-1"></i> View Profile
                            </a>
                        </div>
                        <div class="team-icon d-flex flex-column justify-content-center m-4">
                            <?php if (!empty($member['facebook'])): ?>
                            <a class="btn btn-primary btn-md-square rounded-circle mb-2"
                                href="<?php echo htmlspecialchars($member['facebook']); ?>"
                                target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook-f"></i></a>
                            <?php endif; ?>
                            
                            <?php if (!empty($member['tiktok'])): ?>
                            <a class="btn btn-primary btn-md-square rounded-circle mb-2"
                                href="<?php echo htmlspecialchars($member['tiktok']); ?>"
                                target="_blank" rel="noopener noreferrer"><i class="fab fa-tiktok"></i></a>
                            <?php endif; ?>
                            
                            <?php if (!empty($member['instagram'])): ?>
                            <a class="btn btn-primary btn-md-square rounded-circle mb-2"
                                href="<?php echo htmlspecialchars($member['instagram']); ?>"
                                target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
                            <?php endif; ?>
                            
                            <?php if (!empty($member['youtube'])): ?>
                            <a class="btn btn-primary btn-md-square rounded-circle mb-2"
                                href="<?php echo htmlspecialchars($member['youtube']); ?>"
                                target="_blank" rel="noopener noreferrer"><i class="fab fa-youtube"></i></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php 
                    $delay += 0.2;
                endforeach; 
                ?>
            </div>
        </div>
    </div>
    <!-- Team End -->


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

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>