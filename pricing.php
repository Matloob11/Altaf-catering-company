<?php
// Prevent caching to always show latest data
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

// Load pricing data from JSON
$pricing_data = ['packages' => [], 'faqs' => []];
if (file_exists('admin/data/pricing.json')) {
    $pricing_data = json_decode(file_get_contents('admin/data/pricing.json'), true);
    // Filter only active packages
    $pricing_data['packages'] = array_filter($pricing_data['packages'], function($pkg) {
        return $pkg['status'] == 'active';
    });
    // Filter only active FAQs
    $pricing_data['faqs'] = array_filter($pricing_data['faqs'], function($faq) {
        return $faq['status'] == 'active';
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
    <title>Packages & FAQ — Altaf Catering</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="description"
        content="Altaf Catering — Packages and FAQs. Choose a package for your event and read common questions about booking, menus, and services.">
    <link rel="canonical" href="https://altafcatering.com/pricing.html" />
    <!-- Open Graph / Twitter -->
    <meta property="og:type" content="article" />
    <meta property="og:title" content="Packages & FAQ — Altaf Catering" />
    <meta property="og:description"
        content="Compare our Basic, Premium, and Luxury packages. Contact us to customize your menu and services." />
    <meta property="og:url" content="https://altafcatering.com/pricing.html" />
    <meta property="og:image" content="https://altafcatering.com/img/hero.png" />
    <meta name="twitter:card" content="summary_large_image" />

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

    <?php $loader_text = "Loading Pricing..."; include 'includes/loader.php'; ?>


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
            <h1 class="display-1 mb-4">Pricing & FAQS</h1>
            <ol class="breadcrumb justify-content-center mb-0 animated bounceInDown">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item text-dark" aria-current="page">Pricing & FAQS</li>
            </ol>
        </div>
    </div>
    <!-- Hero End -->


    <!-- Pricing Start -->
    <div class="container-fluid py-6">
        <div class="container">
            <div class="text-center wow bounceInUp" data-wow-delay="0.1s">
                <small
                    class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">Our
                    Packages</small>
                <h1 class="display-5 mb-5">Catering Package Prices</h1>
            </div>
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
        </div>
    </div>
    <!-- Pricing End -->

    <!-- FAQ Start -->
    <div class="container-fluid py-6">
        <div class="container">
            <div class="text-center wow bounceInUp" data-wow-delay="0.1s">
                <small
                    class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">FAQ</small>
                <h1 class="display-5 mb-4">Frequently Asked Questions</h1>
                <p class="text-muted mb-4">Find answers to common questions about our catering services</p>
            </div>

            <!-- FAQ Search Bar -->
            <div class="row justify-content-center mb-5 wow bounceInUp" data-wow-delay="0.2s">
                <div class="col-lg-6">
                    <div class="input-group input-group-lg shadow-sm">
                        <span class="input-group-text bg-primary text-white border-0">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" id="faqSearch" class="form-control border-0" 
                               placeholder="Search FAQs..." aria-label="Search FAQs">
                    </div>
                    <small class="text-muted d-block mt-2 text-center">
                        <span id="faqCount"><?php echo count($pricing_data['faqs']); ?></span> questions available
                    </small>
                </div>
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
                            <h2 class="accordion-header" id="heading<?php echo $faq['id']; ?>">
                                <button class="accordion-button <?php echo $index > 0 ? 'collapsed' : ''; ?>" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse<?php echo $faq['id']; ?>">
                                    <?php echo htmlspecialchars($faq['question']); ?>
                                </button>
                            </h2>
                            <div id="collapse<?php echo $faq['id']; ?>" class="accordion-collapse collapse <?php echo $index == 0 ? 'show' : ''; ?>"
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
                            <h2 class="accordion-header" id="heading<?php echo $faq['id']; ?>">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse<?php echo $faq['id']; ?>">
                                    <?php echo htmlspecialchars($faq['question']); ?>
                                </button>
                            </h2>
                            <div id="collapse<?php echo $faq['id']; ?>" class="accordion-collapse collapse"
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
                <p class="mb-0">No FAQs available at the moment. Please check back later.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- FAQ End -->


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

    <!-- FAQ structured data (JSON-LD) -->
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "FAQPage",
            "mainEntity": [
                {
                    "@type": "Question",
                    "name": "How can I visit or contact you for consultation?",
                    "acceptedAnswer": { "@type": "Answer", "text": "You can visit us at MM Farm House Sharif Medical Jati Umrah Road, Karachi. For consultations, call +923039907296 or book an appointment through our website." }
                },
                {
                    "@type": "Question",
                    "name": "What areas do you serve?",
                    "acceptedAnswer": { "@type": "Answer", "text": "We provide catering services throughout major cities in Pakistan including Karachi, Lahore, Islamabad, and surrounding areas. Additional travel arrangements may apply." }
                },
                {
                    "@type": "Question",
                    "name": "Do you offer special dietary menus?",
                    "acceptedAnswer": { "@type": "Answer", "text": "Yes — vegetarian, vegan, gluten-free and allergy-friendly options are available on request. Please inform us in advance." }
                }
            ]
        }
        </script>

    <!-- FAQ Search Script -->
    <script src="js/faq-search.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>