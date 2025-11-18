<?php
/**
 * Performance Optimization Functions
 * Altaf Catering - Speed & Performance Tools
 */

/**
 * Minify HTML Output
 */
function minifyHTML($html) {
    // Remove comments
    $html = preg_replace('/<!--(?!<!)[^\[>].*?-->/s', '', $html);
    
    // Remove whitespace
    $html = preg_replace('/\s+/', ' ', $html);
    
    // Remove whitespace between tags
    $html = preg_replace('/>\s+</', '><', $html);
    
    return trim($html);
}

/**
 * Start Output Buffering with Minification
 */
function startMinification() {
    ob_start(function($buffer) {
        return minifyHTML($buffer);
    });
}

/**
 * Generate Lazy Loading Image Tag
 */
function lazyImage($src, $alt = '', $class = '', $width = '', $height = '') {
    $attributes = [];
    
    if ($class) $attributes[] = 'class="' . htmlspecialchars($class) . '"';
    if ($width) $attributes[] = 'width="' . htmlspecialchars($width) . '"';
    if ($height) $attributes[] = 'height="' . htmlspecialchars($height) . '"';
    
    $attributes[] = 'loading="lazy"';
    $attributes[] = 'alt="' . htmlspecialchars($alt) . '"';
    
    return '<img src="' . htmlspecialchars($src) . '" ' . implode(' ', $attributes) . '>';
}

/**
 * Optimize Image (Resize and Compress)
 */
function optimizeImage($sourcePath, $targetPath, $maxWidth = 1200, $quality = 85) {
    if (!file_exists($sourcePath)) {
        return false;
    }
    
    $imageInfo = getimagesize($sourcePath);
    if (!$imageInfo) {
        return false;
    }
    
    list($width, $height, $type) = $imageInfo;
    
    // Load image based on type
    switch ($type) {
        case IMAGETYPE_JPEG:
            $source = imagecreatefromjpeg($sourcePath);
            break;
        case IMAGETYPE_PNG:
            $source = imagecreatefrompng($sourcePath);
            break;
        case IMAGETYPE_GIF:
            $source = imagecreatefromgif($sourcePath);
            break;
        default:
            return false;
    }
    
    // Calculate new dimensions
    if ($width > $maxWidth) {
        $newWidth = $maxWidth;
        $newHeight = ($height / $width) * $maxWidth;
    } else {
        $newWidth = $width;
        $newHeight = $height;
    }
    
    // Create new image
    $target = imagecreatetruecolor($newWidth, $newHeight);
    
    // Preserve transparency for PNG
    if ($type == IMAGETYPE_PNG) {
        imagealphablending($target, false);
        imagesavealpha($target, true);
    }
    
    // Resize
    imagecopyresampled($target, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
    
    // Save optimized image
    switch ($type) {
        case IMAGETYPE_JPEG:
            imagejpeg($target, $targetPath, $quality);
            break;
        case IMAGETYPE_PNG:
            imagepng($target, $targetPath, 9);
            break;
        case IMAGETYPE_GIF:
            imagegif($target, $targetPath);
            break;
    }
    
    // Free memory
    imagedestroy($source);
    imagedestroy($target);
    
    return true;
}

/**
 * Get Optimized Image URL
 */
function getOptimizedImage($originalPath, $maxWidth = 1200) {
    $pathInfo = pathinfo($originalPath);
    $optimizedPath = $pathInfo['dirname'] . '/optimized_' . $pathInfo['basename'];
    
    // Check if optimized version exists
    if (!file_exists($optimizedPath) || filemtime($originalPath) > filemtime($optimizedPath)) {
        optimizeImage($originalPath, $optimizedPath, $maxWidth);
    }
    
    return file_exists($optimizedPath) ? $optimizedPath : $originalPath;
}

/**
 * Preload Critical Resources
 */
function preloadResources($resources) {
    $output = '';
    
    foreach ($resources as $resource) {
        $type = $resource['type'] ?? 'script';
        $href = $resource['href'];
        $as = $resource['as'] ?? $type;
        
        $output .= '<link rel="preload" href="' . htmlspecialchars($href) . '" as="' . htmlspecialchars($as) . '">' . "\n";
    }
    
    return $output;
}

/**
 * Generate Critical CSS
 */
function getCriticalCSS() {
    return <<<CSS
<style>
/* Critical CSS - Above the fold */
body{margin:0;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif}
.container{max-width:1200px;margin:0 auto;padding:0 15px}
.navbar{background:#fff;box-shadow:0 2px 4px rgba(0,0,0,.1);position:sticky;top:0;z-index:1000}
.hero{min-height:400px;display:flex;align-items:center;padding:60px 0}
.btn-primary{background:#FE7E00;color:#fff;padding:12px 30px;border:none;border-radius:25px;cursor:pointer}
.loader{position:fixed;top:0;left:0;width:100%;height:100%;background:#fff;display:flex;align-items:center;justify-content:center;z-index:9999}
</style>
CSS;
}

/**
 * Defer JavaScript Loading
 */
function deferJS($src) {
    return '<script defer src="' . htmlspecialchars($src) . '"></script>';
}

/**
 * Async JavaScript Loading
 */
function asyncJS($src) {
    return '<script async src="' . htmlspecialchars($src) . '"></script>';
}

/**
 * Generate WebP Image if Supported
 */
function generateWebP($sourcePath) {
    if (!function_exists('imagewebp')) {
        return false;
    }
    
    $pathInfo = pathinfo($sourcePath);
    $webpPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.webp';
    
    if (file_exists($webpPath) && filemtime($sourcePath) < filemtime($webpPath)) {
        return $webpPath;
    }
    
    $imageInfo = getimagesize($sourcePath);
    if (!$imageInfo) {
        return false;
    }
    
    list($width, $height, $type) = $imageInfo;
    
    switch ($type) {
        case IMAGETYPE_JPEG:
            $source = imagecreatefromjpeg($sourcePath);
            break;
        case IMAGETYPE_PNG:
            $source = imagecreatefrompng($sourcePath);
            break;
        default:
            return false;
    }
    
    imagewebp($source, $webpPath, 85);
    imagedestroy($source);
    
    return $webpPath;
}

/**
 * Get Picture Element with WebP Support
 */
function getPictureElement($imagePath, $alt = '', $class = '') {
    $webpPath = generateWebP($imagePath);
    
    $html = '<picture>';
    
    if ($webpPath && file_exists($webpPath)) {
        $html .= '<source srcset="' . htmlspecialchars($webpPath) . '" type="image/webp">';
    }
    
    $html .= '<img src="' . htmlspecialchars($imagePath) . '" alt="' . htmlspecialchars($alt) . '"';
    
    if ($class) {
        $html .= ' class="' . htmlspecialchars($class) . '"';
    }
    
    $html .= ' loading="lazy">';
    $html .= '</picture>';
    
    return $html;
}

/**
 * Cache Page Output
 */
function cachePageOutput($cacheKey, $duration = 3600) {
    $cacheDir = __DIR__ . '/../cache/';
    
    if (!file_exists($cacheDir)) {
        mkdir($cacheDir, 0755, true);
    }
    
    $cacheFile = $cacheDir . md5($cacheKey) . '.html';
    
    // Check if cache exists and is valid
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $duration) {
        return file_get_contents($cacheFile);
    }
    
    return false;
}

/**
 * Save Page Cache
 */
function savePageCache($cacheKey, $content) {
    $cacheDir = __DIR__ . '/../cache/';
    
    if (!file_exists($cacheDir)) {
        mkdir($cacheDir, 0755, true);
    }
    
    $cacheFile = $cacheDir . md5($cacheKey) . '.html';
    file_put_contents($cacheFile, $content);
}

/**
 * Clear Page Cache
 */
function clearPageCache() {
    $cacheDir = __DIR__ . '/../cache/';
    
    if (!file_exists($cacheDir)) {
        return;
    }
    
    $files = glob($cacheDir . '*.html');
    
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
}

/**
 * Get Page Load Time
 */
function getPageLoadTime($startTime) {
    return round((microtime(true) - $startTime) * 1000, 2);
}

/**
 * Compress CSS
 */
function compressCSS($css) {
    // Remove comments
    $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
    
    // Remove whitespace
    $css = str_replace(["\r\n", "\r", "\n", "\t", '  ', '    ', '    '], '', $css);
    
    // Remove unnecessary spaces
    $css = preg_replace('/\s*([{}|:;,])\s+/', '$1', $css);
    
    return trim($css);
}

/**
 * Compress JavaScript
 */
function compressJS($js) {
    // Remove comments
    $js = preg_replace('!/\*.*?\*/!s', '', $js);
    $js = preg_replace('/\n\s*\n/', "\n", $js);
    
    // Remove whitespace
    $js = preg_replace('/\s+/', ' ', $js);
    
    return trim($js);
}

/**
 * Get Performance Metrics
 */
function getPerformanceMetrics() {
    return [
        'memory_usage' => round(memory_get_usage() / 1024 / 1024, 2) . ' MB',
        'peak_memory' => round(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB',
        'included_files' => count(get_included_files()),
        'server_load' => function_exists('sys_getloadavg') ? sys_getloadavg()[0] : 'N/A'
    ];
}
