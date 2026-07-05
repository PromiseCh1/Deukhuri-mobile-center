<?php
declare(strict_types=1);

/**
 * db.php
 * Secure PDO database connection.
 * Returns a $pdo object for all database operations.
 */

// ---------------------------------------------------------------------------
// Security – Prevent direct access
// ---------------------------------------------------------------------------
if (!defined('APP_START')) {
    header('HTTP/1.1 403 Forbidden');
    exit('Direct access not allowed.');
}

// Load configuration if not already loaded
if (!defined('DB_HOST')) {
    require_once __DIR__ . '/config.php';
}

// ---------------------------------------------------------------------------
// PDO Connection
// ---------------------------------------------------------------------------
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            // Throw exceptions on errors
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            // Fetch associative arrays by default
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // Disable emulated prepares for security and performance
            PDO::ATTR_EMULATE_PREPARES   => false,
            // Ensure UTF-8 encoding
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
        ]
    );
} catch (PDOException $e) {
    // Log the error (server log or custom log file)
    error_log('DB Connection Error: ' . $e->getMessage());

    // Show detailed error only in development
    if (APP_DEBUG) {
        die('Database connection failed: ' . htmlspecialchars($e->getMessage()));
    } else {
        die('Database connection error. Please try again later.');
    }
}