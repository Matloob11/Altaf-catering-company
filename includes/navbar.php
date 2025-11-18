<!-- Navbar start -->
<div class="container-fluid nav-bar">
    <div class="container">
        <nav class="navbar navbar-light navbar-expand-lg py-4">
            <a href="index.php" class="navbar-brand d-flex align-items-center">
                <!-- Logo image from img/ (kept modest size for navbar) -->
                <img src="img/logo.png" alt="Altaf Catering Company" style="height:48px; width:auto;" class="me-2">
                <!-- Keep a small textual brand for accessibility / fallback on larger screens -->
                <span class="h5 mb-0 fw-bold text-primary d-none d-md-inline">ALTAF<span
                        class="text-dark">CATERING</span></span>
            </a>
            <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="fa fa-bars text-primary"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav mx-auto">
                    <a href="index.php" class="nav-item nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Home</a>
                    <a href="about.php" class="nav-item nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>">About</a>
                    <a href="service.php" class="nav-item nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'service.php' ? 'active' : ''; ?>">Services</a>
                    <a href="event.php" class="nav-item nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'event.php' ? 'active' : ''; ?>">Events</a>
                    <a href="menu.php" class="nav-item nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'menu.php' ? 'active' : ''; ?>">Menu</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                        <div class="dropdown-menu bg-light">
                            <a href="book.php" class="dropdown-item">Booking</a>
                            <a href="blog.php" class="dropdown-item">Our Blog</a>
                            <a href="team.php" class="dropdown-item">Our Team</a>
                            <a href="gallery.php" class="dropdown-item">Photo Gallery</a>
                            <a href="careers.php" class="dropdown-item">Careers</a>
                            <a href="pricing.php" class="dropdown-item">Pricing & FAQS</a>
                            <a href="testimonial.php" class="dropdown-item">Testimonial</a>
                            <a href="privacy.php" class="dropdown-item">Privacy Policy</a>
                            <a href="terms.php" class="dropdown-item">Terms of Service</a>
                            <a href="404.php" class="dropdown-item">404 Page</a>
                        </div>
                    </div>
                    <a href="contact.php" class="nav-item nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>">Contact</a>
                </div>
                <button class="btn-search btn btn-primary btn-md-square me-3 rounded-circle d-none d-lg-inline-flex"
                    data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fas fa-search"></i></button>
                
                <a href="book.php" class="btn btn-primary py-2 px-4 d-none d-xl-inline-block rounded-pill">Book
                    Now</a>
            </div>
        </nav>
    </div>
</div>
<!-- Navbar End -->
