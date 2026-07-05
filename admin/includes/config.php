<?php
declare(strict_types=1);

/**
 * config.php
 * Central configuration for the admin panel.
 * All constants are defined here to avoid hardcoding.
 */

// ---------------------------------------------------------------------------
// Security – Prevent direct access
// ---------------------------------------------------------------------------
if (!defined('APP_START')) {
    header('HTTP/1.1 403 Forbidden');
    exit('Direct access not allowed.');
}

// ---------------------------------------------------------------------------
// Application
// ---------------------------------------------------------------------------
define('SITE_NAME', 'Deukhuri Mobile Center');
define('SITE_URL', 'http://localhost/dmc');                     // Change when deploying
define('ADMIN_URL', SITE_URL . '/admin');                       // Convenience constant
define('APP_ENV', 'development');                               // 'development' or 'production'
define('APP_DEBUG', APP_ENV === 'development');                 // Show/hide errors
define('APP_VERSION', '1.0.0');
define('DEFAULT_TIMEZONE', 'Asia/Kathmandu');
date_default_timezone_set(DEFAULT_TIMEZONE);

// ---------------------------------------------------------------------------
// Pagination
// ---------------------------------------------------------------------------
define('PAGINATION_LIMIT', 20);

// ---------------------------------------------------------------------------
// Session
// ---------------------------------------------------------------------------
define('SESSION_NAME', 'dmc_admin');
define('SESSION_TIMEOUT', 1800);                               // 30 minutes (in seconds)
define('SESSION_COOKIE_PATH', '/admin');
define('SESSION_COOKIE_DOMAIN', '');

// ---------------------------------------------------------------------------
// Database
// ---------------------------------------------------------------------------
define('DB_HOST', 'localhost');
define('DB_NAME', 'deukhuri_shop');
define('DB_USER', 'root');
define('DB_PASS', '');

// ---------------------------------------------------------------------------
// Upload Directories
// ---------------------------------------------------------------------------
define('UPLOAD_PATH', __DIR__ . '/../../uploads/');
define('UPLOAD_PRODUCTS', UPLOAD_PATH . 'products/');
define('UPLOAD_CATEGORIES', UPLOAD_PATH . 'categories/');
define('UPLOAD_BRANDS', UPLOAD_PATH . 'brands/');

// Create directories only in development (or if they don't exist)
if (APP_DEBUG && !is_dir(UPLOAD_PATH)) {
    mkdir(UPLOAD_PATH, 0755, true);
    foreach ([UPLOAD_PRODUCTS, UPLOAD_CATEGORIES, UPLOAD_BRANDS] as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
}

// ---------------------------------------------------------------------------
// Security / CSRF
// ---------------------------------------------------------------------------
define('CSRF_TOKEN_NAME', 'csrf_token');
define('CSRF_TOKEN_LIFETIME', 3600);                           // 1 hour