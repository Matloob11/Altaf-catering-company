<?php
// Load notification data
$contacts = file_exists('data/contacts.json') ? json_decode(file_get_contents('data/contacts.json'), true) : [];
$bookings = file_exists('data/bookings.json') ? json_decode(file_get_contents('data/bookings.json'), true) : [];
$applications = file_exists('data/applications.json') ? json_decode(file_get_contents('data/applications.json'), true) : [];

// Count new items
$new_contacts = array_filter($contacts, function($c) { return isset($c['status']) && $c['status'] == 'new'; });
$new_bookings = array_filter($bookings, function($b) { return isset($b['status']) && $b['status'] == 'pending'; });
$pending_apps = array_filter($applications, function($a) { return isset($a['status']) && $a['status'] == 'pending'; });

$total_notifications = count($new_contacts) + count($new_bookings) + count($pending_apps);
?>

<!-- Premium Loader Start -->
<div id="adminSpinner" class="show w-100 vh-100 position-fixed top-0 start-0 d-flex align-items-center justify-content-center">
    <div class="premium-loader-container">
        <div class="premium-logo-container">
            <div class="premium-logo-glow"></div>
            <img src="../img/logo.png" alt="Altaf Catering">
        </div>
        
        <div class="premium-spinner-container">
            <div class="premium-spinner-outer">
                <div class="premium-spinner-inner">
                    <div class="premium-spinner-core"></div>
                </div>
            </div>
        </div>
        
        <div class="premium-loading-text">Loading</div>
        <div class="premium-loading-subtitle">Professional Admin Panel</div>
        
        <div class="premium-progress-container">
            <div class="premium-progress-bar"></div>
        </div>
    </div>
</div>
<!-- Premium Loader End -->

<!-- Header/Navbar Start -->
<nav class="navbar navbar-expand-md navbar-dark fixed-top">
    <div class="container-fluid px-4">
        <!-- Logo & Brand -->
        <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
            <img src="../img/logo.png" alt="Altaf Catering" style="height: 50px; width: auto;">
            <div class="d-flex flex-column">
                <span class="brand-text">Altaf Catering</span>
                <small class="brand-subtitle">Admin Panel</small>
            </div>
        </a>
        
        <!-- Mobile Toggle -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Sidebar Toggle for Mobile -->
        <button class="sidebar-toggle d-md-none" type="button" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        
        <!-- Right Side Menu -->
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav align-items-center">
                <!-- Notifications -->
                <li class="nav-item dropdown me-3">
                    <a class="nav-link position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-bell fa-lg"></i>
                        <?php if ($total_notifications > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?php echo $total_notifications; ?>
                        </span>
                        <?php endif; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end notification-dropdown">
                        <li class="dropdown-header">
                            <i class="fas fa-bell me-2"></i> Notifications 
                            <?php if ($total_notifications > 0): ?>
                                <span class="badge bg-danger ms-2"><?php echo $total_notifications; ?></span>
                            <?php endif; ?>
                        </li>
                        <?php if ($total_notifications == 0): ?>
                            <li><a class="dropdown-item text-muted"><i class="fas fa-check-circle me-2"></i> No new notifications</a></li>
                        <?php else: ?>
                            <?php if (count($new_contacts) > 0): ?>
                            <li><a class="dropdown-item" href="contacts.php">
                                <i class="fas fa-envelope me-2 text-primary"></i> 
                                <?php echo count($new_contacts); ?> New Message<?php echo count($new_contacts) > 1 ? 's' : ''; ?>
                            </a></li>
                            <?php endif; ?>
                            <?php if (count($new_bookings) > 0): ?>
                            <li><a class="dropdown-item" href="bookings.php">
                                <i class="fas fa-calendar me-2 text-success"></i> 
                                <?php echo count($new_bookings); ?> New Booking<?php echo count($new_bookings) > 1 ? 's' : ''; ?>
                            </a></li>
                            <?php endif; ?>
                            <?php if (count($pending_apps) > 0): ?>
                            <li><a class="dropdown-item" href="applications.php">
                                <i class="fas fa-file-alt me-2 text-warning"></i> 
                                <?php echo count($pending_apps); ?> Pending Application<?php echo count($pending_apps) > 1 ? 's' : ''; ?>
                            </a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-center text-primary" href="dashboard.php">
                                <i class="fas fa-eye me-2"></i> View All
                            </a></li>
                        <?php endif; ?>
                    </ul>
                </li>
                
                <!-- Quick Actions -->
                <li class="nav-item dropdown me-3">
                    <a class="nav-link" href="#" id="quickActions" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-plus-circle fa-lg"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li class="dropdown-header">Quick Add</li>
                        <li><a class="dropdown-item" href="blogs.php"><i class="fas fa-blog me-2"></i> New Blog</a></li>
                        <li><a class="dropdown-item" href="team.php"><i class="fas fa-user-plus me-2"></i> Team Member</a></li>
                        <li><a class="dropdown-item" href="gallery.php"><i class="fas fa-image me-2"></i> Gallery Item</a></li>
                    </ul>
                </li>
                
                <!-- User Profile -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <div class="user-avatar me-2">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <span class="d-none d-md-inline">Administrator</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end user-dropdown">
                        <li class="dropdown-header">
                            <div class="text-center">
                                <div class="user-avatar-large mb-2">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                                <strong>Admin User</strong>
                                <p class="small text-muted mb-0">admin@altafcatering.com</p>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="dashboard.php"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a></li>
                        <li><a class="dropdown-item" href="settings.php"><i class="fas fa-cog me-2"></i> Settings</a></li>
                        <li><a class="dropdown-item" href="../index.php" target="_blank"><i class="fas fa-globe me-2"></i> View Website</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- Header/Navbar End -->

<!-- Floating AI Assistant Button -->
<div class="floating-ai-btn" id="floatingAiBtn" onclick="openAiAssistant()">
    <i class="fas fa-robot"></i>
    <span class="ai-pulse"></span>
</div>

<!-- Mini AI Chat Modal -->
<div class="modal fade" id="aiChatModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-robot me-2"></i>AI Assistant
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="aiChatFrame" src="" style="width: 100%; height: 500px; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>

<style>
.floating-ai-btn {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    cursor: pointer;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 1000;
    animation: float 3s ease-in-out infinite;
}

.floating-ai-btn:hover {
    transform: translateY(-5px) scale(1.1);
    box-shadow: 0 15px 35px rgba(102, 126, 234, 0.6);
}

.floating-ai-btn .ai-pulse {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: rgba(102, 126, 234, 0.4);
    animation: pulse 2s infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

@keyframes pulse {
    0% {
        transform: scale(1);
        opacity: 1;
    }
    100% {
        transform: scale(1.5);
        opacity: 0;
    }
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

@media (max-width: 768px) {
    .floating-ai-btn {
        bottom: 20px;
        right: 20px;
        width: 50px;
        height: 50px;
        font-size: 20px;
    }
}
</style>

<script>
// Hide loader when page loads
window.addEventListener('load', function() {
    const spinner = document.getElementById('adminSpinner');
    if (spinner) {
        setTimeout(function() {
            spinner.classList.remove('show');
        }, 300);
    }
});

// Sidebar toggle functionality
function toggleSidebar() {
    const sidebar = document.getElementById('sidebarMenu');
    if (sidebar) {
        sidebar.classList.toggle('show');
    }
}

// Close sidebar when clicking outside on mobile
document.addEventListener('click', function(e) {
    if (window.innerWidth <= 768) {
        const sidebar = document.getElementById('sidebarMenu');
        const toggleBtn = document.querySelector('.sidebar-toggle');
        
        if (sidebar && !sidebar.contains(e.target) && !toggleBtn?.contains(e.target)) {
            sidebar.classList.remove('show');
        }
    }
});

// Handle window resize
window.addEventListener('resize', function() {
    const sidebar = document.getElementById('sidebarMenu');
    if (window.innerWidth > 768 && sidebar) {
        sidebar.classList.remove('show');
    }
});

// AI Assistant Functions
function openAiAssistant() {
    // Check if we're already on the AI chat page
    if (window.location.pathname.includes('ai-chat.php')) {
        // Just focus input
        const messageInput = document.getElementById('messageInput');
        if (messageInput) {
            messageInput.focus();
        }
        return;
    }
    
    // Open in modal for other pages
    const modal = new bootstrap.Modal(document.getElementById('aiChatModal'));
    const iframe = document.getElementById('aiChatFrame');
    iframe.src = 'ai-chat.php?modal=1';
    modal.show();
}

// Hide floating button on AI chat page
document.addEventListener('DOMContentLoaded', function() {
    if (window.location.pathname.includes('ai-chat.php')) {
        const floatingBtn = document.getElementById('floatingAiBtn');
        if (floatingBtn) {
            floatingBtn.style.display = 'none';
        }
    }
});
</script>
