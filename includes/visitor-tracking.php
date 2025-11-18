<?php
/**
 * Visitor Tracking System
 * Altaf Catering - Real-time visitor analytics
 */

/**
 * Track Visitor Page View
 */
function trackVisitorPageView($page) {
    $analyticsFile = __DIR__ . '/../admin/data/analytics.json';
    
    // Load existing data
    $analytics = [];
    if (file_exists($analyticsFile)) {
        $analytics = json_decode(file_get_contents($analyticsFile), true) ?: [];
    }
    
    // Initialize structure if needed
    if (!isset($analytics['page_views'])) {
        $analytics['page_views'] = [];
    }
    if (!isset($analytics['visitors'])) {
        $analytics['visitors'] = [];
    }
    if (!isset($analytics['daily_stats'])) {
        $analytics['daily_stats'] = [];
    }
    
    // Get visitor info
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    $referer = $_SERVER['HTTP_REFERER'] ?? 'direct';
    $timestamp = time();
    $date = date('Y-m-d');
    $datetime = date('Y-m-d H:i:s');
    
    // Track page view
    $pageView = [
        'page' => $page,
        'ip' => $ip,
        'user_agent' => $userAgent,
        'referer' => $referer,
        'timestamp' => $timestamp,
        'datetime' => $datetime
    ];
    
    $analytics['page_views'][] = $pageView;
    
    // Track unique visitor
    $visitorId = md5($ip . $userAgent);
    if (!isset($analytics['visitors'][$visitorId])) {
        $analytics['visitors'][$visitorId] = [
            'first_visit' => $datetime,
            'last_visit' => $datetime,
            'visit_count' => 1,
            'pages_viewed' => [$page],
            'ip' => $ip,
            'user_agent' => $userAgent
        ];
    } else {
        $analytics['visitors'][$visitorId]['last_visit'] = $datetime;
        $analytics['visitors'][$visitorId]['visit_count']++;
        if (!in_array($page, $analytics['visitors'][$visitorId]['pages_viewed'])) {
            $analytics['visitors'][$visitorId]['pages_viewed'][] = $page;
        }
    }
    
    // Track daily stats
    if (!isset($analytics['daily_stats'][$date])) {
        $analytics['daily_stats'][$date] = [
            'date' => $date,
            'page_views' => 0,
            'unique_visitors' => 0,
            'pages' => []
        ];
    }
    
    $analytics['daily_stats'][$date]['page_views']++;
    
    if (!isset($analytics['daily_stats'][$date]['pages'][$page])) {
        $analytics['daily_stats'][$date]['pages'][$page] = 0;
    }
    $analytics['daily_stats'][$date]['pages'][$page]++;
    
    // Keep only last 10,000 page views
    if (count($analytics['page_views']) > 10000) {
        $analytics['page_views'] = array_slice($analytics['page_views'], -10000);
    }
    
    // Keep only last 90 days of daily stats
    if (count($analytics['daily_stats']) > 90) {
        $analytics['daily_stats'] = array_slice($analytics['daily_stats'], -90, null, true);
    }
    
    // Save data
    file_put_contents($analyticsFile, json_encode($analytics, JSON_PRETTY_PRINT));
}

/**
 * Get Visitor Analytics Summary
 */
function getVisitorAnalyticsSummary() {
    $analyticsFile = __DIR__ . '/../admin/data/analytics.json';
    
    if (!file_exists($analyticsFile)) {
        return [
            'total_page_views' => 0,
            'total_visitors' => 0,
            'today_views' => 0,
            'today_visitors' => 0,
            'popular_pages' => []
        ];
    }
    
    $analytics = json_decode(file_get_contents($analyticsFile), true);
    $today = date('Y-m-d');
    
    // Calculate stats
    $totalPageViews = count($analytics['page_views'] ?? []);
    $totalVisitors = count($analytics['visitors'] ?? []);
    
    $todayViews = isset($analytics['daily_stats'][$today]) 
        ? $analytics['daily_stats'][$today]['page_views'] 
        : 0;
    
    // Count today's unique visitors
    $todayVisitors = 0;
    foreach ($analytics['visitors'] ?? [] as $visitor) {
        if (strpos($visitor['last_visit'], $today) === 0) {
            $todayVisitors++;
        }
    }
    
    // Get popular pages
    $pageCounts = [];
    foreach ($analytics['page_views'] ?? [] as $view) {
        $page = $view['page'];
        if (!isset($pageCounts[$page])) {
            $pageCounts[$page] = 0;
        }
        $pageCounts[$page]++;
    }
    
    arsort($pageCounts);
    $popularPages = array_slice($pageCounts, 0, 10, true);
    
    return [
        'total_page_views' => $totalPageViews,
        'total_visitors' => $totalVisitors,
        'today_views' => $todayViews,
        'today_visitors' => $todayVisitors,
        'popular_pages' => $popularPages
    ];
}

/**
 * Get Visitor Traffic Sources
 */
function getVisitorTrafficSources() {
    $analyticsFile = __DIR__ . '/../admin/data/analytics.json';
    
    if (!file_exists($analyticsFile)) {
        return [];
    }
    
    $analytics = json_decode(file_get_contents($analyticsFile), true);
    $sources = [];
    
    foreach ($analytics['page_views'] ?? [] as $view) {
        $referer = $view['referer'];
        
        if ($referer === 'direct') {
            $source = 'Direct';
        } elseif (strpos($referer, 'google') !== false) {
            $source = 'Google';
        } elseif (strpos($referer, 'facebook') !== false) {
            $source = 'Facebook';
        } elseif (strpos($referer, 'instagram') !== false) {
            $source = 'Instagram';
        } elseif (strpos($referer, 'whatsapp') !== false) {
            $source = 'WhatsApp';
        } else {
            $source = 'Other';
        }
        
        if (!isset($sources[$source])) {
            $sources[$source] = 0;
        }
        $sources[$source]++;
    }
    
    arsort($sources);
    return $sources;
}

/**
 * Get Visitor Daily Stats (Last 30 Days)
 */
function getVisitorDailyStats($days = 30) {
    $analyticsFile = __DIR__ . '/../admin/data/analytics.json';
    
    if (!file_exists($analyticsFile)) {
        return [];
    }
    
    $analytics = json_decode(file_get_contents($analyticsFile), true);
    $dailyStats = $analytics['daily_stats'] ?? [];
    
    // Get last N days
    $stats = array_slice($dailyStats, -$days, null, true);
    
    return $stats;
}

/**
 * Get Visitor Device Stats
 */
function getVisitorDeviceStats() {
    $analyticsFile = __DIR__ . '/../admin/data/analytics.json';
    
    if (!file_exists($analyticsFile)) {
        return ['mobile' => 0, 'desktop' => 0, 'tablet' => 0];
    }
    
    $analytics = json_decode(file_get_contents($analyticsFile), true);
    $devices = ['mobile' => 0, 'desktop' => 0, 'tablet' => 0];
    
    foreach ($analytics['page_views'] ?? [] as $view) {
        $ua = strtolower($view['user_agent']);
        
        if (strpos($ua, 'mobile') !== false || strpos($ua, 'android') !== false) {
            $devices['mobile']++;
        } elseif (strpos($ua, 'tablet') !== false || strpos($ua, 'ipad') !== false) {
            $devices['tablet']++;
        } else {
            $devices['desktop']++;
        }
    }
    
    return $devices;
}

/**
 * Export Visitor Analytics to CSV
 */
function exportVisitorAnalyticsCSV() {
    $analyticsFile = __DIR__ . '/../admin/data/analytics.json';
    
    if (!file_exists($analyticsFile)) {
        return false;
    }
    
    $analytics = json_decode(file_get_contents($analyticsFile), true);
    $csv = "Date,Page,IP,User Agent,Referer\n";
    
    foreach ($analytics['page_views'] ?? [] as $view) {
        $csv .= sprintf(
            '"%s","%s","%s","%s","%s"' . "\n",
            $view['datetime'],
            $view['page'],
            $view['ip'],
            str_replace('"', '""', $view['user_agent']),
            $view['referer']
        );
    }
    
    return $csv;
}
