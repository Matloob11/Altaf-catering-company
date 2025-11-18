<?php
// Include authentication check
require_once 'includes/auth-check.php';
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $upload_type = $_POST['type'] ?? 'general'; // team, blog, gallery, menu, etc.
    
    // Define upload directories
    $upload_dirs = [
        'team' => '../img/team/',
        'blog' => '../img/blog/',
        'gallery' => '../img/gallery/',
        'menu' => '../img/menu/',
        'testimonial' => '../img/testimonials/',
        'general' => '../img/uploads/'
    ];
    
    $upload_dir = $upload_dirs[$upload_type] ?? $upload_dirs['general'];
    
    // Create directory if it doesn't exist
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    // Validate file
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    $max_size = 5 * 1024 * 1024; // 5MB
    
    if (!in_array($file['type'], $allowed_types)) {
        echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, GIF, WEBP allowed.']);
        exit;
    }
    
    if ($file['size'] > $max_size) {
        echo json_encode(['success' => false, 'message' => 'File too large. Maximum 5MB allowed.']);
        exit;
    }
    
    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $filepath = $upload_dir . $filename;
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        // Return relative path from root
        $relative_path = str_replace('../', '', $filepath);
        echo json_encode([
            'success' => true, 
            'path' => $relative_path,
            'message' => 'File uploaded successfully!'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to upload file.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No file uploaded.']);
}
?>
