<?php
// Get current page name
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- Sidebar Start -->
<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
    <div class="position-sticky sidebar-sticky">
        <ul class="nav flex-column">
            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>" href="dashboard.php">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'widgets.php') ? 'active' : ''; ?>" href="widgets.php">
                    <i class="fas fa-th"></i>
                    <span>Widgets</span>
                    <span class="badge bg-success ms-auto">New</span>
                </a>
            </li>
            
            <!-- Content Management -->
            <li class="sidebar-heading">
                <span>Content Management</span>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'blogs.php') ? 'active' : ''; ?>" href="blogs.php">
                    <i class="fas fa-blog"></i>
                    <span>Blog Posts</span>
                    <?php
                    $blogs = file_exists('data/blogs.json') ? json_decode(file_get_contents('data/blogs.json'), true) : [];
                    $blog_count = count($blogs);
                    $published_count = count(array_filter($blogs, function($b) { return $b['status'] == 'published'; }));
                    if ($blog_count > 0) echo '<span class="badge bg-primary ms-auto">' . $published_count . '/' . $blog_count . '</span>';
                    ?>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'owner.php') ? 'active' : ''; ?>" href="owner.php">
                    <i class="fas fa-user-tie"></i>
                    <span>Owner/Founder</span>
                    <span class="badge bg-warning ms-auto">Important</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'team.php') ? 'active' : ''; ?>" href="team.php">
                    <i class="fas fa-users"></i>
                    <span>Team Members</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'gallery.php') ? 'active' : ''; ?>" href="gallery.php">
                    <i class="fas fa-images"></i>
                    <span>Gallery (Social & Professional)</span>
                    <?php
                    $gallery_count = 0;
                    if (file_exists('data/gallery.json')) {
                        $gallery_items = json_decode(file_get_contents('data/gallery.json'), true);
                        $gallery_count = count($gallery_items);
                    }
                    if ($gallery_count > 0) echo '<span class="badge bg-primary ms-auto">' . $gallery_count . '</span>';
                    ?>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'event-management.php') ? 'active' : ''; ?>" href="event-management.php">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Event Page Management</span>
                    <span class="badge bg-success ms-auto">New</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'testimonials.php') ? 'active' : ''; ?>" href="testimonials.php">
                    <i class="fas fa-star"></i>
                    <span>Testimonials</span>
                </a>
            </li>
            
            <!-- Website Pages -->
            <li class="sidebar-heading">
                <span>Website Pages</span>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'about.php') ? 'active' : ''; ?>" href="about.php">
                    <i class="fas fa-info-circle"></i>
                    <span>About Page</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'terms.php') ? 'active' : ''; ?>" href="terms.php">
                    <i class="fas fa-file-contract"></i>
                    <span>Terms & Conditions</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'privacy.php') ? 'active' : ''; ?>" href="privacy.php">
                    <i class="fas fa-user-shield"></i>
                    <span>Privacy Policy</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'services.php') ? 'active' : ''; ?>" href="services.php">
                    <i class="fas fa-concierge-bell"></i>
                    <span>Services</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'menu.php') ? 'active' : ''; ?>" href="menu.php">
                    <i class="fas fa-utensils"></i>
                    <span>Menu Items</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'packages.php') ? 'active' : ''; ?>" href="packages.php">
                    <i class="fas fa-tags"></i>
                    <span>Packages & FAQs</span>
                </a>
            </li>
            
            <!-- Business Management -->
            <li class="sidebar-heading">
                <span>Business Management</span>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'jobs.php') ? 'active' : ''; ?>" href="jobs.php">
                    <i class="fas fa-briefcase"></i>
                    <span>Job Listings</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'applications.php') ? 'active' : ''; ?>" href="applications.php">
                    <i class="fas fa-file-alt"></i>
                    <span>Applications</span>
                    <?php
                    $apps = file_exists('data/applications.json') ? json_decode(file_get_contents('data/applications.json'), true) : [];
                    $pending = array_filter($apps, function($a) { return $a['status'] == 'pending'; });
                    if (count($pending) > 0) echo '<span class="badge bg-warning ms-auto">' . count($pending) . '</span>';
                    ?>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'contacts.php') ? 'active' : ''; ?>" href="contacts.php">
                    <i class="fas fa-envelope"></i>
                    <span>Contact Messages</span>
                    <?php
                    $contacts = file_exists('data/contacts.json') ? json_decode(file_get_contents('data/contacts.json'), true) : [];
                    $new_contacts = array_filter($contacts, function($c) { return $c['status'] == 'new'; });
                    if (count($new_contacts) > 0) echo '<span class="badge bg-danger ms-auto">' . count($new_contacts) . '</span>';
                    ?>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'bookings.php') ? 'active' : ''; ?>" href="bookings.php">
                    <i class="fas fa-calendar-check"></i>
                    <span>Booking Requests</span>
                </a>
            </li>
            
            <!-- Settings -->
            <li class="sidebar-heading">
                <span>System</span>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'ai-chat.php') ? 'active' : ''; ?>" href="ai-chat.php">
                    <i class="fas fa-robot"></i>
                    <span>AI Assistant</span>
                    <span class="badge bg-gradient ms-auto" style="background: linear-gradient(45deg, #667eea, #764ba2); color: white;">AI</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'search.php') ? 'active' : ''; ?>" href="search.php">
                    <i class="fas fa-search"></i>
                    <span>Global Search</span>
                    <span class="badge bg-success ms-auto">New</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'analytics.php') ? 'active' : ''; ?>" href="analytics.php">
                    <i class="fas fa-chart-line"></i>
                    <span>Analytics</span>
                    <span class="badge bg-info ms-auto">Basic</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'analytics-advanced.php') ? 'active' : ''; ?>" href="analytics-advanced.php">
                    <i class="fas fa-chart-area"></i>
                    <span>Advanced Analytics</span>
                    <span class="badge bg-success ms-auto">Real-time</span>
                </a>
            </li>
            
            
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'email-settings.php') ? 'active' : ''; ?>" href="email-settings.php">
                    <i class="fas fa-envelope-open-text"></i>
                    <span>Email Settings</span>
                    <span class="badge bg-primary ms-auto">New</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'activity-log.php') ? 'active' : ''; ?>" href="activity-log.php">
                    <i class="fas fa-history"></i>
                    <span>Activity Log</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'backup.php') ? 'active' : ''; ?>" href="backup.php">
                    <i class="fas fa-database"></i>
                    <span>Backup & Restore</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'settings.php') ? 'active' : ''; ?>" href="settings.php">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'system-check.php') ? 'active' : ''; ?>" href="system-check.php">
                    <i class="fas fa-check-circle"></i>
                    <span>System Check</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'verify.php') ? 'active' : ''; ?>" href="verify.php">
                    <i class="fas fa-shield-alt"></i>
                    <span>System Verification</span>
                    <span class="badge bg-success ms-auto">New</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
<!-- Sidebar End -->

<!-- Auto-scroll active menu item to top -->
<script>
(function() {
    'use strict';
    
    // Function to scroll active item into view
    function scrollActiveItemToTop() {
        const sidebar = document.getElementById('sidebarMenu');
        const activeLink = sidebar ? sidebar.querySelector('.nav-link.active') : null;
        
        if (!activeLink || !sidebar) return;
        
        // Get the scrollable container (sidebar-sticky or sidebar itself)
        const scrollContainer = sidebar.querySelector('.sidebar-sticky') || sidebar;
        
        // Calculate the position of active link relative to the scroll container
        const containerTop = scrollContainer.getBoundingClientRect().top;
        const activeLinkTop = activeLink.getBoundingClientRect().top;
        const relativePosition = activeLinkTop - containerTop;
        
        // Calculate scroll position (bring active item near top with some padding)
        const scrollPosition = scrollContainer.scrollTop + relativePosition - 20;
        
        // Smooth scroll to active item
        scrollContainer.scrollTo({
            top: scrollPosition,
            behavior: 'smooth'
        });
        
        // Add a subtle pulse animation to highlight active item
        activeLink.style.transition = 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
        activeLink.style.boxShadow = '0 0 20px rgba(212, 175, 55, 0.6)';
        
        setTimeout(function() {
            activeLink.style.boxShadow = '';
        }, 800);
    }
    
    // Execute when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(scrollActiveItemToTop, 150);
        });
    } else {
        setTimeout(scrollActiveItemToTop, 150);
    }
    
    // Also scroll on window load (in case of slow loading)
    window.addEventListener('load', function() {
        setTimeout(scrollActiveItemToTop, 200);
    });
})();
</script>
