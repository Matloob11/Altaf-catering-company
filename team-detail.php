<?php
// Prevent caching
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

// Get team member ID
$member_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Load team member data
$member = null;
if (file_exists('admin/data/team.json')) {
    $team_data = json_decode(file_get_contents('admin/data/team.json'), true);
    foreach ($team_data as $m) {
        if ($m['id'] == $member_id && $m['status'] == 'active') {
            $member = $m;
            break;
        }
    }
}

// If no member found, redirect
if (!$member) {
    header('Location: team.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo htmlspecialchars($member['name']); ?> — <?php echo htmlspecialchars($member['position']); ?> | Altaf Catering</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="keywords" content="<?php echo htmlspecialchars($member['name']); ?>, <?php echo htmlspecialchars($member['position']); ?>, Altaf Catering team">
    <meta name="description" content="<?php echo htmlspecialchars(!empty($member['bio']) ? substr($member['bio'], 0, 160) : $member['name'] . ' - ' . $member['position'] . ' at Altaf Catering'); ?>">
    
    <!-- Favicon -->
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <meta name="theme-color" content="#0d6efd">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Playball&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/owl.carousel.min.css" rel="stylesheet">

    <!-- Bootstrap & Template -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/loader.css" rel="stylesheet">
    <link href="css/text-fix.css" rel="stylesheet">
    
    <!-- CRITICAL: Hover State Fix -->
    <link href="css/hover-fix.css" rel="stylesheet">
</head>

<body>
    <?php include 'includes/contact-buttons.php'; ?>
    <?php $loader_text = "Loading Team Member..."; include 'includes/loader.php'; ?>
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
                                placeholder="Type keywords and press Enter" aria-label="Search site">
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

    <!-- Hero Section -->
    <div class="team-detail-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-4 text-center mb-4 mb-lg-0">
                    <?php if (!empty($member['image']) && file_exists($member['image'])): ?>
                        <img src="<?php echo htmlspecialchars($member['image']); ?>" 
                             alt="<?php echo htmlspecialchars($member['name']); ?>" 
                             class="team-member-image wow zoomIn">
                    <?php else: ?>
                        <div class="team-member-image d-flex align-items-center justify-content-center" 
                             style="background: rgba(255,255,255,0.2);">
                            <i class="fas fa-user fa-5x" style="opacity: 0.5;"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-lg-8" style="position: relative; z-index: 1;">
                    <h1 class="display-4 mb-3 wow fadeInUp"><?php echo htmlspecialchars($member['name']); ?></h1>
                    <h3 class="mb-4 wow fadeInUp" data-wow-delay="0.1s" style="opacity: 0.9;">
                        <i class="fas fa-briefcase me-2"></i><?php echo htmlspecialchars($member['position']); ?>
                    </h3>
                    
                    <?php if (!empty($member['tagline'])): ?>
                    <p class="lead mb-4 wow fadeInUp" data-wow-delay="0.2s" style="opacity: 0.95;">
                        <i class="fas fa-quote-left me-2"></i><?php echo htmlspecialchars($member['tagline']); ?>
                    </p>
                    <?php endif; ?>
                    
                    <!-- Social Links -->
                    <div class="social-links-large wow fadeInUp" data-wow-delay="0.3s">
                        <?php if (!empty($member['facebook'])): ?>
                            <a href="<?php echo htmlspecialchars($member['facebook']); ?>" target="_blank" title="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($member['instagram'])): ?>
                            <a href="<?php echo htmlspecialchars($member['instagram']); ?>" target="_blank" title="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($member['tiktok'])): ?>
                            <a href="<?php echo htmlspecialchars($member['tiktok']); ?>" target="_blank" title="TikTok">
                                <i class="fab fa-tiktok"></i>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($member['youtube'])): ?>
                            <a href="<?php echo htmlspecialchars($member['youtube']); ?>" target="_blank" title="YouTube">
                                <i class="fab fa-youtube"></i>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($member['email'])): ?>
                            <a href="mailto:<?php echo htmlspecialchars($member['email']); ?>" title="Email">
                                <i class="fas fa-envelope"></i>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($member['phone'])): ?>
                            <a href="tel:<?php echo htmlspecialchars($member['phone']); ?>" title="Phone">
                                <i class="fas fa-phone"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Content -->
    <div class="container py-6">
        <div class="row">
            <div class="col-lg-8">
                <!-- About Section -->
                <?php if (!empty($member['bio'])): ?>
                <div class="info-card wow fadeInUp">
                    <h3><i class="fas fa-user-circle me-2"></i>About <?php echo htmlspecialchars($member['name']); ?></h3>
                    <p style="font-size: 16px; line-height: 1.8; color: #64748b;">
                        <?php echo nl2br(htmlspecialchars($member['bio'])); ?>
                    </p>
                </div>
                <?php endif; ?>
                
                <!-- Experience Section -->
                <?php if (!empty($member['experience'])): ?>
                <div class="info-card wow fadeInUp" data-wow-delay="0.1s">
                    <h3><i class="fas fa-briefcase me-2"></i>Experience & Expertise</h3>
                    <p style="font-size: 16px; line-height: 1.8; color: #64748b;">
                        <?php echo nl2br(htmlspecialchars($member['experience'])); ?>
                    </p>
                </div>
                <?php endif; ?>
                
                <!-- Skills -->
                <?php if (!empty($member['skills'])): ?>
                <div class="info-card wow fadeInUp" data-wow-delay="0.2s">
                    <h3><i class="fas fa-star me-2"></i>Skills & Specialties</h3>
                    <div class="skills-list">
                        <?php 
                        $skills = is_array($member['skills']) ? $member['skills'] : explode(',', $member['skills']);
                        foreach($skills as $skill): 
                            if (trim($skill)):
                        ?>
                        <span class="skill-badge"><?php echo htmlspecialchars(trim($skill)); ?></span>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Achievements -->
                <?php if (!empty($member['achievements'])): ?>
                <div class="info-card wow fadeInUp" data-wow-delay="0.3s">
                    <h3><i class="fas fa-trophy me-2"></i>Achievements & Awards</h3>
                    <?php 
                    $achievements = is_array($member['achievements']) ? $member['achievements'] : explode("\n", $member['achievements']);
                    foreach($achievements as $achievement): 
                        if (trim($achievement)):
                    ?>
                    <div class="achievement-card">
                        <h5><i class="fas fa-award text-warning me-2"></i><?php echo htmlspecialchars(trim($achievement)); ?></h5>
                    </div>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="col-lg-4">
                <!-- Quick Stats -->
                <?php if (!empty($member['years_experience'])): ?>
                <div class="stat-box wow fadeInUp">
                    <h2><?php echo htmlspecialchars($member['years_experience']); ?>+</h2>
                    <p>Years of Experience</p>
                </div>
                <?php endif; ?>
                
                <!-- Quick Info -->
                <div class="info-card wow fadeInUp" data-wow-delay="0.1s">
                    <h3><i class="fas fa-info-circle me-2"></i>Quick Info</h3>
                    
                    <div class="info-item">
                        <i class="fas fa-user"></i>
                        <div>
                            <strong>Name</strong><br>
                            <span class="text-muted"><?php echo htmlspecialchars($member['name']); ?></span>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <i class="fas fa-briefcase"></i>
                        <div>
                            <strong>Position</strong><br>
                            <span class="text-muted"><?php echo htmlspecialchars($member['position']); ?></span>
                        </div>
                    </div>
                    
                    <?php if (!empty($member['department'])): ?>
                    <div class="info-item">
                        <i class="fas fa-building"></i>
                        <div>
                            <strong>Department</strong><br>
                            <span class="text-muted"><?php echo htmlspecialchars($member['department']); ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($member['email'])): ?>
                    <div class="info-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <strong>Email</strong><br>
                            <a href="mailto:<?php echo htmlspecialchars($member['email']); ?>" class="text-primary">
                                <?php echo htmlspecialchars($member['email']); ?>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($member['phone'])): ?>
                    <div class="info-item">
                        <i class="fas fa-phone"></i>
                        <div>
                            <strong>Phone</strong><br>
                            <a href="tel:<?php echo htmlspecialchars($member['phone']); ?>" class="text-primary">
                                <?php echo htmlspecialchars($member['phone']); ?>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Back Button -->
                <div class="wow fadeInUp" data-wow-delay="0.2s">
                    <a href="team.php" class="btn btn-primary w-100 btn-lg">
                        <i class="fas fa-arrow-left me-2"></i>Back to Team
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Other Team Members -->
    <div class="container-fluid bg-light py-6">
        <div class="container">
            <div class="text-center mb-5 wow fadeInUp">
                <h2 class="display-6">Meet Other Team Members</h2>
                <p class="text-muted">Discover more talented professionals at Altaf Catering</p>
            </div>
            <div class="row g-4">
                <?php
                // Load other team members
                $other_members = array_filter($team_data, function($m) use ($member_id) {
                    return $m['id'] != $member_id && $m['status'] == 'active';
                });
                $other_members = array_slice($other_members, 0, 3);
                
                foreach($other_members as $other):
                ?>
                <div class="col-lg-4 col-md-6 wow fadeInUp">
                    <div class="card border-0 shadow-sm h-100 hover-lift" style="transition: all 0.3s ease;">
                        <?php if (!empty($other['image']) && file_exists($other['image'])): ?>
                        <img src="<?php echo htmlspecialchars($other['image']); ?>" 
                             class="card-img-top" 
                             alt="<?php echo htmlspecialchars($other['name']); ?>"
                             style="height: 300px; object-fit: cover;">
                        <?php else: ?>
                        <div class="card-img-top d-flex align-items-center justify-content-center" 
                             style="height: 300px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="fas fa-user fa-5x text-white" style="opacity: 0.3;"></i>
                        </div>
                        <?php endif; ?>
                        <div class="card-body text-center">
                            <h5 class="card-title text-primary"><?php echo htmlspecialchars($other['name']); ?></h5>
                            <p class="text-muted mb-3"><?php echo htmlspecialchars($other['position']); ?></p>
                            <a href="team-detail.php?id=<?php echo $other['id']; ?>" class="btn btn-primary">
                                View Profile <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

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
                    <small class="text-light">&copy; <span id="copyright-year">2025</span> Altaf Catering Company. All rights reserved.</small>
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
    <script src="js/search.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
