<?php
// Simple login system
session_start();

$admin_username = "admin";
$admin_password = "altaf2025";
$error = '';
$logout_message = '';
$timeout_message = '';

// Check for logout message
if (isset($_GET['logout'])) {
    $logout_message = "You have been successfully logged out.";
}

// Check for timeout message
if (isset($_GET['timeout'])) {
    $timeout_message = "Your session has expired. Please login again.";
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    if ($username == $admin_username && $password == $admin_password) {
        // Login successful
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['last_activity'] = time();
        $_SESSION['admin_username'] = $username;
        $_SESSION['login_time'] = time();
        
        // Redirect to dashboard
        header('Location: dashboard.php');
        exit;
    } else {
        // Login failed
        $error = "Invalid username or password. Please try again.";
    }
}

// Check if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal - Altaf Catering</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link href="css/admin-unified.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(-45deg, #667eea, #764ba2, #f093fb, #f5576c);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            position: relative;
            overflow-x: hidden;
            overflow-y: auto;
            padding: 20px 0;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        /* VIP Animated Background */
        .vip-background {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }
        
        .floating-shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 20s infinite linear;
        }
        
        .floating-shape:nth-child(1) {
            width: 100px;
            height: 100px;
            left: 10%;
            animation-delay: 0s;
            animation-duration: 25s;
        }
        
        .floating-shape:nth-child(2) {
            width: 60px;
            height: 60px;
            left: 70%;
            animation-delay: 5s;
            animation-duration: 20s;
        }
        
        .floating-shape:nth-child(3) {
            width: 80px;
            height: 80px;
            left: 40%;
            animation-delay: 10s;
            animation-duration: 30s;
        }
        
        .floating-shape:nth-child(4) {
            width: 120px;
            height: 120px;
            left: 80%;
            animation-delay: 15s;
            animation-duration: 18s;
        }
        
        .floating-shape:nth-child(5) {
            width: 90px;
            height: 90px;
            left: 20%;
            animation-delay: 8s;
            animation-duration: 22s;
        }
        
        @keyframes float {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh) rotate(360deg);
                opacity: 0;
            }
        }
        
        /* VIP Login Container */
        .vip-login-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 450px;
            padding: 20px;
            margin: auto;
            min-height: auto;
        }
        
        .vip-login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(25px);
            border-radius: 24px;
            box-shadow: 
                0 32px 64px rgba(0, 0, 0, 0.25),
                0 0 0 1px rgba(255, 255, 255, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.4);
            padding: 50px 40px;
            animation: slideInUp 1s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            position: relative;
            overflow: hidden;
        }
        
        .vip-login-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transform: rotate(45deg);
            animation: shimmer 4s infinite;
        }
        
        @keyframes shimmer {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }
        
        @keyframes slideInUp {
            from {
                transform: translateY(60px) scale(0.9);
                opacity: 0;
            }
            to {
                transform: translateY(0) scale(1);
                opacity: 1;
            }
        }
        
        /* VIP Header */
        .vip-header {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
            z-index: 2;
        }
        
        .vip-logo-container {
            position: relative;
            display: inline-block;
            margin-bottom: 25px;
        }
        
        .vip-logo {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            box-shadow: 
                0 20px 40px rgba(102, 126, 234, 0.4),
                0 0 0 8px rgba(102, 126, 234, 0.1);
            animation: logoPulse 3s ease-in-out infinite;
            position: relative;
        }
        
        .vip-logo::before {
            content: '';
            position: absolute;
            width: 110px;
            height: 110px;
            border: 2px solid rgba(102, 126, 234, 0.3);
            border-radius: 50%;
            animation: logoRipple 3s ease-in-out infinite;
        }
        
        .vip-logo::after {
            content: '';
            position: absolute;
            width: 130px;
            height: 130px;
            border: 1px solid rgba(102, 126, 234, 0.2);
            border-radius: 50%;
            animation: logoRipple 3s ease-in-out infinite 0.5s;
        }
        
        @keyframes logoPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        @keyframes logoRipple {
            0% { transform: scale(1); opacity: 1; }
            100% { transform: scale(1.4); opacity: 0; }
        }
        
        .vip-logo img {
            width: 50px;
            height: auto;
            filter: brightness(0) invert(1);
        }
        
        .vip-logo i {
            font-size: 40px;
            color: white;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }
        
        .vip-title {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 8px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.5px;
        }
        
        .vip-subtitle {
            color: #64748b;
            font-size: 15px;
            font-weight: 500;
            margin-bottom: 5px;
        }
        
        .vip-tagline {
            color: #94a3b8;
            font-size: 13px;
            font-weight: 400;
        }
        
        /* VIP Form Styles */
        .vip-form-group {
            margin-bottom: 28px;
            position: relative;
            z-index: 2;
        }
        
        .vip-form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 12px;
            font-size: 14px;
            display: block;
            letter-spacing: 0.3px;
        }
        
        .vip-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .vip-input-icon {
            position: absolute;
            left: 20px;
            color: #9ca3af;
            font-size: 18px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1;
        }
        
        .vip-form-control {
            width: 100%;
            padding: 18px 24px 18px 55px;
            border: 2px solid #e5e7eb;
            border-radius: 16px;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: #f9fafb;
            font-family: 'Inter', sans-serif;
            letter-spacing: 0.3px;
        }
        
        .vip-form-control:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 
                0 0 0 4px rgba(102, 126, 234, 0.1),
                0 12px 32px rgba(102, 126, 234, 0.15);
            transform: translateY(-2px);
        }
        
        .vip-form-control:focus + .vip-input-icon {
            color: #667eea;
            transform: scale(1.1) translateX(2px);
        }
        
        .vip-form-control:not(:placeholder-shown) {
            background: white;
            border-color: #d1d5db;
        }
        
        .vip-password-toggle {
            position: absolute;
            right: 20px;
            color: #9ca3af;
            cursor: pointer;
            font-size: 18px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 2;
            padding: 4px;
        }
        
        .vip-password-toggle:hover {
            color: #667eea;
            transform: scale(1.1);
        }
        
        /* VIP Button */
        .vip-btn-login {
            width: 100%;
            padding: 18px 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 16px;
            color: white;
            font-weight: 700;
            font-size: 16px;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 
                0 12px 32px rgba(102, 126, 234, 0.4),
                0 2px 8px rgba(102, 126, 234, 0.2);
            position: relative;
            overflow: hidden;
            z-index: 2;
            text-transform: uppercase;
        }
        
        .vip-btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .vip-btn-login:hover::before {
            left: 100%;
        }
        
        .vip-btn-login:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 
                0 20px 48px rgba(102, 126, 234, 0.5),
                0 4px 16px rgba(102, 126, 234, 0.3);
        }
        
        .vip-btn-login:active {
            transform: translateY(-2px) scale(1.01);
        }
        
        .vip-btn-login i {
            margin-right: 10px;
            font-size: 16px;
        }
        
        /* VIP Security Badge */
        .vip-security-badge {
            text-align: center;
            margin-top: 32px;
            padding: 18px 24px;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-radius: 16px;
            font-size: 13px;
            color: #0369a1;
            border: 1px solid #bae6fd;
            position: relative;
            z-index: 2;
            font-weight: 500;
        }
        
        .vip-security-badge i {
            color: #10b981;
            margin-right: 8px;
            font-size: 16px;
            animation: securityPulse 2s ease-in-out infinite;
        }
        
        @keyframes securityPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        /* VIP Alerts */
        .vip-alert {
            border-radius: 16px;
            border: none;
            padding: 18px 24px;
            margin-bottom: 28px;
            animation: alertSlide 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            z-index: 2;
            font-weight: 500;
        }
        
        @keyframes alertSlide {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .vip-alert-success {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            color: #166534;
            border-left: 4px solid #10b981;
            box-shadow: 0 8px 24px rgba(16, 185, 129, 0.15);
        }
        
        .vip-alert-warning {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
            border-left: 4px solid #f59e0b;
            box-shadow: 0 8px 24px rgba(245, 158, 11, 0.15);
        }
        
        .vip-alert-danger {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
            border-left: 4px solid #ef4444;
            box-shadow: 0 8px 24px rgba(239, 68, 68, 0.15);
        }
        
        .vip-alert i {
            margin-right: 12px;
            font-size: 16px;
        }
        
        /* Loading State */
        .vip-btn-login.loading {
            pointer-events: none;
            opacity: 0.8;
        }
        
        .vip-btn-login.loading::after {
            content: '';
            position: absolute;
            width: 24px;
            height: 24px;
            top: 50%;
            left: 50%;
            margin-left: -12px;
            margin-top: -12px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Responsive Design */
        @media (max-width: 576px) {
            body {
                padding: 10px 0;
                align-items: flex-start;
                justify-content: center;
            }
            
            .vip-login-container {
                margin-top: 20px;
                margin-bottom: 20px;
            }
            
            .vip-login-card {
                padding: 40px 28px;
                border-radius: 20px;
                margin: 15px;
            }
            
            .vip-title {
                font-size: 28px;
            }
            
            .vip-logo {
                width: 75px;
                height: 75px;
            }
            
            .vip-logo i {
                font-size: 32px;
            }
            
            .vip-form-control {
                padding: 16px 20px 16px 50px;
                font-size: 14px;
            }
            
            .vip-btn-login {
                padding: 16px 20px;
                font-size: 15px;
            }
        }
        
        @media (max-width: 400px) {
            .vip-login-container {
                padding: 10px;
            }
            
            .vip-login-card {
                padding: 32px 20px;
            }
        }
        
        /* Fix for very small screens */
        @media (max-height: 600px) {
            body {
                align-items: flex-start;
                padding: 10px 0;
            }
            
            .vip-login-container {
                margin: 10px auto;
            }
            
            .vip-logo {
                width: 60px;
                height: 60px;
            }
            
            .vip-logo i {
                font-size: 24px;
            }
            
            .vip-title {
                font-size: 24px;
            }
        }
        
        /* VIP Hover Effects */
        .vip-input-wrapper:hover .vip-form-control {
            border-color: #d1d5db;
            transform: translateY(-1px);
        }
        
        .vip-form-group:hover .vip-input-icon {
            color: #6b7280;
            transform: scale(1.05);
        }
        
        /* Auto-fill Detection */
        .vip-form-control:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 30px white inset !important;
            -webkit-text-fill-color: #374151 !important;
        }
        
        /* Focus Ring for Accessibility */
        .vip-btn-login:focus-visible,
        .vip-form-control:focus-visible {
            outline: 2px solid #667eea;
            outline-offset: 2px;
        }
    </style>
</head>
<body>
    <!-- VIP Animated Background -->
    <div class="vip-background">
        <div class="floating-shape"></div>
        <div class="floating-shape"></div>
        <div class="floating-shape"></div>
        <div class="floating-shape"></div>
        <div class="floating-shape"></div>
    </div>
    
    <div class="vip-login-container">
        <div class="vip-login-card">
            <div class="vip-header">
                <div class="vip-logo-container">
                    <div class="vip-logo">
                        <img src="../img/logo.png" alt="Altaf Catering" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <i class="fas fa-utensils" style="display: none;"></i>
                    </div>
                </div>
                <h1 class="vip-title">Admin Portal</h1>
                <p class="vip-subtitle">Altaf Catering Management System</p>
                <p class="vip-tagline">Secure • Professional • Exclusive</p>
            </div>
            
            <?php if (!empty($logout_message)): ?>
            <div class="vip-alert vip-alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo htmlspecialchars($logout_message); ?>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($timeout_message)): ?>
            <div class="vip-alert vip-alert-warning">
                <i class="fas fa-clock"></i>
                <?php echo htmlspecialchars($timeout_message); ?>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($error)): ?>
            <div class="vip-alert vip-alert-danger">
                <i class="fas fa-exclamation-triangle"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>
            
            <form method="POST" action="" id="vipLoginForm">
                <div class="vip-form-group">
                    <label class="vip-form-label">Administrator Username</label>
                    <div class="vip-input-wrapper">
                        <input 
                            type="text" 
                            name="username" 
                            class="vip-form-control" 
                            placeholder="Enter your username" 
                            required 
                            autofocus
                            autocomplete="username"
                            spellcheck="false"
                        >
                        <i class="fas fa-user-shield vip-input-icon"></i>
                    </div>
                </div>
                
                <div class="vip-form-group">
                    <label class="vip-form-label">Secure Password</label>
                    <div class="vip-input-wrapper">
                        <input 
                            type="password" 
                            name="password" 
                            id="vipPassword" 
                            class="vip-form-control" 
                            placeholder="Enter your password" 
                            required
                            autocomplete="current-password"
                        >
                        <i class="fas fa-lock vip-input-icon"></i>
                        <i class="fas fa-eye vip-password-toggle" id="vipTogglePassword"></i>
                    </div>
                </div>
                
                <button type="submit" name="login" class="vip-btn-login" id="vipLoginBtn">
                    <i class="fas fa-sign-in-alt"></i>
                    Access Admin Portal
                </button>
            </form>
            
            <div class="vip-security-badge">
                <i class="fas fa-shield-alt"></i>
                Protected by Advanced Security • Rate Limiting • Session Management
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simple password toggle
        const togglePassword = document.getElementById('vipTogglePassword');
        const passwordInput = document.getElementById('vipPassword');
        
        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        }
        
        console.log('Simple login form ready');
    </script>
</body>
</html>
