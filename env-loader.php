<?php
/**
 * Environment Variables Loader
 * Safely loads environment variables from .env file
 * 
 * This prevents sensitive API keys from being exposed in code
 */

class EnvLoader {
    private static $loaded = false;
    
    /**
     * Load environment variables from .env file
     */
    public static function load($path = __DIR__) {
        if (self::$loaded) {
            return;
        }
        
        $envFile = $path . '/.env';
        
        // Check if .env file exists
        if (!file_exists($envFile)) {
            // Try to create from example if it doesn't exist
            $exampleFile = $path . '/.env.example';
            if (file_exists($exampleFile)) {
                copy($exampleFile, $envFile);
                error_log("Created .env file from .env.example. Please update with your actual API keys.");
            }
            return;
        }
        
        // Read and parse .env file
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            // Skip comments
            if (strpos(trim($line), '#') === 0) {
                continue;
            }
            
            // Parse key=value pairs
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
                
                // Remove quotes if present
                if (preg_match('/^(["\'])(.*)\\1$/', $value, $matches)) {
                    $value = $matches[2];
                }
                
                // Set environment variable if not already set
                if (!isset($_ENV[$key])) {
                    $_ENV[$key] = $value;
                    putenv("$key=$value");
                }
            }
        }
        
        self::$loaded = true;
    }
    
    /**
     * Get environment variable with fallback
     */
    public static function get($key, $default = null) {
        return $_ENV[$key] ?? getenv($key) ?: $default;
    }
    
    /**
     * Check if environment variable exists
     */
    public static function has($key) {
        return isset($_ENV[$key]) || getenv($key) !== false;
    }
}

// Auto-load environment variables
EnvLoader::load();