<?php
// Load privacy data from JSON
$privacy_file = 'admin/data/privacy.json';
$privacy = file_exists($privacy_file) ? json_decode(file_get_contents($privacy_file), true) : [
    'page_title' => 'Privacy Policy',
    'page_subtitle' => 'Please read how we handle and protect your information',
    'last_updated' => 'November 2025',
    'intro_text' => 'Below is a clear summary of how Altaf Catering collects, uses, protects, and shares personal information — presented in focused sections for quick scanning.',
    'sections' => []
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Privacy Policy — Altaf Catering Company</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="keywords" content="privacy policy, data protection, personal information, Altaf Catering">
    <meta name="description"
        content="Privacy Policy for Altaf Catering. Learn how we protect your personal information and data. Last Updated: November 2025.">
    <link rel="canonical" href="https://altafcatering.com/privacy.html" />

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
    
    <!-- Custom Privacy Page Styles -->
    <style>
        .privacy-card .card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 15px;
            overflow: hidden;
        }
        
        .privacy-card .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
        }
        
        .hover-lift {
            transition: all 0.3s ease;
        }
        
        .icon-box {
            transition: all 0.3s ease;
        }
        
        .privacy-card:hover .icon-box {
            transform: scale(1.1);
            box-shadow: 0 8px 25px rgba(13, 110, 253, 0.3);
        }
        
        .privacy-card .card-title {
            transition: color 0.3s ease;
        }
        
        .privacy-card:hover .card-title {
            color: #0d6efd !important;
        }
        
        .bg-light.rounded-4 {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
            border: 1px solid rgba(13, 110, 253, 0.1);
        }
        
        @media (max-width: 768px) {
            .privacy-card .card {
                margin-bottom: 1rem;
            }
        }
        
        /* Smooth scroll behavior */
        html {
            scroll-behavior: smooth;
        }
        
        /* Enhanced animations */
        .wow {
            visibility: hidden;
        }
        
        .animated {
            animation-duration: 1s;
            animation-fill-mode: both;
        }
        
        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translate3d(-100%, 0, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }
        
        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translate3d(100%, 0, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }
        
        .fadeInLeft {
            animation-name: fadeInLeft;
        }
        
        .fadeInRight {
            animation-name: fadeInRight;
        }
    </style>
</head>

<body>

    <?php include 'includes/contact-buttons.php'; ?>

    <?php $loader_text = "Loading Privacy Policy..."; include 'includes/loader.php'; ?>


    <!-- Navbar start -->
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
            <h1 class="display-1 mb-4"><?php echo htmlspecialchars($privacy['page_title']); ?></h1>
            <ol class="breadcrumb justify-content-center mb-0 animated bounceInDown">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item text-dark" aria-current="page">privacy</li>
            </ol>
        </div>
    </div>
    <!-- Hero End -->

    <!-- Privacy Policy Content Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <!-- Header Section -->
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 700px;">
                <h2 class="display-6 mb-4"><?php echo htmlspecialchars($privacy['page_title']); ?></h2>
                <p class="text-muted fs-5"><?php echo htmlspecialchars($privacy['page_subtitle']); ?></p>
                <div class="bg-primary mx-auto rounded-pill" style="width: 100px; height: 3px;"></div>
            </div>

            <!-- Intro Text -->
            <?php if (!empty($privacy['intro_text'])): ?>
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8">
                    <div class="alert alert-primary text-center wow fadeInUp" data-wow-delay="0.2s">
                        <i class="fas fa-shield-alt me-2"></i>
                        <?php echo htmlspecialchars($privacy['intro_text']); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Last Updated Info -->
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8">
                    <div class="alert alert-info text-center wow fadeInUp" data-wow-delay="0.25s">
                        <i class="fas fa-calendar-alt me-2"></i>
                        <strong>Last Updated:</strong> <?php echo htmlspecialchars($privacy['last_updated']); ?>
                    </div>
                </div>
            </div>

            <!-- Privacy Sections Grid -->
            <div class="row g-4">
                <?php 
                $delay = 0.3;
                $index = 0;
                foreach ($privacy['sections'] as $section): 
                    $animationClass = ($index % 2 == 0) ? 'fadeInLeft' : 'fadeInRight';
                ?>
                <div class="col-lg-6">
                    <div class="privacy-card h-100 wow <?php echo $animationClass; ?>" data-wow-delay="<?php echo $delay; ?>s">
                        <div class="card border-0 shadow-sm h-100 hover-lift">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-box bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                        <i class="<?php echo htmlspecialchars($section['icon']); ?>"></i>
                                    </div>
                                    <h5 class="card-title mb-0 text-primary"><?php echo htmlspecialchars($section['title']); ?></h5>
                                </div>
                                <p class="card-text text-muted lh-lg"><?php echo nl2br(htmlspecialchars($section['content'])); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php 
                $delay += 0.1;
                $index++;
                endforeach; 
                ?>
            </div>

            <!-- Contact Section -->
            <div class="row justify-content-center mt-5">
                <div class="col-lg-8">
                    <div class="text-center wow fadeInUp" data-wow-delay="<?php echo $delay + 0.2; ?>s">
                        <div class="bg-light rounded-4 p-5">
                            <h4 class="text-primary mb-3">
                                <i class="fas fa-shield-alt me-2"></i>
                                Questions About Your Privacy?
                            </h4>
                            <p class="text-muted mb-4">
                                We're committed to protecting your privacy and personal data. 
                                If you have any questions or concerns, please reach out to us.
                            </p>
                            <div class="d-flex justify-content-center gap-3 flex-wrap">
                                <a href="contact.php" class="btn btn-primary rounded-pill px-4">
                                    <i class="fas fa-envelope me-2"></i>Contact Us
                                </a>
                                <a href="mailto:altafcatering@gmail.com" class="btn btn-outline-primary rounded-pill px-4">
                                    <i class="fas fa-mail-bulk me-2"></i>Email Direct
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Privacy Policy Content End -->


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