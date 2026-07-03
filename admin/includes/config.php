<?php
/**
 * config.php
 * Central configuration for the admin panel.
 * All constants defined here are used throughout the application.
 */

// ---------------------------------------------------------------------------
// Prevent direct access
// ---------------------------------------------------------------------------
if (!defined('APP_START')) {
    header('HTTP/1.1 403 Forbidden');
    exit('Direct access not allowed.');
}

// ---------------------------------------------------------------------------
// Application
// ---------------------------------------------------------------------------
define('SITE_NAME', 'Deukhuri Mobile Center');
define('SITE_URL', 'http://localhost/dmc');                  // Change when deploying
define('APP_ENV', 'development');                            // development | production
define('APP_VERSION', '1.0.0');
define('DEFAULT_TIMEZONE', 'Asia/Kathmandu');
date_default_timezone_set(DEFAULT_TIMEZONE);

// ---------------------------------------------------------------------------
// Session
// ---------------------------------------------------------------------------
define('SESSION_NAME', 'dmc_admin');
define('SESSION_TIMEOUT', 1800);                             // 30 minutes in seconds

// ---------------------------------------------------------------------------
// Database
// ---------------------------------------------------------------------------
define('DB_HOST', 'localhost');
define('DB_NAME', 'deukhuri_shop');
define('DB_USER', 'root');
define('DB_PASS', '');

// ---------------------------------------------------------------------------
// Upload Directories (relative to project root)
// ---------------------------------------------------------------------------
define('UPLOAD_PATH', __DIR__ . '/../../uploads/');
define('UPLOAD_PRODUCTS', UPLOAD_PATH . 'products/');
define('UPLOAD_CATEGORIES', UPLOAD_PATH . 'categories/');
define('UPLOAD_BRANDS', UPLOAD_PATH . 'brands/');

// Ensure upload directories exist (optional, but helpful during setup)
if (!is_dir(UPLOAD_PATH)) {
    mkdir(UPLOAD_PATH, 0755, true);
}
foreach ([UPLOAD_PRODUCTS, UPLOAD_CATEGORIES, UPLOAD_BRANDS] as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// ---------------------------------------------------------------------------
// Security / CSRF
// ---------------------------------------------------------------------------
define('CSRF_TOKEN_NAME', 'csrf_token');