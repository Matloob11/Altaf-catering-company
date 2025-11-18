<?php
// Load Analytics
require_once 'includes/analytics.php';

// Track visitor
require_once 'includes/visitor-tracking.php';
trackVisitorPageView('contact');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo getAnalyticsScript(); ?>
    <meta charset="utf-8">
    <title>Contact Altaf Catering — Get in Touch for Catering Inquiries</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="keywords"
        content="contact catering, booking inquiry, event catering contact, Altaf Catering, catering services Karachi">
    <meta name="description"
        content="Contact Altaf Catering for event bookings, catering inquiries, and special requests. Get in touch via phone, email, or our contact form. Fast response guaranteed.">
    <link rel="canonical" href="https://altafcatering.com/contact.html" />
    <!-- Open Graph / Twitter -->
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Contact Altaf Catering — Get in Touch for Catering Inquiries" />
    <meta property="og:description"
        content="Contact us for event bookings, menu customization, and catering inquiries. Professional response within 24 hours." />
    <meta property="og:url" content="https://altafcatering.com/contact.html" />
    <meta property="og:image" content="https://altafcatering.com/img/hero.png" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Contact Altaf Catering" />
    <meta name="twitter:description" content="Quick response to all catering inquiries. Contact us today!" />

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

    <?php $loader_text = "Loading Contact Info..."; include 'includes/loader.php'; ?>


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
            <h1 class="display-1 mb-4">Contact</h1>
            <ol class="breadcrumb justify-content-center mb-0 animated bounceInDown">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item text-dark" aria-current="page">Contact</li>
            </ol>
        </div>
    </div>
    <!-- Hero End -->


    <!-- Contact Start -->
    <div class="container-fluid contact py-6 wow bounceInUp" data-wow-delay="0.1s">
        <div class="container">
            <!-- Enhanced Interactive Map Section -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="text-center mb-4">
                        <h2 class="display-6 mb-3">📍 Find Us Here</h2>
                        <p class="text-muted">Visit our locations or get directions</p>
                    </div>
                </div>
                
                <!-- Location Tabs -->
                <div class="col-12 mb-4">
                    <ul class="nav nav-pills justify-content-center" id="locationTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="location1-tab" data-bs-toggle="pill" 
                                    data-bs-target="#location1" type="button" role="tab">
                                <i class="fas fa-building me-2"></i>Head Office - Bahria Town
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="location2-tab" data-bs-toggle="pill" 
                                    data-bs-target="#location2" type="button" role="tab">
                                <i class="fas fa-home me-2"></i>Farm House - Event Venue
                            </button>
                        </li>
                    </ul>
                </div>

                <!-- Map Content -->
                <div class="col-12">
                    <div class="tab-content" id="locationTabContent">
                        <!-- Location 1 -->
                        <div class="tab-pane fade show active" id="location1" role="tabpanel">
                            <div class="card shadow-lg border-0">
                                <div class="card-body p-0">
                                    <div class="ratio ratio-21x9">
                                        <iframe src="https://www.google.com/maps?q=31.3372585,74.2035432&z=16&output=embed"
                                            style="border:0;" allowfullscreen="" loading="lazy"
                                            referrerpolicy="no-referrer-when-downgrade"
                                            aria-label="Head Office Location"></iframe>
                                    </div>
                                    <div class="p-4 bg-light">
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <h5 class="mb-2"><i class="fas fa-map-marker-alt text-primary me-2"></i>Head Office</h5>
                                                <p class="mb-2 text-muted">Bahria Town Lahore — Umer Block (Gate No.2), near Bahria Grand Station</p>
                                                <p class="mb-0"><i class="fas fa-phone text-primary me-2"></i>+92 303 990 7296</p>
                                            </div>
                                            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                                <a href="https://www.google.com/maps/place/31.3372585,74.2035432" 
                                                   target="_blank" class="btn btn-primary btn-lg">
                                                    <i class="fas fa-directions me-2"></i>Get Directions
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Location 2 -->
                        <div class="tab-pane fade" id="location2" role="tabpanel">
                            <div class="card shadow-lg border-0">
                                <div class="card-body p-0">
                                    <div class="ratio ratio-21x9">
                                        <iframe src="https://www.google.com/maps?q=31.3774448,74.1989742&z=16&output=embed"
                                            style="border:0;" allowfullscreen="" loading="lazy"
                                            referrerpolicy="no-referrer-when-downgrade"
                                            aria-label="Farm House Location"></iframe>
                                    </div>
                                    <div class="p-4 bg-light">
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <h5 class="mb-2"><i class="fas fa-map-marker-alt text-primary me-2"></i>Farm House & Event Venue</h5>
                                                <p class="mb-2 text-muted">MM Farm House, Sharif Medical, Jati Umrah Road</p>
                                                <p class="mb-0"><i class="fas fa-phone text-primary me-2"></i>+92 300 885 9633</p>
                                            </div>
                                            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                                <a href="https://www.google.com/maps/place/31.3774448,74.1989742" 
                                                   target="_blank" class="btn btn-primary btn-lg">
                                                    <i class="fas fa-directions me-2"></i>Get Directions
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-5 bg-light rounded contact-form">
                <div class="row g-4">
                    <div class="col-12">
                        <small
                            class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">Get
                            in touch</small>
                        <h1 class="display-5 mb-0">Contact Us For Any Queries!</h1>
                    </div>
                    <div class="col-md-6 col-lg-7">
                        <p class="mb-4">At Altaf Catering, our commitment to taste, presentation, and customer
                            satisfaction has earned us a loyal client base. Let us make your next event unforgettable
                            with our exquisite food and Decore exceptional service.</p>
                        <form id="contactForm">
                            <input id="contactName" name="name" type="text"
                                class="w-100 form-control p-3 mb-4 border-primary bg-light" placeholder="Your Name"
                                data-validate="required|min:3" required>
                            <input id="contactEmail" name="email" type="email"
                                class="w-100 form-control p-3 mb-4 border-primary bg-light"
                                placeholder="Enter Your Email" data-validate="required|email" required>
                            <input id="contactPhone" name="phone" type="tel"
                                class="w-100 form-control p-3 mb-4 border-primary bg-light"
                                placeholder="Your Phone Number" data-validate="required|phone" required>
                            <input id="contactSubject" name="subject" type="text"
                                class="w-100 form-control p-3 mb-4 border-primary bg-light" placeholder="Subject"
                                data-validate="required|min:5" required>
                            <textarea id="contactMessage" name="message"
                                class="w-100 form-control mb-4 p-3 border-primary bg-light" rows="4" cols="10"
                                placeholder="Your Message" data-validate="required|min:10" required></textarea>
                            <button id="contactSubmit"
                                class="w-100 btn btn-primary form-control p-3 border-primary bg-primary rounded-pill"
                                type="submit">Submit Now</button>
                        </form>
                    </div>
                    <div class="col-md-6 col-lg-5">
                        <div>
                            <div class="d-flex w-100 border border-primary p-4 rounded mb-4">
                                <i class="fas fa-map-marker-alt fa-2x text-primary me-4"></i>
                                <div class="">
                                    <h4>Address</h4>
                                    <p>MM Farm House, Sharif Medical, Jati Umrah Road</p>
                                    <p>Head Office: Bahria Town Lahore — Umer Block (Gate No.2), near Bahria Grand
                                        Station</p>
                                </div>
                            </div>
                            <div class="d-flex w-100 border border-primary p-4 rounded mb-4">
                                <i class="fas fa-envelope fa-2x text-primary me-4"></i>
                                <div class="">
                                    <h4>Mail Us</h4>
                                    <p class="mb-2">altafcatering@gmail.com</p>
                                    <p class="mb-0">info@altafcatering.com</p>
                                </div>
                            </div>
                            <div class="d-flex w-100 border border-primary p-4 rounded">
                                <i class="fa fa-phone-alt fa-2x text-primary me-4"></i>
                                <div class="">
                                    <h4>Telephone</h4>
                                    <p class="mb-2">+92 303 990 7296 (WhatsApp)</p>
                                    <p class="mb-0">+92 300 885 9633</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact End -->


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

    <!-- Custom JS -->
    <script src="js/custom.js"></script>

    <!-- Form Validation & Handler -->
    <script src="js/form-validation.js"></script>
    
    <!-- Form Handler with Email Notifications -->
    <script src="js/form-handler.js"></script>
</body>

</html>