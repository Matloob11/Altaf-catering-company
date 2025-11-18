<?php
// Include authentication check
require_once 'includes/auth-check.php';
/**
 * Secure Admin Login
 * Altaf Catering - Enhanced Security
 */

require_once '../includes/security.php';

startSecureSession();

// Check if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in']) {
    header('Location: dashboard.php');
    exit;
}

// Check if IP is blocked
if (isIPBlocked()) {
    die('Access denied. Your IP has been blocked due to suspicious activity.');
}

$error = '';
$timeout = isset($_GET['timeout']) ? true : false;

// Handle login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        $error = 'Invalid security token. Please try again.';
        logSecurityEvent('csrf_failed', ['action' => 'login']);
    } else {
        
        // Check rate limiting (5 attempts per 15 minutes)
        if (!checkRateLimit('login', 5, 900)) {
            $waitTime = getRateLimitWaitTime('login', 900);
            $error = "Too many login attempts. Please try again in " . ceil($waitTime / 60) . " minutes.";
            
            logSecurityEvent('rate_limit_exceeded', [
                'action' => 'login',
                'ip' => $_SERVER['REMOTE_ADDR']
            ]);
            
            // Block IP after 10 failed attempts
            if (isset($_SESSION['failed_login_count']) && $_SESSION['failed_login_count'] >= 10) {
                blockIP($_SERVER['REMOTE_ADDR'], 'Excessive failed login attempts');
            }
        } else {
            
            $username = sanitizeInput($_POST['username']);
            $password = $_POST['password']; // Don't sanitize password
            
            // Load users from JSON
            $usersFile = 'data/users.json';
            if (file_exists($usersFile)) {
                $users = json_decode(file_get_contents($usersFile), true);
                
                $loginSuccess = false;
                foreach ($users as &$user) {
                    if ($user['username'] === $username) {
                        // Verify password
                        if (verifyPassword($password, $user['password'])) {
                            // Login successful
                            $_SESSION['admin_logged_in'] = true;
                            $_SESSION['admin_id'] = $user['id'];
                            $_SESSION['admin_username'] = $user['username'];
                            $_SESSION['admin_email'] = $user['email'];
                            $_SESSION['last_activity'] = time();
                            
                            // Update last login
                            $user['last_login'] = date('Y-m-d H:i:s');
                            file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
                            
                            // Reset failed login count
                            unset($_SESSION['failed_login_count']);
                            unset($_SESSION['rate_limit_login']);
                            
                            logSecurityEvent('login_success', [
                                'username' => $username
                            ]);
                            
                            header('Location: dashboard.php');
                            exit;
                        }
                        break;
                    }
                }
                
                if (!$loginSuccess) {
                    $error = 'Invalid username or password!';
                    
                    // Increment failed login count
                    if (!isset($_SESSION['failed_login_count'])) {
                        $_SESSION['failed_login_count'] = 0;
                    }
                    $_SESSION['failed_login_count']++;
                    
                    logSecurityEvent('login_failed', [
                        'username' => $username,
                        'attempt' => $_SESSION['failed_login_count']
                    ]);
                }
            } else {
                $error = 'User database not found. Please run setup-password.php first.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Admin Login - Altaf Catering</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link href="css/admin-unified.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            position: relative;
            overflow: hidden;
        }
        
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        /* Animated Background Particles */
        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }
        
        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 20s infinite;
        }
        
        .particle:nth-child(1) { width: 80px; height: 80px; left: 10%; animation-delay: 0s; }
        .particle:nth-child(2) { width: 60px; height: 60px; left: 20%; animation-delay: 2s; }
        .particle:nth-child(3) { width: 100px; height: 100px; left: 60%; animation-delay: 4s; }
        .particle:nth-child(4) { width: 50px; height: 50px; left: 80%; animation-delay: 6s; }
        .particle:nth-child(5) { width: 70px; height: 70px; left: 40%; animation-delay: 8s; }
        
        @keyframes float {
            0%, 100% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-100vh) rotate(360deg); opacity: 0; }
        }
        
        .login-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 480px;
            padding: 20px;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.3), 
                        0 0 0 1px rgba(255, 255, 255, 0.2);
            padding: 50px 40px;
            animation: slideUp 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            position: relative;
            overflow: hidden;
        }
        
        .login-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transform: rotate(45deg);
            animation: shine 3s infinite;
        }
        
        @keyframes shine {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }
        
        @keyframes slideUp {
            from { 
                transform: translateY(100px) scale(0.8); 
                opacity: 0; 
            }
            to { 
                transform: translateY(0) scale(1); 
                opacity: 1; 
            }
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
            z-index: 2;
        }
        
        .logo-container {
            position: relative;
            display: inline-block;
            margin-bottom: 20px;
        }
        
        .logo-circle {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
            animation: pulse 2s infinite;
            position: relative;
        }
        
        .logo-circle::before {
            content: '';
            position: absolute;
            width: 120px;
            height: 120px;
            border: 3px solid rgba(102, 126, 234, 0.3);
            border-radius: 50%;
            animation: ripple 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        @keyframes ripple {
            0% { transform: scale(1); opacity: 1; }
            100% { transform: scale(1.3); opacity: 0; }
        }
        
        .logo-circle i {
            font-size: 45px;
            color: white;
        }
        
        .login-header h2 {
            color: #2d3748;
            font-weight: 700;
            margin-bottom: 10px;
            font-size: 28px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .login-header p {
            color: #718096;
            font-size: 14px;
            font-weight: 400;
        }
        
        .form-group {
            margin-bottom: 25px;
            position: relative;
            z-index: 2;
        }
        
        .form-label {
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 10px;
            font-size: 14px;
            display: block;
            transition: all 0.3s;
        }
        
        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .input-icon {
            position: absolute;
            left: 18px;
            color: #a0aec0;
            font-size: 18px;
            transition: all 0.3s;
            z-index: 1;
        }
        
        .form-control {
            width: 100%;
            padding: 15px 20px 15px 50px;
            border: 2px solid #e2e8f0;
            border-radius: 15px;
            font-size: 15px;
            transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            background: #f7fafc;
            font-family: 'Poppins', sans-serif;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1),
                        0 10px 25px rgba(102, 126, 234, 0.15);
            transform: translateY(-2px);
        }
        
        .form-control:focus + .input-icon {
            color: #667eea;
            transform: scale(1.1);
        }
        
        .form-control:not(:placeholder-shown) {
            background: white;
            border-color: #cbd5e0;
        }
        
        .password-toggle {
            position: absolute;
            right: 18px;
            color: #a0aec0;
            cursor: pointer;
            font-size: 18px;
            transition: all 0.3s;
            z-index: 2;
        }
        
        .password-toggle:hover {
            color: #667eea;
            transform: scale(1.1);
        }
        
        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 15px;
            color: white;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
            z-index: 2;
        }
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }
        
        .btn-login:hover::before {
            left: 100%;
        }
        
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }
        
        .btn-login:active {
            transform: translateY(-1px);
        }
        
        .btn-login i {
            margin-right: 8px;
        }
        
        .security-badge {
            text-align: center;
            margin-top: 30px;
            padding: 15px;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-radius: 15px;
            font-size: 12px;
            color: #0369a1;
            border: 1px solid #bae6fd;
            position: relative;
            z-index: 2;
        }
        
        .security-badge i {
            color: #10b981;
            margin-right: 8px;
            font-size: 14px;
            animation: shield 2s infinite;
        }
        
        @keyframes shield {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .alert {
            border-radius: 15px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 25px;
            animation: slideDown 0.5s ease;
            position: relative;
            z-index: 2;
        }
        
        @keyframes slideDown {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .alert-warning {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
            border-left: 4px solid #f59e0b;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }
        
        .alert i {
            margin-right: 10px;
        }
        
        /* Responsive Design */
        @media (max-width: 576px) {
            .login-card {
                padding: 40px 25px;
                border-radius: 25px;
            }
            
            .login-header h2 {
                font-size: 24px;
            }
            
            .logo-circle {
                width: 80px;
                height: 80px;
            }
            
            .logo-circle i {
                font-size: 35px;
            }
            
            .form-control {
                padding: 13px 18px 13px 45px;
                font-size: 14px;
            }
            
            .btn-login {
                padding: 14px;
                font-size: 15px;
            }
        }
        
        @media (max-width: 400px) {
            .login-container {
                padding: 15px;
            }
            
            .login-card {
                padding: 30px 20px;
            }
        }
        
        /* Loading Animation */
        .btn-login.loading {
            pointer-events: none;
            opacity: 0.8;
        }
        
        .btn-login.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Animated Background Particles -->
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>
    
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="logo-container">
                    <div class="logo-circle">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                </div>
                <h2>Secure Admin Portal</h2>
                <p>Welcome back! Please login to continue</p>
            </div>
            
            <?php
// Include authentication check
require_once 'includes/auth-check.php'; if ($timeout): ?>
            <div class="alert alert-warning">
                <i class="fas fa-clock"></i> Your session has expired. Please login again.
            </div>
            <?php
// Include authentication check
require_once 'includes/auth-check.php'; endif; ?>
            
            <?php
// Include authentication check
require_once 'includes/auth-check.php'; if ($error): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i> <?php
// Include authentication check
require_once 'includes/auth-check.php'; echo $error; ?>
            </div>
            <?php
// Include authentication check
require_once 'includes/auth-check.php'; endif; ?>
            
            <form method="POST" action="" id="loginForm">
                <?php
// Include authentication check
require_once 'includes/auth-check.php'; echo csrfTokenField(); ?>
                
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <div class="input-wrapper">
                        <input 
                            type="text" 
                            name="username" 
                            class="form-control" 
                            placeholder="Enter your username" 
                            required 
                            autofocus
                            autocomplete="username"
                        >
                        <i class="fas fa-user input-icon"></i>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-wrapper">
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            class="form-control" 
                            placeholder="Enter your password" 
                            required
                            autocomplete="current-password"
                        >
                        <i class="fas fa-lock input-icon"></i>
                        <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                    </div>
                </div>
                
                <button type="submit" name="login" class="btn-login" id="loginBtn">
                    <i class="fas fa-sign-in-alt"></i> Login Securely
                </button>
            </form>
            
            <div class="security-badge">
                <i class="fas fa-shield-alt"></i> 
                Protected by CSRF tokens, rate limiting & password hashing
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password Toggle
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle icon
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
        
        // Form Submit Animation
        const loginForm = document.getElementById('loginForm');
        const loginBtn = document.getElementById('loginBtn');
        
        loginForm.addEventListener('submit', function(e) {
            loginBtn.classList.add('loading');
            loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Authenticating...';
        });
        
        // Input Focus Animation
        const inputs = document.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
        
        // Prevent multiple submissions
        let isSubmitting = false;
        loginForm.addEventListener('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault();
                return false;
            }
            isSubmitting = true;
        });
    </script>
</body>
</html>
