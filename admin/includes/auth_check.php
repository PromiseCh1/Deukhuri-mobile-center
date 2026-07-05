<?php
declare(strict_types=1);

/**
 * auth_check.php
 * Authentication middleware for every admin page.
 *
 * This file:
 * 1. Configures the session securely.
 * 2. Checks for session timeout.
 * 3. Periodically regenerates the session ID.
 * 4. Ensures the user is logged in.
 * 5. Provides helpers for role-based access.
 */

// ---------------------------------------------------------------------------
// Security – Prevent direct access
// ---------------------------------------------------------------------------
if (!defined('APP_START')) {
    header('HTTP/1.1 403 Forbidden');
    exit('Direct access not allowed.');
}

// Load core files
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

// ===========================================================================
// 1. SESSION CONFIGURATION
// ===========================================================================

session_name(SESSION_NAME);
session_set_cookie_params([
    'lifetime' => SESSION_TIMEOUT,
    'path'     => SESSION_COOKIE_PATH ?: '/',
    'domain'   => SESSION_COOKIE_DOMAIN ?: '',
    'secure'   => isHttps(),   // Auto-set if HTTPS
    'httponly' => true,
    'samesite' => 'Lax'
]);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ===========================================================================
// 2. SESSION TIMEOUT CHECK
// ===========================================================================

if (isset($_SESSION['last_activity'])) {
    if (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT) {
        // Session expired – preserve flash messages
        $flash = $_SESSION['__flash'] ?? [];
        $_SESSION = [];
        $_SESSION['__flash'] = $flash;

        setFlashMessage('error', 'Your session has expired. Please log in again.');
        redirect(ADMIN_URL . '/login.php');
    }
}
$_SESSION['last_activity'] = time();

// ===========================================================================
// 3. SESSION REGENERATION (Periodic)
// ===========================================================================

if (!isset($_SESSION['created'])) {
    $_SESSION['created'] = time();
} elseif (time() - $_SESSION['created'] > 1800) { // Every 30 minutes
    session_regenerate_id(true);
    $_SESSION['created'] = time();
}

// ===========================================================================
// 4. AUTHENTICATION CHECK
// ===========================================================================

if (!isLoggedIn()) {
    setFlashMessage('error', 'Please log in to access the admin panel.');
    redirect(ADMIN_URL . '/login.php');
}

// ===========================================================================
// 5. REUSABLE ROLE HELPERS (for future pages)
// ===========================================================================

/**
 * Ensure the user is logged in. If not, redirect to login.
 * This is a simple alias to the core check.
 */
/**
 * Combined authentication and optional role check.
 * Call this after including the file.
 */
function authenticate(string|array|null $requiredRole = null, string $redirectUrl = ''): void
{
    // Already checked above, but re‑check for safety.
    if (!isLoggedIn()) {
        setFlashMessage('error', 'Please log in.');
        redirect(ADMIN_URL . '/login.php');
    }

    if ($requiredRole !== null) {
        requireRole($requiredRole, $redirectUrl);
    }
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        setFlashMessage('error', 'Please log in to access this page.');
        redirect(ADMIN_URL . '/login.php');
    }
}

/**
 * Ensure the logged-in user has a specific role.
 *
 * @param string $requiredRole The role required ('Admin' or 'Staff').
 * @param string $redirectUrl  Optional custom redirect URL.
 */
function requireRole(string $requiredRole, string $redirectUrl = ''): void
{
    requireLogin(); // Ensure user is logged in first

    $userRole = $_SESSION['role'] ?? '';

    // Admin has universal access
    if ($userRole === 'Admin') {
        return;
    }

    if ($userRole !== $requiredRole) {
        setFlashMessage('error', 'You do not have permission to access this page.');
        $redirectUrl = $redirectUrl ?: ADMIN_URL . '/dashboard.php';
        redirect($redirectUrl);
    }
}

/**
 * Ensure the logged-in user has any one of the allowed roles.
 *
 * @param array  $allowedRoles Array of allowed roles (e.g., ['Admin', 'Manager']).
 * @param string $redirectUrl  Optional custom redirect URL.
 */
function requireAnyRole(array $allowedRoles, string $redirectUrl = ''): void
{
    requireLogin(); // Ensure user is logged in first

    $userRole = $_SESSION['role'] ?? '';

    // Admin has universal access
    if ($userRole === 'Admin') {
        return;
    }

    if (!in_array($userRole, $allowedRoles, true)) {
        setFlashMessage('error', 'You do not have permission to access this page.');
        $redirectUrl = $redirectUrl ?: ADMIN_URL . '/dashboard.php';
        redirect($redirectUrl);
    }
}

// ===========================================================================
// 6. (Optional) Automatic role check if page defines REQUIRE_ROLE
// ===========================================================================
if (defined('REQUIRE_ROLE')) {
    if (is_string(REQUIRE_ROLE)) {
        requireRole(REQUIRE_ROLE);
    } elseif (is_array(REQUIRE_ROLE)) {
        requireAnyRole(REQUIRE_ROLE);
    }
}