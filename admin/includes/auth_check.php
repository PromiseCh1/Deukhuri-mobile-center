<?php
/**
 * auth_check.php
 * Authentication and session validation for every admin page.
 *
 * Include this file at the top of every admin page to:
 * - Start the session securely.
 * - Verify the user is logged in.
 * - Enforce session timeout.
 * - Optionally check for required role (Admin or Staff).
 */

// Ensure this file is only accessed via the bootstrap
if (!defined('APP_START')) {
    header('HTTP/1.1 403 Forbidden');
    exit('Direct access not allowed.');
}

// Load required core files
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

// ---------------------------------------------------------------------------
// Session Security
// ---------------------------------------------------------------------------

// Set session name
session_name(SESSION_NAME);

// Configure session cookie parameters for security (only when on HTTPS in production)
if (APP_ENV === 'production') {
    session_set_cookie_params([
        'lifetime' => SESSION_TIMEOUT,
        'path'     => '/',
        'domain'   => '',
        'secure'   => true,   // only send over HTTPS
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
} else {
    // Development: allow HTTP, but still enforce httponly
    session_set_cookie_params([
        'lifetime' => SESSION_TIMEOUT,
        'path'     => '/',
        'domain'   => '',
        'secure'   => false,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
}

// Start or resume session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ---------------------------------------------------------------------------
// Session Timeout Check
// ---------------------------------------------------------------------------

if (isset($_SESSION['last_activity'])) {
    $inactive_time = time() - $_SESSION['last_activity'];
    if ($inactive_time > SESSION_TIMEOUT) {
        // Session expired: destroy and redirect
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        session_destroy();
        setFlashMessage('error', 'Your session has expired. Please log in again.');
        redirect(SITE_URL . '/admin/login.php');
        exit;
    }
}

// Update last activity time
$_SESSION['last_activity'] = time();

// Regenerate session ID periodically (optional, but adds security)
if (!isset($_SESSION['created'])) {
    $_SESSION['created'] = time();
} elseif (time() - $_SESSION['created'] > 1800) {
    // Regenerate every 30 minutes
    session_regenerate_id(true);
    $_SESSION['created'] = time();
}

// ---------------------------------------------------------------------------
// Authentication Check
// ---------------------------------------------------------------------------

// If not logged in, redirect to login page
if (!isLoggedIn()) {
    setFlashMessage('error', 'Please log in to access the admin panel.');
    redirect(SITE_URL . '/admin/login.php');
    exit;
}

// ---------------------------------------------------------------------------
// Role-Based Access Control (optional)
// ---------------------------------------------------------------------------

/**
 * Check for a required role.
 * Usage: auth_check('Admin') or auth_check('Staff')
 *
 * @param string $required_role The role required to view the page.
 * @return void Redirects if user doesn't have the required role.
 */
function requireRole($required_role)
{
    if (!isLoggedIn()) {
        setFlashMessage('error', 'Please log in.');
        redirect(SITE_URL . '/admin/login.php');
        exit;
    }

    $user_role = $_SESSION['role'] ?? '';
    $allowed = false;

    if ($required_role === 'Admin') {
        $allowed = ($user_role === 'Admin');
    } elseif ($required_role === 'Staff') {
        $allowed = ($user_role === 'Staff' || $user_role === 'Admin');
    }

    if (!$allowed) {
        setFlashMessage('error', 'You do not have permission to access this page.');
        redirect(SITE_URL . '/admin/dashboard.php'); // Redirect to dashboard or login
        exit;
    }
}

// ---------------------------------------------------------------------------
// End of auth_check.php
// ---------------------------------------------------------------------------