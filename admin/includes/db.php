<?php
/**
 * db.php
 * Secure PDO database connection.
 * Include this file to get a $pdo object for all database operations.
 */

// Ensure this file is only accessed via the bootstrap
if (!defined('APP_START')) {
    header('HTTP/1.1 403 Forbidden');
    exit('Direct access not allowed.');
}

// Load configuration if not already loaded
if (!defined('DB_HOST')) {
    require_once __DIR__ . '/config.php';
}

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    // In development, show the error; in production, log it and show a generic message.
    if (APP_ENV === 'development') {
        die('Database connection failed: ' . htmlspecialchars($e->getMessage()));
    } else {
        // Log error to file (adjust path as needed)
        error_log('DB Connection Error: ' . $e->getMessage());
        die('Database connection error. Please try again later.');
    }
}