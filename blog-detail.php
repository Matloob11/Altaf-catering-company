<?php
// Prevent caching to always show latest data
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

// Get blog ID from URL parameter
$blog_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Load blog data from blogs.json (unified system)
$blog_detail = null;
if (file_exists('admin/data/blogs.json')) {
    $blogs = json_decode(file_get_contents('admin/data/blogs.json'), true);
    foreach ($blogs as $blog) {
        if ($blog['id'] == $blog_id && $blog['status'] == 'published') {
            $blog_detail = $blog;
            break;
        }
    }
}

// If no blog found, redirect to blog page
if (!$blog_detail) {
    header('Location: blog.php');
    exit;
}

// Load Analytics
require_once 'includes/analytics.php';

// Track visitor
require_once 'includes/visitor-tracking.php';
trackVisitorPageView('blog-detail-' . $blog_id);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo getAnalyticsScript(); ?>
    <meta charset="utf-8">
    <title><?php echo htmlspecialchars($blog_detail['title']); ?> — Altaf Catering Blog</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="keywords" content="catering blog, <?php echo htmlspecialchars($blog_detail['title']); ?>, event planning, food tips">
    <meta name="description" content="<?php echo htmlspecialchars(substr($blog_detail['content'], 0, 160)); ?>...">
    <link rel="canonical" href="https://altafcatering.com/blog-detail.php?id=<?php echo $blog_detail['id']; ?>" />
    
    <!-- Open Graph / Twitter -->
    <meta property="og:type" content="article" />
    <meta property="og:title" content="<?php echo htmlspecialchars($blog_detail['title']); ?>" />
    <meta property="og:description" content="<?php echo htmlspecialchars(substr($blog_detail['content'], 0, 160)); ?>..." />
    <meta property="og:url" content="https://altafcatering.com/blog-detail.php?id=<?php echo $blog_detail['id']; ?>" />
    <meta property="og:image" content="https://altafcatering.com/<?php echo !empty($blog_detail['author_image']) ? $blog_detail['author_image'] : 'img/blog-1.jpg'; ?>" />
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

    <?php $loader_text = "Loading Blog Post..."; include 'includes/loader.php'; ?>

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
            <h1 class="display-4 mb-4"><?php echo htmlspecialchars($blog_detail['title']); ?></h1>
            <ol class="breadcrumb justify-content-center mb-0 animated bounceInDown">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="blog.php">Blog</a></li>
                <li class="breadcrumb-item text-dark" aria-current="page">Blog Detail</li>
            </ol>
        </div>
    </div>
    <!-- Hero End -->

    <!-- Blog Detail Start -->
    <div class="container-fluid py-6">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <article class="blog-detail-article">
                        <!-- Featured Image -->
                        <?php if (!empty($blog_detail['image']) && file_exists($blog_detail['image'])): ?>
                        <div class="blog-featured-image mb-5">
                            <img src="<?php echo htmlspecialchars($blog_detail['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($blog_detail['title']); ?>" 
                                 class="img-fluid rounded shadow-lg"
                                 style="width: 100%; max-height: 500px; object-fit: cover;">
                        </div>
                        <?php endif; ?>

                        <!-- Blog Meta -->
                        <div class="blog-meta-detail mb-4 pb-4 border-bottom">
                            <div class="d-flex flex-wrap align-items-center gap-4">
                                <?php if (!empty($blog_detail['category'])): ?>
                                <span class="badge bg-primary px-3 py-2" style="font-size: 14px;">
                                    <i class="fas fa-tag me-1"></i><?php echo htmlspecialchars($blog_detail['category']); ?>
                                </span>
                                <?php endif; ?>
                                <div class="d-flex align-items-center text-muted">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    <span><?php echo date('F d, Y', strtotime($blog_detail['date'])); ?></span>
                                </div>
                                <?php if (!empty($blog_detail['read_time'])): ?>
                                <div class="d-flex align-items-center text-muted">
                                    <i class="fas fa-clock me-2"></i>
                                    <span><?php echo $blog_detail['read_time']; ?> min read</span>
                                </div>
                                <?php endif; ?>
                                <div class="d-flex align-items-center text-muted">
                                    <i class="fas fa-user me-2"></i>
                                    <span>By <?php echo htmlspecialchars($blog_detail['author']); ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Blog Content -->
                        <div class="blog-content-detail" style="font-size: 18px; line-height: 1.8; color: #334155;">
                            <?php echo nl2br(htmlspecialchars($blog_detail['content'])); ?>
                        </div>

                        <!-- Tags (if available) -->
                        <?php if (!empty($blog_detail['tags'])): ?>
                        <div class="blog-tags mt-5 pt-4 border-top">
                            <h6 class="mb-3"><i class="fas fa-tags me-2"></i>Tags:</h6>
                            <div class="d-flex flex-wrap gap-2">
                                <?php 
                                $tags = is_array($blog_detail['tags']) ? $blog_detail['tags'] : explode(',', $blog_detail['tags']);
                                foreach($tags as $tag): 
                                ?>
                                <span class="badge bg-light text-dark border px-3 py-2"><?php echo htmlspecialchars(trim($tag)); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Author Bio -->
                        <?php if (!empty($blog_detail['author_bio'])): ?>
                        <div class="author-bio-card mt-5 p-4 rounded shadow-sm" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                            <h5 class="mb-4"><i class="fas fa-user-circle me-2"></i>About the Author</h5>
                            <div class="d-flex align-items-start">
                                <?php if (!empty($blog_detail['author_image']) && file_exists($blog_detail['author_image'])): ?>
                                    <img src="<?php echo htmlspecialchars($blog_detail['author_image']); ?>" 
                                         alt="<?php echo htmlspecialchars($blog_detail['author']); ?>" 
                                         class="rounded-circle me-4 shadow" 
                                         style="width: 100px; height: 100px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="rounded-circle me-4 shadow d-flex align-items-center justify-content-center" 
                                         style="width: 100px; height: 100px; background: linear-gradient(135deg, #FEA116 0%, #ff6b35 100%);">
                                        <i class="fas fa-user fa-3x text-white"></i>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <h5 class="mb-2 text-primary"><?php echo htmlspecialchars($blog_detail['author']); ?></h5>
                                    <p class="mb-0 text-muted" style="line-height: 1.7;">
                                        <?php echo htmlspecialchars($blog_detail['author_bio']); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Share & Navigation -->
                        <div class="blog-actions mt-5 pt-4 border-top">
                            <div class="row align-items-center">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <a href="blog.php" class="btn btn-outline-primary btn-lg">
                                        <i class="fas fa-arrow-left me-2"></i>Back to All Posts
                                    </a>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <div class="d-flex justify-content-md-end align-items-center gap-2">
                                        <span class="me-2 fw-bold">Share:</span>
                                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://altafcatering.com/blog-detail.php?id=' . $blog_detail['id']); ?>" 
                                           target="_blank" class="btn btn-primary btn-sm rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fab fa-facebook-f"></i>
                                        </a>
                                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('https://altafcatering.com/blog-detail.php?id=' . $blog_detail['id']); ?>&text=<?php echo urlencode($blog_detail['title']); ?>" 
                                           target="_blank" class="btn btn-info btn-sm rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fab fa-twitter"></i>
                                        </a>
                                        <a href="https://wa.me/?text=<?php echo urlencode($blog_detail['title'] . ' - https://altafcatering.com/blog-detail.php?id=' . $blog_detail['id']); ?>" 
                                           target="_blank" class="btn btn-success btn-sm rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode('https://altafcatering.com/blog-detail.php?id=' . $blog_detail['id']); ?>" 
                                           target="_blank" class="btn btn-dark btn-sm rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fab fa-linkedin-in"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </div>
    <!-- Blog Detail End -->

    <!-- Related Posts Start -->
    <div class="container-fluid py-6 bg-light">
        <div class="container">
            <div class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">
                <h2 class="display-6 mb-2">You Might Also Like</h2>
                <p class="text-muted">Explore more articles from our blog</p>
            </div>
            <div class="row g-4">
                <?php
                // Load other blogs for related posts (from unified blogs.json)
                $related_posts = [];
                if (file_exists('admin/data/blogs.json')) {
                    $all_blogs = json_decode(file_get_contents('admin/data/blogs.json'), true);
                    $related_posts = array_filter($all_blogs, function($blog) use ($blog_id, $blog_detail) {
                        // Filter by same category or just different posts
                        $same_category = !empty($blog['category']) && !empty($blog_detail['category']) && 
                                       $blog['category'] == $blog_detail['category'];
                        return $blog['id'] != $blog_id && $blog['status'] == 'published';
                    });
                    $related_posts = array_slice($related_posts, 0, 3);
                }

                if (!empty($related_posts)):
                    foreach ($related_posts as $post):
                ?>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="card border-0 shadow-sm h-100 hover-lift" style="transition: transform 0.3s ease, box-shadow 0.3s ease;">
                        <?php if (!empty($post['image']) && file_exists($post['image'])): ?>
                        <img src="<?php echo htmlspecialchars($post['image']); ?>" 
                             class="card-img-top" 
                             alt="<?php echo htmlspecialchars($post['title']); ?>"
                             style="height: 200px; object-fit: cover;">
                        <?php else: ?>
                        <div class="card-img-top" style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-image fa-3x text-white" style="opacity: 0.5;"></i>
                        </div>
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <?php if (!empty($post['category'])): ?>
                            <span class="badge bg-primary mb-2 align-self-start"><?php echo htmlspecialchars($post['category']); ?></span>
                            <?php endif; ?>
                            <h5 class="card-title">
                                <a href="blog-detail.php?id=<?php echo $post['id']; ?>" class="text-decoration-none text-dark">
                                    <?php echo htmlspecialchars($post['title']); ?>
                                </a>
                            </h5>
                            <p class="card-text text-muted flex-grow-1">
                                <?php 
                                $excerpt = !empty($post['excerpt']) ? $post['excerpt'] : substr($post['content'], 0, 100);
                                echo htmlspecialchars($excerpt) . '...'; 
                                ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    <?php echo date('M d, Y', strtotime($post['date'])); ?>
                                </small>
                                <a href="blog-detail.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-primary">
                                    Read More <i class="fas fa-arrow-right ms-1"></i>
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
                    <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No related posts available at the moment.</p>
                    <a href="blog.php" class="btn btn-primary mt-3">
                        <i class="fas fa-arrow-left me-2"></i>Browse All Posts
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- Related Posts End -->
    
    <style>
        .hover-lift:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15) !important;
        }
    </style>

    <!-- Footer Start -->
    <div class="container-fluid footer py-6 my-6 mb-0 bg-light wow bounceInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="footer-item">
                        <!-- Footer logo (small) -->
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
                            <p><i class="fa fa-map-marker-alt text-primary me-2"></i><span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"><span itemprop="streetAddress">MM Farm House Sharif Medical Jati Umrah Road</span>, <span itemprop="addressLocality">Karachi</span>, <span itemprop="addressCountry">Pakistan</span></span></p>
                            <p><i class="fa fa-phone-alt text-primary me-2"></i><a href="tel:+923039907296" itemprop="telephone">+923039907296</a></p>
                            <p><i class="fas fa-envelope text-primary me-2"></i><a href="mailto:altafcatering@gmail.com" itemprop="email">altafcatering@gmail.com</a></p>
                            <p><i class="fa fa-clock text-primary me-2"></i><span itemprop="openingHours">24/7 Hours Service</span></p>
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
                    <small class="text-light">&copy; <span id="copyright-year">2025</span> Altaf Catering Company. All rights reserved.</small>
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