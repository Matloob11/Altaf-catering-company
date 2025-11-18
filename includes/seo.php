<?php
/**
 * SEO Helper Functions
 * Altaf Catering - SEO Optimization Tools
 */

/**
 * Generate Meta Tags
 */
function generateMetaTags($data = []) {
    $defaults = [
        'title' => 'Altaf Catering - Professional Catering Services in Pakistan',
        'description' => 'Professional catering services for weddings, corporate events, and special occasions in Pakistan.',
        'keywords' => 'catering services, wedding catering, corporate catering, event catering, Pakistani food',
        'image' => 'https://altafcatering.com/img/hero.png',
        'url' => 'https://altafcatering.com/',
        'type' => 'website'
    ];
    
    $meta = array_merge($defaults, $data);
    
    $tags = [];
    $tags[] = '<meta name="description" content="' . htmlspecialchars($meta['description']) . '">';
    $tags[] = '<meta name="keywords" content="' . htmlspecialchars($meta['keywords']) . '">';
    $tags[] = '<meta property="og:title" content="' . htmlspecialchars($meta['title']) . '">';
    $tags[] = '<meta property="og:description" content="' . htmlspecialchars($meta['description']) . '">';
    $tags[] = '<meta property="og:image" content="' . htmlspecialchars($meta['image']) . '">';
    $tags[] = '<meta property="og:url" content="' . htmlspecialchars($meta['url']) . '">';
    $tags[] = '<meta name="twitter:card" content="summary_large_image">';
    
    return implode("\n    ", $tags);
}

/**
 * Generate Breadcrumb Schema
 */
function generateBreadcrumbSchema($items) {
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => []
    ];
    
    foreach ($items as $index => $item) {
        $schema['itemListElement'][] = [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'name' => $item['name'],
            'item' => $item['url']
        ];
    }
    
    return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>';
}

/**
 * Generate FAQ Schema
 */
function generateFAQSchema($faqs) {
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => []
    ];
    
    foreach ($faqs as $faq) {
        $schema['mainEntity'][] = [
            '@type' => 'Question',
            'name' => $faq['question'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => $faq['answer']
            ]
        ];
    }
    
    return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>';
}

/**
 * Optimize Image Alt Text
 */
function optimizeImageAlt($filename, $context = '') {
    $alt = pathinfo($filename, PATHINFO_FILENAME);
    $alt = str_replace(['-', '_'], ' ', $alt);
    $alt = ucwords($alt);
    
    if ($context) {
        $alt .= ' - ' . $context;
    }
    
    return $alt . ' | Altaf Catering';
}

/**
 * Generate Canonical URL
 */
function getCanonicalURL() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $uri = strtok($_SERVER['REQUEST_URI'], '?');
    
    return $protocol . '://' . $host . $uri;
}

/**
 * Clean URL for SEO
 */
function cleanURL($url) {
    $url = strtolower($url);
    $url = preg_replace('/[^a-z0-9-]/', '-', $url);
    $url = preg_replace('/-+/', '-', $url);
    $url = trim($url, '-');
    
    return $url;
}
