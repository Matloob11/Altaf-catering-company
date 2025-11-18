<?php
// Prevent caching to always show latest data
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

// Load blog data from JSON
$blog_data = [];
if (file_exists('admin/data/blogs.json')) {
    $blog_data = json_decode(file_get_contents('admin/data/blogs.json'), true);
    // Filter only published blogs
    $blog_data = array_filter($blog_data, function($blog) {
        return $blog['status'] == 'published';
    });
    $blog_data = array_values($blog_data);
    // Sort by date, newest first
    usort($blog_data, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });
}

// Load Analytics
require_once 'includes/analytics.php';

// Track visitor
require_once 'includes/visitor-tracking.php';
trackVisitorPageView('blog');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo getAnalyticsScript(); ?>
    <meta charset="utf-8">
    <title>Blog — Catering Tips & Event Ideas | Altaf Catering</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="keywords" content="catering blog, event planning tips, food ideas, wedding tips, party planning, catering advice">
    <meta name="description" content="Read our blog for catering tips, event planning ideas, menu suggestions, and industry insights. Expert advice from Altaf Catering professionals.">
    <link rel="canonical" href="https://altafcatering.com/blog.php" />
    
    <!-- Open Graph / Twitter -->
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Blog — Catering Tips & Ideas" />
    <meta property="og:description" content="Expert catering advice, event planning tips, and inspiring ideas for your next celebration." />
    <meta property="og:url" content="https://altafcatering.com/blog.php" />
    <meta property="og:image" content="https://altafcatering.com/img/hero.png" />
    <meta name="twitter:card" content="summary_large_image" />

    <!-- Favicon & Theme Color -->
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <meta name="theme-color" content="#0d6efd">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Playball&display=swap" rel="stylesheet">

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
    <?php $loader_text = "Loading Latest Posts..."; include 'includes/loader.php'; ?>
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
                            <button class="btn btn-primary" type="submit" aria-label="Search"><i class="fa fa-search"></i></button>
                        </div>
                    </form>
                    <div class="container">
                        <div id="searchStatus" class="text-center text-muted mb-3">Enter a keyword to search the site.</div>
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
            <h1 class="display-1 mb-4">Our Blog</h1>
            <ol class="breadcrumb justify-content-center mb-0 animated bounceInDown">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item text-dark" aria-current="page">Our Blog</li>
            </ol>
        </div>
    </div>
    <!-- Hero End -->

    <!-- Blog Section Start -->
    <div class="container py-6">
        
        <!-- Featured Post (if available) -->
        <?php if (!empty($blog_data)): 
            $featured = $blog_data[0]; // First post as featured
        ?>
        <div class="featured-post wow fadeInUp" data-wow-delay="0.1s">
            <div class="featured-content">
                <div class="featured-badge">
                    <i class="fas fa-star me-2"></i>Featured Post
                </div>
                <h2 class="display-5 mb-3"><?php echo htmlspecialchars($featured['title']); ?></h2>
                <p class="lead mb-4"><?php echo htmlspecialchars(substr($featured['content'], 0, 200)) . '...'; ?></p>
                <div class="d-flex align-items-center gap-4 mb-4">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-user"></i>
                        <span><?php echo htmlspecialchars($featured['author']); ?></span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-calendar"></i>
                        <span><?php echo date('M d, Y', strtotime($featured['date'])); ?></span>
                    </div>
                    <?php if (!empty($featured['read_time'])): ?>
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-clock"></i>
                        <span><?php echo $featured['read_time']; ?> min read</span>
                    </div>
                    <?php endif; ?>
                </div>
                <a href="blog-detail.php?id=<?php echo $featured['id']; ?>" class="btn btn-light btn-lg">
                    Read Full Article <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
        <?php endif; ?>

        <!-- Blog Grid -->
        <div class="row g-4">
            <?php 
            // Skip first post (already shown as featured)
            $posts_to_show = array_slice($blog_data, 1);
            
            if (!empty($posts_to_show)): 
                foreach($posts_to_show as $blog): 
            ?>
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="modern-blog-card">
                    <div class="blog-image-wrapper">
                        <?php if (!empty($blog['image']) && file_exists($blog['image'])): ?>
                            <img src="<?php echo htmlspecialchars($blog['image']); ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>">
                        <?php else: ?>
                            <!-- Placeholder with gradient background -->
                            <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; flex-direction: column; color: white;">
                                <i class="fas fa-image fa-3x mb-3" style="opacity: 0.7;"></i>
                                <p style="margin: 0; font-size: 14px; opacity: 0.8;">No Image</p>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($blog['category'])): ?>
                            <div class="blog-category-badge"><?php echo htmlspecialchars($blog['category']); ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="blog-card-body">
                        <div class="blog-meta">
                            <div class="blog-meta-item">
                                <i class="fas fa-calendar"></i>
                                <span><?php echo date('M d, Y', strtotime($blog['date'])); ?></span>
                            </div>
                            <?php if (!empty($blog['read_time'])): ?>
                            <div class="blog-meta-item">
                                <i class="fas fa-clock"></i>
                                <span><?php echo $blog['read_time']; ?> min</span>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <h3 class="blog-title">
                            <a href="blog-detail.php?id=<?php echo $blog['id']; ?>">
                                <?php echo htmlspecialchars($blog['title']); ?>
                            </a>
                        </h3>
                        
                        <p class="blog-excerpt">
                            <?php 
                            $excerpt = !empty($blog['excerpt']) ? $blog['excerpt'] : substr($blog['content'], 0, 120);
                            echo htmlspecialchars($excerpt) . '...'; 
                            ?>
                        </p>
                        
                        <div class="blog-footer">
                            <div class="blog-author">
                                <?php if (!empty($blog['author_image']) && file_exists($blog['author_image'])): ?>
                                    <img src="<?php echo htmlspecialchars($blog['author_image']); ?>" 
                                         alt="<?php echo htmlspecialchars($blog['author']); ?>" 
                                         class="blog-author-avatar">
                                <?php else: ?>
                                    <div class="blog-author-avatar" style="background: #FEA116; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-user" style="color: white; font-size: 14px;"></i>
                                    </div>
                                <?php endif; ?>
                                <span class="blog-author-name"><?php echo htmlspecialchars($blog['author']); ?></span>
                            </div>
                            <a href="blog-detail.php?id=<?php echo $blog['id']; ?>" class="blog-read-more">
                                Read More <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                endforeach;
            else: 
            ?>
            <div class="col-12 text-center py-5">
                <i class="fas fa-blog fa-4x text-muted mb-3"></i>
                <h4>No Blog Posts Yet</h4>
                <p class="text-muted">Check back soon for exciting content!</p>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Newsletter Section -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card shadow-lg border-0" style="background: linear-gradient(135deg, #FEA116 0%, #ff6b35 100%); border-radius: 20px;">
                    <div class="card-body p-5 text-white">
                        <div class="row align-items-center">
                            <div class="col-lg-6 mb-4 mb-lg-0">
                                <h3 class="mb-3"><i class="fas fa-envelope-open-text me-2"></i>Subscribe to Our Newsletter</h3>
                                <p class="mb-0">Get the latest catering tips, event ideas, and exclusive offers delivered to your inbox!</p>
                            </div>
                            <div class="col-lg-6">
                                <form id="newsletterForm" class="d-flex gap-2">
                                    <input id="newsletterEmail" type="email" class="form-control form-control-lg" 
                                           placeholder="Enter your email" required style="border-radius: 50px;">
                                    <button type="submit" class="btn btn-dark btn-lg px-4" style="border-radius: 50px; white-space: nowrap;">
                                        Subscribe <i class="fas fa-paper-plane ms-2"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Blog Section End -->

    <!-- Footer Start -->
    <div class="container-fluid footer py-6 my-6 mb-0 bg-light wow bounceInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="footer-item">
                        <div class="d-flex align-items-center">
                            <img src="img/logo.png" alt="Altaf Catering" style="height:40px; width:auto;" class="me-2">
                            <span class="h6 mb-0 fw-bold text-primary d-none d-sm-inline">Altaf<span class="text-dark">Catering</span></span>
                        </div>
                        <p class="lh-lg mb-4">Altaf Catering — fresh seasonal menus and professional event services across Pakistan.</p>
                        <div class="footer-icon d-flex">
                            <a class="btn btn-primary btn-sm-square me-2 rounded-circle" href="https://web.facebook.com/AltafCateringCompany?mibextid=ZbWKwL&_rdc=1&_rdr#" target="_blank"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-primary btn-sm-square me-2 rounded-circle" href="https://www.tiktok.com/@altafcateringcompany?_t=8scdCc9SFQ9&_r=1" target="_blank"><i class="fab fa-tiktok"></i></a>
                            <a href="https://www.instagram.com/altafcateringcompany/" target="_blank" class="btn btn-primary btn-sm-square me-2 rounded-circle"><i class="fab fa-instagram"></i></a>
                            <a href="https://www.youtube.com/@Altafcateringcompanyy" target="_blank" class="btn btn-primary btn-sm-square rounded-circle"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer-item">
                        <h4 class="mb-4">Special Facilities</h4>
                        <div class="d-flex flex-column align-items-start">
                            <a class="text-body mb-3" href=""><i class="fa fa-check text-primary me-2"></i>Cheese Burger</a>
                            <a class="text-body mb-3" href=""><i class="fa fa-check text-primary me-2"></i>Sandwich</a>
                            <a class="text-body mb-3" href=""><i class="fa fa-check text-primary me-2"></i>Panner Burger</a>
                            <a class="text-body mb-3" href=""><i class="fa fa-check text-primary me-2"></i>Special Sweets</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer-item">
                        <h4 class="mb-4">Contact Us</h4>
                        <div class="d-flex flex-column align-items-start">
                            <p><i class="fa fa-map-marker-alt text-primary me-2"></i>MM Farm House Sharif Medical Jati Umrah Road, Karachi, Pakistan</p>
                            <p><i class="fa fa-phone-alt text-primary me-2"></i><a href="tel:+923039907296">+923039907296</a></p>
                            <p><i class="fas fa-envelope text-primary me-2"></i><a href="mailto:altafcatering@gmail.com">altafcatering@gmail.com</a></p>
                            <p><i class="fa fa-clock text-primary me-2"></i>24/7 Hours Service</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer-item">
                        <h4 class="mb-4">Social Gallery</h4>
                        <div class="row g-2">
                            <div class="col-4">
                                <img src="img/menu-01.jpg" class="img-fluid rounded-circle border border-primary p-2" alt="">
                            </div>
                            <div class="col-4">
                                <img src="img/menu-02.jpg" class="img-fluid rounded-circle border border-primary p-2" alt="">
                            </div>
                            <div class="col-4">
                                <img src="img/menu-03.jpg" class="img-fluid rounded-circle border border-primary p-2" alt="">
                            </div>
                            <div class="col-4">
                                <img src="img/menu-04.jpg" class="img-fluid rounded-circle border border-primary p-2" alt="">
                            </div>
                            <div class="col-4">
                                <img src="img/menu-05.jpg" class="img-fluid rounded-circle border border-primary p-2" alt="">
                            </div>
                            <div class="col-4">
                                <img src="img/menu-06.jpg" class="img-fluid rounded-circle border border-primary p-2" alt="">
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