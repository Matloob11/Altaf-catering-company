<?php
// Prevent caching to always show latest data
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

// Load testimonials data from JSON
$testimonials_data = [];
if (file_exists('admin/data/testimonials.json')) {
    $all_testimonials = json_decode(file_get_contents('admin/data/testimonials.json'), true);
    // Filter only published testimonials
    $testimonials_data = array_filter($all_testimonials, function($testimonial) {
        return $testimonial['status'] == 'published';
    });
    $testimonials_data = array_values($testimonials_data);
    // Sort by ID descending to get latest entries first
    usort($testimonials_data, function($a, $b) {
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
    <title>Customer Testimonials & Reviews — Altaf Catering</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="keywords"
        content="catering reviews, customer testimonials, Altaf Catering reviews, catering feedback, 5-star ratings">
    <meta name="description"
        content="See what our satisfied clients say about Altaf Catering. Read testimonials and reviews from weddings, corporate events, and special occasions.">
    <link rel="canonical" href="https://altafcatering.com/testimonial.html" />
    <!-- Open Graph / Twitter -->
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Customer Testimonials — Altaf Catering" />
    <meta property="og:description"
        content="Hear from our satisfied clients. Exceptional catering experiences and outstanding service feedback." />
    <meta property="og:url" content="https://altafcatering.com/testimonial.html" />
    <meta property="og:image" content="https://altafcatering.com/img/hero.png" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Testimonials & Reviews" />
    <meta name="twitter:description" content="See what customers say about us." />

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

    <?php $loader_text = "Loading Testimonials..."; include 'includes/loader.php'; ?>


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
            <h1 class="display-1 mb-4">Testimonial</h1>
            <ol class="breadcrumb justify-content-center mb-0 animated bounceInDown">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item text-dark" aria-current="page">Testimonial</li>
            </ol>
        </div>
    </div>
    <!-- Hero End -->


    <!-- Testimonial Start -->
    <div class="container-fluid py-6">
        <div class="container">
            <div class="text-center wow bounceInUp" data-wow-delay="0.1s">
                <small
                    class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">Testimonial</small>
                <h1 class="display-5 mb-5">What Our Customers says!</h1>
            </div>
            <div class="owl-carousel owl-theme testimonial-carousel testimonial-carousel-1 mb-4 wow bounceInUp"
                data-wow-delay="0.1s">
                <div class="testimonial-item rounded bg-light" itemscope itemtype="http://schema.org/Review">
                    <div class="d-flex mb-3">
                        <img src="img/testimonial-1.jpg" class="img-fluid rounded-circle flex-shrink-0 lazy"
                            loading="lazy" alt="Ayesha Khan" itemprop="image">
                        <div class="position-absolute" style="top: 15px; right: 20px;">
                            <i class="fa fa-quote-right fa-2x"></i>
                        </div>
                        <div class="ps-3 my-auto">
                            <h4 class="mb-0"><span itemprop="author">Ayesha Khan</span></h4>
                            <p class="m-0" itemprop="jobTitle">Event Planner, Karachi</p>
                        </div>
                    </div>
                    <div class="testimonial-content">
                        <div class="d-flex">
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                        </div>
                        <p class="fs-5 m-0 pt-3" itemprop="reviewBody">“Absolutely outstanding service! The food was
                            delicious, the
                            presentation was beautiful, and the team handled everything with professionalism. My clients
                            were truly impressed.”</p>
                        <div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="d-none">
                            <meta itemprop="ratingValue" content="5" />
                        </div>
                    </div>
                </div>
                <div class="testimonial-item rounded bg-light" itemscope itemtype="http://schema.org/Review">
                    <div class="d-flex mb-3">
                        <img src="img/testimonial-2.jpg" class="img-fluid rounded-circle flex-shrink-0"
                            alt="Imran Siddiqui" itemprop="image">
                        <div class="position-absolute" style="top: 15px; right: 20px;">
                            <i class="fa fa-quote-right fa-2x"></i>
                        </div>
                        <div class="ps-3 my-auto">
                            <h4 class="mb-0"><span itemprop="author">Imran Siddiqui</span></h4>
                            <p class="m-0" itemprop="jobTitle">Business Owner, Lahore</p>
                        </div>
                    </div>
                    <div class="testimonial-content">
                        <div class="d-flex">
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                        </div>
                        <p class="fs-5 m-0 pt-3" itemprop="reviewBody">“From start to finish, everything was perfect.
                            The team was punctual,
                            courteous, and the food quality was top-notch. Highly recommended for any corporate event!”
                        </p>
                        <div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="d-none">
                            <meta itemprop="ratingValue" content="5" />
                        </div>
                    </div>
                </div>
                <div class="testimonial-item rounded bg-light" itemscope itemtype="http://schema.org/Review">
                    <div class="d-flex mb-3">
                        <img src="img/testimonial-3.jpg" class="img-fluid rounded-circle flex-shrink-0"
                            alt="Sarah Malik" itemprop="image">
                        <div class="position-absolute" style="top: 15px; right: 20px;">
                            <i class="fa fa-quote-right fa-2x"></i>
                        </div>
                        <div class="ps-3 my-auto">
                            <h4 class="mb-0"><span itemprop="author">Sarah Malik</span></h4>
                            <p class="m-0" itemprop="jobTitle">Bride, Islamabad</p>
                        </div>
                    </div>
                    <div class="testimonial-content">
                        <div class="d-flex">
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                        </div>
                        <p class="fs-5 m-0 pt-3" itemprop="reviewBody">“Our wedding was made memorable by the amazing
                            catering. Every guest
                            complimented the taste and variety. Thank you for making our day so special!”</p>
                        <div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="d-none">
                            <meta itemprop="ratingValue" content="5" />
                        </div>
                    </div>
                </div>
                <div class="testimonial-item rounded bg-light" itemscope itemtype="http://schema.org/Review">
                    <div class="d-flex mb-3">
                        <img src="img/testimonial-4.jpg" class="img-fluid rounded-circle flex-shrink-0"
                            alt="Dr. Faisal Ahmed" itemprop="image">
                        <div class="position-absolute" style="top: 15px; right: 20px;">
                            <i class="fa fa-quote-right fa-2x"></i>
                        </div>
                        <div class="ps-3 my-auto">
                            <h4 class="mb-0"><span itemprop="author">Dr. Faisal Ahmed</span></h4>
                            <p class="m-0" itemprop="jobTitle">Doctor, Multan</p>
                        </div>
                    </div>
                    <div class="testimonial-content">
                        <div class="d-flex">
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                        </div>
                        <p class="fs-5 m-0 pt-3" itemprop="reviewBody">“I have attended many events, but the food and
                            service here are on
                            another level. The attention to detail and taste is simply unmatched in Pakistan.”</p>
                        <div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="d-none">
                            <meta itemprop="ratingValue" content="5" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="owl-carousel testimonial-carousel testimonial-carousel-2 wow bounceInUp" data-wow-delay="0.3s">
                <div class="testimonial-item rounded bg-light" itemscope itemtype="http://schema.org/Review">
                    <div class="d-flex mb-3">
                        <img src="img/testimonial-1.jpg" class="img-fluid rounded-circle flex-shrink-0"
                            alt="Nadia Rafiq" itemprop="image">
                        <div class="position-absolute" style="top: 15px; right: 20px;">
                            <i class="fa fa-quote-right fa-2x"></i>
                        </div>
                        <div class="ps-3 my-auto">
                            <h4 class="mb-0"><span itemprop="author">Nadia Rafiq</span></h4>
                            <p class="m-0" itemprop="jobTitle">Event Coordinator, Karachi</p>
                        </div>
                    </div>
                    <div class="testimonial-content">
                        <div class="d-flex">
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                        </div>
                        <p class="fs-5 m-0 pt-3" itemprop="reviewBody">“Their team delivered a flawless event — on time,
                            immaculately
                            presented and the flavours were incredible. I will always recommend them to my clients.”</p>
                        <div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="d-none">
                            <meta itemprop="ratingValue" content="5" />
                        </div>
                    </div>
                </div>
                <div class="testimonial-item rounded bg-light" itemscope itemtype="http://schema.org/Review">
                    <div class="d-flex mb-3">
                        <img src="img/testimonial-2.jpg" class="img-fluid rounded-circle flex-shrink-0" alt="Bilal Khan"
                            itemprop="image">
                        <div class="position-absolute" style="top: 15px; right: 20px;">
                            <i class="fa fa-quote-right fa-2x"></i>
                        </div>
                        <div class="ps-3 my-auto">
                            <h4 class="mb-0"><span itemprop="author">Bilal Khan</span></h4>
                            <p class="m-0" itemprop="jobTitle">CEO, Islamabad</p>
                        </div>
                    </div>
                    <div class="testimonial-content">
                        <div class="d-flex">
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                        </div>
                        <p class="fs-5 m-0 pt-3" itemprop="reviewBody">“We used their services for a corporate dinner.
                            The menu was customised
                            exactly as requested, service was discreet and professional, and attendees kept asking for
                            second servings — a big success.”</p>
                        <div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="d-none">
                            <meta itemprop="ratingValue" content="5" />
                        </div>
                    </div>
                </div>
                <div class="testimonial-item rounded bg-light" itemscope itemtype="http://schema.org/Review">
                    <div class="d-flex mb-3">
                        <img src="img/testimonial-3.jpg" class="img-fluid rounded-circle flex-shrink-0" alt="Mona Ahmed"
                            itemprop="image">
                        <div class="position-absolute" style="top: 15px; right: 20px;">
                            <i class="fa fa-quote-right fa-2x"></i>
                        </div>
                        <div class="ps-3 my-auto">
                            <h4 class="mb-0"><span itemprop="author">Mona Ahmed</span></h4>
                            <p class="m-0" itemprop="jobTitle">Bride's Mother, Lahore</p>
                        </div>
                    </div>
                    <div class="testimonial-content">
                        <div class="d-flex">
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                        </div>
                        <p class="fs-5 m-0 pt-3" itemprop="reviewBody">“Our guests couldn’t stop praising the food —
                            fresh, tasty and
                            beautifully plated. The staff were warm and kept everything running smoothly.”</p>
                        <div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="d-none">
                            <meta itemprop="ratingValue" content="5" />
                        </div>
                    </div>
                </div>
                <div class="testimonial-item rounded bg-light" itemscope itemtype="http://schema.org/Review">
                    <div class="d-flex mb-3">
                        <img src="img/testimonial-4.jpg" class="img-fluid rounded-circle flex-shrink-0"
                            alt="Col. (Retd) Shahid Mir" itemprop="image">
                        <div class="position-absolute" style="top: 15px; right: 20px;">
                            <i class="fa fa-quote-right fa-2x"></i>
                        </div>
                        <div class="ps-3 my-auto">
                            <h4 class="mb-0"><span itemprop="author">Col. (Retd) Shahid Mir</span></h4>
                            <p class="m-0" itemprop="jobTitle">Retired Officer, Peshawar</p>
                        </div>
                    </div>
                    <div class="testimonial-content">
                        <div class="d-flex">
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                        </div>
                        <p class="fs-5 m-0 pt-3" itemprop="reviewBody">“Dependable, great value and exceptional taste —
                            they took care of
                            every small detail which made our reunion a memorable occasion.”</p>
                        <div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="d-none">
                            <meta itemprop="ratingValue" content="5" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Testimonial End -->


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